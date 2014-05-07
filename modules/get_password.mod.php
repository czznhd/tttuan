<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name get_password.mod.php
 * @date 2014-01-23 10:27:10
 */
 



class ModuleObject extends MasterObject
{
	
	var $ProductLogic;
	var $iiConfig;
	
	function ModuleObject($config)
	{
		$this->MasterObject($config);
		if (MEMBER_ID>0) {
				}
		$this->iiConfig = ini('settings');
		Load::moduleCode($this);$this->Execute();
	}

	
	function Execute()
	{
		ob_start();		
		switch ($this->Code) {
			case 'do_send':
				$this->DoSend();
				break;
			case 'do_reset';
				$this->DoReset();
				break;
				
			default:
				$this->Main();
		}				
		$body=ob_get_clean();
		
		$this->ShowBody($body);
	}

	function Main() 
	{
		$act_list = array('base'=>'取回密码','reset'=>'重设密码',);
		$act = isset($act_list[$this->Code]) ? $this->Code : 'base';
		$act_name = $act_list[$act];

		handler('form');
		$FormHandler = new FormHandler();
		
		if('base' == $act) {
		
		} elseif ('reset' == $act) {
			extract($this->_resetCheck());		
		}
		
		$this->Title = $act_list[$act];
		include($this->TemplateHandler->Template('get_password_main'));
	}
	
	function DoSend()
	{
		$this->Title = '提示';
		$to = $this->Post['to'];
		
		$sql="
		SELECT
			M.uid,MF.authstr,M.email
		FROM
			".TABLE_PREFIX. 'system_members'." M LEFT JOIN ".TABLE_PREFIX.'system_memberfields'." MF ON(M.uid=MF.uid)
		WHERE
			BINARY M.email='{$to}'";
		$query = $this->DatabaseHandler->Query($sql);
		$member=$query->GetRow();
		if ($member==false)$this->Messager("用户已经不存在", 0);
		$timestamp=time();
		if ($member['authstr']!='')
		{
			list($dateline, $operation, $idstring) = explode("\t", $member['authstr']);
			$inteval=600;			if ($dateline+$inteval>$timestamp)
			{
				$this->Messager("请不要重复恶意发送，您的请求已经发送到您的信箱中，如有问题，请与管理员联系。", 0);
			}
		}

		$idstring = random(32);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_memberfields');
		$member['authstr'] = "$timestamp\t1\t$idstring";
		$member['auth_try_times'] = 0;
		$result=$this->DatabaseHandler->Update($member,"uid={$member['uid']}");
		if ($result==false)
		{
			$this->DatabaseHandler->Insert($member);
		}
		$onlineip=$_SERVER['REMOTE_ADDR'];
				$email_message="您好：<br/>
这封信是由 {$this->iiConfig['site_name']} 发送的。<br/>
您收到这封邮件，是因为在{$this->iiConfig['site_name']}上这个邮箱地址被登记为用户邮箱，且该用户请求使用 Email 密码重置功能所致。<br/>
----------------------------------------------------------------------<br/>
重要！<br/>
----------------------------------------------------------------------<br/>
如果您没有提交密码重置的请求或不是{$this->iiConfig['site_name']}的注册用户，请立即忽略并删除这封邮件。只在您确认需要重置密码的情况下，才继续阅读下面的内容。<br/>
----------------------------------------------------------------------<br/>
密码重置说明<br/>
----------------------------------------------------------------------<br/>
您只需在提交请求后的两小时之内，通过点击下面的链接重置您的密码：<br/>
{$this->iiConfig['site_url']}/index.php?mod=get_password&code=reset&uid={$member['uid']}&id={$idstring}<br/>
(如果上面不是链接形式，请将地址手工粘贴到浏览器地址栏再访问)<br/>
<br/>
上面的页面打开后，输入新的密码后提交，之后您即可使用新的密码登录{$this->iiConfig['site_name']}了。您可以在个人设置中随时修改您的密码。<br/>
<br/>
本请求提交者的 IP 为 $onlineip<br/>
此致<br/><br/>
{$this->iiConfig['site_name']} 管理团队.<br/>
{$this->iiConfig['site_url']}";
			require("./setting/product.php");
			$set = $config['product'];
			$subject="[{$this->iiConfig['site_name']}] 取回密码说明";
			logic('service')->mail()->Send($member['email'], $subject, $email_message);
		$mail_service=strstr($member['email'], '@');
		$message=array(
		"标题为\"<b>{$subject}</b>\"的邮件已经发送到您后缀为<b>\"{$mail_service}\"</b>的信箱中，请在两小时之内修改您的密码。",
		"邮件发送可能会延迟几分钟，请耐心等待。",
		"部分邮件提供商会将本邮件当成垃圾邮件来处理，您或许可以进垃圾箱找到此邮件。",
		);
		$this->Messager($message, 0);
	}
	
	function DoReset()
	{
		$this->_resetCheck();
		
		$uid=(int)($this->Get['uid']?$this->Get['uid']:$this->Post['uid']);		
		if($this->Post['password']!=$this->Post['confirm'] or $this->Post['password']=='')
		{
			$this->Messager('两次输入的密码不一致,或新密码不能为空。',-1,null);
		}
		$password=md5($this->Post['password']);
		$sql="UPDATE ".TABLE_PREFIX. 'system_members'." SET `password`='{$password}' WHERE uid='$uid'";
		$this->DatabaseHandler->Query($sql);
		$sql="UPDATE ".TABLE_PREFIX.'system_memberfields'." SET `authstr`='',`auth_try_times`='0' WHERE uid='$uid'";
		$this->DatabaseHandler->Query($sql);
		$this->Messager("新密码设置成功,现在为您转入登录界面.","?mod=account&code=login");
	}
	
	function _resetCheck()
	{
		$uid=(int)($this->Post['uid'] ? $this->Post['uid'] : $this->Get['uid']);
		$id=$this->Post['id'] ? $this->Post['id'] : $this->Get['id'];
		if ($uid<1 or $id=='') $this->Messager("请求错误",0);

		$sql="
		SELECT
			M.uid,M.username,MF.authstr,M.email,M.secques,MF.auth_try_times
		FROM
			".TABLE_PREFIX. 'system_members'." M LEFT JOIN ".TABLE_PREFIX.'system_memberfields'." MF ON(M.uid=MF.uid)
		WHERE
			BINARY M.uid=$uid";
		$query = $this->DatabaseHandler->Query($sql);
		$member=$query->GetRow();
		if ($member==false)$this->Messager("用户已经不存在",0);
		
		$member['auth_try_times'] = (max(0, (int) $member['auth_try_times']) + 1);
		dbc(DBCMax)->Update('memberfields')->data(array('auth_try_times'=>$member['auth_try_times']))->where(array('uid'=>$uid))->done();
		if($member['auth_try_times']>=10) {$this->Messager('您尝试的错误次数太多了，请重新发起找回密码的请求!', null);}

		$timestamp=time();
		list($dateline, $operation, $idstring) = explode("\t", $member['authstr']);
		if($dateline < $timestamp - 86400 * 3 || $operation != 1 || $idstring != $id) 
		{
			$message=array(
				"重置密码的请求不存在或已经过期，无法取回密码。",
				"如您想重新设置密码，请<a href='index.php?mod=get_password'>单击此处</a>。"
			);
			$this->Messager($message,0);
		}
		$member['id'] = $id;
		
		return $member;
	}
	
}

?>
