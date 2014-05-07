<?php

/**
 * 短信通道：LS
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package service
 * @name sms.ls.php
 * @version 1.0
 */

class ls_smsServiceDriver extends smsServiceDriver
{
    private $cfg = array();
    private $Gateway = 'http://smsls.tttuangou.net:8080/';
    public function config($cfg)
    {
        $this->cfg = $cfg;
    }
    public function IMSend($phone, $content)
    {
    	if (false != $exids = $this->BC_EXPS($phone, $content, $this->cfg['bcmax']))
    	{
    		return '@exps('.count($exids).')';
    	}
        $this->Gateway = base64_decode('aHR0cDovL3Ntc2xzLnR0dHVhbmdvdS5uZXQ6ODA4MC8=');
        $sms = $content;
    	if (!ENC_IS_GBK) $sms = iconv('UTF-8', 'GB2312/'.'/IGNORE', $content);
    	$url  = $this->Gateway;
    	$url .= 'LSBsms/smsInterface.do?method=sendsms';
    	$url .= '&name='.$this->cfg['account'].'&password='.md5($this->cfg['password']).'&troughid=&priorityid=&timing=&mobile='.$phone.'&content='.rawurlencode($sms).'&splitsuffix=0';
    	$this->Debug('Request: Started');
    	$this->Debug('Send: '.htmlspecialchars($content));
    	$result = $this->Get($url);
    	if ($result == '')
    	{
    	    $this->Error('Connected Failed.');
    	    return $this->result_error('reponse-empty');
    	}
    	if (!ENC_IS_GBK) $result = iconv('GB2312', 'UTF-8/'.'/IGNORE', $result);
    	$this->Debug('Response: '.htmlspecialchars($result));
    	preg_match('/<success>(.*?)<\/success>/', $result, $match);
    	$success = $match[1];
    	preg_match('/<message>(.*?)<\/message>/', $result, $match);
    	$msgstate = $match[1];
    	if ($success == '1')
    	{
    	    $this->Debug('Status: Send Succeed.');
    	}
    	else
    	{
    		$this->Debug('Status: Send Failed.');
	    	if (ini('service.sms.autoERSend') && strlen($phone) < 13)
	    	{
	            $msgstate = $this->IMERSend($msgstate, $phone, $content);
	    	}
    	}
    	return $this->result_success(
				$msgstate,
				array(
					'raw' => $result,
					'status' => ($success == '1' ? 'success' : 'failed')
				)
			);
    }
    public function IMStatus()
    {
    	$url  = $this->Gateway;
    	$url .= 'LSBsms/smsInterface.do?method=remaincount';
    	$url .= '&name='.$this->cfg['account'].'&password='.md5($this->cfg['password']);
    	$result = $this->Get($url);
    	if (!ENC_IS_GBK) $result = iconv('GB2312', 'UTF-8/'.'/IGNORE', $result);
    	preg_match('/<describe>(.*?)<\/describe>/', $result, $match);
    	$status = $match[1];
    	preg_match('/<count>(.*?)<\/count>/', $result, $match);
    	$remain = (int)$match[1]/10;
    	if ($match[0] == '')
    	{
    		preg_match('/<message>(.*?)<\/message>/', $result, $match);
    		$remain = $match[1];
    	}
    	return sprintf('通道状态：%s<br/>短信剩余：%d 条', $status, $remain);
    }
    public function IMERSend($result, $phone, $content)
    {
        preg_match_all('/^存在敏感信息：(.*?)！$/', $result, $matchs);
        if (empty($matchs[1][0]))
        {
        	return $result;
        }
        $wd = $matchs[1][0];
        $nwd = '';
        $loops = strlen($wd);
        $last1wd = 0;
        $last2wd = 0;
        for ($i=0; $i<$loops; $i++)
        {
        	$char = $wd[$i];
        	$ascii = ord($char);
        	if ($ascii > 127)
        	{
        		if ($last2wd > 0)
        		{
        			if (ENC_IS_GBK)
        			{
        				$nwd .= chr($last2wd).chr($ascii).' ';
        				$last2wd = 0;
        			}
        			else
        			{
        				if ($last1wd > 0)
        				{
        					$nwd .= chr($last1wd).chr($last2wd).chr($ascii).' ';
        					$last1wd = $last2wd = 0;
        				}
        			}
        		}
        		else
        		{
        			if (!ENC_IS_GBK && $last1wd == 0)
        			{
        				$last1wd = $ascii;
        			}
        			else
        			{
        				$last2wd = $ascii;
        			}
        		}
        	}
        	else
        	{
        		$nwd .= $char.' ';
        	}
        }
        $nwd = substr($nwd, 0, -1);
        $content = str_replace($wd, $nwd, $content);
        return $this->IMSend($phone, $content);
    }
}

?>