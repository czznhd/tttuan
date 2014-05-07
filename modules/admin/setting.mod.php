<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name setting.mod.php
 * @date 2014-01-11 12:49:16
 */
 



class ModuleObject extends MasterObject
{
	
	var $FormHandler = null;
	
	var $IoHandler=null;



	
	function ModuleObject($config)
	{
		$this->MasterObject($config);

		$this->FormHandler = new FormHandler;

		include_once(LIB_PATH.'io.han.php');
		$this->IoHandler=new IoHandler;

		Load::moduleCode($this);$this->Execute();
	}

	
	function Execute()
	{

		switch($this->Code)
		{
			case 'modify_normal':
				$this->ModifyNormal();
				break;
			case 'domodify_normal':
				$this->DoModifyNormal();
				break;

			case 'modify_credits':
				$this->ModifyCredits();
				break;
			case 'domodify_credits':
				$this->DoModifyCredits();
				break;


			case 'modify_header_menu':
				$this->ModifyHeaderMenu();
				break;
			case 'domodify_header_menu':
				$this->DoModifyHeaderMenu();
				break;

			case 'modify_header_sub_menu':
				$this->ModifyHeaderSubMenu();
				break;
			case 'modify_header_sub_menu':
				$this->DoModifyHeaderSubMenu();
				break;
				
			case 'modify_rewrite':
				$this->ModifyRewrite();
				break;
			case 'domodify_rewrite':
				$this->DoModifyRewrite();
				break;
				
			case 'modify_remote':
				$this->ModifyRemote();
				break;
			case 'domodify_remote':
				$this->DoModifyRemote();
				break;
				
			case 'modify_filter':
				$this->ModifyFilter();
				break;
			case 'domodify_filter':
				$this->DoModifyFilter();
				break;

			case 'modify_access':
				$this->ModifyAccess();
				break;
			case 'domodify_access':
				$this->DoModifyAccess();
				break;
				
			case 'modify_seccode':
				$this->ModifySeccode();
				break;
			case 'do_modify_seccode':
				$this->DoModifySeccode();
				break;
				
			case 'modify_smtp':
				$this->ModifySmtp();
				break;
			case 'do_modify_smtp':
				$this->DoModifySmtp();
				break;				
	
			case 'modify_shortcut':
				$this->ModifyShortcut();
				break;
			case 'do_modify_shortcut':
				$this->DoModifyShortcut();
				break;				
			case 'mnote':
				$this->mnote();
				break;
			case 'push':
				$this->push();
				break;
			default:
				$this->ModifyNormal();
				break;
		}

	}

	
	function ModifyNormal()
	{
		$action="admin.php?mod=setting&code=domodify_normal";
		
		if(false === ($role_list = cache("misc/role/admin_role_list",-1))) {	
			$sql="
			SELECT
				id,name,`type`
			FROM
				".TABLE_PREFIX.'system_role';
			$query = $this->DatabaseHandler->Query($sql);
			while($row=$query->getRow())
			{
				$role_list[$row['type']][]=array('name'=>$row['name'],'value'=>$row['id']);
			}
			
			cache($role_list);
		}
		
				$default_module_list=ConfigHandler::get('header_menu','list');
		$default_module_select=$this->FormHandler->Select('config[default_module]',$default_module_list,$this->Config['default_module']);
		
		$_config = array(
			"-12" => array("value"=>"-12","name"=>"(GMT -12:00) Eniwetok, Kwajalein"),
			"-11" => array("value"=>"-11","name"=>"(GMT -11:00) Midway Island, Samoa"),
			"-10" => array("value"=>"-10","name"=>"(GMT -10:00) Hawaii"),
			"-9" => array("value"=>"-9","name"=>"(GMT -09:00) Alaska"),
			"-8" => array("value"=>"-8","name"=>"(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana"),
			"-7" => array("value"=>"-7","name"=>"(GMT -07:00) Mountain Time (US &amp; Canada), Arizona"),
			"-6" => array("value"=>"-6","name"=>"(GMT -06:00) Central Time (US &amp; Canada), Mexico City"),
			"-5" => array("value"=>"-5","name"=>"(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito"),
			"-4" => array("value"=>"-4","name"=>"(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz"),
			"-3.5" => array("value"=>"-3.5","name"=>"(GMT -03:30) Newfoundland"),
			"-3" => array("value"=>"-3","name"=>"(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is"),
			"-2" => array("value"=>"-2","name"=>"(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena"),
			"-1" => array("value"=>"-1","name"=>"(GMT -01:00) Azores, Cape Verde Islands"),
			"0"  =>array("value"=>"0","name"=>"(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia"),
			"1" => array("value"=>"1","name"=>"(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome"),
			"2" => array("value"=>"2","name"=>"(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa"),
			"3" => array("value"=>"3","name"=>"(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi"),
			"3.5" => array("value"=>"3.5","name"=>"(GMT +03:30) Tehran"),
			"4" => array("value"=>"4","name"=>"(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi"),
			"4.5" => array("value"=>"4.5","name"=>"(GMT +04:30) Kabul"),
			"5" => array("value"=>"5","name"=>"(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent"),
			"5.5" => array("value"=>"5.5","name"=>"(GMT +05:30) Bombay, Calcutta, Madras, New Delhi"),
			"5.75" => array("value"=>"5.75","name"=>"(GMT +05:45) Katmandu"),
			"6" => array("value"=>"6","name"=>"(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk"),
			"6.5" => array("value"=>"6.5","name"=>"(GMT +06:30) Rangoon"),
			"7" => array("value"=>"7","name"=>"(GMT +07:00) Bangkok, Hanoi, Jakarta"),
			"8" => array("value"=>"8","name"=>"(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei"),
			"9" => array("value"=>"9","name"=>"(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk"),
			"9.5" => array("value"=>"9.5","name"=>"(GMT +09:30) Adelaide, Darwin"),
			"10" => array("value"=>"10","name"=>"(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok"),
			"11" => array("value"=>"11","name"=>"(GMT +11:00) Magadan, New Caledonia, Solomon Islands"),
			"12" => array("value"=>"12","name"=>"(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island"),
		);
		$timezone_select = $this->FormHandler->Select("config[timezone]",$_config,(int) $this->Config['timezone']);
		$close_second_verify_enable_radio = $this->FormHandler->YesNoRadio("config[close_second_verify_enable]",(boolean) $this->Config['close_second_verify_enable']);

		$normal_role_select=$this->FormHandler->Select('config[normal_default_role_id]',
		$role_list['normal'],
		$this->Config['normal_default_role_id']);		
		
		$no_verify_email_role_select=$this->FormHandler->Select('config[no_verify_email_role_id]',
		$role_list['normal'],
		$this->Config['no_verify_email_role_id']);


		$gzip_radio=$this->FormHandler->YesNoRadio("config[gzip]",(int)$this->Config['gzip']);

	
		
		
		$user_forbid = ConfigHandler::get('user','forbid');
		
		@$site_enable = file_get_contents(CACHE_PATH . './site_enable.php');
		
		include handler('template')->file('@admin/setting_normal');
	}

	
	function DoModifyNormal()
	{
		if($this->Post['site_enable']) {
			$this->IoHandler->WriteFile(CACHE_PATH . './site_enable.php',$this->Post['site_enable']);
		} else {
			$this->IoHandler->DeleteFile(CACHE_PATH . './site_enable.php');
		}
		unset($this->Post['site_enable']);
		
		if($this->Post['user_forbid']) {
			$forbid_list = explode("\r\n",$this->Post['user_forbid']);
			$forbid_list = array_unique($forbid_list);
			$forbid = implode("\r\n",$forbid_list);
			ConfigHandler::set('user',array('forbid'=>$forbid));
		}
		unset($this->Post['user_forbid']);
		
		extract($this->Post['config']);
		if($site_name=="")
		{
			$this->Messager("修改出现错误,站点名称不能为空");
		}
		if(default_role_id=='')
		{
			$this->Messager("修改出现错误,请先选择一个角色");
		}
		if (!$_FILES['config']['error']['site_logo']) {
			$this->Post['config']['site_logo'] = IMAGE_PATH . 'site_logo.gif';
			if(!move_uploaded_file($_FILES['config']['tmp_name']['site_logo'],$this->Post['config']['site_logo'])) {
				@copy($_FILES['config']['tmp_name']['site_logo'],$this->Post['config']['site_logo']);
			}
			if (!is_file($this->Post['config']['site_logo'])) {
				unset($this->Post['config']['site_logo']);
			}
		}

		$this->Post['config']['thumbwidth'] = min(300,max(30,(int) $this->Post['config']['thumbwidth']));
		$this->Post['config']['thumbheight'] = min(300,max(30,(int) $this->Post['config']['thumbheight']));
		$this->Post['config']['watermark_position'] = (int) $this->Post['config']['watermark_position'];
		
		include(CONFIG_PATH.'settings.php');
		

		$new_config=array_merge($config['settings'],$this->Post['config']);
		ksort($new_config);
		$new_config['copyright']=HttpHandler::UnCleanVal($new_config['copyright']);
		$new_config['tongji']=HttpHandler::UnCleanVal($new_config['tongji']);
		$result = ini('settings', $new_config);
		if($result!=false)
		{
			$this->Messager("配置修改成功");
		}
		else
		{
			$this->Messager("配置修改失败");
		}

	}

	

