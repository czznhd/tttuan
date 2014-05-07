<?php

/**
 * 逻辑区：服务集群管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name service.logic.php
 * @version 1.0
 */

class ServiceLogic
{
	
    function __call($name, $args)
    {
        $SID = 'logic.service.'.$name;
        $obj = moSpace($SID);
        if ( ! $obj )
        {
            $class = 'ServiceLogic_'.strtoupper($name);
            if (!class_exists($class))
            {
                return false;
            }
            $obj = moSpace($SID, (new $class()));
        }
        return $obj;
    }
    
    public function GetAPI($code, $cfg = null)
    {
        $SID = 'service.driver.api.'.$code;
        $api = moSpace($SID);
        if ( ! $api )
        {
            $api = moSpace($SID, driver('service')->load($code));
            if (is_null($cfg))
            {
                $server = logic('service')->$code()->RndOne();
                if (!$server) return false;
                $cfg = unserialize($server['config']);
            }
            $api->config($cfg);
        }
        return $api;
    }
}

/**
 * 扩充类：邮件服务器
 * @author Moyo <dev@uuland.org>
 */
class ServiceLogic_MAIL
{
    
    private $lck = 'server.logic.mail.list';
    
    private $ick = 'server.logic.mail.sinc.cache';
    
    public function GetList($enabled = null)
    {
        $sql_limit_enabled = '1';
        if (!is_null($enabled))
        {
            $sql_limit_enabled = 'enabled="'.$enabled.'"';
        }
        $sql = 'SELECT * FROM '.table('service').' WHERE type = "mail" AND '.$sql_limit_enabled;
        $result = dbc()->Query($sql)->GetAll();
        if (ini('service.mail.balance') && is_null($enabled))
        {
                        $cached = $this->SendInc(false, null, true);
            if (!$cached)
            {
                                $this->SendInc(false, $result);
                                return $this->GetList();
            }
        }
        return $result;
    }
    
    public function GetOne($id)
    {
        $sql = 'SELECT * FROM '.table('service').' WHERE id='.$id;
        $result = dbc()->Query($sql)->GetRow();
        $result['cfg'] = unserialize($result['config']);
        return $result;
    }
    
    public function Update($id, $data)
    {
        dbc()->SetTable(table('service'));
        $data['update'] = time();
        if ($id == 0)
        {
            $data['type'] = 'mail';
            $data['count'] = 0;
            dbc()->Insert($data);
        }
        else
        {
            dbc()->Update($data, 'id='.$id);
        }
        fcache($this->lck, 0);
    }
	
    public function Del($id)
    {
        dbc()->SetTable(table('service'));
        dbc()->Delete('', 'id='.$id);
        fcache($this->lck, 0);
    }
    
    private function SendInc($idx = false, $data = null, $check = false)
    {
        if ($check)
        {
            return fcache($this->ick, -1);
        }
        if (!is_null($data))
        {
            $cached['update'] = time();
            foreach ($data as $i => $one)
            {
                $cached['list'][$one['id']] = array(
                	'weight' => $one['weight'],
                	'count' => $one['count']
                );
            }
            fcache($this->ick, $cached);
            return $cached;
        }
        if (!$idx)
        {
            fcache($this->ick, 0);
            return false;
        }
        $cached = fcache($this->ick, -1);
                $cl = 3600;
        if (DEBUG)
        {
                        $cl = 3;
        }
        if ($cached && time() - $cached['update'] > $cl)
        {
            foreach ($cached['list'] as $id => $val)
            {
                $data = array(
                    'weight' => $val['weight'],
                    'count' => $val['count']
                );
                $this->Update($id, $data);
            }
            $cached['update'] = time();
        }
        $i = &$cached['list'][$idx];
                if (ini('service.mail.balance'))
        {
                        $i['weight'] --;
                        if ($i['weight'] <= 0)
            {
                                $wmm = $this->wmmList();
                $i['weight'] = $wmm['max'];
                            }
        }
                $i['count'] ++;
        fcache($this->ick, $cached);
        return $cached;
    }
    
    public function RndOne()
    {
        if (!ini('service.mail.balance'))
        {
                        return $this->dbRndOne();
        }
        $list = $this->cacheList();
        if (count($list) == 0)
        {
            return false;
        }
        $wmm = $this->wmmList($list);
        $widx = $wmm['list'];
        $min = $wmm['min'];
        $max = $wmm['max'];
        $pox = rand($min, $max);
        $sel = 0;
        foreach ($widx as $i => $weight)
        {
            if ($weight >= $pox)
            {
                $sel = $list[$i];
            }
        }
        return $sel;
    }
    
    private function cacheList()
    {
        $list = fcache($this->lck, -1);
        if (!$list)
        {
            $list = $this->GetList('true');
            fcache($this->lck, $list);
        }
        return $list;
    }
    
    private function wmmList($list = null)
    {
        if (is_null($list))
        {
            $list = $this->cacheList();
        }
        $min = $list[0]['weight'];
        $max = 0;
        $widx = array();
        foreach ($list as $i => $one)
        {
            $weight = intval($one['weight']);
            $min = min($min, $weight);
            $max = max($max, $weight);
            $widx[$i] = $one['weight'];
        }
        return array(
            'list' => $widx,
            'min' => $min,
            'max' => $max
        );
    }
    
