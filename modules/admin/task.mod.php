<?php
/**
 * 文件名：task.mod.php
 * 版本号：1.0
 * 最后修改时间：Tue Aug 28 14:25:32 CST 2007
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：计划任务操作模块
 */

class ModuleObject extends MasterObject
{

	
	var $ID = 0;

	
	var $IDS;

	
	var $ModuleList;

	
	function ModuleObject($config)
	{
		$this->MasterObject($config);

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

		Load::moduleCode($this);$this->Execute();
	}

	
	function Execute()
	{
		switch($this->Code)
		{
			case 'list':
				$this->Main();
				break;
			case 'log_list':
				$this->LogList();
				break;
			case 'log_delete':
				$this->LogDelete();
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
			case 'run':
				$this->run();
				break;

			case 'modify':
				$this->Modify();
				break;
			case 'domodify':
				$this->DoModify();
				break;
			case 'dobatchmodify':
				$this->doBatchModify();
				break;
				
			case 'hand_exec':
				$this->HandExec();
				break;

			case 'activetask':
				$this->ActiveTask();
				break;

			default:
				$this->Main();
				break;
		}
	}

	
	function Main()
	{
		$sql="SELECT * FROM ".TABLE_PREFIX.'task';
		$query = $this->DatabaseHandler->Query($sql);
		$task_list=array();
		$available_count=0;
		while ($row=$query->GetRow())
		{
			$disabled = $row['weekday'] == -1 && $row['day'] == -1 && $row['hour'] == -1 && $row['minute'] == '' ? 'disabled' : '';
			$row['disabled']=$disabled;
			$row['type_name']=$row['type']=='system'?"内置":"自定义";
			if($row['available']==1)$available_count++;
			foreach(array('weekday', 'day', 'hour', 'minute') as $key) {
				if(in_array($row[$key], array(-1, ''))) {
					$row[$key] = '<b>*</b>';
				} elseif($key == 'weekday') {
					$row[$key] = $lang['rows_week_day_'.$row[$key]];
				} elseif($key == 'minute') {
					foreach($row[$key] = explode("\t", $row[$key]) as $k => $v) {
						$row[$key][$k] = sprintf('%02d', $v);
					}
					$row[$key] = implode(',', $row[$key]);
				}
			}
			$row['lastrun']=$row['lastrun']?my_date_format($row['lastrun'],"Y-m-d<\b\\r>H:i:s"): '<b>N/A</b>';
			$row['nextrun']=$row['nextrun']?my_date_format($row['nextrun'],"Y-m-d<\b\\r>H:i:s"): '<b>N/A</b>';
			$task_list[]=$row;
		}
				
		
		if ($available_count > 0) {
			$task_disable = 0;
		} else {
			$task_disable = 1;
		}
		if($task_disable!=$this->Config['task_disable']) {
			unset($config);
			include(CONFIG_PATH	 . 'settings.php');
			$config['task_disable'] = $task_disable;
		
			ConfigHandler::set($config);
		}
		
		include handler('template')->file('@admin/task_list');

	}
	
	
	
	function LogList()
	{
		$task_id=(int)$this->Get['task_id']?(int)$this->Get['task_id']:(int)$this->Post['task_id'];
		$limit=(int)$this->Get['limit']?(int)$this->Get['limit']:(int)$this->Post['limit'];
		if($limit==0)$limit=5;
		$where_list=array();
		$where="";
		if($task_id)
		{
			$where_list['task_id']="task_id=$task_id";
		}
		if ($where_list!=false) 
		{
			$where=' where '.implode(" AND ",$where_list);
		}
		
				$error_type=array(
			0=>"成功",
			E_USER_ERROR=>"<span style='color:red'>错误</span>",
			E_USER_WARNING=>"<span style='color:#EF6000'>警告</span>",
			E_USER_NOTICE=>"<span style='color:#FF9710'>注意</span>",
		);
				
				$sql="SELECT id,name from ".TABLE_PREFIX.'task';
		$query = $this->DatabaseHandler->Query($sql);
		$task_list=array();
		$task_list[]=array('name'=>"所有任务",'id'=>0);
		while ($row=$query->GetRow()) 
		{
			$task_list[$row['id']]=$row;
		}
		
		$task_select=FormHandler::Select('task_id',$task_list,$task_id);
		
				$sql="SELECT * from ".TABLE_PREFIX.'task_log'.$where." order by id desc limit {$limit}";
		$query = $this->DatabaseHandler->Query($sql);
		$log_list=array();
		while ($row=$query->GetRow()) 
		{
			$row['error_string']=$error_type[$row['error']];
			$row['task_name']=$task_list[$row['task_id']]['name'];
			$row['dateline']=my_date_format($row['dateline']);
			$log_list[]=$row;
		}
		

		include handler('template')->file('@admin/task_log_list');

	}
	
