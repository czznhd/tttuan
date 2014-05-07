<?php

/**
 * 短信通道：QXT
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package service
 * @name sms.qxt.php
 * @version 1.0
 */

class qxt_smsServiceDriver extends smsServiceDriver
{
    private $cfg = array();
    private $Gateway = 'http://gd106.tttuangou.net:9000/QxtSms/';
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
        $this->Gateway = base64_decode('aHR0cDovL2dkMTA2LnR0dHVhbmdvdS5uZXQ6OTAwMC9ReHRTbXMv');
        $sms = $content;
    	if (!ENC_IS_GBK) $sms = ENC_U2G($content);
    	if (strlen($phone) > 13) $phone = str_replace(';', ',', $phone);
    	$url  = $this->Gateway;
    	$url .= 'QxtFirewall?';
    	$url .= 'OperID='.$this->cfg['account'].'&OperPass='.$this->cfg['password'].'&DesMobile='.$phone.'&Content='.rawurlencode($sms).'&ContentType=8';
    	$this->Debug('Request: Started');
    	$this->Debug('Send: '.htmlspecialchars($content));
    	$result = $this->Get($url);
    	if ($result == '')
    	{
    	    $this->Error('Connected Failed.');
    	    return $this->result_error('reponse-empty');
    	}
    	if (!ENC_IS_GBK) $result = ENC_G2U($result);
    	$this->Debug('Response: '.htmlspecialchars($result));
    	preg_match('/<code>(\d+)<\/code>/', $result, $match);
    	$code = $match[1];
    	if ($this->IMSend_IS_SUCC($code))
    	{
    	    $this->Debug('Status: Send success.');
    	}
    	return $this->result_success(
				$this->IMSend_STATUS($code),
				array(
					'raw' => $result,
					'status' => ($this->IMSend_IS_SUCC($code) ? 'success' : 'failed')
				)
			);
    }
    
    public function IMStatus()
    {
    	$url  = $this->Gateway;
    	$url .= 'surplus?';
    	$url .= '&OperID='.$this->cfg['account'].'&OperPass='.$this->cfg['password'];
    	$result = $this->Get($url);
    	if (!ENC_IS_GBK) $result = ENC_G2U($result);
    	preg_match('/<rcode>(.*?)<\/rcode>/', $result, $match);
    	if ($match[0] != '')
    	{
    		$status = '响应正常';
    	}
    	else
    	{
    		$status = '响应异常';
    	}
    	$remain = (int)$match[1];
    	return sprintf('通道状态：%s<br/>短信剩余：%d 条', $status, $remain);
    }
    
    private function IMSend_IS_SUCC($code)
    {
    	$succ_Code = array(
    		'00' => true,
    		'01' => true,
    		'03' => true
    	);
    	if (isset($succ_Code[$code]) && $succ_Code[$code])
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    private function IMSend_STATUS($code)
    {
    	$code_STA = array(
    		'00' => '批量提交待审批',
    		'01' => '批量提交成功',
    		'03' => '短信提交成功',
    		'04' => '用户名错误',
    		'05' => '密码错误',
	    	'06' => '剩余条数不足',
	    	'07' => '存在敏感信息',
	    	'08' => '信息内容为黑内容',
	    	'09' => '短信内容重复',
	    	'10' => '批量下限不足',
    		'97' => '短信参数有误',
    		'98' => '防火墙无法处理这种短信'
    	);
    	if (isset($code_STA[$code]))
    	{
    		return $code_STA[$code];
    	}
    	else
    	{
    		return 'ERROR';
    	}
    }
}

?>