	function ModifyCredits()
	{
		$action="admin.php?mod=setting&code=domodify_credits";
		
		
		unset($config);
		include(CONFIG_PATH.'credits.php');
				include handler('template')->file('@admin/setting_credits');
	}
	
	function DoModifyCredits()
	{
		$config=array();
		foreach($this->Post as $key=>$val)
		{
			if(strpos($key,'credits')!==false)
			{
				$new_config[$key]=$val;
			}
		}
		$file=CONFIG_PATH.'credits.php';
		$result=$this->IoHandler->UpdateFileArray($file,'config',$new_config);
		if($result!=false)
		{
			$this->Messager("配置修改成功");
		}
		else
		{
			$this->Messager("配置修改失败，请检查{$file}文件是否有可写的权限。");
		}
	}
	
	
	
	function ModifyRewrite()
	{
				$mode_list=array
		(
			''=>array('name'=>"不启用静态化",'value'=>""),
			'stand'=>array('name'=>"标准Rewrite模式",'value'=>"stand"),
			'apache_path'=>array('name'=>"路径模式",'value'=>"apache_path"),
					);
		
				$config_file=CONFIG_PATH.'rewrite.php';
		include($config_file);
				
				$mode_select=FormHandler::Select('mode',$mode_list,$_rewrite['mode']);
		
		$mod_list=array(
			"首页" =>"index",	
			"列表" =>"list",
			"用户页" => "me",
					);
		$mod_alias=$_rewrite['value_replace_list']['mod'];
		
		include handler('template')->file('@admin/setting_rewrite');
		
	}

