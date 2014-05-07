<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name howparallel.function.php
 * @date 2014-01-23 10:27:10
 */
 




function ad_config_save_parser_howparallel(&$data)
{
	if (count($data['list']) < 1) return;
	$orders = array();
	
	logic('upload')->Save('file_adl', './templates/html/ad/images/howparallel.adl.jpg');
	logic('upload')->Save('file_adr', './templates/html/ad/images/howparallel.adr.jpg');
	
		
	$data['list']['adl']['image'] = 'templates/html/ad/images/howparallel.adl.jpg';
	$data['list']['adr']['image'] = 'templates/html/ad/images/howparallel.adr.jpg';
	$data['fu'] = true;
}

?>
