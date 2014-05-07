<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name constants.php
 * @date 2014-02-24 14:09:23
 */
 



define('SYS_NAME',			$config['site_name']);		define('SYS_VERSION',		'3.6.2');
define('SYS_BUILD',			'build 20140224');
define('SYS_RELEASE',		'');
define('SYS_PATH',			'./');

define('GZIP',				(boolean) $config['gzip']);





define('SMALL_PIC_PREFIX',	's-');
define('WATERMARK_PIC_PREFIX',	'w-');
define('SMALL_PIC_WIDTH',	89);
define('SMALL_PIC_HEIGHT',	89);

define('TABLE_PREFIX',				$config['db_table_prefix']);


@include_once(ROOT_PATH . './setting/ucenter.php');

define('UCENTER' , 			(boolean) $config['ucenter']['enable']);	
define('UC_CLIENT_ROOT', 	ROOT_PATH . './uc_client/');

if (true === UCENTER) {

define('UC_CONNECT', 		$config['ucenter']['uc_connect']);	
define('UC_DBHOST',		 	$config['ucenter']['uc_db_host']);			define('UC_DBUSER', 		$config['ucenter']['uc_db_user']);				define('UC_DBPW', 			$config['ucenter']['uc_db_password']);					define('UC_DBNAME', 		$config['ucenter']['uc_db_name']);				define('UC_DBCHARSET',		$config['ucenter']['uc_db_charset'] ? $config['ucenter']['uc_db_charset'] : 'gbk');				define('UC_DBTABLEPRE', 	$config['ucenter']['uc_db_table_prefix']);			
define('UC_KEY', 			$config['ucenter']['uc_key']);				define('UC_API', 			$config['ucenter']['uc_api']);	define('UC_CHARSET', 		$config['ucenter']['uc_charset'] ? $config['ucenter']['uc_charset'] : 'gbk');				define('UC_IP', 			$config['ucenter']['uc_ip']);					define('UC_APPID', 			$config['ucenter']['uc_app_id']);					}

?>