	function LogDelete()
	{
		$log_id_list=(array)$this->Post['delete'];
		$day=(int)$this->Post['day'];
		$task_id=(int)$this->Post['task_id'];
				if(count($log_id_list)>0)
		{
			$sql="DELETE FROM ".TABLE_PREFIX.'task_log'." where ".$this->DatabaseHandler->BuildIn($log_id_list,'id');
			$query = $this->DatabaseHandler->Query($sql);
		}
				elseif ($day>0) 
		{
			$day_before=time()-($day*86400);
			$task_add=$task_id>0?" and task_id={$task_id}":"";
			$sql="DELETE FROM ".TABLE_PREFIX.'task_log'." WHERE dateline<{$day_before}".$task_add;
			$query = $this->DatabaseHandler->Query($sql);
		}
		else
		{
			$this->Messager("未指定删除条件",-1);
		}
		$delete_count=$this->DatabaseHandler->AffectedRows();
		$this->Messager("删除成功，共删除{$delete_count}条记录");
	}





	
	function Add()
	{

		
		$action="admin.php?mod=role&code=doadd";
		$title="添加";
		$sql="SELECT * FROM ".TABLE_PREFIX.'system_role_action';
		$query = $this->DatabaseHandler->Query($sql);
		$privilege_list=$query->GetAll();


		$options=array(
		array('name'=>'普通用户','value'=>'normal')
		);
		$type_select=FormHandler::Select('type',$options);

		$privileges=explode(',',$role_info['privilege']);
		foreach($privilege_list as $key=>$privilege)
		{
			if($privilege['allow_all']==1)
			{
				$privilege['disabled']=" disabled";
			}

			$module_name=isset($this->ModuleList[$privilege['module']])
			?$this->ModuleList[$privilege['module']]
			:"z.<b>[其它]</b>模块权限";

			if(in_array($privilege['id'],$privileges) or
			$privileges[0]=="*" or
			$privilege['allow_all']==1)
			{
				$privilege['checked']=" checked";
			}

			$privilege['link']="admin.php?mod=role_action&code=modify&id=".$privilege['id'];

			$privilege['name']=strpos($privilege['action'],"_other")!==false?"<font color='#660099'>{$privilege['name']}</font>":$privilege['name'];
			$module_list[$module_name][]=$privilege;
		}
						include handler('template')->file('@admin/admin/role_info');
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


	function run($id=0,$messager=true)
	{
		$id = (int) ($this->ID ? $this->ID : $id);
		if ($id < 1) {
			$messager && $this->Messager("请先指定一个ID",null);
			return false;
		}
		
		include_once(LOGIC_PATH.'task.logic.php');
		$TaskLogic=new TaskLogic();
		$TaskLogic->run($id);
		$messager && $this->Messager("已成功执行",'',5);

		return true;
	}




	
	function Modify()
	{
		$sql="SELECT * FROM ".TABLE_PREFIX.'task'." where id=".$this->ID;
		$query = $this->DatabaseHandler->Query($sql);
		$task=$query->getRow();
		if ($task==false)
		{
			$this->Messager("任务已经不存在");
		}
		$task['filename'] = str_replace(array('..', '/', '\\'), array('', '', ''), $task['filename']);
		$task['minute'] = explode("\t", $task['minute']);

		$weekdaylist=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
		$weekdayselect = $dayselect = $hourselect = $minuteselect = '';

		for($i = 0; $i <= 6; $i++) {
			$weekdayselect .= "<option value=\"$i\" ".($task['weekday'] == $i ? 'selected' : '').">".$weekdaylist[$i]."</option>";
		}

		for($i = 1; $i <= 31; $i++) {
			$dayselect .= "<option value=\"$i\" ".($task['day'] == $i ? 'selected' : '').">$i</option>";
		}

		for($i = 0; $i <= 23; $i++) {
			$hourselect .= "<option value=\"$i\" ".($task['hour'] == $i ? 'selected' : '').">$i</option>";
		}

		for($i = 0; $i < 12; $i++) {
			$minuteselect .= '<select name="minutenew[]"><option value="-1">*</option>';
			for($j = 0; $j <= 59; $j++) {
				$minuteselect .= "<option value=\"$j\" ".($task['minute'][$i] != '' && $task['minute'][$i] == $j ? 'selected' : '').">".sprintf("%02d", $j)."</option>";
			}
			$minuteselect .= '</select>'.($i == 5 ? '<br>' : ' ');
		}
		include handler('template')->file('@admin/task_info');
	}


	
	function DoModify()
	{
		$sql="SELECT * FROM ".TABLE_PREFIX.'task'." where id=".$this->ID;
		$query = $this->DatabaseHandler->Query($sql);
		$task=$query->getRow();
		if ($task==false)
		{
			$this->Messager("任务已经不存在");
		}
		$task['filename'] = str_replace(array('..', '/', '\\'), array('', '', ''), $task['filename']);
		$task['minute'] = explode("\t", $task['minute']);

		extract($this->Post);
		$daynew = $weekdaynew != -1 ? -1 : $daynew;

		if(is_array($minutenew)) {
			sort($minutenew = array_unique($minutenew));
			foreach($minutenew as $key => $val) {
				if($val < 0 || $var > 59) {
					unset($minutenew[$key]);
				}
			}
			$minutenew = implode("\t", $minutenew);
		} else {
			$minutenew = '';
		}

		if(preg_match("/[\\\\\/\:\*\?\"\<\>\|]+/", $filenamenew)) {
			$this->Messager("计划任务文件名不正确",-1);
		} elseif(!is_readable(TASK_PATH.($cronfile = $filenamenew))) {
			$this->Messager("计划任务文件名 {$cronfile} 不存在",-1);
		} elseif($weekdaynew == -1 && $daynew == -1 && $hournew == -1 && $minutenew == '') {
			$this->Messager("时间设置不正确",-1);
		}
		$sql="UPDATE ".TABLE_PREFIX.'task'." SET weekday='$weekdaynew', day='$daynew', hour='$hournew', minute='$minutenew', filename='".trim($filenamenew)."' WHERE id='{$this->ID}'";
		$this->DatabaseHandler->Query($sql);

		require_once(LOGIC_PATH.'task.logic.php');
		$TaskLogic=new TaskLogic();
		$TaskLogic->nextRun($task);
		$this->Messager("编辑成功","admin.php?mod=task&code=list");
	}
	
	function doBatchModify()
	{
		extract($this->Post);
		$timestamp=time();

		if($ids = $this->DatabaseHandler->BuildIn($delete,"")) {
			$this->DatabaseHandler->Query("DELETE FROM ".TABLE_PREFIX.'task'." WHERE id IN ($ids) AND type='user'");
			$this->DatabaseHandler->Query("DELETE FROM ".TABLE_PREFIX.'task_log'." WHERE task_id IN ($ids)");
		}

		if(is_array($namenew)) {
			foreach($namenew as $id => $name) {
				$this->DatabaseHandler->Query("UPDATE ".TABLE_PREFIX.'task'." SET name='".$namenew[$id]."', available='".$availablenew[$id]."' ".($availablenew[$id] ? '' : ', nextrun=\'0\'')." WHERE id='$id'");
			}
		}

		if($newname = trim($newname)) {
			$this->DatabaseHandler->Query("INSERT INTO ".TABLE_PREFIX.'task'."(name, type, available, weekday, day, hour, minute, nextrun)
				VALUES ('".$newname."', 'user', '0', '-1', '-1', '-1', '', '$timestamp')");
		}
		$this->Messager("计划任务成功更新","admin.php?mod=task&code=list");
	}	
	
	function HandExec() {

		$collector_times = max(0,(int) $this->Get['collector_times']);		
		
		$query = $this->DatabaseHandler->Query("select count(*) as `total` from `".TABLE_PREFIX."collector`");
		$row = $query->GetRow();
		if ($row['total'] < 1) {
			$this->Messager("请先添加采集源",'admin.php?mod=channel');
		}
		
		if ($collector_times > 0 && !($collector_times % $row['total'])) {
			$this->Messager("完成本次的全部采集任务，共执行了 <b>{$collector_times}</b> 次采集，<a href='admin.php?mod=task&code=hand_exec"."&"."collector_times=".(++$collector_times)."'>点此继续采集？</a>，<a href='admin.php?mod=task&code=log_list'>或者点此查看此次采集任务LOG</a>",null);
		}
		

		$run_result = $this->run(1,false);
		
		$message  = "1、正在进行数据采集，请稍候……<br>";
		$message .= "2、本次数据采集 " . ($run_result ? "成功" : "失败") . "<br>";
		$message .= "3、本次采集进度为　<b>{$collector_times}/{$row['total']}</b><br>";
		$message .= "4、<a href='admin.php?mod=task&code=log_list'>或者点此停止此次采集任务</a><br>";
				
		$this->Messager($message,"admin.php?mod=task&code=hand_exec"."&"."collector_times=".(++$collector_times),5);
	}	

	
	function ActiveTask()
	{
		Load::functions('channel');
		
		$check_result = wzb_os_check_file(false);
		
		if (!$check_result) {
			$check_result = "已成功放置激活计划任务的代码";
		}
		
		include handler('template')->file('@admin/task_active');
	}

}

?>