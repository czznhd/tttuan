<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename show.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 


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
		$show=ConfigHandler::get('show');
		$cache = ConfigHandler::get('cache');
		$time = ConfigHandler::get('time');
		
		
				$fp=opendir($this->Config['template_root_path']);
		while ($template=readdir($fp)) 
		{
			if($template=='..' || $template=='.' || $template=='.svn' || $template=='_svn')continue;
			if(is_dir($this->Config['template_root_path'].'/'.$template))
			{
				$tpl_name=$template;
				@include($this->Config['template_root_path'].'/'.$template.'/tplinfo.php');
				$template_list[$template]=array("name"=>$tpl_name,"value"=>$template);
				$template_name_list[$template]=$tpl_name;
			}
		}
		array_multisort($template_name_list,SORT_DESC,$template_list);
		$template_select=FormHandler::Select('template_path',$template_list,$this->Config['template_path']);
		
		include(handler('template')->file('@admin/show'));
	}
	
	function DoModify()
	{
		ConfigHandler::set('show',$this->Post['show']);
		ConfigHandler::set('cache',$this->Post['cache']);
		ConfigHandler::set('time',$this->Post['time']);
		
		clearcache();
		
				if($this->Post['template_path']!="" && 
		$this->Post['template_path']!=$this->Config['template_path'])
		{
			include(CONFIG_PATH.'settings.php');
			$config['template_path']=$this->Post['template_path'];
			ConfigHandler::set($config);
		}
		$this->Messager("设置成功");
	}
	
	function cache_time($min=0,$max=0)
	{
		$list = array(
			180 => array('name'=>'三分钟','value'=>'180',),
			300 => array('name'=>'五分钟','value'=>'300',),
			600 => array('name'=>'十分钟','value'=>'600',),
			1800 => array('name'=>'半小时','value'=>'1800',),
			3600 => array('name'=>'一小时','value'=>'3600',),
			7200 => array('name'=>'两小时','value'=>'7200',),
			14400 => array('name'=>'四小时','value'=>'14400',),
			28800 => array('name'=>'八小时','value'=>'28800',),
			43200 => array('name'=>'(12小时)半天','value'=>'43200',),
			86400 => array('name'=>'(24小时)一天','value'=>'86400',),
			172800 => array('name'=>'(48小时)两天','value'=>'172800',),
			345600 => array('name'=>'四天','value'=>'345600',),
			604800 => array('name'=>'一星期','value'=>'604800',),
			1209600 => array('name'=>'两星期','value'=>'1209600',),
			1814400 => array("name"=>"三星期",'value'=>1814400),
			2592000 => array('name'=>'一个月','value'=>'2592000',),
			5184000 => array('name'=>'两个月','value'=>'5184000',),
			7776000 => array("name"=>"三个月",'value'=>7776000),
			15552000 => array('name'=>'(6个月)半年','value'=>'15552000',),
			31104000 => array('name'=>'(12个月)一年','value'=>'31104000',),
			62208000 => array('name'=>'(24个月)两年','value'=>'62208000',),
		);
		if(0==$min && 0==$max) return $list;
		
		$_min = min((int) $min,(int) $max);
		$_max = max((int) $min,(int) $max);
		$cache_time = array();
		foreach ($list as $k=>$v) {
			if($k >= $_min && $k <= $_max) {
				$cache_time[$k] = $v;
			}
		}

		return $cache_time;	
	}
}
?>
