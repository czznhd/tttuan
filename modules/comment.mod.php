<?php

/**
 * 模块：评论相关
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name comment.mod.php
 * @version 1.1
 */

class ModuleObject extends MasterObject
{
	function ModuleObject( $config )
	{
		$this->MasterObject($config);
		$runCode = Load::moduleCode($this, false, false);
		$this->$runCode();
	}
	
	public function submit()
	{
		$product_id = get('pid', 'int');
		$score = post('score', 'int');
		$content = post('content', 'txt');
		$result = logic('comment')->front_user_submit($product_id, $score, $content);
		if (is_numeric($result))
		{
			exit('ok');
		}
		else
		{
			exit($result);
		}
	}
}

?>