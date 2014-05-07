<?php

/**
 * 界面支持：产品展示
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package UserInterface
 * @name igos.ui.php
 * @version 1.0
 */

class iGOSUI
{
	
	public function load($product)
	{
		$style = ini('ui.igos.style');
		$style || $style = 'default';
		if ($style == 'm1selse' && get('page', 'int') > 1)
		{
						$style = 'meituan';
		}
		include handler('template')->file('@html/igos/'.$style.'/index');
	}
}

?>