	function DoModifyRewrite()
	{
		$mod_alias=array();
		foreach ((array)$this->Post['mod_alias'] as $old_name=>$new_name)
		{
			$new_name=trim($new_name);
			if(!empty($new_name) && $old_name!=$new_name && preg_match("~^[A-Za-z0-9_]+$~",$new_name))
			{
				$mod_alias[$old_name]=$new_name;
			}
		}
				$config_file=CONFIG_PATH.'rewrite.php';
		include($config_file);
		$_rewrite['mode']=$this->Post['mode'];
		$_rewrite['abs_path']=preg_replace("/\/+/",'/',str_replace("\\",'/',dirname($_SERVER['PHP_SELF']))."/");
				
		$gateway=array("stand"=>"","apache_path"=>"index.php/","normal"=>"?",""=>"");
		$_rewrite['gateway']=$gateway[$_rewrite['mode']];
		
		if(!empty($mod_alias))
		{
			$_rewrite['value_replace_list']['mod']=$mod_alias;
		}
		else
		{
			unset($_rewrite['value_replace_list']['mod']);
		}
		
		$this->_saveRewriteConfig("",'rewrite',$_rewrite);
		if($_rewrite['mode']=='stand')
		{
			$this->_writeHtaccess($_rewrite['abs_path']);
		}
		else
		{
					}
		clearcache();
		
		$this->Messager("修改成功,正在更新缓存");
	}

