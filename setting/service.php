<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name service.php
 * @date 2013-11-07 19:52:54
 */
 


$config["service"] =  array (
  'mail' => 
  array (
    'balance' => true,
  ),
  'sms' => 
  array (
    'driver' => 
    array (
      'ls' => 
      array (
        'name' => '电信通道',
        'intro' => '075开头，网关短信直发（自动重发功能暂时只支持此通道）<br/><a href="'.ihelper('tg.shop.sms.qxt').'" target="_blank"><font color="red">点此在线购买</font></a>',
      ),
      'qxt' => 
      array (
        'name' => 'GD106通道',
        'intro' => '通知通道，禁发营销信息，67字/条，免签名<br/><a href="'.ihelper('tg.shop.sms.qxt').'" target="_blank"><font color="red">点此在线购买</font></a>',
      ),
      'wnd' => 
      array (
        'name' => 'WN106通道',
        'intro' => '订单通知通道，禁发营销信息，70字单条，长短信67字条，须签名（签名联系客服QQ800058566设置）<br/><a href="'.ihelper('tg.shop.sms.qxt').'" target="_blank"><font color="red">点此在线购买</font></a>',
      ),
      'zt' => 
      array (
        'name' => 'ZT106通道',
        'intro' => '<font color="red">【推荐】</font>优质团购订单直连通知通道，下发速度快，专用订单通知通道，禁发营销信息，67字/条，须签名（签名接口设置页自行设置，推荐用站点名称）<br/><a href="'.ihelper('tg.shop.sms.qxt').'" target="_blank"><font color="red">点此在线购买</font></a>',
      ),
    ),
    'autoERSend' => true,
  ),
  'push' => 
  array (
    'mthread' => false,
  ),
);
?>