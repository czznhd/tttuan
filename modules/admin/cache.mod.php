<?php

/**
 * 缓存管理
 *
 * @author 狐狸<foxis@qq.com>
 * @package TTTuangou
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
			
			default:
				$this->Main();
				break;
		}
	}
	function Main()
	{		
		$this->clearAll();
	}
	function clearAll()
	{
		include(LIB_PATH.'io.han.php');
		$IO=new IoHandler();
		@$IO->ClearDir(CACHE_PATH);
		$this->Messager("缓存已清空",null);
	}

}
?>