	function ModifyFilter()
	{
		$filter=ConfigHandler::get('filter');
		$enable_radio=FormHandler::YesNoRadio("filter[enable]",(int)$filter['enable']);
		
		include(handler('template')->file("@admin/setting_filter"));
	}
	function DoModifyFilter()
	{
		$last_tid=(int)ConfigHandler::get('filter','item_list','thread','last_tid');
		$this->Post['filter']['item_list']['thread']['last_tid']=$last_tid;		
		
				$this->Post['filter']['keywords']=trim($this->Post['filter']['keywords']);
		$keywords=str_replace(array("\s","\r\n","\n","\\|"),"|",$this->Post['filter']['keywords']);
		if ($keywords)
		{
			$tmp_keyword_list=explode("|",$keywords);
			$keyword_list = array();
			foreach($tmp_keyword_list as $k=>$v) {
				$v = trim($v);
				if(($vl=strlen($v))>2 && $vl<40) {
					$keyword_list[] = $v;
				}
			}
			$keyword_list=array_unique($keyword_list);
			$this->Post['filter']['keywords']=implode("\r\n",$keyword_list);
			sort($keyword_list);
			$this->Post['filter']['keywords_crc32']=crc32(implode("",$keyword_list));
		}
		ConfigHandler::set('filter',$this->Post['filter']);
		$this->Messager("修改成功");
	}

	function modifyAccess()
	{
		$access=(array)ConfigHandler::get('access');
		foreach ($access as $type =>$ips)
		{
			if(!empty($ips))
			{
				$ips=str_replace("|","\n",$ips);
				$access[$type]=stripslashes($ips);
			}
		}
		$action="admin.php?mod=setting&code=domodify_access";
		include handler('template')->file('@admin/setting_access');
	}
	function DoModifyAccess()
	{
		$access=(array)$this->Post['access'];
		$access['ipbanned']=trim($access['ipbanned']);
		$access['admincp']=trim($access['admincp']);
		foreach ($access as $type =>$ips)
		{
			if(!empty($ips))
			{
				$ips=preg_replace("/[\r\n]+/i","\n",$ips);
				$_ip_list=explode("\n",$ips);
				$access[$type]=$sep="";
				foreach ($_ip_list as $_ip)
				{
					if(preg_match("/^(\d{1,3}\.){1,3}\d{1,3}\.?$/",$_ip))
					{
						$access[$type].=$sep.str_replace(".","\.",$_ip);
						$sep="|";
					}
				}
			}
		}
				if(!empty($access['ipbanned']) && preg_match("~^({$access['ipbanned']})~",$_SERVER['REMOTE_ADDR']))
		{
			$this->Messager("您当前的IP在禁止IP里，无法设置。");
		}
				if(!empty($access['admincp']) && !preg_match("~^({$access['admincp']})~",$_SERVER['REMOTE_ADDR']))
		{
			$this->Messager("您当前的IP在不在后台允许的IP里，无法设置。",-1);
		}
		
		ConfigHandler::set('access',$access);
		
		$ipbanned_enable = (boolean) $access['ipbanned'];
		if($ipbanned_enable!=$this->Config['ipbanned_enable']) {
			unset($config);
			include(CONFIG_PATH . 'settings.php');
			$config['ipbanned_enable'] = $ipbanned_enable;
			
			ConfigHandler::set($config);
		}		
		
		$this->Messager("设置成功");
	}
	
