<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name howdo.function.php
 * @date 2011-12-07 13:42:08
 */
 




function ad_config_save_parser_howdo($data)
{
	logic('upload')->Save('file', ROOT_PATH.$data['image']);
}

?>