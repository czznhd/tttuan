<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name image.php
 * @date 2013-07-31 16:18:09
 */
 


$config["image"] =  array (
  'watermark_test' => 
  array (
    'type' => 'text',
    'image' => './static/images/watermark/mark.png',
    'text' => '天天团购系统，开源，免费 www.tttuangou.net',
    'font' => 'font.ttf',
    'fontsize' => '13',
    'position' => '4',
    'enabled' => 'true',
  ),
  'watermark' => 
  array (
    'type' => 'image',
    'image' => './static/images/watermark/mark.png',
    'text' => '天天团购系统，开源，免费 www.tttuangou.net',
    'font' => 'font.ttf',
    'fontsize' => '13',
    'position' => '4',
    'enabled' => false,
  ),
);
?>