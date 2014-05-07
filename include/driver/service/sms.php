<?php

/**
 * 服务接口：短信
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package service
 * @name sms.php
 * @version 1.0
 */

class smsServiceDriver extends ServiceDriver
{
	
    public $Debug = false;
	
    public $Errored = false;
	
    public $errorMsg = '';
	
    private $__debugs = array();
	
    private $__apis = array();
    
    public final function api($driver)
    {
        $api = &$this->__apis[$driver];
        if (!is_null($api))
        {
            return $api;
        }
        $file = dirname(__FILE__).'/sms.'.($driver).'.php';
	    include_once $file;
	    $className = $driver.'_smsServiceDriver';
	    $api = new $className();
				if ((int)$this->conf['bcmax'] < 1)
		{
			$this->conf['bcmax'] = 10;
		}
		$this->conf['sinv'] = (int)((float)$this->conf['sinv'] * 1000000);
			    $api->config($this->conf);
	    return $api;
    }
    
    public final function Send($phone, $content)
    {
        $r = $this->api($this->conf['driver'])->IMSend($phone, $content);
		if ($this->conf['sinv'])
		{
			usleep($this->conf['sinv']);
		}
		return $r;
    }
    
    public final function Status()
    {
        return $this->api($this->conf['driver'])->IMStatus();
    }
	
    public function BC_EXPS(&$phone, $content, $max = 100)
    {
    	if (strlen($phone) == 11 || !strstr($phone, ';'))
    	{
    		return false;
    	}
    	$phones = explode(';', $phone);
    	foreach ($phones as $i => $one)
    	{
    		if (!preg_match('/[0-9]{11}/', $one))
    		{
    			unset($phones[$i]);
    		}
    	}
		$loops = count($phones);
    	if ($loops <= $max)
    	{
    		$phone = implode(';', $phones);
    		return false;
    	}
    	$sphone = '';
    	$ii = 0;
		$ids = array();
    	foreach ($phones as $i => $one)
    	{
    		$sphone .= $one.';';
    		$ii ++ ;
    		if ($ii >= $max || $i == ($loops - 1))
    		{
    			$sphone = substr($sphone, 0, -1);
    			$ids[] = logic('push')->add('sms', $sphone, array('content' => $content));
    			$sphone = '';
    			$ii = 0;
    		}
    	}
    	return $ids;
    }
    
    public final function Get($url,$post='')
    {
        return dfopen($url, 10485760, $post, '', true, 10, 'CENWOR.TTTG.SMS.AGENT.'.SYS_VERSION.'.'.SYS_BUILD);
    }
    
    public final function Debug($msg = null)
    {
        if (!$this->Debug) return;
        if (is_null($msg)) return $this->__debugs;
        list($ms, $s) = explode(' ', microtime());
        $this->__debugs[] = array(
            'timer' => (float)($s+$ms),
            'msg' => $msg."\r\n"
        );
    }
    
    public final function Error($msg)
    {
        $this->Errored = true;
        $this->errorMsg = $msg;
    }
}

?>