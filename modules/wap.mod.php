<?php

/**
 * 模块：WAP
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name wap.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
    function ModuleObject( $config )
    {
        $this->MasterObject($config);
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    function Main()
    {
        include handler('template')->file('@wap/index');
    }
	
    public function account_login()
	{
		include handler('template')->file('@wap/account_login');
	}
	
	public function account_logcheck()
	{
		$username = post('username', 'txt');
		$password = post('password', 'txt');
		$loginR = account()->Login($username, $password, true); 		if ($loginR['error'])
		{
			$errmsg = $loginR['result'];
			include handler('template')->file('@wap/account_login');
		}
		else
		{
			$ref = account()->loginReferer();
			$ref || $ref = rewrite('index.php?mod=wap');
			header('Location: '.$ref);
		}
	}
	
	public function account_logout()
	{
		account()->Logout(MEMBER_NAME);
		header('Location: '.rewrite('index.php?mod=wap'));
	}
	
	public function coupon_input()
	{
		$msgcode = get('msgcode', 'chars');
		$number = get('number') ? get('number', 'number') : '';
		$password = get('password') ? get('password', 'number') : '';
		if ($msgcode)
		{
			$mmaps = array(
				'ops-success' => '验证消费成功！',
				'input-blank' => '请输入号码和密码！',
				'not-found' => '团购券输入无效！',
				'access-denied' => '此券不是您的产品！',
				'password-wrong' => '团购券密码错误！',
				'be-used' => '此券已经被使用了！',
				'be-overdue' => '此券已经过期了！',
				'be-invalid' => '此券已经失效了！'
			);
			$msg = isset($mmaps[$msgcode]) ? $mmaps[$msgcode] : '未知错误';
			if ($msgcode == 'ops-success')
			{
				$product = logic('coupon')->ProductGet(get('last', 'number'));
			}
		}
		include handler('template')->file('@wap/coupon_input');
	}
	
	public function coupon_verify()
	{
		$number = post('number') ? post('number', 'number') : '';
		$password = post('password') ? post('password', 'number') : '';
		if ($number && $password)
		{
			$result = logic('coupon')->MakeUsed($number, $password);
			if ($result['error'])
			{
				$this->coupon_input_msg($result['errcode'], $number, $password);
			}
			else
			{
				$this->coupon_input_msg('ops-success', '', '', $number);
			}
		}
		else
		{
			$this->coupon_input_msg('input-blank', $number, $password);
		}
	}
	
	private function coupon_input_msg($msgcode, $number = '', $password = '', $last = '')
	{
		$url = rewrite('index.php?mod=wap&code=coupon&op=input&msgcode='.$msgcode.'&number='.$number.'&password='.$password.'&last='.$last);
		header('Location: '.$url);
	}
}

?>