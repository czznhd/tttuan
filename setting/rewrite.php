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
 * @Date 2011-07-28 07:47:51 $
 *******************************************************************/ 
 

$_rewrite=array (
  'abs_path' => '/',
  'arg_separator' => '/',
  'gateway' => '',
  'mode' => '',
  'prepend_var_list' => 
  array (
    0 => 'mod',
    1 => 'code',
  ),
  'value_replace_list' => 
  array (
    'mod' => 
    array (
      'index' => 'home',
      'list' => 'channel',
      'me' => 'user',
    ),
  ),
  'var_replace_list' => 
  array (
    'mod' => 
    array (
    ),
  ),
  'var_separator' => '-',
);
?>