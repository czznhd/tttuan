/*
SQLyog Enterprise - MySQL GUI v8.1 
MySQL - 5.0.51b-community-nt : Database - tgmax
*********************************************************************
天天团购数据库结构
最后修改时间：2011-09-27
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `{prefix}system_failedlogins` */

DROP TABLE IF EXISTS `{prefix}system_failedlogins`;

CREATE TABLE `{prefix}system_failedlogins` (
  `ip` char(15) NOT NULL default '',
  `count` tinyint(1) unsigned NOT NULL default '0',
  `lastupdate` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_log` */

DROP TABLE IF EXISTS `{prefix}system_log`;

CREATE TABLE `{prefix}system_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL default '0',
  `username` char(15) NOT NULL default '游客',
  `action_id` smallint(4) unsigned NOT NULL default '0',
  `module` char(50) NOT NULL default 'index',
  `action` char(100) NOT NULL default '',
  `item_id` int(10) unsigned NOT NULL default '0',
  `item_title` char(100) NOT NULL default '',
  `ip` char(15) NOT NULL default '',
  `time` int(10) unsigned NOT NULL default '0',
  `uri` char(100) NOT NULL default '',
  `extcredits1` smallint(4) NOT NULL default '0',
  `extcredits2` smallint(4) NOT NULL default '0',
  `extcredits3` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `action_id` (`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_memberfields` */

DROP TABLE IF EXISTS `{prefix}system_memberfields`;

CREATE TABLE `{prefix}system_memberfields` (
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `nickname` varchar(30) NOT NULL default '',
  `site` varchar(75) NOT NULL default '',
  `alipay` varchar(50) NOT NULL default '',
  `icq` varchar(12) NOT NULL default '',
  `yahoo` varchar(40) NOT NULL default '',
  `taobao` varchar(40) NOT NULL default '',
  `location` varchar(30) NOT NULL default '',
  `customstatus` varchar(30) NOT NULL default '',
  `medals` varchar(255) NOT NULL default '',
  `avatar` varchar(255) NOT NULL default '',
  `avatarwidth` tinyint(1) unsigned NOT NULL default '0',
  `avatarheight` tinyint(1) unsigned NOT NULL default '0',
  `bio` text NOT NULL,
  `signature` text NOT NULL,
  `sightml` text NOT NULL,
  `ignorepm` text NOT NULL,
  `groupterms` text NOT NULL,
  `authstr` varchar(50) NOT NULL default '',
  `auth_try_times` TINYINT(1) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL default '',
  `answer` varchar(255) NOT NULL default '',
  `address` varchar(40) NOT NULL default '',
  `postcode` varchar(6) NOT NULL default '',
  `validate_true_name` varchar(50) NOT NULL default '',
  `validate_card_type` varchar(10) NOT NULL default '',
  `validate_card_id` varchar(50) NOT NULL default '',
  `validate_remark` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_members` */

DROP TABLE IF EXISTS `{prefix}system_members`;

CREATE TABLE `{prefix}system_members` (
  `uid` int(10) NOT NULL auto_increment,
  `username` varchar(45) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `secques` varchar(24) NOT NULL default '',
  `gender` tinyint(1) NOT NULL default '0',
  `adminid` tinyint(1) NOT NULL default '0',
  `regip` varchar(45) NOT NULL default '',
  `regdate` int(10) NOT NULL default '0',
  `lastip` varchar(45) NOT NULL default '',
  `lastvisit` int(10) NOT NULL default '0',
  `lastactivity` int(10) NOT NULL default '0',
  `lastpost` int(10) NOT NULL default '0',
  `oltime` int(10) NOT NULL default '0',
  `pageviews` int(10) NOT NULL default '0',
  `credits` int(10) NOT NULL default '0',
  `extcredits1` int(10) NOT NULL default '0',
  `extcredits2` int(10) NOT NULL default '0',
  `email` varchar(150) NOT NULL default '',
  `bday` date NOT NULL default '0000-00-00',
  `sigstatus` tinyint(1) NOT NULL default '0',
  `tpp` tinyint(1) NOT NULL default '0',
  `ppp` tinyint(1) NOT NULL default '0',
  `styleid` int(10) NOT NULL default '0',
  `dateformat` varchar(30) NOT NULL default '',
  `timeformat` tinyint(1) NOT NULL default '0',
  `pmsound` tinyint(1) NOT NULL default '0',
  `showemail` tinyint(1) default '0',
  `newsletter` tinyint(1) NOT NULL default '0',
  `invisible` tinyint(1) NOT NULL default '0',
  `timeoffset` varchar(12) NOT NULL default '',
  `newpm` tinyint(1) NOT NULL default '0',
  `accessmasks` tinyint(1) NOT NULL default '0',
  `face` varchar(180) NOT NULL default '',
  `tag_count` int(10) NOT NULL default '0',
  `role_id` tinyint(1) NOT NULL default '0',
  `role_type` varchar(18) NOT NULL default '',
  `new_msg_count` tinyint(1) NOT NULL default '0',
  `tag` varchar(255) NOT NULL default '',
  `own_tags` int(10) NOT NULL default '0',
  `login_count` int(10) NOT NULL default '0',
  `truename` varchar(48) NOT NULL default '',
  `phone` varchar(45) NOT NULL default '',
  `last_year_rank` int(10) NOT NULL default '0',
  `last_month_rank` int(10) NOT NULL default '0',
  `last_week_rank` int(10) NOT NULL default '0',
  `this_year_rank` int(10) NOT NULL default '0',
  `this_month_rank` int(10) NOT NULL default '0',
  `this_week_rank` int(10) NOT NULL default '0',
  `last_year_credit` int(10) NOT NULL default '0',
  `last_month_credit` int(10) NOT NULL default '0',
  `last_week_credit` int(10) NOT NULL default '0',
  `this_year_credit` int(10) NOT NULL default '0',
  `this_month_credit` int(10) NOT NULL default '0',
  `this_week_credit` int(10) NOT NULL default '0',
  `view_times` int(10) NOT NULL default '0',
  `use_tag_count` int(10) NOT NULL default '0',
  `create_tag_count` int(10) NOT NULL default '0',
  `image_count` int(10) NOT NULL default '0',
  `noticenum` int(10) NOT NULL default '0',
  `ucuid` int(10) NOT NULL default '0',
  `invite_count` int(10) NOT NULL default '0',
  `invitecode` varchar(48) NOT NULL default '',
  `province` varchar(48) NOT NULL default '',
  `city` varchar(48) NOT NULL default '',
  `topic_count` int(10) NOT NULL default '0',
  `at_count` int(10) NOT NULL default '0',
  `follow_count` int(10) NOT NULL default '0',
  `fans_count` int(10) NOT NULL default '0',
  `email2` varchar(150) NOT NULL default '',
  `qq` varchar(30) NOT NULL default '',
  `msn` varchar(150) NOT NULL default '',
  `aboutme` varchar(255) NOT NULL default '',
  `at_new` int(10) NOT NULL default '0',
  `comment_new` int(10) NOT NULL default '0',
  `fans_new` int(10) NOT NULL default '0',
  `topic_favorite_count` int(10) NOT NULL default '0',
  `tag_favorite_count` int(10) NOT NULL default '0',
  `disallow_beiguanzhu` tinyint(1) NOT NULL default '0',
  `validate` tinyint(1) NOT NULL default '0',
  `favoritemy_new` int(10) NOT NULL default '0',
  `money` decimal(10,2) NOT NULL default '0.00',
  `checked` tinyint(1) NOT NULL default '0',
  `finder` int(10) NOT NULL default '0',
  `findtime` int(10) NOT NULL default '0',
  `totalpay` decimal(10,2) default '0.00',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_onlinetime` */

DROP TABLE IF EXISTS `{prefix}system_onlinetime`;

CREATE TABLE `{prefix}system_onlinetime` (
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `thismonth` smallint(4) unsigned NOT NULL default '0',
  `total` mediumint(8) unsigned NOT NULL default '0',
  `lastupdate` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_report` */

DROP TABLE IF EXISTS `{prefix}system_report`;

CREATE TABLE `{prefix}system_report` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) NOT NULL default '0',
  `username` char(15) NOT NULL default '',
  `ip` char(15) NOT NULL default '',
  `type` tinyint(1) NOT NULL default '0',
  `reason` tinyint(1) NOT NULL default '0',
  `content` text NOT NULL,
  `url` text NOT NULL,
  `dateline` int(10) NOT NULL default '0',
  `process_user` char(15) NOT NULL default '',
  `process_time` int(10) NOT NULL default '0',
  `process_result` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_robot` */

DROP TABLE IF EXISTS `{prefix}system_robot`;

CREATE TABLE `{prefix}system_robot` (
  `name` char(50) NOT NULL default '',
  `times` int(10) unsigned NOT NULL default '0',
  `first_visit` int(10) NOT NULL default '0',
  `last_visit` int(10) NOT NULL default '0',
  `agent` char(255) NOT NULL default '',
  `disallow` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_robot_ip` */

DROP TABLE IF EXISTS `{prefix}system_robot_ip`;

CREATE TABLE `{prefix}system_robot_ip` (
  `ip` char(15) NOT NULL default '',
  `name` char(50) NOT NULL default '',
  `times` int(10) unsigned NOT NULL default '0',
  `first_visit` int(10) NOT NULL default '0',
  `last_visit` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_robot_log` */

DROP TABLE IF EXISTS `{prefix}system_robot_log`;

CREATE TABLE `{prefix}system_robot_log` (
  `name` char(50) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `times` int(10) unsigned NOT NULL default '0',
  `first_visit` int(10) unsigned NOT NULL default '0',
  `last_visit` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `name` (`name`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_role` */

DROP TABLE IF EXISTS `{prefix}system_role`;

CREATE TABLE `{prefix}system_role` (
  `id` tinyint(1) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `creditshigher` int(10) NOT NULL default '0',
  `creditslower` int(10) NOT NULL default '0',
  `privilege` text NOT NULL,
  `type` enum('normal','admin') NOT NULL default 'normal',
  `rank` tinyint(1) unsigned NOT NULL default '0',
  `discuz_group_id` tinyint(1) unsigned NOT NULL default '10',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_role_action` */

DROP TABLE IF EXISTS `{prefix}system_role_action`;

CREATE TABLE `{prefix}system_role_action` (
  `id` smallint(4) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `module` varchar(50) NOT NULL default 'index',
  `action` varchar(150) NOT NULL default '',
  `describe` varchar(255) NOT NULL default '',
  `message` varchar(255) NOT NULL default '',
  `allow_all` tinyint(1) NOT NULL default '0',
  `credit_require` varchar(255) NOT NULL default '',
  `credit_update` varchar(255) NOT NULL default '',
  `php_code` text NOT NULL,
  `log` tinyint(1) unsigned NOT NULL default '0',
  `is_admin` tinyint(1) unsigned default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `action` (`module`,`name`,`is_admin`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_role_module` */

DROP TABLE IF EXISTS `{prefix}system_role_module`;

CREATE TABLE `{prefix}system_role_module` (
  `module` varchar(50) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  UNIQUE KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}system_sessions` */

DROP TABLE IF EXISTS `{prefix}system_sessions`;

CREATE TABLE `{prefix}system_sessions` (
  `sid` char(6) NOT NULL default '',
  `ip1` tinyint(1) unsigned NOT NULL default '0',
  `ip2` tinyint(1) unsigned NOT NULL default '0',
  `ip3` tinyint(1) unsigned NOT NULL default '0',
  `ip4` tinyint(1) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(100) NOT NULL default '',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `styleid` smallint(4) unsigned NOT NULL default '0',
  `invisible` tinyint(1) NOT NULL default '0',
  `action` tinyint(1) unsigned NOT NULL default '0',
  `lastactivity` int(10) unsigned NOT NULL default '0',
  `lastolupdate` int(10) unsigned NOT NULL default '0',
  `pageviews` smallint(4) unsigned NOT NULL default '0',
  `seccode` mediumint(6) unsigned NOT NULL default '0',
  `fid` smallint(4) unsigned NOT NULL default '0',
  `tid` mediumint(8) unsigned NOT NULL default '0',
  `bloguid` mediumint(8) unsigned NOT NULL default '0',
  UNIQUE KEY `sid` (`sid`),
  KEY `uid` (`uid`),
  KEY `bloguid` (`bloguid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_address` */

DROP TABLE IF EXISTS `{prefix}tttuangou_address`;

CREATE TABLE `{prefix}tttuangou_address` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `owner` int(10) unsigned NOT NULL,
  `name` varchar(18) NOT NULL,
  `region` varchar(18) NOT NULL,
  `address` text NOT NULL,
  `zip` varchar(6) default NULL,
  `phone` varchar(12) NOT NULL,
  `lastuse` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `owner` (`owner`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_api_apps` */

DROP TABLE IF EXISTS `{prefix}tttuangou_api_apps`;

CREATE TABLE `{prefix}tttuangou_api_apps` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `appcode` CHAR(32) NOT NULL,
  `protocol` CHAR(32) NOT NULL,
  `name` CHAR(32) NOT NULL,
  `description` CHAR(128) NOT NULL,
  `seckey` CHAR(255) NOT NULL,
  `enabled` ENUM('true','false') NOT NULL DEFAULT 'false',
  `timestamp_update` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `appcode` (`appcode`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_api_protocol` */

DROP TABLE IF EXISTS `{prefix}tttuangou_api_protocol`;

CREATE TABLE `{prefix}tttuangou_api_protocol` (
  `channel` ENUM('system','client') NOT NULL,
  `sign` CHAR(32) NOT NULL,
  `appcode` CHAR(32) NOT NULL,
  `uri` CHAR(128) NOT NULL,
  `login` ENUM('yes','no') NOT NULL DEFAULT 'yes',
  `private` ENUM('yes','no') NOT NULL DEFAULT 'yes',
  `fields` TEXT NOT NULL,
  `timestamp_update` INT(10) UNSIGNED NOT NULL,
  UNIQUE INDEX `channel_sign_appcode_uri` (`channel`, `sign`, `appcode`, `uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_api_session` */

DROP TABLE IF EXISTS `{prefix}tttuangou_api_session`;

CREATE TABLE `{prefix}tttuangou_api_session` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `appcode` CHAR(32) NOT NULL,
  `token` CHAR(32) NOT NULL,
  `user_id` INT(10) UNSIGNED NOT NULL,
  `total_request` INT(10) UNSIGNED NOT NULL,
  `timestamp_request` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `appcode_token` (`appcode`, `token`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_article` */

DROP TABLE IF EXISTS `{prefix}tttuangou_article`;

CREATE TABLE `{prefix}tttuangou_article` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`author_id` INT(10) UNSIGNED NOT NULL,
	`title` VARCHAR(256) NOT NULL,
	`content` TEXT NOT NULL,
	`writer` VARCHAR(32) NOT NULL,
	`timestamp_create` INT(10) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_attrs` */

DROP TABLE IF EXISTS `{prefix}tttuangou_attrs`;

CREATE TABLE `{prefix}tttuangou_attrs` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`cat_id` INT(10) UNSIGNED NOT NULL,
	`name` VARCHAR(128) NOT NULL,
	`price_moves` DECIMAL(10,2) NOT NULL,
	`binding` ENUM('true','false') NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `cat_id` (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_attrs_cat` */

DROP TABLE IF EXISTS `{prefix}tttuangou_attrs_cat`;

CREATE TABLE `{prefix}tttuangou_attrs_cat` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`product_id` INT(10) UNSIGNED NOT NULL,
	`name` VARCHAR(128) NOT NULL,
	`required` ENUM('true','false') NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_attrs_order` */

DROP TABLE IF EXISTS `{prefix}tttuangou_attrs_order`;

CREATE TABLE `{prefix}tttuangou_attrs_order` (
	`sign` BIGINT(11) UNSIGNED NOT NULL,
	`price` DECIMAL(10,2) NOT NULL,
	`data` TEXT NOT NULL,
	UNIQUE INDEX `sign` (`sign`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_catalog` */

DROP TABLE IF EXISTS `{prefix}tttuangou_catalog`;

CREATE TABLE `{prefix}tttuangou_catalog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL,
  `flag` varchar(32) NOT NULL,
  `oslcount` int(10) unsigned NOT NULL DEFAULT '0',
  `procount` int(10) unsigned NOT NULL DEFAULT '0',
  `upstime` int(10) unsigned NOT NULL,
  `order` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `flag` (`flag`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_city` */

DROP TABLE IF EXISTS `{prefix}tttuangou_city`;

CREATE TABLE `{prefix}tttuangou_city` (
  `cityid` int(10) NOT NULL auto_increment,
  `cityname` varchar(50) NOT NULL default '',
  `shorthand` varchar(20) NOT NULL default '',
  `display` tinyint(1) default '0',
  UNIQUE KEY `cityid` (`cityid`),
  KEY `display` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_city_place` */

DROP TABLE IF EXISTS `{prefix}tttuangou_city_place`;

CREATE TABLE `{prefix}tttuangou_city_place` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`type` ENUM('region','street') NOT NULL,
	`parent_type` ENUM('city','region') NOT NULL,
	`parent_id` INT(10) UNSIGNED NOT NULL,
	`name` CHAR(32) NOT NULL,
	`timestamp_update` INT(10) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_comments` */

DROP TABLE IF EXISTS `{prefix}tttuangou_comments`;

CREATE TABLE `{prefix}tttuangou_comments` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT(10) UNSIGNED NOT NULL,
  `user_id` INT(10) UNSIGNED NOT NULL,
  `user_name` CHAR(32) NOT NULL,
  `score` TINYINT(1) UNSIGNED NOT NULL DEFAULT '5',
  `content` TEXT NOT NULL,
  `reply` TEXT NOT NULL,
  `toped` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `status` ENUM('auditing','approved','denied') NOT NULL DEFAULT 'auditing',
  `timestamp_update` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_express` */

DROP TABLE IF EXISTS `{prefix}tttuangou_express`;

CREATE TABLE `{prefix}tttuangou_express` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(120) NOT NULL,
  `express` smallint(5) unsigned NOT NULL default '0',
  `firstunit` int(10) unsigned NOT NULL default '1000',
  `firstprice` decimal(10,2) unsigned NOT NULL default '10.00',
  `continueunit` int(10) unsigned NOT NULL default '1000',
  `continueprice` decimal(10,2) unsigned NOT NULL default '5.00',
  `regiond` tinyint(1) unsigned NOT NULL default '0',
  `dpenable` enum('true','false') NOT NULL default 'false',
  `detail` text NOT NULL,
  `order` smallint(5) unsigned NOT NULL default '1',
  `enabled` enum('true','false') NOT NULL default 'false',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_express_area` */

DROP TABLE IF EXISTS `{prefix}tttuangou_express_area`;

CREATE TABLE `{prefix}tttuangou_express_area` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL,
  `firstprice` decimal(10,2) unsigned NOT NULL default '10.00',
  `continueprice` decimal(10,2) unsigned NOT NULL default '5.00',
  `region` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_express_cdp` */

DROP TABLE IF EXISTS `{prefix}tttuangou_express_cdp`;

CREATE TABLE `{prefix}tttuangou_express_cdp` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `config` text NOT NULL,
  `upstime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_express_corp` */

DROP TABLE IF EXISTS `{prefix}tttuangou_express_corp`;

CREATE TABLE `{prefix}tttuangou_express_corp` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `flag` varchar(10) NOT NULL default 'OTHER',
  `name` varchar(50) NOT NULL,
  `site` varchar(120) NOT NULL,
  `enabled` enum('true','false') NOT NULL default 'false',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_express_printer_log` */

DROP TABLE IF EXISTS `{prefix}tttuangou_express_printer_log`;

CREATE TABLE `{prefix}tttuangou_express_printer_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sign` bigint(11) unsigned NOT NULL,
  `corp` int(10) unsigned NOT NULL,
  `sender` int(10) unsigned NOT NULL,
  `upstime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sign` (`sign`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_finder` */

DROP TABLE IF EXISTS `{prefix}tttuangou_finder`;

CREATE TABLE `{prefix}tttuangou_finder` (
  `id` int(10) NOT NULL auto_increment,
  `buyid` int(10) NOT NULL default '0',
  `buytime` int(10) NOT NULL default '0',
  `productid` int(10) NOT NULL default '0',
  `finderid` int(10) NOT NULL default '0',
  `findtime` int(10) NOT NULL default '0',
  `status` smallint(2) NOT NULL default '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_metas` */

DROP TABLE IF EXISTS `{prefix}tttuangou_metas`;

CREATE TABLE `{prefix}tttuangou_metas` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned default NULL,
  `key` char(64) NOT NULL,
  `val` text NOT NULL,
  `life` int(10) unsigned NOT NULL default '3600',
  `uptime` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_order` */

DROP TABLE IF EXISTS `{prefix}tttuangou_order`;

CREATE TABLE `{prefix}tttuangou_order` (
  `orderid` bigint(11) NOT NULL default '0',
  `productid` int(10) NOT NULL default '0',
  `productnum` int(10) NOT NULL default '0',
  `productprice` decimal(10,2) NOT NULL default '0.00',
  `totalprice` decimal(10,2) default '0.00',
  `userid` int(10) NOT NULL default '0',
  `addressid` int(10) NOT NULL default '0',
  `buytime` int(10) NOT NULL default '0',
  `paytype` int(10) default '0',
  `paymoney` decimal(10,2) NOT NULL default '0.00',
  `pay` tinyint(1) default '0',
  `paytime` int(10) NOT NULL default '0',
  `expresstype` int(10) NOT NULL default '0',
  `expressprice` decimal(10,2) NOT NULL default '0.00',
  `invoice` varchar(32) NOT NULL,
  `expresstime` int(10) NOT NULL default '0',
  `extmsg` text NOT NULL,
  `extmsg_reply` text NOT NULL,
  `process` varchar(24) NOT NULL default '__CREATE__',
  `status` tinyint(1) unsigned NOT NULL,
  `remark` text,
  UNIQUE KEY `orderid` (`orderid`),
  KEY `productid` (`productid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_order_clog` */

DROP TABLE IF EXISTS `{prefix}tttuangou_order_clog`;

CREATE TABLE `{prefix}tttuangou_order_clog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sign` bigint(11) unsigned default NULL,
  `action` varchar(36) default NULL,
  `uid` int(10) unsigned NOT NULL,
  `remark` text,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_paylog` */

DROP TABLE IF EXISTS `{prefix}tttuangou_paylog`;

CREATE TABLE `{prefix}tttuangou_paylog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `sign` varchar(32) NOT NULL,
  `money` decimal(10,2) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `trade_no` varchar(32) NOT NULL,
  `status` varchar(24) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `sign` (`sign`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_payment` */

DROP TABLE IF EXISTS `{prefix}tttuangou_payment`;

CREATE TABLE `{prefix}tttuangou_payment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `code` varchar(20) NOT NULL,
  `name` varchar(120) NOT NULL,
  `detail` text NOT NULL,
  `order` int(10) unsigned NOT NULL default '0',
  `config` text NOT NULL,
  `enabled` enum('true','false') NOT NULL default 'false',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pay_code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_prize_phone` */

DROP TABLE IF EXISTS `{prefix}tttuangou_prize_phone`;

CREATE TABLE `{prefix}tttuangou_prize_phone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `phone` char(11) NOT NULL,
  `vfcode` char(6) NOT NULL,
  `vftime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_prize_ticket` */

DROP TABLE IF EXISTS `{prefix}tttuangou_prize_ticket`;

CREATE TABLE `{prefix}tttuangou_prize_ticket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `remark` varchar(128) NOT NULL,
  `upstime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_prize_ticket_win` */

DROP TABLE IF EXISTS `{prefix}tttuangou_prize_ticket_win`;

CREATE TABLE `{prefix}tttuangou_prize_ticket_win` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `phone` char(11) NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `upstime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_product` */

DROP TABLE IF EXISTS `{prefix}tttuangou_product`;

CREATE TABLE `{prefix}tttuangou_product` (
  `id` int(10) NOT NULL auto_increment,
  `category` int(10) unsigned NOT NULL DEFAULT '0',
  `sellerid` int(10) NOT NULL default '0',
  `city` int(10) NOT NULL default '0',
  `city_place_region` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `city_place_street` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL default '',
  `flag` varchar(80) NOT NULL,
  `price` decimal(10,2) NOT NULL default '0.00',
  `nowprice` decimal(10,2) NOT NULL default '0.00',
  `img` text NOT NULL,
  `intro` text NOT NULL,
  `content` text NOT NULL,
  `cue` text NOT NULL,
  `theysay` text NOT NULL,
  `wesay` text NOT NULL,
  `begintime` int(10) NOT NULL default '0',
  `overtime` int(10) NOT NULL default '0',
  `type` enum('ticket','stuff','prize') NOT NULL default 'ticket',
  `perioddate` int(10) NOT NULL default '0',
  `weight` int(10) unsigned NOT NULL,
  `successnum` smallint(6) NOT NULL default '0',
  `virtualnum` smallint(6) NOT NULL default '0',
  `maxnum` int(10) default '0',
  `oncemax` int(10) default '0',
  `oncemin` int(10) default '1',
  `multibuy` enum('true','false') NOT NULL default 'false',
  `allinone` enum('true','false') NOT NULL default 'false',
  `totalnum` int(10) default '0',
  `display` tinyint(1) NOT NULL default '0',
  `addtime` int(10) NOT NULL default '0',
  `status` smallint(1) NOT NULL default '1',
  `order` smallint(6) default '0',
  `saveHandler` enum('normal','draft') NOT NULL default 'normal',
  `draft` int(10) unsigned NOT NULL default '0',
  `sells_count` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `longitude` DOUBLE(15,7) UNSIGNED NOT NULL DEFAULT '0.0000000',
  `latitude` DOUBLE(15,7) UNSIGNED NOT NULL DEFAULT '0.0000000',
  UNIQUE KEY `id` (`id`),
  KEY `city` (`city`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_push_log` */

DROP TABLE IF EXISTS `{prefix}tttuangou_push_log`;

CREATE TABLE `{prefix}tttuangou_push_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(8) NOT NULL,
  `driver` varchar(8) NOT NULL,
  `target` varchar(128) NOT NULL,
  `data` text NOT NULL,
	`title` TEXT NOT NULL,
	`content` TEXT NOT NULL,
  `result` varchar(256) NOT NULL,
	`result_raw` TEXT NOT NULL,
  `update` int(10) unsigned NOT NULL,
	`logger` ENUM('true','false') NOT NULL DEFAULT 'false',
	`status` ENUM('success','failed','system') NOT NULL DEFAULT 'system',
  `queuemsg` VARCHAR(128) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_push_queue` */

DROP TABLE IF EXISTS `{prefix}tttuangou_push_queue`;

CREATE TABLE `{prefix}tttuangou_push_queue` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(8) NOT NULL,
  `target` MEDIUMTEXT NOT NULL,
  `title` VARCHAR(512) NOT NULL,
  `content` TEXT NOT NULL,
  `guid` CHAR(36) NOT NULL,
  `worked` ENUM('idle','busying','completed','overdue') NOT NULL DEFAULT 'idle',
  `rund` ENUM('true','false') NOT NULL DEFAULT 'false',
  `result` VARCHAR(256) NULL DEFAULT NULL,
  `update` INT(10) UNSIGNED NOT NULL,
  `pr` TINYINT(3) UNSIGNED NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `guid_worked` (`guid`, `worked`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_push_template` */

DROP TABLE IF EXISTS `{prefix}tttuangou_push_template`;

CREATE TABLE `{prefix}tttuangou_push_template` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(8) NOT NULL,
  `name` varchar(128) NOT NULL,
  `intro` varchar(256) NOT NULL,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `update` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_question` */

DROP TABLE IF EXISTS `{prefix}tttuangou_question`;

CREATE TABLE `{prefix}tttuangou_question` (
  `id` int(10) NOT NULL auto_increment,
  `userid` int(10) NOT NULL default '0',
  `username` varchar(100) NOT NULL default '',
  `content` text NOT NULL,
  `reply` text NOT NULL,
  `time` int(10) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_recharge_card` */

DROP TABLE IF EXISTS `{prefix}tttuangou_recharge_card`;

CREATE TABLE `{prefix}tttuangou_recharge_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` char(12) NOT NULL,
  `password` char(6) NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  `usetime` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `number` (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_recharge_order` */

DROP TABLE IF EXISTS `{prefix}tttuangou_recharge_order`;

CREATE TABLE `{prefix}tttuangou_recharge_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(11) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `money` decimal(10,2) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `payment` int(10) unsigned NOT NULL,
  `paytime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orderid` (`orderid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_regions` */

DROP TABLE IF EXISTS `{prefix}tttuangou_regions`;

CREATE TABLE `{prefix}tttuangou_regions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned default NULL,
  `path` varchar(20) default NULL,
  `grade` mediumint(8) unsigned default NULL,
  `name` varchar(50) NOT NULL,
  `enabled` enum('true','false') default 'false',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_reports` */

DROP TABLE IF EXISTS `{prefix}tttuangou_reports`;

CREATE TABLE `{prefix}tttuangou_reports` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`service` CHAR(32) NOT NULL,
	`channel` CHAR(32) NOT NULL,
	`hoster` INT(10) UNSIGNED NOT NULL,
	`data` DECIMAL(10,2) UNSIGNED NOT NULL,
	`dateline` INT(10) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `service` (`service`),
	INDEX `channel` (`channel`),
	INDEX `hoster` (`hoster`),
	INDEX `dateline` (`dateline`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_seller` */

DROP TABLE IF EXISTS `{prefix}tttuangou_seller`;

CREATE TABLE `{prefix}tttuangou_seller` (
  `id` int(10) NOT NULL auto_increment,
  `userid` int(10) NOT NULL default '0',
  `sellername` varchar(100) default NULL,
  `sellerphone` varchar(100) default NULL,
  `selleraddress` varchar(200) default NULL,
  `sellerurl` varchar(100) default NULL,
  `sellermap` varchar(100) default NULL,
  `area` smallint(6) default NULL,
  `productnum` int(10) default '0',
  `successnum` int(10) default '0',
  `money` decimal(10,2) default '0.00',
  `time` int(10) NOT NULL default '0',
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_service` */

DROP TABLE IF EXISTS `{prefix}tttuangou_service`;

CREATE TABLE `{prefix}tttuangou_service` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `type` varchar(8) NOT NULL,
  `flag` varchar(18) NOT NULL,
  `name` varchar(32) NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `config` text NOT NULL,
  `enabled` enum('true','false') NOT NULL default 'false',
  `update` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_subscribe` */

DROP TABLE IF EXISTS `{prefix}tttuangou_subscribe`;

CREATE TABLE `{prefix}tttuangou_subscribe` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(8) NOT NULL,
  `target` varchar(128) NOT NULL,
  `city` int(10) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL,
  `validated` enum('true','false') NOT NULL DEFAULT 'false',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `target` (`target`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_ticket` */

DROP TABLE IF EXISTS `{prefix}tttuangou_ticket`;

CREATE TABLE `{prefix}tttuangou_ticket` (
  `ticketid` INT(10) NOT NULL AUTO_INCREMENT,
  `uid` INT(10) NOT NULL DEFAULT '0',
  `productid` INT(10) NOT NULL DEFAULT '0',
  `orderid` BIGINT(11) NOT NULL DEFAULT '0',
  `guid` CHAR(36) NOT NULL,
  `number` VARCHAR(12) NOT NULL,
  `password` VARCHAR(6) NOT NULL DEFAULT '',
  `usetime` DATETIME NOT NULL,
  `status` TINYINT(1) NULL DEFAULT '0',
  `mutis` INT(10) UNSIGNED NOT NULL DEFAULT '1',
  UNIQUE INDEX `ticketid` (`ticketid`),
  UNIQUE INDEX `number` (`number`),
  UNIQUE INDEX `guid` (`guid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_uploads` */

DROP TABLE IF EXISTS `{prefix}tttuangou_uploads`;

CREATE TABLE `{prefix}tttuangou_uploads` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `intro` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(12) NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `mime` varchar(32) NOT NULL,
  `extra` text NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `update` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_usermoney` */

DROP TABLE IF EXISTS `{prefix}tttuangou_usermoney`;

CREATE TABLE `{prefix}tttuangou_usermoney` (
  `id` int(10) NOT NULL auto_increment,
  `userid` int(10) NOT NULL default '0',
  `class` enum('sys','usr') NOT NULL default 'sys',
  `type` enum('plus','minus') NOT NULL default 'plus',
  `name` varchar(100) NOT NULL,
  `intro` varchar(200) NOT NULL,
  `money` decimal(10,2) NOT NULL default '0.00',
  `time` int(10) NOT NULL default '0',
  UNIQUE KEY `mid` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_usermsg` */

DROP TABLE IF EXISTS `{prefix}tttuangou_usermsg`;

CREATE TABLE `{prefix}tttuangou_usermsg` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `phone` varchar(50) NOT NULL default '',
  `elsecontat` varchar(200) NOT NULL default '',
  `content` text NOT NULL,
  `time` int(10) NOT NULL default '0',
  `type` smallint(6) NOT NULL default '0',
  `readed` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `{prefix}tttuangou_zlog` */

DROP TABLE IF EXISTS `{prefix}tttuangou_zlog`;

CREATE TABLE `{prefix}tttuangou_zlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` char(12) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `uip` int(10) unsigned NOT NULL,
  `index` char(32) NOT NULL,
  `name` varchar(128) NOT NULL,
  `extra` text NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `index` (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
