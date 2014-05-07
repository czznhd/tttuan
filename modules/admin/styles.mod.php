<?php

/**
 * 模块：多风格管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name styles.mod.php
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
	
	public function vlist()
	{
		$default = ini('styles.default');
		$styles = ui('style')->get_all();
		include handler('template')->file('@admin/styles_list');
	}
	
	public function setdefault()
	{
		$id = get('id');
		if (ini('styles.local.'.$id.'.enabled'))
		{
			ini('styles.default', $id);
			$this->Messager('默认风格设置成功！', '?mod=styles&code=vlist');
		}
		else
		{
			$this->Messager('默认风格设置失败，此风格未启用，或者不存在！', '?mod=styles&code=vlist');
		}
	}
}

?>