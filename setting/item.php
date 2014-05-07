<?php
/**
 *[TTTuangou] (C)2005 - 2010 Cenwor Inc.
 *
 系统对象
 *
 * @author 狐狸<foxis@qq.com>
 * @since 2009年9月2日 
 * @package www.tttuangou.net
 */
global $_LANG;
$config['item']=
array
(
	'member'=>array
	(
		'table_name'	=>TABLE_PREFIX. 'system_members',	//*必填 	对象表名称
		'pri_field'		=>'uid',			//选填 	对象表主键的字段名 不填默认为 "id"
		'name_field'	=>'username',			//选填 	对象名称的字段名 不填默认为 "id"
		'view_url'		=>'index.php?mod=member&code=view&id=%s',//*必填 对象查看的地址格式
		'name'			=>$_LANG['member'],	//	操作对象的名称
		'photo_field'	=>"face",
		'photo_path'	=>"face",
	),
	
);

?>