    private function dbRndOne()
    {
        $sql = 'SELECT * FROM '.table('service').' WHERE type="mail" AND enabled="true" ORDER BY RAND() LIMIT 1';
        return dbc()->Query($sql)->GetRow();
    }
	
    public function Send($mail, $subject, $content, $queuemsg = '')
    {
        if (!preg_match('/[a-z0-9\._]+@[a-z0-9\.-]+/i', $mail)) return 'invaidTarget';
        $server = $this->RndOne();
        if (!$server) return 'noServer';
        $cfg = unserialize($server['config']);
        $api = logic('service')->GetAPI('mail', $cfg);
        $this->SendInc($server['id']);
        $result = $api->Send($mail, $subject, $content);
        logic('push')->log('mail', $cfg['type'], $mail, array('subject'=>$subject,'content'=>$content), $result, $queuemsg);
        return $result['message'];
    }
	
    public function Test($mail)
    {
        $server = $this->RndOne();
        if (!$server) return array();
        $cfg = unserialize($server['config']);
        $api = logic('service')->GetAPI('mail', $cfg);
        $api->SMTPDebug = 2;
        $this->SendInc($server['id']);
        $result = $api->Send($mail, __('邮件测试！'), __('这是一封测试邮件！'));
        if ($api->IsError())
        {
            $result = $api->ErrorInfo;
        }
		else
		{
			$result = $result['message'];
		}
        return array(
            'server' => $server,
            'config' => $cfg,
            'result' => $result,
            'debug' => $api->Debug()
        );
    }
}
/**
 * 扩充类：短信服务器
 * @author Moyo <dev@uuland.org>
 */
class ServiceLogic_SMS
{
	
    public function GetList($enabled = null)
    {
        $sql_limit_enabled = '1';
        if (!is_null($enabled))
        {
            $sql_limit_enabled = 'enabled="'.$enabled.'"';
        }
        $sql = 'SELECT * FROM '.table('service').' WHERE type = "sms" AND '.$sql_limit_enabled;
        $result = dbc()->Query($sql)->GetAll();
        foreach ($result as $i => $one)
        {
            $result[$i]['cfg'] = unserialize($one['config']);
        }
        return $result;
    }
	
    public function GetOne($id)
    {
        $sql = 'SELECT * FROM '.table('service').' WHERE id='.$id;
        $result = dbc()->Query($sql)->GetRow();
        $result['cfg'] = unserialize($result['config']);
        $result['cfg']['driver'] = $result['flag'];
        return $result;
    }
	
    public function DriverList()
    {
        return ini('service.sms.driver');
    }
	
    public function Update($id, $data)
    {
        dbc()->SetTable(table('service'));
        $data['update'] = time();
        if ($id == 0)
        {
            $data['type'] = 'sms';
            $data['weight'] = 9;
            $data['count'] = 0;
            dbc()->Insert($data);
        }
        else
        {
            dbc()->Update($data, 'id='.$id);
        }
    }
	
    public function Del($id)
    {
        dbc()->SetTable(table('service'));
        dbc()->Delete('', 'id='.$id);
    }
    
    private function SendInc($idx)
    {
        $sql = 'UPDATE '.table('service').' SET count=count+1 WHERE id='.$idx;
        return dbc()->Query($sql);
    }
	
    public function RndOne()
    {
        $sql = 'SELECT * FROM '.table('service').' WHERE type="sms" AND enabled="true" ORDER BY RAND() LIMIT 1';
        return dbc()->Query($sql)->GetRow();
    }
	
    public function Send($phone, $content, $queuemsg = '')
    {
        if (strlen($phone) == 11 && !preg_match('/[0-9]{11}/', $phone)) return 'invaidTarget';
        $server = $this->RndOne();
        if (!$server) return 'noServer';
        $cfg = unserialize($server['config']);
        $cfg['driver'] = $server['flag'];
        $api = logic('service')->GetAPI('sms', $cfg);
        $this->SendInc($server['id']);
        $result = $api->Send($phone, $content);
        logic('push')->log('sms', $cfg['driver'], $phone, array('content'=>$content), $result, $queuemsg);
        return is_array($result) ? $result['message'] : $result;
    }
	
    public function Test($phone, $content)
    {
        $server = $this->RndOne();
        if (!$server) return array();
        $cfg = unserialize($server['config']);
        $cfg['driver'] = $server['flag'];
        $api = logic('service')->GetAPI('sms', $cfg);
        $mApi = $api->api($cfg['driver']);
        $mApi->Debug = true;
        $this->SendInc($server['id']);
        $result = $api->Send($phone, $content);
        if ($mApi->Errored)
        {
            $result = $mApi->errorMsg;
        }
		else
		{
			$result = $result['message'];
		}
        return array(
            'server' => $server,
            'config' => $cfg,
            'result' => $result,
            'debug' => $mApi->Debug()
        );
    }
	
    public function Status($id)
    {
        $server = $this->GetOne($id);
        if (!$server) return 'noServer';
        $api = logic('service')->GetAPI('sms', $server['cfg']);
        return $api->Status();
    }
}

#if PUB

#endif

?>