	function ModifySeccode()
	{
		$seccode = ConfigHandler::get('seccode');
		
		$seccode_enable_radio = $this->FormHandler->YesNoRadio('seccode[enable]',$this->Config['seccode_verify']);
		$seccode_type_radio = $this->FormHandler->YesNoRadio('seccode[type]',$seccode['type']);
		$seccode_onclickdisplay_radio = $this->FormHandler->YesNoRadio('seccode[onclickdisplay]',$seccode['onclickdisplay']);
		$seccode_background_radio = $this->FormHandler->YesNoRadio('seccode[background]',$seccode['background']);
		$seccode_adulterate_radio = $this->FormHandler->YesNoRadio('seccode[adulterate]',$seccode['adulterate']);
		$seccode_ttf_radio = $this->FormHandler->YesNoRadio('seccode[ttf]',$seccode['ttf']);
		$seccode_angle_radio = $this->FormHandler->YesNoRadio('seccode[angle]',$seccode['angle']);
		$seccode_color_radio = $this->FormHandler->YesNoRadio('seccode[color]',$seccode['color']);
		$seccode_size_radio = $this->FormHandler->YesNoRadio('seccode[size]',$seccode['size']);
		$seccode_shadow_radio = $this->FormHandler->YesNoRadio('seccode[shadow]',$seccode['shadow']);
		$seccode_animator_radio = $this->FormHandler->YesNoRadio('seccode[animator]',$seccode['animator']);
		
		$where = array(		
			'login' => array(
				'name' => "用户登录",
			),
			'register' => array(
				'name' => "用户注册",
			),
						
		);
		$seccode_verify_where_checkbox = $this->FormHandler->CheckBox('seccode[item][]',$where,$seccode['item']);
		
		$action = "admin.php?mod=setting&code=do_modify_seccode";
		include(handler('template')->file('@admin/setting_seccode'));
	}
	function DoModifySeccode()
	{
		$seccode = (array) $this->Post['seccode'];
		$seccode['type'] = (int) ((boolean) $seccode['type']);
		if($seccode['type']) {
			$ch = false;
			$_chl = $this->IoHandler->ReadDir(ROOT_PATH . 'images/fonts/ch');
			if(is_array($_chl) && count($_chl)) {
				foreach ($_chl as $_val) {
					if ('ttf' == strtolower(trim(substr(strrchr($_val, '.'), 1, 10)))) {
						$ch = true;
						break;
					}
				}
			}
			
			if ($ch = false) {
				$this->Messager('开启中文图'.'片验证码前，需要将包含完'.'整中文汉字的 TTF 中'.'文字体文件上传到 ima'.'ges/fo'.'nts/ch 目'.'录下，<a hr'.'ef=ht'.'tp:/'.'/ai'.'j'.'uhe'.'.net/gro'.'up_'.'thread/'.'view/id-'.'802 target="_blank"><font '.'color=r'.'ed>点此查'.'看说明及下载'.'记事狗官方提'.'供的中文字体</f'.'ont></a>',null);
			}
		}
		
		$sec_enable = $seccode['enable'];
		unset($seccode['enable']);
		ConfigHandler::set('seccode',$seccode);
		
		if ($sec_enable!=$this->Config['seccode_verify']) {
			unset($config);
			include(CONFIG_PATH . './settings.php');
			$config['seccode_verify'] = $sec_enable;
			ConfigHandler::set($config);
		}
		
		$this->Messager("设置成功");
	}
	
