<?php
/**
 * 文件名：login.mod.php
 * 版本号：(1.0)
 * 最后修改时间：2006年8月22日 18:58:20
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：用户登录
 */

class ModuleObject extends MasterObject
{


	
	var $Code = false;

	
	var $Username = '';

	
	var $Password = '';
	
	var $Secques = '';

	
	function ModuleObject($config)
	{
		$this->MasterObject($config);
		Load::moduleCode($this);$this->Execute();
	}

	
	function Execute()
	{
		$this -> config=ConfigHandler::get('product');
				$sql='select * from '.TABLE_PREFIX.'tttuangou_city ';
		$query = $this->DatabaseHandler->Query($sql);
		$this -> cityary=$query->GetAll();
				if($_GET['city']!=''){
			foreach($this -> cityary as $value){
				if($value['shorthand'] == $_GET['city']){
					$this->CookieHandler->setVar('mycity',$value['cityid']);
					$this -> city =$value['cityid'];
					break;
				};
			};
		};
				if($this -> city == ''){
			if($this->CookieHandler->getVar('mycity')!=''){
				$this -> city = $this->CookieHandler->getVar('mycity');
			}else{
				$this -> city=1;
			};
		};
				foreach($this -> cityary as $value){
			if($value['cityid'] == $this -> city){
				$this -> cityname = $value['cityname'];
				break;
			};
		};
		ob_start();
		$load_file=array("vivian_reg.css",'validate.js');
		switch($this->Code)
		{
			case 'dologin':
				$this->DoLogin();
				break;
			case 'logout':
				$this->LogOut();
				break;
			default:
				$this->login();
				break;
		}
		$body=ob_get_clean();
		
		$this->ShowBody($body);
	}
	

}

?>