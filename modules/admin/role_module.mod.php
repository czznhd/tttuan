<?php
/**
 * 文件名：linkvertisement.mod.php
 * 版本号：1.0
 * 最后修改时间：Tue Oct 30 13:16:22 CST 2007
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：广告模块管理
 */

class ModuleObject extends MasterObject
{

	var $Code = array();
	
	var $ID = 0;

	
	function ModuleObject($config)
	{
		$this->MasterObject($config);
		$this->Code = $this->Get['code']?$this->Get['code']:$this->Post['code'];
		$this->ID = $this->Get['id']?(int)$this->Get['id']:(int)$this->Post['id'];
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
			default:
				$this->Main();
				break;
		}
	}
	
	
	function Main()
	{
		$this->Modify();
	}
	
	function Modify()
	{
		$sql="SELECT * from ".TABLE_PREFIX."system_role_module order by module";
		$query = $this->DatabaseHandler->Query($sql);
		$module_list=array();
		while ($row=$query->GetRow()) 
		{
			$module_list[$row['module']]=$row['name'];
		}
		include(handler('template')->file('@admin/role_module'));
	}
	
	function DoModify()
	{
		$this->DatabaseHandler->SetTable(TABLE_PREFIX."system_role_module");
				if(($new_module=trim($this->Post['new_module'])) 
		&& (trim($new_module_name=$this->Post['new_module_name'])))
		{
			$this->DatabaseHandler->Replace(array("module"=>$new_module,"name"=>$new_module_name));
		}
		
				$module_list=(array)$this->Post['module'];
		foreach ($module_list as $module)
		{
			$this->DatabaseHandler->Replace($module);
		}
		
				$delete_list=(array)$this->Post['delete'];
		if($delete_list)
		{
			$module_in=$this->DatabaseHandler->BuildIn($delete_list,"module");
			$this->DatabaseHandler->Delete("",$module_in);
						$sql="DELETE FROM ".TABLE_PREFIX."system_role_action where ".$module_in;
			$this->DatabaseHandler->Query($sql);
		}
					

		$this->Messager("修改成功");
	}
}
?>