	function ModifySmtp()
	{
		$smtp = ConfigHandler::get('smtp');
		
		$smtp_enable_radio = $this->FormHandler->YesNoRadio('smtp[enable]',(int) $smtp['enable']);
		
		$action = "admin.php?mod=setting&code=do_modify_smtp";
		include(handler('template')->file('@admin/setting_smtp'));
	}
	function DoModifySmtp()
	{					
		if('请输入SMTP服务器的用户密码'==$_POST['smtp']['password']) {
			$smtp_config = ConfigHandler::get('smtp');
			$_POST['smtp']['password'] = $smtp_config['password'];
		}
		
		ConfigHandler::set('smtp',$_POST['smtp']);
		
		if($_POST['settingsubmitandtesting'] && $_POST['smtp']['mail']) {
			$smtp_config = ConfigHandler::get('smtp');
			
			Load::lib('mail');
			$success=send_mail($smtp_config['mail'],$this->Config['site_name'] . '　测试邮件的标题','邮件测试正文',$this->Config['site_name'],$smtp_config['mail']);
			$msg = "，并向<b>{$smtp_config['mail']}</b>邮箱发送了一封测试邮件，请注意查收";
			if($success==false){
				echo '邮件发送失败';exit;
			}
		}
		
		$this->Messager("设置成功{$msg}");
	}
	
	function ModifyShortcut()
	{
		$action = 'admin.php?mod=setting&code=do_modify_shortcut';
		unset($menu_list);
		include(CONFIG_PATH . 'admin_left_menu.php');
		
		include(handler('template')->file('@admin/setting_shortcut'));
	}
	function DoModifyShortcut()
	{
		unset($menu_list);
		$cfg_file = CONFIG_PATH . 'admin_left_menu.php';
		include($cfg_file);
		
		foreach ($menu_list as $m_key=>$m_val) {
			if($m_val['sub_menu_list'] && is_array($m_val['sub_menu_list']) && count($m_val['sub_menu_list'])) {
				foreach ($m_val['sub_menu_list'] as $s_m_key=>$s_m_val) {
					$menu_list[$m_key]['sub_menu_list'][$s_m_key]['shortcut'] = (boolean) $this->Post['menu_list'][$m_key][$s_m_key]['shortcut'];
				}
			}			
		}
		
		
		$this->IoHandler->WriteFile($cfg_file,'<?php $menu_list = '.var_export($menu_list,true).'; ?>');
				
		$this->Messager("修改成功");
	}
	
	function _saveRewriteConfig($domain,$name,$config)
	{
		$file=CONFIG_PATH.'rewrite.php';
		$fp=fopen($file,"wb");
		if($fp==false)$this->Messager("无法写入配置文件");
		if($name=='settings')
		fwrite($fp,"<?php\r\n \$_rewrite=".var_export($config,true).";\r\n?>");
		else
		fwrite($fp,"<?php\r\n \$_rewrite=".var_export($config,true).";\r\n?>");
		fclose($fp);
	}
	function _writeHtaccess($abs_path)
	{
		$is_local=preg_match("~^localhost|127\.0\.0\.1|192\.168\.\d+\.\d+$~",$_SERVER['SERVER_ADDR']);
		$str="# BEGIN TTTuangou
<IfModule mod_rewrite.c>
RewriteEngine On
".
($is_local?"Options FollowSymLinks":"")
."
RewriteBase $abs_path
RewriteCond %{REQUEST_URI}	!\.(gif|jpeg|png|jpg|bmp)$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php [L]
</IfModule>
# END TTTuangou";
		@$fp=fopen(ROOT_PATH.".htaccess","wb");
		if($fp==false)$this->Messager(".htaccess文件无法写入，请检查根目录是否有可写权限。");
		fwrite($fp,$str);
		fclose($fp);
	}
	
	function UpdateCache()
	{
		clearcache();
		$this->Messager("修改成功","admin.php?mod=rewrite");
	}

	function mnote(){
		echo '请到<a href="admin.php?mod=payment">支付宝移动支付</a>里面进行操作！';
	}

	function push(){
		echo '安卓客户端通知栏推送配置说明见<a href="http://t.jishigou.net/topic/262230" target="_blank">链接</a>';
	}
}

?>