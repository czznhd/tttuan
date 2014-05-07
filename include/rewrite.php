<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename rewrite.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 

include_once('./setting/rewrite.php');
if($_rewrite['mode']!=''){
	include_once('./include/lib/rewrite.han.php');
	$rewriteHandler=new rewriteHandler();
	$rewriteHandler->absPath=$_rewrite['abs_path'];
	$rewriteHandler->gateway=$_rewrite['gateway'];
	$rewriteHandler->argSeparator=$_rewrite['arg_separator'];
	$rewriteHandler->varSeparator=$_rewrite['var_separator'];
	$rewriteHandler->prependVarList=$_rewrite['prepend_var_list'];
	$rewriteHandler->varReplaceList=(array)$_rewrite['var_replace_list'];
	$rewriteHandler->valueReplaceList=(array)$_rewrite['value_replace_list'];
	$rewriteHandler->parseRequest($_rewrite['request']);
}
?>