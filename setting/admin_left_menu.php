<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name admin_left_menu.php
 * @date 2014-01-11 12:49:16
 */
 
 $menu_list = array (
  1 => 
  array (
	'title' => '常用操作',
	'link' => '',
	'sub_menu_list' => 
	array (
	),
  ),
  2 => 
  array (
	'title' => '全局设置',
	'link' => '',
	'sub_menu_list' => 
	array (
	  1 => 
	  array (
		'title' => '核心设置',
		'link' => 'admin.php?mod=setting&code=modify_normal',
		'shortcut' => false,
	  ),
	  2 => 
	  array (
		'title' => '伪静态',
		'link' => 'admin.php?mod=setting&code=modify_rewrite',
		'shortcut' => false,
	  ),
	  3 => 
	  array (
		'title' => '内容过滤',
		'link' => 'admin.php?mod=setting&code=modify_filter',
		'shortcut' => false,
	  ),
	  4 => 
	  array (
		'title' => '友情链接',
		'link' => 'admin.php?mod=link',
		'shortcut' => false,
	  ),
	  5 => 
	  array (
		'title' => 'IP访问控制',
		'link' => 'admin.php?mod=setting&code=modify_access',
		'shortcut' => false,
	  ),
	  6 => 
	  array (
		'title' => '顶部导航设置',
		'link' => 'admin.php?mod=tttuangou&code=indexnav',
		'shortcut' => false,
	  ),
	  7 => 
	  array (
		'title' => '客服信息设置',
		'link' => 'admin.php?mod=widget&code=block&op=config&flag=cservice',
		'shortcut' => false,
	  ),
	  8 => 
	  array (
		'title' => '支付设置',
		'link' => 'admin.php?mod=payment',
		'shortcut' => true,
	  ),
	  9 => 
	  array (
		'title' => '上传设置',
		'link' => 'admin.php?mod=upload&code=config',
		'shortcut' => false,
	  ),
	  10 => 
	  array (
		'title' => '图片水印',
		'link' => 'admin.php?mod=image&code=watermark',
		'shortcut' => false,
	  ),
	  25 => 
	  array (
		'title' => '一键登录',
		'link' => 'admin.php?mod=ulogin',
		'shortcut' => false,
	  ),
	  1001 => 
	  array (
		'title' => '团购设置',
		'link' => 'hr',
		'shortcut' => false,
	  ),
	  11 => 
	  array (
		'title' => '常用设置',
		'link' => 'admin.php?mod=tttuangou&code=varshow',
		'shortcut' => true,
	  ),
	  12 => 
	  array (
		'title' => '侧边栏管理',
		'link' => 'admin.php?mod=widget',
		'shortcut' => false,
	  ),
	  13 => 
	  array (
		'title' => '广告管理',
		'link' => 'admin.php?mod=ad&code=vlist',
		'shortcut' => false,
	  ),
	  14 =>
	  array (
		'title' => '静态页面管理',
		'link' => 'admin.php?mod=html&code=front',
		'shortcut' => false,
	  ),
	  15 => 
	  array (
		'title' => '团购券设置',
		'link' => 'admin.php?mod=coupon&code=config',
		'shortcut' => false,
	  ),
	  1002 => 
	  array (
		'title' => '风格设置',
		'link' => 'hr',
		'shortcut' => false,
	  ),
	  21 => 
	  array (
		'title' => '皮肤风格',
		'link' => 'admin.php?mod=styles&code=vlist',
		'shortcut' => true,
	  ),
	  22 => 
	  array (
		'title' => '站点Logo',
		'link' => 'admin.php?mod=tttuangou&code=sitelogo',
		'shortcut' => false,
	  ),
	  23 => 
	  array (
		'title' => '分享设置',
		'link' => 'admin.php?mod=tttuangou&code=shareconfig',
		'shortcut' => false,
	  ),
	  24 => 
	  array (
		'title' => '多团设置',
		'link' => 'admin.php?mod=ui&code=igos&op=config',
		'shortcut' => false,
	  ),
	),
  ),
  3 => 
  array (
	'title' => '团购管理',
	'link' => '',
	'sub_menu_list' => 
	array (
	  1 => 
	  array (
		'title' => '产品管理',
		'link' => 'admin.php?mod=product',
		'shortcut' => true,
	  ),
	  2 => 
	  array (
		'title' => '订单管理',
		'link' => 'admin.php?mod=order',
		'shortcut' => true,
	  ),
	  3 => 
	  array (
		'title' => '团购券管理',
		'link' => 'admin.php?mod=coupon',
		'shortcut' => false,
	  ),
	  4 => 
	  array (
		'title' => '发货管理',
		'link' => 'admin.php?mod=delivery&code=vlist&alsend=no',
		'shortcut' => true,
	  ),
	  5 => 
	  array (
		'title' => '快递单打印',
		'link' => 'admin.php?mod=print&code=delivery&op=queue',
		'shortcut' => true,
	  ),
	  6 => 
	  array (
		'title' => '返利管理',
		'link' => 'admin.php?mod=tttuangou&code=mainfinder',
		'shortcut' => false,
	  ),
	  7 => 
	  array (
		'title' => '城市区域',
		'link' => 'admin.php?mod=tttuangou&code=city',
		'shortcut' => false,
	  ),
	  8 => 
	  array (
		'title' => '商家管理',
		'link' => 'admin.php?mod=tttuangou&code=mainseller',
		'shortcut' => true,
	  ),
	  9 => 
	  array (
		'title' => '配送管理',
		'link' => 'admin.php?mod=express',
		'shortcut' => false,
	  ),
	  10 => 
	  array (
		'title' => '分类管理',
		'link' => 'admin.php?mod=catalog',
		'shortcut' => false,
	  ),
	  11 => 
	  array (
		'title' => '充值卡管理',
		'link' => 'admin.php?mod=recharge&code=card',
		'shortcut' => false,
	  ),
	  12 => 
	  array (
		'title' => '充值订单管理',
		'link' => 'admin.php?mod=recharge&code=order',
		'shortcut' => false,
	  ),
	  13 => 
	  array (
		'title' => '抽奖管理',
		'link' => 'admin.php?mod=prize&code=vlist',
		'shortcut' => false,
	  ),
	  1001 => 
	  array (
		'title' => '数据清理',
		'link' => 'hr',
		'shortcut' => false,
	  ),
	  101 => 
	  array (
		'title' => '数据初始化',
		'link' => 'admin.php?mod=tttuangou&code=clear',
		'shortcut' => false,
	  ),
	),
  ),
  4 => 
  array (
	'title' => '互动营销',
	'link' => '',
	'sub_menu_list' => 
	array (
	  1 => 
	  array (
		'title' => '短信平台设置',
		'link' => 'admin.php?mod=service&code=sms',
		'shortcut' => true,
	  ),
	  2 => 
	  array (
		'title' => '群发服务管理',
		'link' => 'admin.php?mod=service',
		'shortcut' => false,
	  ),
	  3 => 
	  array (
		'title' => '订阅管理',
		'link' => 'admin.php?mod=subscribe',
		'shortcut' => false,
	  ),
	  4 => 
	  array (
		'title' => '订阅群发',
		'link' => 'admin.php?mod=subscribe&code=broadcast&class=mail',
		'shortcut' => false,
	  ),
	  5 => 
	  array (
		'title' => '通知方式',
		'link' => 'admin.php?mod=notify',
		'shortcut' => false,
	  ),
	  6 =>
	  array (
		'title' => '通知事件管理',
		'link' => 'admin.php?mod=notify&code=event',
		'shortcut' => false,
	  ),
	  7 =>
	  array (
		'title' => '问答管理',
		'link' => 'admin.php?mod=tttuangou&code=mainquestion',
		'shortcut' => false,
	  ),
	  8 =>
	  array (
		'title' => '反馈信息',
		'link' => 'admin.php?mod=tttuangou&code=usermsg',
		'shortcut' => false,
	  ),
	  9 =>
	  array (
		'title' => '文章管理',
		'link' => 'admin.php?mod=article',
		'shortcut' => false,
	  ),
	  10 =>
	  array (
		'title' => '财务报表',
		'link' => 'admin.php?mod=reports',
		'shortcut' => false,
	  ),
	  11 =>
	  array (
		'title' => '评论管理',
		'link' => 'admin.php?mod=comment&code=vlist',
		'shortcut' => false,
	  ),
	  1001 => 
	  array (
		'title' => '推送管理',
		'link' => 'hr',
		'shortcut' => false,
	  ),
	  101 => 
	  array (
		'title' => '推送队列',
		'link' => 'admin.php?mod=push&code=queue',
		'shortcut' => false,
	  ),
	  2001 => 
	  array (
		'title' => '数据调用',
		'link' => 'hr',
		'shortcut' => false,
	  ),
	  201 => 
	  array (
		'title' => '外部调用',
		'link' => 'admin.php?mod=tttuangou&code=dataapi',
		'shortcut' => false,
	  ),
	),
  ),
  5 =>
  array (
	'title' => '客户端管理',
	'link' => '',
	'sub_menu_list' => 
	array (
	  1 => 
	  array (
		'title' => '移动应用管理',
		'link' => 'admin.php?mod=api',
		'shortcut' => false,
	  ),
	  2 => 
	  array (
		'title' => '客户端版本管理',
		'link' => 'admin.php?mod=api&code=release',
		'shortcut' => false,
	  ),
	  3 => 
	  array (
		'title' => '移动支付管理',
		'link' => 'admin.php?mod=setting&code=mnote',
		'shortcut' => false,
	  ),
	  4 => 
	  array (
		'title' => '通知栏推送设置',
		'link' => 'admin.php?mod=setting&code=push',
		'shortcut' => false,
	  ),
	  5 => 
	  array (
		'title' => '安卓客户端介绍',
		'link' => ihelper('tg.app.android'),
		'shortcut' => false,
	  ),
	),
  ),
  6 => 
  array (
	'title' => '系统工具',
	'link' => '',
	'sub_menu_list' => 
	array (
	  1 => 
	  array (
		'title' => '更新缓存',
		'link' => 'admin.php?mod=cache',
		'shortcut' => false,
	  ),
	  2 => 
	  array (
		'title' => '在线升级',
		'link' => 'admin.php?mod=upgrade',
		'shortcut' => true,
	  ),
	  3 => 
	  array (
		'title' => '错误调试',
		'link' => 'admin.php?mod=dev',
		'shortcut' => false,
	  ),
	  4 => 
	  array (
		'title' => '日志中心',
		'link' => 'admin.php?mod=zlog',
		'shortcut' => true,
	  ),
	  5 => 
	  array (
		'title' => '入侵检测',
		'link' => 'admin.php?mod=wips',
		'shortcut' => false,
	  ),
	  1001 => 
	  array (
		'title' => '数据库',
		'link' => 'hr',
		'shortcut' => false,
	  ),
	  11 => 
	  array (
		'title' => '数据备份',
		'link' => 'admin.php?mod=db&code=export',
		'shortcut' => false,
	  ),
	  12 => 
	  array (
		'title' => '数据恢复',
		'link' => 'admin.php?mod=db&code=import',
		'shortcut' => false,
	  ),
	  13 => 
	  array (
		'title' => '数据表优化',
		'link' => 'admin.php?mod=db&code=optimize',
		'shortcut' => false,
	  ),
	  14 => 
	  array (
		'title' => '数据库修复',
		'link' => 'admin.php?mod=db&code=repair',
		'shortcut' => false,
	  ),
	  1002 => 
	  array (
		'title' => '站点信息',
		'link' => 'hr',
		'shortcut' => false,
	  ),
	  21 => 
	  array (
		'title' => '蜘蛛爬行统计',
		'link' => 'admin.php?mod=robot',
		'shortcut' => false,
	  ),
	),
  ),
  7 => 
  array (
	'title' => '用户管理',
	'link' => '',
	'sub_menu_list' => 
	array (
	  1 => 
	  array (
		'title' => 'Ucenter整合',
		'link' => 'admin.php?mod=ucenter',
		'shortcut' => false,
	  ),
	  2 => 
	  array (
		'title' => '+添加新用户',
		'link' => 'admin.php?mod=member&code=add',
		'shortcut' => false,
	  ),
	  3 => 
	  array (
		'title' => '编辑用户',
		'link' => 'admin.php?mod=member&code=search',
		'shortcut' => false,
	  ),
	  4 => 
	  array (
		'title' => '当前在线用户',
		'link' => 'admin.php?mod=sessions',
		'shortcut' => false,
	  ),
	  2001 => 
	  array (
		'title' => '用户设置',
		'link' => 'hr',
		'shortcut' => false,
	  ),
	  201 => 
	  array (
		'title' => '用户设置',
		'link' => 'admin.php?mod=account&code=config',
		'shortcut' => false,
	  ),
	),
  ),
  8 => 
  array (
	'title' => '使用帮助',
	'link' => '',
	'sub_menu_list' => 
	array (
	  1111 => 
	  array (
		'title' => '帮助手册',
		'link' => ihelper('tg.helper'),
		'shortcut' => false,
	  ),
	  1112 => 
	  array (
		'title' => '短信购买',
		'link' => ihelper('tg.shop'),
		'shortcut' => false,
	  ),
	  1113 => 
	  array (
		'title' => '支付宝申请',
		'link' => ihelper('tg.payment.alipay'),
		'shortcut' => false,
	  ),
	  1114 => 
	  array (
		'title' => '网银直连接口',
		'link' => ihelper('tg.payment.wangyin'),
		'shortcut' => false,
	  ),
	  1115 => 
	  array (
		'title' => '技术支持',
		'link' => 'hr',
		'shortcut' => false,
	  ),
	  1116 => 
	  array (
		'title' => '支持论坛',
		'link' => ihelper('cenwor.forum'),
		'shortcut' => false,
	  ),
	),
  ),
); ?>
