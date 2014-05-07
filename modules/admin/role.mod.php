<?php
/**
 * 文件名：role.mod.php
 * 版本号：1.0
 * 最后修改时间：2006年8月14日 2:22:27
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：角色操作模块
 */

class ModuleObject extends MasterObject
{
	
	var $ID = 0;

	
	var $ModuleList;

	
	function ModuleObject($config)
	{
		$this->MasterObject($config);
		$this->ID = (int)$this->Get['id']?(int)$this->Get['id']:(int)$this->Post['id'];
		
		$sql="SELECT name,module from ".TABLE_PREFIX.'system_role_module';
		$query = $this->DatabaseHandler->Query($sql);
		while ($row=$query->GetRow()) 
		{
			$this->ModuleList[$row['module']]=$row['name'];
		}

		Load::moduleCode($this);$this->Execute();
	}

	
	function Execute()
	{
		switch($this->Code)
		{
			case 'list':
				$this->Main();
				break;

			case 'add':
				$this->Add();
				break;
			case 'doadd':
				$this->DoAdd();
				break;
			case 'delete':
				$this->DoDelete();
				break;

			case 'modify':
				$this->Modify();
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
		$role_type=in_array($this->Get['type'],array('admin','normal'))
						?$this->Get['type']
						:'normal';
		$sql="
		SELECT
			*
		FROM
			".TABLE_PREFIX.'system_role'."
		where
			`type`='{$role_type}'
		ORDER BY
				creditshigher,Rank";
		$query = $this->DatabaseHandler->Query($sql);
		$role_list=array();
		while($row = $query->GetRow())
		{
			$role_list[] = $row;
		}

		include handler('template')->file('@admin/role_list');

	}





	
	 function Add() 
	 {

		 $action="admin.php?mod=role&code=doadd";
		 $title="添加";
		 $sql="SELECT * FROM ".TABLE_PREFIX.'system_role_action';
		 $query = $this->DatabaseHandler->Query($sql);
		 $privilege_list=$query->GetAll();
		 
		 $options=array(
			 array('name'=>'管理员组','value'=>'admin'),
			 array('name'=>'普通用户组','value'=>'normal')
			 );
		 $type_select=FormHandler::Select('type',$options);
	
		 		 $privileges=explode(',',$role_info['privilege']);
		 foreach($privilege_list as $key=>$privilege) 
		 {
			 if($privilege['allow_all']==1 && false === AIJUHE_FOUNDER)
			 {
				 $privilege['disabled']=" disabled";
			 }

			 $module_name=isset($this->ModuleList[$privilege['module']])
							?$this->ModuleList[$privilege['module']]
							:"[其它]权限";

			 if(in_array($privilege['id'],$privileges) or 
				 $privileges[0]=="*" or 
				 $privilege['allow_all']==1) 
			 {
			 	$privilege['checked']=" checked";
			 }

			 $privilege['link']="admin.php?mod=role_action&code=modify&id=".$privilege['id'];

			 $privilege['name']=strpos($privilege['action'],"_other")!==false?"<font color='#660099'>{$privilege['name']}</font>":$privilege['name'];
			 $module_list[($privilege['is_admin'] ? "后台权限" : "前台权限")][$module_name][]=$privilege;
		 }
				 krsort($module_list);
		 
		 include handler('template')->file('@admin/role_info');
	 }

	
	 function DoAdd() 
	 {

		 $data=array(
				'name'=>$this->Post['name'],
				'type'=>$this->Post['type'],
				'creditshigher'=>$this->Post['creditshigher'],
				'creditslower'=>$this->Post['creditslower'],
				'privilege'=>implode(',',(array)$this->Post['privilege']));
		 $this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_role');
		 $result=$this->DatabaseHandler->Insert($data);
		 if($result!=false) 
		 {
			 $this->Messager("添加成功",'admin.php?mod=role');		 	
		 }
		 else 
		 {
		 	$this->Messager("添加失败");	
		 }

	 }

	 
	 function DoDelete() 
	 {
		$this->GroupHandler->Delete($this->IDS,USER_ID);
		if($error=$this->GroupHandler->GetError()) 
		{
			$this->Error("删除出现错误",implode("<br>",$error));
		}
		$this->DatabaseHandler->Cache->ClearCacheByName('index_group_list','group');
		$this->Messager("您选择的记录已经成功删除","index.php?mod=group");
	 }




	
	 function Modify() 
	 {

		 		 $sql="
		 SELECT
			 *
		 FROM
			 ".TABLE_PREFIX.'system_role'."
		 WHERE
			 id={$this->ID}";
		 $query = $this->DatabaseHandler->Query($sql);
		 $role_info=$query->GetRow();

		 if($role_info==false) 
		 {
		 	$this->Messager("您要编辑的角色信息已经不存在!");
		 }

		 $action="admin.php?mod=role&code=domodify";
		 $title="修改";
		 $sql="SELECT * FROM ".TABLE_PREFIX.'system_role_action';
		 $query = $this->DatabaseHandler->Query($sql);
		 $privilege_list=$query->GetAll();
		 		 $privileges=explode(',',$role_info['privilege']);
		 foreach($privilege_list as $key=>$privilege) 
		 {
			 if($privilege['allow_all']==1 && false === AIJUHE_FOUNDER)
			 {
				 $privilege['disabled']=" disabled";
			 }

			 $module_name=isset($this->ModuleList[$privilege['module']])
							?$this->ModuleList[$privilege['module']]
							:"[其它]权限";

			 if(in_array($privilege['id'],$privileges) or 
				 $privileges[0]=="*" or 
				 $privilege['allow_all']==1) 
			 {
			 	$privilege['checked']=" checked";
			 }

			 $privilege['link']="admin.php?mod=role_action&code=modify&id=".$privilege['id'];

			 $privilege['name']=strpos($privilege['action'],"_other")!==false?"<font color='#660099'>{$privilege['name']}</font>":$privilege['name'];
			 $module_list[($privilege['is_admin'] ? "后台权限" : "前台权限")][$module_name][]=$privilege;
		 }
		 krsort($module_list);

		 include handler('template')->file('@admin/role_info');
	 }


	
	 function DoModify() 
	 {

		 		 $this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_role');
		 $role=$this->DatabaseHandler->Select($this->ID);
		 if ($role==false)
		 {
		 	$this->Messager("该角色已经不存在",null);
		 }
		 		 $data=array(
				'id'=>$this->ID,
				'name'=>$this->Post['name'],
				'creditshigher'=>$this->Post['creditshigher'],
								'creditslower'=>$this->Post['creditslower'],
				'privilege'=>implode(',',(array)$this->Post['privilege']));

		 $result=$this->DatabaseHandler->Update($data);
		 if($result===false) 
		 {
		 	$this->Messager("编辑失败");	
		 }
		 else 
		 {
		 	cache('role/role_'.$role['id'],0);
		 	$this->Messager("编辑成功");		 
		 }

	 }



}

?>