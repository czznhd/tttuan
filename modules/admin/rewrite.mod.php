<?php
/**
 * 文件名：keyword.mod.php
 * 版本号：1.0
 * 最后修改时间：Tue Aug 28 14:25:32 CST 2007
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：角色操作模块
 */

class ModuleObject extends MasterObject
{
	
	function ModuleObject($config)
	{
		$this->MasterObject($config);
		Load::moduleCode($this);$this->Execute();
	}

	
	function Execute()
	{
		switch($this->Code)
		{
	
			case 'modify':
				$this->Main();
				break;
			case 'domodify':
				$this->DoModify();
				break;
			case 'update_cache':
				$this->UpdateCache();
				break;
			case 'check':
				$this->Check();
				break;
			default:
				$this->Main();
				break;
		}
	}
	
	
	function Main()
	{
				$mode_list=array
		(
			''=>array('name'=>"不启用",'value'=>""),
			'stand'=>array('name'=>"标准rewrite模式",'value'=>"stand"),
			'apache_path'=>array('name'=>"apache路径模式",'value'=>"apache_path"),
					);
		
				$config_file=CONFIG_PATH.'rewrite.php';
		include($config_file);
				
				$mode_select=FormHandler::Select('mode',$mode_list,$_rewrite['mode']);
		

		include handler('template')->file('@admin/setting_rewrite');
		
	}

	function DoModify()
	{
				$config_file=CONFIG_PATH.'rewrite.php';
		include($config_file);
		$_rewrite['mode']=$this->Post['mode'];
		$_rewrite['abs_path']=str_replace("/"."/","","/".trim($this->Post['abs_path'],"/")."/");
		
		$gateway=array("stand"=>"","apache_path"=>"index.php/","normal"=>"?",""=>"");
		$_rewrite['gateway']=$gateway[$_rewrite['mode']];
		
		$this->_writeConfig("",'rewrite',$_rewrite);
		if($_rewrite['mode']=='stand')
		{
			$this->_writeHtaccess($_rewrite['abs_path']);
		}
		else
		{
					}
				$this->UpdateCache();
		
		$this->Messager("修改成功,正在更新缓存","admin.php?mod=rewrite&code=update_cache");
	}
	
	function _writeConfig($domain,$name,$config)
	{
		$file=CONFIG_PATH.'rewrite.php';
		@$fp=fopen($file,"wb");
		if($fp==false)$this->Messager("无法写入配置文件,请修改".$file."文件属性为0777");
		if($name=='settings')
		fwrite($fp,"<?php\r\n \$_rewrite=".var_export($config,true).";\r\n?>");
		else
		fwrite($fp,"<?php\r\n \$_rewrite=".var_export($config,true).";\r\n?>");
		fclose($fp);
	}
	function _writeHtaccess($abs_path)
	{
		$abs_path=rtrim($abs_path,"/");
		$is_local=preg_match("~^localhost|127\.0\.0\.1|192\.168\.\d+\.\d+$~",$_SERVER['SERVER_ADDR']);
		$str="# BEGIN WordPress
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
# END WordPress";
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
	
	function check()
	{
		
	}
}
?>
