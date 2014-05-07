<?php

/**
 * 模块：账户管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name account.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
	function ModuleObject( $config )
	{
		$this->MasterObject($config);
		$runCode = Load::moduleCode($this);
		$this->$runCode();
	}
	public function Main()
	{
		exit('admin.mod.account.index');
	}
	public function config()
	{
		include handler('template')->file('@admin/account_config');
	}
}

?>