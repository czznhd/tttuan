<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name member.mod.php
 * @date 2011-12-07 13:42:07
 */
 



class ModuleObject extends MasterObject
{

	
	
	var $Code = array();


	
	var $ID = 0;

	
	var $IDS;

	
	function ModuleObject($config)
	{
		$this->MasterObject($config);

		if(isset($this->Get['code']))
		{
			$this->Code = $this->Get['code'];
		}elseif(isset($this->Post['code']))
		{
			$this->Code = $this->Post['code'];
		}

		if(isset($this->Get['id']))
		{
			$this->ID = (int)$this->Get['id'];
		}elseif(isset($this->Post['id']))
		{
			$this->ID = (int)$this->Post['id'];
		}

		if(isset($this->Get['ids']))
		{
			$this->IDS = $this->Get['ids'];
		}elseif(isset($this->Post['ids']))
		{
			$this->IDS = $this->Post['ids'];
		}

		$this->FormHandler = new FormHandler();

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
			case 'dodelete':
			$this->DoDelete();
			break;

			case 'search':
			$this->search();
			break;
			case 'dosearch':
			$this->DoSearch();
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
		$this->DoSearch();
	}

	
	function Add()
	{
		$sql = "
		 SELECT
			 id,name
		 FROM
			 " . TABLE_PREFIX.'system_role' . "
		 WHERE
			 id!=1";
		$query = $this->DatabaseHandler->Query($sql);
		while($row = $query->GetRow())
		{
			$role_list[] = array('name' => $row['name'], 'value' => $row['id']);
		}
		$role_select = $this->FormHandler->Select('role_id', $role_list, $this->Config['default_role_id']);
		$action = "admin.php?mod=member&code=doadd";
		$title = "添加";
		include handler('template')->file('@admin/member_add');
	}

	
	function DoAdd()
	{
		$data = array();
		$data['username'] = trim($this->Post['username']);
		$data['password'] = md5(trim($this->Post['password']));
		$data['email'] = trim($this->Post['email']);
		$data['role_id'] = (int)$this->Post['role_id'];

		if ($data['username']=='' or $data['password']=='')
		{
			$this->Messager("用户名或密码不能为空");
		}
		if ($data['role_id']===0) {
			$this->Messager("角色编号未指定");
		}
				$sql="select * from ".TABLE_PREFIX."system_role where id=".$data['role_id'];
		$query = $this->DatabaseHandler->Query($sql);
		$role=$query->GetRow();
		if ($role==false) {
			$this->Messager("角色已经不存在");
		}
		$data['role_type']=$role['type'];
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_members');
		$is_exists = $this->DatabaseHandler->Select('', "username='{$data['username']}'");

		if($is_exists != false)
		{
			$this->Messager("用户名　{$data['username']}　已经被注册");
		}
		$result = $this->DatabaseHandler->Insert($data);
		if($result != false)
		{
						account()->Validated($result);
			$this->Messager("添加成功", 'admin.php?mod=member');
		}
		else
		{
			$this->Messager("添加失败");
		}
	}

	
	function Search()
	{
		$action = "admin.php?mod=member&code=dosearch";
				$sql = "
		 SELECT
			 id,name
		 FROM
			 " . TABLE_PREFIX.'system_role' . "
		 WHERE
			 id!=1";
		$query = $this->DatabaseHandler->Query($sql);
		while($row = $query->GetRow())
		{
			$role_list[] = $row;
		}
		$role_count = count($role_list) + 1;
				
		include handler('template')->file('@admin/member_search');
	}

	
	function DoSearch()
	{
		extract($this->Get);
		
		
		$where_list = array();
		if($username != '')
		{
			$where_list['username'] = "username like '%{$username}%'";
		}
		if($email != '')
		{
			$where_list['email'] = "email like '%{$email}%'";
		}
		if ($lower_money != '')
		{
			$where_list['money_lower'] = "money <= {$lower_money}";
		}
		if ($higher_money != '')
		{
			$where_list['money_higher'] = "money >= {$higher_money}";
		}
		if ($totalpay != '')
		{
			$where_list['totalpay'] = "totalpay >= {$totalpay}";
		}
		if($regip != '')
		{
			$where_list['regip'] = "regip like '{$regip}%'";
		}
		if($lastip != '')
		{
			$where_list['lastip'] = "lastip like '%{$lastip}'";
		}
		if(is_string($role_ids)==false)
		{
			if($role_id[0] != 'all' and is_array($role_id) and count($role_id) > 0)
			{
				$where_list['role_id'] = $this->DatabaseHandler->BuildIn($role_id, 'role_id');
				$_GET['role_ids']=implode(",",$role_id);
			}
			if($role_id[0]=='all')
			{
				unset($where_list['role_id']);
			}
		}
		else
		{
			$where_list['role_id'] ="role_id in($role_ids)";
		}
				
				$sql = "
		 SELECT
			 id,name,`type`
		 FROM
			 " . TABLE_PREFIX.'system_role' . "
		 WHERE
			 id!=1";
		$query = $this->DatabaseHandler->Query($sql);
		while($row = $query->GetRow())
		{
			$role_list[$row['id']] = $row;
		}
		if($where_list!=false)
		{
			$where = "WHERE ".implode(" AND \n\t", $where_list);
		}

				$sql = "
		  SELECT
			 count(1) total
		  FROM
			  " . TABLE_PREFIX.'system_members' . "
		  $where";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
		
		$page_num=20;
		$p=max($p,1);
		$offset=($p-1)*$page_num;
		$pages=page($total,$page_num,'',array('var'=>'p'));
		$sql = "
		  SELECT
			  *
		  FROM
			  " . TABLE_PREFIX.'system_members' . "
		  $where
		  LIMIT $offset,$page_num";
		$query = $this->DatabaseHandler->Query($sql);

		foreach($this->Config as $field => $name)
		{
			if(strpos($field, 'credits') !== false)
			{
				if($name != '')
				{
					$credit_list[$field] = $name;
				}
			}
		}
		while($row = $query->GetRow())
		{
			foreach($credit_list as $field => $val)
			{
				$row['credit_value_list'][] = $row[$field];
			}
			$role = $role_list[$row['role_id']];
			if($role != false)
			{
				if($role['is_system'] == 1)
				{
					$row['role_name'] = "<B>{$role['name']}</B>";
				}
				else
				{
					$row['role_name'] = $role['name'];
				}
								$row['money'] *= 1;
				$row['totalpay'] *= 1;
			}
			$member_list[] = $row;
		}

		$action = 'admin.php?mod=member&code=delete';
		include handler('template')->file('@admin/member_search_list');
	}

	
	function DoDelete()
	{
		$this->IDS = (array) ($this->IDS ? $this->IDS : $this->ID);
		foreach ($this->IDS as $key=>$val) {
			if(1 > ($this->IDS[$key] = (int) $val)) {
				unset($this->IDS[$key]);
			}
		}
		if (!$this->IDS) {
			$this->Messager("请先指定一个要删除的用户ID",null);
		}
		$query = $this->DatabaseHandler->Query("select * from `".TABLE_PREFIX."system_members` where `uid` in('".implode("','",$this->IDS)."')");

		$member_ids = array();
		$admin_list = array();
		$member_ids_count = 0;
		while ($row = $query->GetRow()) 
		{
			if(1==$row['uid'] || $row['role_type']=='admin') {
				$admin_list[$row['uid']] = $row['username'];
				continue;
			}
			
						if(true === UCENTER && $row['ucuid'] > 0) {
				include_once(UC_CLIENT_ROOT . './client.php');
				
				uc_user_delete($row['ucuid']);
			}
			$member_ids[$row['uid']] = $row['uid'];
		}
		if(isset($member_ids[1])) unset($member_ids[1]);
		
		if (0 < ($member_ids_count =  count($member_ids))) {
						$this->DatabaseHandler->Query("delete from `".TABLE_PREFIX."system_members` where `uid` in ('".implode("','",$member_ids)."')");
						$this->DatabaseHandler->Query("delete from `".TABLE_PREFIX."system_memberfields` where `uid` in('".implode("','",$member_ids)."')");
						$this->DatabaseHandler->Query("delete from `".TABLE_PREFIX."system_log` where `uid` in('".implode("','",$member_ids)."')");
						foreach ($member_ids as $i => $uid)
			{
				$aliuid = meta('luid_'.$uid);
				meta('luid_'.$uid, null);
				meta('token_'.$aliuid, null);
				meta('ul.alipay.'.$aliuid, null);
			}
		}
		
		$msg = '';
		$msg .= "成功删除<b>{$member_ids_count}</b>位会员";
		if($admin_list) {
			$msg .= "，其中<b>".implode(' , ',$admin_list)."</b>是管理员，不能直接删除";
		}
		$this->Messager($msg);
	}

	
	function Modify()
	{
		$this->Title="编辑用户";
		$action="admin.php?mod=member&code=domodify";
				$sql="
		 SELECT
			 *
		 FROM
			 ".TABLE_PREFIX.'system_members'." M LEFT JOIN ".TABLE_PREFIX.'system_memberfields'." MF ON(M.uid=MF.uid)
		 WHERE
			 M.uid={$this->ID}";

		$query = $this->DatabaseHandler->Query($sql);

		$member_info=$query->GetRow();
		if($member_info==false)
		{
			$this->Messager("用户已经不存在");
		}

		extract($member_info);
		$uid=$this->ID;
				$sql = "
		 SELECT
			 id,name
		 FROM
			 " . TABLE_PREFIX.'system_role' . "
		 WHERE
			 id!=1";
		$query = $this->DatabaseHandler->Query($sql);
		while($row = $query->GetRow())
		{
			$role_list[$row['id']] = array('name' => $row['name'], 'value' => $row['id']);
		}

				foreach($this->Config as $field => $name)
		{
			if(strpos($field, 'credits') !== false)
			{
				if($name != '')
				{
					$credit_list[$field] = $name;
				}
			}
		}

		$role_select = $this->FormHandler->Select('role_id', $role_list,$role_id);
		$role_name = $role_list[$role_id]['name'];
		$gender_radio=$this->FormHandler->Radio('gender',array(
		array('name'=>"男",'value'=>'1'),
		array('name'=>"女",'value'=>'2'),
		array('name'=>"保密",'value'=>'0'),
		),$gender);
		list($year,$month,$day)=explode('-',$bday);
		$year_select=$this->FormHandler->NumSelect('year','1920','2006',$year!='0000'?$year:1980);
		$month_select=$this->FormHandler->NumSelect('month','1','12',$month);
		$day_select=$this->FormHandler->NumSelect('day','1','31',$day);
		$validate_radio = $this->FormHandler->YesNoRadio('validate',$member_info['validate']);
		$_options = array(
			'0' => array(
				'name' => '请选择',
				'value' => '0',
			),
			'身份证' => array(
				'name' => '身份证',
				'value' => '身份证',
			),
			'学生证' => array(
				'name' => '学生证',
				'value' => '学生证',
			),
			'军官证' => array(
				'name' => '军官证',
				'value' => '军官证',
			),				
			'护照' => array(
				'name' => '护照',
				'value' => '护照',
			),				
			'其他' => array(
				'name' => '其他',
				'value' => '其他',
			),				
		);	
		$validate_card_type_select = $this->FormHandler->Select('validate_card_type',$_options,$member_info['validate_card_type']);

				$log = logic('me')->money()->log($this->ID, '*');
		$money = logic('me')->money()->count($this->ID);

		include handler('template')->file('@admin/member_info');
	}

	
	function DoModify()
	{
		extract($this->Post);
		if($password=='')
		{
			unset($this->Post['password']);
		}
		else
		{
			$this->Post['password']=md5($password);
		}

		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_members');
		if($old_username!=$username)
		{
			$is_exists=$this->DatabaseHandler->Select('',"username='$username'");
			if($is_exists)
			{
				$this->Messager("{$username}已经存在");
			}
		}

		if ($moneyMoved != '')
		{
						Load::logic('me');
			$this->MeLogic = new MeLogic();

			if ($moneyOps == 'plus')
			{
								logic('me')->money()->add($moneyMoved, $uid, array(
					'name' => '后台编辑(增加)',
					'intro' => '管理员（'.MEMBER_NAME.'）增加了您的余额，详情请联系！'
				));
			}
			elseif ($moneyOps == 'less')
			{
								logic('me')->money()->less($moneyMoved, $uid, array(
					'name' => '后台编辑(减少)',
					'intro' => '管理员（'.MEMBER_NAME.'）减少了您的余额，详情请联系！'
				));
			}
		}

		if ((int)$this->Post['role_id']!=0)
		{
			$this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_role');
			$role=$this->DatabaseHandler->Select((int)$this->Post['role_id']);
			if($role!=false)
			{
				zlog('admin')->roleChange($this->Post['uid'], $role);
				$this->Post['role_type']=$role['type'];
			}
			else {
				$this->messager("角色已经不存在");
			}
		}
		elseif($this->ID > 1)
		{
			$this->messager("角色必须选择");
		}
		if (1==$this->ID) {
			unset($this->Post['role_id']);
			$this->Post['role_type'] = 'admin';
		}

		$this->Post['bday']=$year.'-'.$month.'-'.$day;
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_members');
		$table1=$this->DatabaseHandler->Update($this->Post);


		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_memberfields');
		$table2=$this->DatabaseHandler->Replace($this->Post);

		if($table1 !==false)
		{
			$this->Messager("编辑成功");
		}
		else
		{
			$this->Messager("编辑失败");
		}
	}
}

?>
