<?php

/**
 * 模块：APP接口
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name app.mod.php
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
	function lpc()
	{
		$master = get('master', 'txt');
		$processor = get('processor', 'txt');
		$appObject = app($master);
		if (method_exists($appObject, $processor))
		{
			$appObject->$processor();
			exit;
		}
		else
		{
			exit('LPC.E.404');
		}
	}
}

?>