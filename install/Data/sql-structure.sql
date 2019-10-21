DROP TABLE IF EXISTS `qs_ad`;
CREATE TABLE `qs_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(30) NOT NULL,
  `alias` varchar(80) NOT NULL,
  `is_display` tinyint(1) NOT NULL DEFAULT '1',
  `category_id` int(10) NOT NULL,
  `type_id` smallint(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `note` varchar(230) NOT NULL,
  `show_order` int(10) unsigned NOT NULL DEFAULT '50',
  `addtime` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `deadline` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `text_color` varchar(60) NOT NULL,
  `explain` varchar(255) NOT NULL,
  `uid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias_starttime_deadline` (`alias`,`starttime`,`deadline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_ad||-_-||

DROP TABLE IF EXISTS `qs_admin`;
CREATE TABLE `qs_admin` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(32) NOT NULL,
  `pwd_hash` varchar(30) NOT NULL,
  `role_id` int(11) NOT NULL,
  `add_time` int(10) NOT NULL,
  `last_login_time` int(10) NOT NULL,
  `last_login_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_admin||-_-||

DROP TABLE IF EXISTS `qs_admin_auth`;
CREATE TABLE `qs_admin_auth` (
  `role_id` INT(10) NOT NULL,
  `menu_id` smallint(6) NOT NULL,
  KEY `role_id` (`role_id`,`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_admin_auth||-_-||

DROP TABLE IF EXISTS `qs_admin_auth_group`;
CREATE TABLE `qs_admin_auth_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `mid` int(11) NOT NULL,
  `msid` int(11) NOT NULL,
  `mids` varchar(500) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `ordid` int(10) NOT NULL,
  `addtime` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_admin_auth_group||-_-||

DROP TABLE IF EXISTS `qs_admin_log`;
CREATE TABLE `qs_admin_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_url` varchar(255) NOT NULL,
  `log_ip` varchar(30) NOT NULL,
  `log_address` varchar(30) NOT NULL,
  `log_addtime` int(10) unsigned NOT NULL,
  `operater_id` int(10) unsigned NOT NULL,
  `operater` varchar(30) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_admin_log||-_-||

DROP TABLE IF EXISTS `qs_admin_role`;
CREATE TABLE `qs_admin_role` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `groups` varchar(500) NOT NULL,
  `mids` varchar(500) NOT NULL,
  `remark` text NOT NULL,
  `ordid` INT(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_admin_role||-_-||

DROP TABLE IF EXISTS `qs_adv_resume`;
CREATE TABLE `qs_adv_resume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT NULL,
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `display_name` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `audit` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `stick` tinyint(1) unsigned NOT NULL,
  `strong_tag` int(10) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `fullname` varchar(15) NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL,
  `sex_cn` varchar(3) NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `nature_cn` varchar(30) NOT NULL,
  `trade` varchar(60) NOT NULL,
  `trade_cn` varchar(100) NOT NULL,
  `birthdate` smallint(4) unsigned NOT NULL,
  `residence` varchar(30) NOT NULL DEFAULT '',
  `height` varchar(5) NOT NULL,
  `marriage` tinyint(3) unsigned NOT NULL,
  `marriage_cn` varchar(5) NOT NULL,
  `experience` smallint(5) NOT NULL,
  `experience_cn` varchar(30) NOT NULL,
  `district` varchar(100) NOT NULL,
  `district_cn` varchar(255) NOT NULL DEFAULT '',
  `wage` smallint(5) unsigned NOT NULL,
  `wage_cn` varchar(30) NOT NULL,
  `householdaddress` varchar(30) NOT NULL DEFAULT '',
  `education` smallint(5) unsigned NOT NULL,
  `education_cn` varchar(30) NOT NULL,
  `major` smallint(5) NOT NULL,
  `major_cn` varchar(50) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `tag_cn` varchar(100) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `email_notify` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `intention_jobs_id` varchar(100) NOT NULL,
  `intention_jobs` varchar(255) NOT NULL,
  `specialty` varchar(1000) NOT NULL,
  `photo` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `photo_img` varchar(255) NOT NULL DEFAULT '',
  `photo_audit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `photo_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `qq` varchar(30) NOT NULL,
  `weixin` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `stime` int(10) NOT NULL,
  `entrust` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL,
  `complete_percent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `current` smallint(5) unsigned NOT NULL,
  `current_cn` varchar(50) NOT NULL DEFAULT '',
  `word_resume` varchar(255) NOT NULL,
  `word_resume_title` varchar(255) NOT NULL,
  `word_resume_addtime` int(10) unsigned NOT NULL,
  `key_full` text NOT NULL,
  `key_precise` text NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `tpl` varchar(60) NOT NULL,
  `resume_from_pc` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `def` tinyint(1) NOT NULL,
  `mobile_audit` tinyint(1) NOT NULL,
  `subsite_id` int(11) unsigned NOT NULL,
  `auto_refresh` tinyint(10) DEFAULT NULL,
  `auto_apply` tinyint(10) DEFAULT NULL,
  `comment_content` varchar(255) NOT NULL,
  `talent` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_quick` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `refreshtime` (`refreshtime`),
  KEY `addtime` (`addtime`),
  KEY `audit_addtime` (`audit`,`addtime`) USING BTREE,
  KEY `audit_display` (`audit`,`display`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_adv_resume||-_-||

DROP TABLE IF EXISTS `qs_adv_resume_credent`;
CREATE TABLE `qs_adv_resume_credent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET gbk NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `images` varchar(255) CHARACTER SET gbk NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_adv_resume_credent||-_-||

DROP TABLE IF EXISTS `qs_adv_resume_education`;
CREATE TABLE `qs_adv_resume_education` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `startyear` smallint(4) unsigned NOT NULL,
  `startmonth` smallint(2) unsigned NOT NULL,
  `endyear` smallint(4) unsigned NOT NULL,
  `endmonth` smallint(2) unsigned NOT NULL,
  `school` varchar(50) NOT NULL,
  `speciality` varchar(50) NOT NULL,
  `education` smallint(5) unsigned NOT NULL,
  `education_cn` varchar(30) NOT NULL DEFAULT '',
  `todate` int(10) unsigned NOT NULL,
  `campus_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_adv_resume_education||-_-||

DROP TABLE IF EXISTS `qs_adv_resume_img`;
CREATE TABLE `qs_adv_resume_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL,
  `audit` tinyint(1) NOT NULL,
  `subsite_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `resume_id` (`resume_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_adv_resume_img||-_-||

DROP TABLE IF EXISTS `qs_adv_resume_language`;
CREATE TABLE `qs_adv_resume_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `language` smallint(5) NOT NULL,
  `language_cn` varchar(50) CHARACTER SET gbk NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `level_cn` varchar(50) CHARACTER SET gbk NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_adv_resume_language||-_-||

DROP TABLE IF EXISTS `qs_adv_resume_training`;
CREATE TABLE `qs_adv_resume_training` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `startyear` smallint(4) unsigned NOT NULL,
  `startmonth` smallint(2) unsigned NOT NULL,
  `endyear` smallint(4) unsigned NOT NULL,
  `endmonth` smallint(2) unsigned NOT NULL,
  `agency` varchar(50) NOT NULL,
  `course` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `todate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_adv_resume_training||-_-||

DROP TABLE IF EXISTS `qs_adv_resume_work`;
CREATE TABLE `qs_adv_resume_work` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `startyear` smallint(4) unsigned NOT NULL,
  `startmonth` smallint(2) unsigned NOT NULL,
  `endyear` smallint(4) unsigned NOT NULL,
  `endmonth` smallint(2) unsigned NOT NULL,
  `companyname` varchar(50) NOT NULL,
  `jobs` varchar(30) NOT NULL,
  `achievements` varchar(1000) NOT NULL,
  `todate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_adv_resume_work||-_-||

DROP TABLE IF EXISTS `qs_ad_category`;
CREATE TABLE `qs_ad_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(30) NOT NULL,
  `org` varchar(10) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `categoryname` varchar(100) NOT NULL,
  `width` smallint(5) NOT NULL,
  `height` smallint(5) NOT NULL,
  `float` tinyint(1) NOT NULL,
  `floating_left` smallint(5) NOT NULL,
  `floating_right` smallint(5) NOT NULL,
  `floating_top` smallint(5) NOT NULL,
  `admin_set` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ad_num` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_ad_category||-_-||

DROP TABLE IF EXISTS `qs_apply`;
CREATE TABLE `qs_apply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `module_name` varchar(30) NOT NULL,
  `version` varchar(20) NOT NULL,
  `is_create_table` tinyint(1) NOT NULL,
  `is_insert_data` tinyint(1) NOT NULL,
  `is_exe` tinyint(1) NOT NULL,
  `is_delete_data` tinyint(1) NOT NULL,
  `update_time` varchar(20) NOT NULL,
  `setup_time` int(10) NOT NULL,
  `explain` text NOT NULL,
  `versioning` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_apply||-_-||

DROP TABLE IF EXISTS `qs_article`;
CREATE TABLE `qs_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` smallint(5) unsigned NOT NULL,
  `parentid` smallint(5) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `tit_color` varchar(10) DEFAULT NULL,
  `tit_b` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Small_img` varchar(255) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `focos` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_url` varchar(200) NOT NULL DEFAULT '0',
  `seo_keywords` varchar(100) DEFAULT NULL,
  `seo_description` varchar(200) DEFAULT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `addtime` int(10) unsigned NOT NULL,
  `article_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `robot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`,`article_order`,`id`),
  KEY `click` (`click`),
  KEY `focos_article_order` (`focos`,`article_order`,`id`),
  KEY `addtime` (`addtime`),
  KEY `parentid` (`parentid`,`article_order`,`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_article||-_-||

DROP TABLE IF EXISTS `qs_article_category`;
CREATE TABLE `qs_article_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned NOT NULL,
  `categoryname` varchar(80) NOT NULL,
  `category_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `admin_set` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_article_category||-_-||

DROP TABLE IF EXISTS `qs_article_property`;
CREATE TABLE `qs_article_property` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `categoryname` varchar(30) NOT NULL,
  `category_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `admin_set` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_article_property||-_-||

DROP TABLE IF EXISTS `qs_audit_reason`;
CREATE TABLE `qs_audit_reason` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jobs_id` int(10) unsigned NOT NULL DEFAULT '0',
  `company_id` int(10) unsigned NOT NULL DEFAULT '0',
  `resume_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(30) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `audit_man` varchar(30) NOT NULL,
  `addtime` int(10) NOT NULL,
  `famous` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `jobs_id` (`jobs_id`),
  KEY `company_id` (`company_id`),
  KEY `resume_id` (`resume_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_audit_reason||-_-||

DROP TABLE IF EXISTS `qs_authentication`;
CREATE TABLE `qs_authentication` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(11) NOT NULL,
  `secretkey` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mobile_key` (`mobile`,`secretkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_audit_reason||-_-||

DROP TABLE IF EXISTS `qs_badword`;
CREATE TABLE `qs_badword` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `badword` varchar(100) NOT NULL COMMENT '原始数据',
  `replace` varchar(100) NOT NULL COMMENT '替换数据',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否激活',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_badword||-_-||

DROP TABLE IF EXISTS `qs_baiduxml`;
CREATE TABLE `qs_baiduxml` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_baiduxml||-_-||

DROP TABLE IF EXISTS `qs_baidu_submiturl`;
CREATE TABLE `qs_baidu_submiturl` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_baidu_submiturl||-_-||

DROP TABLE IF EXISTS `qs_category`;
CREATE TABLE `qs_category` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_parentid` int(10) unsigned NOT NULL,
  `c_alias` char(30) NOT NULL,
  `c_name` char(30) NOT NULL,
  `c_order` int(10) NOT NULL,
  `c_index` char(1) NOT NULL,
  `c_note` char(60) NOT NULL,
  `stat_jobs` char(15) NOT NULL,
  `stat_resume` char(15) NOT NULL,
  PRIMARY KEY (`c_id`),
  KEY `c_alias` (`c_alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_category||-_-||

DROP TABLE IF EXISTS `qs_category_district`;
CREATE TABLE `qs_category_district` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `categoryname` varchar(30) NOT NULL,
  `category_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `stat_jobs` varchar(15) NOT NULL,
  `stat_resume` varchar(15) NOT NULL,
  `spell` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_category_district||-_-||

DROP TABLE IF EXISTS `qs_category_group`;
CREATE TABLE `qs_category_group` (
  `g_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `g_alias` varchar(60) NOT NULL,
  `g_name` varchar(100) NOT NULL,
  `g_sys` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`g_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_category_group||-_-||

DROP TABLE IF EXISTS `qs_category_jobs`;
CREATE TABLE `qs_category_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned NOT NULL,
  `categoryname` varchar(80) NOT NULL,
  `category_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `stat_jobs` varchar(15) NOT NULL,
  `stat_resume` varchar(15) NOT NULL,
  `content` text,
  `spell` varchar(200) NOT NULL,
  `relation1` varchar(30) NULL,
  `relation1_cn` varchar(30) NULL,
  `relation2` varchar(30) NULL,
  `relation2_cn` varchar(30) NULL, 
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_category_jobs||-_-||

DROP TABLE IF EXISTS `qs_category_major`;
CREATE TABLE `qs_category_major` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) NOT NULL,
  `categoryname` varchar(50) NOT NULL,
  `category_order` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_category_major||-_-||

DROP TABLE IF EXISTS `qs_company_down_resume`;
CREATE TABLE `qs_company_down_resume` (
  `did` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` int(10) unsigned NOT NULL,
  `resume_name` varchar(60) NOT NULL,
  `resume_uid` int(10) unsigned NOT NULL,
  `company_uid` int(10) unsigned NOT NULL,
  `company_name` varchar(60) NOT NULL,
  `down_addtime` int(10) unsigned NOT NULL,
  `is_reply` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`did`),
  KEY `resume_uid_rid` (`resume_uid`,`resume_id`),
  KEY `down_addtime` (`down_addtime`),
  KEY `company_uid_addtime` (`company_uid`,`down_addtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_company_down_resume||-_-||

DROP TABLE IF EXISTS `qs_company_favorites`;
CREATE TABLE `qs_company_favorites` (
  `did` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` int(10) unsigned NOT NULL,
  `company_uid` int(10) unsigned NOT NULL,
  `favorites_addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`did`),
  KEY `company_uid` (`company_uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_company_favorites||-_-||

DROP TABLE IF EXISTS `qs_company_img`;
CREATE TABLE `qs_company_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `img` varchar(255) NOT NULL,
  `addtime` int(100) unsigned NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_company_img||-_-||

DROP TABLE IF EXISTS `qs_company_interview`;
CREATE TABLE `qs_company_interview` (
  `did` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` int(10) unsigned NOT NULL,
  `resume_name` varchar(30) NOT NULL,
  `resume_addtime` int(10) NOT NULL,
  `resume_uid` int(10) unsigned NOT NULL,
  `jobs_id` int(10) unsigned NOT NULL,
  `jobs_name` varchar(60) NOT NULL,
  `jobs_addtime` int(10) NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `company_name` varchar(60) NOT NULL,
  `company_addtime` int(10) NOT NULL,
  `company_uid` int(10) unsigned NOT NULL,
  `interview_addtime` int(10) unsigned NOT NULL,
  `notes` varchar(255) NOT NULL DEFAULT '',
  `personal_look` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `interview_time` varchar(20) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL,
  `contact` varchar(60) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  PRIMARY KEY (`did`),
  KEY `resume_uid_resumeid` (`resume_uid`,`resume_id`),
  KEY `company_uid_jobid` (`company_uid`,`jobs_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_company_interview||-_-||

DROP TABLE IF EXISTS `qs_company_praise`;
CREATE TABLE `qs_company_praise` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `click_type` tinyint(1) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_company_praise||-_-||

DROP TABLE IF EXISTS `qs_company_profile`;
CREATE TABLE `qs_company_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `tpl` varchar(60) NOT NULL,
  `companyname` varchar(60) NOT NULL,
  `nature` smallint(5) unsigned NOT NULL,
  `nature_cn` varchar(30) NOT NULL,
  `trade` smallint(5) unsigned NOT NULL,
  `trade_cn` varchar(30) NOT NULL,
  `district` varchar(100) NOT NULL DEFAULT '',
  `district_cn` varchar(100) NOT NULL DEFAULT '',
  `street` smallint(5) unsigned NOT NULL,
  `street_cn` varchar(50) NOT NULL,
  `scale` smallint(5) unsigned NOT NULL,
  `scale_cn` varchar(30) NOT NULL,
  `registered` varchar(150) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `address` varchar(250) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `telephone` varchar(130) NOT NULL,
  `landline_tel` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `website` varchar(100) NOT NULL,
  `certificate_img` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `contents` text NOT NULL,
  `setmeal_id` smallint(5) unsigned NOT NULL,
  `setmeal_name` varchar(30) NOT NULL,
  `audit` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `map_open` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `map_x` decimal(9,6) NOT NULL,
  `map_y` decimal(9,6) NOT NULL,
  `map_zoom` tinyint(3) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `user_status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `contact_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `telephone_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `email_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `landline_tel_show` tinyint(1) NOT NULL,
  `robot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `comment` int(10) unsigned NOT NULL,
  `resume_processing` tinyint(3) NOT NULL DEFAULT '100',
  `tag` varchar(60) NOT NULL,
  `wzp_tpl` tinyint(1) NOT NULL DEFAULT '0',
  `jobs` int(10) unsigned NOT NULL,
  `replys` int(10) unsigned NOT NULL,
  `qq` varchar(15) NOT NULL,
  `short_name` varchar(60) NOT NULL,
  `short_desc` varchar(255) NOT NULL,
  `famous` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `audit` (`audit`),
  KEY `companyname` (`companyname`),
  KEY `addtime` (`addtime`),
  KEY `jobs_rtime` (`jobs`,`refreshtime`),
  KEY `setmeal_id` (`setmeal_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_company_profile||-_-||

DROP TABLE IF EXISTS `qs_company_statistics`;
CREATE TABLE `qs_company_statistics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `source` tinyint(1) unsigned NOT NULL COMMENT '1pc 2触屏 3移动',
  `jobid` int(10) unsigned NOT NULL,
  `apply` tinyint(1) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `viewtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comid_apply_addtime_source_jobid` (`comid`,`apply`,`addtime`,`source`,`jobid`),
  KEY `comid_apply_addtime_jobid` (`comid`,`apply`,`addtime`,`jobid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_company_statistics||-_-||

DROP TABLE IF EXISTS `qs_company_tpl`;
CREATE TABLE `qs_company_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `tplid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_company_tpl||-_-||

DROP TABLE IF EXISTS `qs_config`;
CREATE TABLE `qs_config` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `note` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_config||-_-||

DROP TABLE IF EXISTS `qs_consultant`;
CREATE TABLE `qs_consultant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `pic` text,
  `qq` varchar(15) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `tel` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_consultant||-_-||

DROP TABLE IF EXISTS `qs_consultant_complaint`;
CREATE TABLE `qs_consultant_complaint` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `consultant_id` int(10) unsigned NOT NULL,
  `consultant_name` varchar(30) NOT NULL,
  `notes` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `audit` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_consultant_complaint||-_-||

DROP TABLE IF EXISTS `qs_crons`;
CREATE TABLE `qs_crons` (
  `cronid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `available` tinyint(1) unsigned NOT NULL,
  `admin_set` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL,
  `filename` varchar(60) NOT NULL,
  `lastrun` int(10) unsigned NOT NULL,
  `nextrun` int(10) unsigned NOT NULL,
  `weekday` tinyint(1) NOT NULL,
  `day` tinyint(2) NOT NULL,
  `hour` tinyint(2) NOT NULL,
  `minute` varchar(60) NOT NULL,
  PRIMARY KEY (`cronid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_crons||-_-||

DROP TABLE IF EXISTS `qs_explain`;
CREATE TABLE `qs_explain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` smallint(5) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `tit_color` varchar(10) DEFAULT NULL,
  `tit_b` tinyint(1) NOT NULL DEFAULT '0',
  `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_url` varchar(200) NOT NULL DEFAULT '0',
  `seo_keywords` varchar(100) DEFAULT NULL,
  `seo_description` varchar(200) DEFAULT NULL,
  `click` int(11) NOT NULL DEFAULT '1',
  `addtime` int(10) NOT NULL,
  `show_order` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_explain||-_-||

DROP TABLE IF EXISTS `qs_explain_category`;
CREATE TABLE `qs_explain_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `categoryname` varchar(80) NOT NULL,
  `category_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `admin_set` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_explain_category||-_-||

DROP TABLE IF EXISTS `qs_feedback`;
CREATE TABLE `qs_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `infotype` tinyint(3) unsigned NOT NULL,
  `feedback` varchar(250) NOT NULL,
  `addtime` int(10) NOT NULL,
  `tel` varchar(255) NOT NULL DEFAULT '',
  `audit` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_feedback||-_-||

DROP TABLE IF EXISTS `qs_help`;
CREATE TABLE `qs_help` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` smallint(5) unsigned NOT NULL,
  `parentid` smallint(5) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `addtime` int(10) unsigned NOT NULL,
  `ordid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`,`ordid`,`id`),
  KEY `focos_article_order` (`ordid`,`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_help||-_-||

DROP TABLE IF EXISTS `qs_help_category`;
CREATE TABLE `qs_help_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned NOT NULL,
  `categoryname` varchar(80) NOT NULL,
  `category_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_help_category||-_-||

DROP TABLE IF EXISTS `qs_hotword`;
CREATE TABLE `qs_hotword` (
  `w_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `w_word` varchar(120) NOT NULL,
  `w_hot` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`w_id`),
  KEY `w_word` (`w_word`),
  KEY `w_hot` (`w_hot`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_hotword||-_-||

DROP TABLE IF EXISTS `qs_hrtools`;
CREATE TABLE `qs_hrtools` (
  `h_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `h_typeid` smallint(5) unsigned NOT NULL,
  `h_filename` varchar(200) NOT NULL,
  `h_fileurl` varchar(200) NOT NULL,
  `h_order` int(10) NOT NULL DEFAULT '0',
  `h_color` varchar(7) NOT NULL,
  `h_strong` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`h_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_hrtools||-_-||

DROP TABLE IF EXISTS `qs_hrtools_category`;
CREATE TABLE `qs_hrtools_category` (
  `c_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `c_name` varchar(80) NOT NULL,
  `c_order` int(11) NOT NULL DEFAULT '0',
  `c_adminset` tinyint(3) NOT NULL DEFAULT '0',
  `c_img` varchar(200) NOT NULL,
  `c_desc` varchar(200) NOT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_hrtools_category||-_-||

DROP TABLE IF EXISTS `qs_im_message`;
CREATE TABLE `qs_im_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ftid` int(11) unsigned NOT NULL,
  `formuid` int(11) unsigned NOT NULL,
  `touid` int(11) unsigned NOT NULL,
  `message` text NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `mutually` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ftid` (`ftid`),
  KEY `touid` (`touid`),
  KEY `addtime` (`addtime`),
  KEY `formuid` (`formuid`),
  KEY `ftid_touid_formuid_mutually` (`ftid`,`formuid`,`touid`,`mutually`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_im_message||-_-||

DROP TABLE IF EXISTS `qs_im_user`;
CREATE TABLE `qs_im_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formuid` int(11) unsigned NOT NULL,
  `touid` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `message` text,
  `sendtime` int(11) DEFAULT NULL,
  `unread` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `formuid` (`formuid`),
  KEY `touid` (`touid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_im_user||-_-||

DROP TABLE IF EXISTS `qs_jobs`;
CREATE TABLE `qs_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `jobs_name` varchar(50) NOT NULL,
  `companyname` varchar(50) NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `company_addtime` int(10) unsigned NOT NULL,
  `company_audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `emergency` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stick` tinyint(1) NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `nature_cn` varchar(30) NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '3',
  `sex_cn` varchar(3) NOT NULL,
  `age` varchar(10) NOT NULL,
  `amount` smallint(5) unsigned NOT NULL,
  `topclass` smallint(5) unsigned NOT NULL,
  `category` smallint(5) unsigned NOT NULL,
  `subclass` smallint(5) unsigned NOT NULL,
  `category_cn` varchar(100) NOT NULL DEFAULT '',
  `trade` smallint(5) unsigned NOT NULL,
  `trade_cn` varchar(30) NOT NULL,
  `scale` smallint(5) unsigned NOT NULL,
  `scale_cn` varchar(30) NOT NULL,
  `district` varchar(100) NOT NULL DEFAULT '',
  `district_cn` varchar(100) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `tag_cn` varchar(100) NOT NULL,
  `education` smallint(5) unsigned NOT NULL,
  `education_cn` varchar(30) NOT NULL,
  `experience` smallint(5) unsigned NOT NULL,
  `experience_cn` varchar(30) NOT NULL,
  `minwage` int(10) NOT NULL,
  `maxwage` int(10) NOT NULL,
  `negotiable` tinyint(1) unsigned NOT NULL,
  `contents` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `deadline` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `stime` int(10) NOT NULL,
  `setmeal_deadline` int(10) unsigned NOT NULL DEFAULT '0',
  `setmeal_id` smallint(5) unsigned NOT NULL,
  `setmeal_name` varchar(60) NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `robot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tpl` varchar(60) NOT NULL,
  `map_x` decimal(9,6) NOT NULL,
  `map_y` decimal(9,6) NOT NULL,
  `map_zoom` tinyint(3) unsigned NOT NULL,
  `add_mode` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `department` varchar(60) NOT NULL,
  `key_precise` text NOT NULL,
  `key_full` text NOT NULL,
  `famous` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `company_id` (`company_id`),
  KEY `audit_id` (`audit`,`id`),
  KEY `audit_deadline` (`audit`,`deadline`),
  KEY `audit_addtime` (`audit`,`addtime`),
  KEY `audit_refreshtime` (`audit`,`refreshtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_jobs||-_-||

DROP TABLE IF EXISTS `qs_jobs_contact`;
CREATE TABLE `qs_jobs_contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `contact` varchar(80) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `telephone` varchar(80) NOT NULL,
  `landline_tel` varchar(50) NOT NULL,
  `address` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `notify` tinyint(3) unsigned NOT NULL,
  `notify_mobile` tinyint(3) NOT NULL,
  `contact_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `telephone_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `qq_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `landline_tel_show` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_jobs_contact||-_-||

DROP TABLE IF EXISTS `qs_jobs_search`;
CREATE TABLE `qs_jobs_search` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `minwage` int(10) unsigned NOT NULL,
  `maxwage` int(10) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `stime` int(10) NOT NULL,
  `click` int(10) unsigned NOT NULL,
  `map_x` double(9,6) unsigned NOT NULL,
  `map_y` double(9,6) unsigned NOT NULL,
  `jobs_name` varchar(30) NOT NULL,
  `companyname` varchar(50) NOT NULL,
  `key` text NOT NULL,
  `famous` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `refreshtime` (`refreshtime`),
  KEY `stime` (`stime`),
  KEY `addtime_click` (`addtime`,`click`),
  FULLTEXT KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_jobs_search||-_-||

DROP TABLE IF EXISTS `qs_jobs_search_key`;
CREATE TABLE `qs_jobs_search_key` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `minwage` int(10) unsigned NOT NULL,
  `maxwage` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  `map_x` double(9,6) NOT NULL,
  `map_y` double(9,6) NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `stime` int(10) NOT NULL,
  `key` text NOT NULL,
  `famous` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `refreshtime` (`refreshtime`),
  KEY `stime` (`stime`),
  KEY `addtime` (`addtime`),
  FULLTEXT KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_jobs_search_key||-_-||

DROP TABLE IF EXISTS `qs_jobs_tag`;
CREATE TABLE `qs_jobs_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `tag` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_jobs_tag||-_-||

DROP TABLE IF EXISTS `qs_jobs_tmp`;
CREATE TABLE `qs_jobs_tmp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `jobs_name` varchar(50) NOT NULL,
  `companyname` varchar(50) NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `company_addtime` int(10) unsigned NOT NULL,
  `company_audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `emergency` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stick` tinyint(1) NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `nature_cn` varchar(30) NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '3',
  `sex_cn` varchar(3) NOT NULL,
  `age` varchar(10) NOT NULL,
  `amount` smallint(5) unsigned NOT NULL,
  `topclass` smallint(5) unsigned NOT NULL,
  `category` smallint(5) unsigned NOT NULL,
  `subclass` smallint(5) unsigned NOT NULL,
  `category_cn` varchar(100) NOT NULL DEFAULT '',
  `trade` smallint(5) unsigned NOT NULL,
  `trade_cn` varchar(30) NOT NULL,
  `scale` smallint(5) unsigned NOT NULL,
  `scale_cn` varchar(30) NOT NULL,
  `district` varchar(100) NOT NULL DEFAULT '',
  `district_cn` varchar(100) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `tag_cn` varchar(100) NOT NULL,
  `education` smallint(5) unsigned NOT NULL,
  `education_cn` varchar(30) NOT NULL,
  `experience` smallint(5) unsigned NOT NULL,
  `experience_cn` varchar(30) NOT NULL,
  `minwage` int(10) NOT NULL,
  `maxwage` int(10) NOT NULL,
  `negotiable` tinyint(1) unsigned NOT NULL,
  `contents` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `deadline` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `stime` int(10) NOT NULL,
  `setmeal_deadline` int(10) unsigned NOT NULL DEFAULT '0',
  `setmeal_id` smallint(5) unsigned NOT NULL,
  `setmeal_name` varchar(60) NOT NULL,
  `audit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `robot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tpl` varchar(60) NOT NULL,
  `map_x` decimal(9,6) NOT NULL,
  `map_y` decimal(9,6) NOT NULL,
  `map_zoom` tinyint(3) unsigned NOT NULL,
  `add_mode` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `department` varchar(60) NOT NULL,
  `key_precise` text NOT NULL,
  `key_full` text NOT NULL,
  `famous` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `company_id` (`company_id`),
  KEY `audit_id` (`audit`,`id`),
  KEY `audit_deadline` (`audit`,`deadline`),
  KEY `audit_addtime` (`audit`,`addtime`),
  KEY `audit_refreshtime` (`audit`,`refreshtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_jobs_tmp||-_-||

DROP TABLE IF EXISTS `qs_link`;
CREATE TABLE `qs_link` (
  `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `alias` varchar(30) NOT NULL,
  `link_name` varchar(255) NOT NULL,
  `link_url` varchar(255) NOT NULL,
  `link_logo` varchar(255) NOT NULL,
  `show_order` smallint(5) unsigned NOT NULL DEFAULT '50',
  `notes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`link_id`),
  KEY `show_order` (`show_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_link||-_-||

DROP TABLE IF EXISTS `qs_link_category`;
CREATE TABLE `qs_link_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `categoryname` varchar(80) NOT NULL,
  `c_sys` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `c_alias` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_link_category||-_-||

DROP TABLE IF EXISTS `qs_mailconfig`;
CREATE TABLE `qs_mailconfig` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_mailconfig||-_-||

DROP TABLE IF EXISTS `qs_mailqueue`;
CREATE TABLE `qs_mailqueue` (
  `m_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `m_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `m_addtime` int(10) unsigned NOT NULL,
  `m_sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  `m_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `m_mail` varchar(80) NOT NULL,
  `m_subject` varchar(200) NOT NULL,
  `m_body` text NOT NULL,
  PRIMARY KEY (`m_id`),
  KEY `m_uid` (`m_uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_mailqueue||-_-||

DROP TABLE IF EXISTS `qs_mail_templates`;
CREATE TABLE `qs_mail_templates` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` text NOT NULL,
  `value` text NOT NULL,
  `variate` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_mail_templates||-_-||

DROP TABLE IF EXISTS `qs_members`;
CREATE TABLE `qs_members` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `utype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `username` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(80) NOT NULL,
  `email_audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(11) NOT NULL,
  `mobile_audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `password` varchar(100) NOT NULL DEFAULT '',
  `pwd_hash` varchar(30) NOT NULL,
  `reg_time` int(10) unsigned NOT NULL,
  `reg_ip` varchar(30) NOT NULL,
  `reg_address` varchar(30) NOT NULL,
  `last_login_time` int(10) unsigned NOT NULL,
  `last_login_ip` varchar(30) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `avatars` varchar(255) NOT NULL,
  `robot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `consultant` smallint(5) unsigned NOT NULL,
  `remind_email_time` int(10) unsigned DEFAULT NULL,
  `imei` varchar(50) NOT NULL DEFAULT '',
  `sms_num` int(10) NOT NULL DEFAULT '0',
  `reg_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `remind_email_ex_time` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `invitation_code` varchar(8) NOT NULL,
  `reg_source` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `reg_source_cn` varchar(30) NOT NULL,
  `secretkey` varchar(100) NOT NULL,
  `sitegroup_uid` INT(10) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members||-_-||

DROP TABLE IF EXISTS `qs_members_appeal`;
CREATE TABLE `qs_members_appeal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realname` varchar(30) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members_appeal||-_-||

DROP TABLE IF EXISTS `qs_members_bind`;
CREATE TABLE `qs_members_bind` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `type` varchar(60) NOT NULL,
  `keyid` varchar(100) NOT NULL,
  `info` text NOT NULL,
  `bindingtime` int(10) unsigned NOT NULL,
  `focustime` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `unionid` varchar(100) NOT NULL,
  `is_bind` tinyint(1) unsigned NOT NULL,
  `is_focus` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid_type` (`uid`,`type`),
  KEY `type_keyid` (`type`,`keyid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members_bind||-_-||

DROP TABLE IF EXISTS `qs_members_charge_log`;
CREATE TABLE `qs_members_charge_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_uid` int(10) NOT NULL,
  `log_username` varchar(60) NOT NULL,
  `log_addtime` int(10) NOT NULL,
  `log_value` varchar(255) NOT NULL,
  `log_amount` decimal(10,2) NOT NULL,
  `log_ismoney` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `log_type` tinyint(1) unsigned NOT NULL,
  `log_mode` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `log_utype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`log_id`),
  KEY `log_username` (`log_username`),
  KEY `log_addtime` (`log_addtime`),
  KEY `type_addtime` (`log_type`,`log_addtime`),
  KEY `uid_addtime` (`log_uid`,`log_addtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members_charge_log||-_-||

DROP TABLE IF EXISTS `qs_members_handsel`;
CREATE TABLE `qs_members_handsel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `htype` varchar(60) NOT NULL,
  `htype_cn` varchar(30) NOT NULL,
  `operate` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `points` int(10) unsigned NOT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`htype`,`addtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members_handsel||-_-||

DROP TABLE IF EXISTS `qs_members_log`;
CREATE TABLE `qs_members_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_uid` int(10) NOT NULL,
  `log_username` varchar(60) NOT NULL,
  `log_addtime` int(10) NOT NULL,
  `log_value` varchar(255) NOT NULL,
  `log_ip` varchar(30) NOT NULL,
  `log_address` varchar(50) NOT NULL,
  `log_utype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `log_type` varchar(30) NOT NULL DEFAULT '',
  `log_source` varchar(20) NOT NULL,
  `jobs_id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `operater_id` int(10) unsigned NOT NULL,
  `operater` varchar(30) NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_username` (`log_username`),
  KEY `log_addtime` (`log_addtime`),
  KEY `type_addtime` (`log_type`,`log_addtime`),
  KEY `utype_addtime` (`log_utype`,`log_addtime`),
  KEY `uid_addtime` (`log_uid`,`log_addtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members_log||-_-||

DROP TABLE IF EXISTS `qs_members_msgtip`;
CREATE TABLE `qs_members_msgtip` (
  `uid` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1:系统消息,2：业务流程',
  `update_time` int(10) unsigned NOT NULL,
  `unread` int(10) NOT NULL,
  `num` int(10) NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members_msgtip||-_-||

DROP TABLE IF EXISTS `qs_members_perfected_allowance`;
CREATE TABLE `qs_members_perfected_allowance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `percent` int(10) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `reason` varchar(100) NOT NULL,
  `nobind` tinyint(1) unsigned NOT NULL,
  `notice` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_members_perfected_allowance||-_-||

DROP TABLE IF EXISTS `qs_members_points`;
CREATE TABLE `qs_members_points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members_points||-_-||

DROP TABLE IF EXISTS `qs_members_setmeal`;
CREATE TABLE `qs_members_setmeal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `expire` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL,
  `setmeal_id` int(10) unsigned NOT NULL,
  `setmeal_name` varchar(200) NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `expense` int(10) unsigned NOT NULL,
  `jobs_meanwhile` int(10) unsigned NOT NULL,
  `refresh_jobs_free` int(10) unsigned NOT NULL,
  `download_resume` int(10) unsigned NOT NULL,
  `download_resume_max` int(10) unsigned NOT NULL,
  `added` varchar(250) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `setmeal_img` varchar(50) NOT NULL,
  `show_apply_contact` tinyint(1) unsigned NOT NULL,
  `is_free` tinyint(1) unsigned NOT NULL,
  `discount_download_resume` double(2,1) unsigned NOT NULL,
  `discount_sms` double(2,1) unsigned NOT NULL,
  `discount_stick` double(2,1) unsigned NOT NULL,
  `discount_emergency` double(2,1) unsigned NOT NULL,
  `discount_tpl` double(2,1) unsigned NOT NULL,
  `discount_auto_refresh_jobs` double(2,1) unsigned NOT NULL,
  `show_contact_direct` tinyint(1) unsigned DEFAULT 0 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `effective_setmealid` (`expire`,`setmeal_id`),
  KEY `effective_endtime` (`expire`,`endtime`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members_setmeal||-_-||

DROP TABLE IF EXISTS `qs_members_setmeal_log`;
CREATE TABLE `qs_members_setmeal_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_uid` int(10) NOT NULL,
  `log_username` varchar(60) NOT NULL,
  `log_addtime` int(10) NOT NULL,
  `log_value` varchar(255) NOT NULL,
  `log_ip` varchar(20) NOT NULL,
  `log_address` varchar(50) NOT NULL,
  `log_utype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `log_leave` smallint(5) unsigned NOT NULL DEFAULT '1',
  `log_source` varchar(20) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_username` (`log_username`),
  KEY `log_addtime` (`log_addtime`),
  KEY `utype_addtime` (`log_utype`,`log_addtime`),
  KEY `uid_addtime` (`log_uid`,`log_addtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_members_setmeal_log||-_-||

DROP TABLE IF EXISTS `qs_menu`;
CREATE TABLE `qs_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `name` varchar(20) NOT NULL COMMENT '菜单各称',
  `pid` int(10) unsigned NOT NULL COMMENT '菜单父索引ID',
  `spid` varchar(50) NOT NULL,
  `log_cn` varchar(30) NOT NULL,
  `module_name` varchar(30) NOT NULL COMMENT '模块',
  `controller_name` varchar(30) NOT NULL COMMENT '控制器',
  `action_name` varchar(30) NOT NULL COMMENT '方法',
  `menu_type` tinyint(1) NOT NULL COMMENT '类型(0:按钮或功能1:导航)',
  `is_parent` tinyint(1) unsigned NOT NULL COMMENT '是否有子菜单列表',
  `data` varchar(100) NOT NULL COMMENT '附加参数',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `often` tinyint(1) unsigned NOT NULL,
  `ordid` int(10) unsigned NOT NULL COMMENT '排序',
  `display` tinyint(1) unsigned NOT NULL COMMENT '是否激活',
  `stat` varchar(20) NOT NULL,
  `sys_set` tinyint(1) unsigned NOT NULL COMMENT '是否系统设置',
  `mods` tinyint(1) NOT NULL,
  `img` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_menu||-_-||

DROP TABLE IF EXISTS `qs_msg`;
CREATE TABLE `qs_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `spid` tinyint(1) NOT NULL,
  `fromuid` int(11) NOT NULL,
  `touid` int(11) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `addtime` int(10) NOT NULL,
  `mutually` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_msg||-_-||

DROP TABLE IF EXISTS `qs_navigation`;
CREATE TABLE `qs_navigation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) NOT NULL,
  `urltype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `color` varchar(30) NOT NULL,
  `pagealias` varchar(100) NOT NULL,
  `list_id` varchar(30) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL,
  `target` varchar(100) NOT NULL,
  `navigationorder` int(10) unsigned NOT NULL DEFAULT '0',
  `is_personal` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_navigation||-_-||

DROP TABLE IF EXISTS `qs_navigation_category`;
CREATE TABLE `qs_navigation_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) NOT NULL,
  `categoryname` varchar(30) NOT NULL,
  `admin_set` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_navigation_category||-_-||

DROP TABLE IF EXISTS `qs_notice`;
CREATE TABLE `qs_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` smallint(5) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `tit_color` varchar(10) DEFAULT NULL,
  `tit_b` tinyint(1) NOT NULL DEFAULT '0',
  `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_url` varchar(200) NOT NULL DEFAULT '0',
  `seo_keywords` varchar(100) DEFAULT NULL,
  `seo_description` varchar(200) DEFAULT NULL,
  `click` int(11) NOT NULL DEFAULT '1',
  `addtime` int(10) NOT NULL,
  `sort` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`,`sort`,`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_notice||-_-||

DROP TABLE IF EXISTS `qs_notice_category`;
CREATE TABLE `qs_notice_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `categoryname` varchar(80) NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `admin_set` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_notice_category||-_-||

DROP TABLE IF EXISTS `qs_oauth`;
CREATE TABLE `qs_oauth` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `alias` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `info` varchar(255) NOT NULL,
  `config` varchar(255) NOT NULL,
  `apply` varchar(255) NOT NULL,
  `create_time` int(10) NOT NULL,
  `ordid` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_oauth||-_-||

DROP TABLE IF EXISTS `qs_order`;
CREATE TABLE `qs_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oid` varchar(200) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `utype` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `order_type` int(10) unsigned NOT NULL COMMENT '订单类型，详见OrderModel',
  `pay_type` tinyint(1) unsigned NOT NULL COMMENT '支付类型：1积分 2现金 3现金+积分',
  `is_paid` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1待支付 2已支付',
  `amount` decimal(10,2) NOT NULL COMMENT '总金额',
  `pay_amount` decimal(10,2) NOT NULL COMMENT '现金支付金额',
  `pay_points` int(10) unsigned NOT NULL COMMENT '积分支付数',
  `payment` varchar(20) NOT NULL COMMENT '支付方式英文',
  `payment_cn` varchar(20) NOT NULL COMMENT '支付方式中文',
  `description` varchar(150) NOT NULL COMMENT '订单详情描述',
  `service_name` varchar(30) NOT NULL COMMENT '所购买服务名称',
  `points` int(10) unsigned NOT NULL COMMENT '购买积分数',
  `setmeal` int(10) unsigned NOT NULL COMMENT '购买套餐/增值服务id',
  `params` text NOT NULL COMMENT '需要特殊处理的参数序列化',
  `notes` varchar(150) NOT NULL COMMENT '备注',
  `addtime` int(11) unsigned NOT NULL,
  `payment_time` int(10) unsigned NOT NULL,
  `discount` varchar(200) NOT NULL COMMENT '优惠',
  `fee` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`),
  KEY `payment_name` (`payment`),
  KEY `oid` (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_order||-_-||

DROP TABLE IF EXISTS `qs_order_invoice`;
CREATE TABLE `qs_order_invoice` (
  `order_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `title` tinyint(1) unsigned NOT NULL,
  `cid` tinyint(1) unsigned NOT NULL,
  `organization` varchar(30) NOT NULL,
  `addressee` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `address` varchar(100) NOT NULL,
  `postcode` varchar(6) NOT NULL,
  `audit` tinyint(1) NOT NULL,
  `addtime` int(10) NOT NULL,
  `tax_number` varchar(30) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_order_invoice||-_-||

DROP TABLE IF EXISTS `qs_order_invoice_category`;
CREATE TABLE `qs_order_invoice_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `categoryname` varchar(30) NOT NULL,
  `category_order` int(10) NOT NULL,
  `admin_set` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_order_invoice_category||-_-||

DROP TABLE IF EXISTS `qs_page`;
CREATE TABLE `qs_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `systemclass` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pagetpye` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `alias` varchar(60) NOT NULL,
  `pname` varchar(12) NOT NULL,
  `module` varchar(100) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `rewrite` varchar(200) NOT NULL,
  `url` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `caching` int(10) unsigned NOT NULL DEFAULT '0',
  `tag` varchar(60) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `variate` varchar(1000) NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_page||-_-||

DROP TABLE IF EXISTS `qs_payment`;
CREATE TABLE `qs_payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `listorder` int(10) unsigned NOT NULL DEFAULT '50',
  `typename` varchar(15) NOT NULL,
  `byname` varchar(50) NOT NULL,
  `p_introduction` varchar(100) NOT NULL,
  `notes` text,
  `partnerid` varchar(80) DEFAULT NULL,
  `ytauthkey` varchar(100) DEFAULT NULL,
  `fee` varchar(6) NOT NULL DEFAULT '0',
  `parameter1` varchar(50) DEFAULT NULL,
  `parameter2` varchar(50) DEFAULT NULL,
  `parameter3` varchar(50) DEFAULT NULL,
  `p_install` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_payment||-_-||

DROP TABLE IF EXISTS `qs_personal_favorites`;
CREATE TABLE `qs_personal_favorites` (
  `did` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `personal_uid` int(10) unsigned NOT NULL,
  `jobs_id` int(10) unsigned NOT NULL,
  `jobs_name` varchar(100) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`did`),
  KEY `personal_uid` (`personal_uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_favorites||-_-||

DROP TABLE IF EXISTS `qs_personal_focus_company`;
CREATE TABLE `qs_personal_focus_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_2` (`uid`,`company_id`),
  KEY `uid` (`uid`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_focus_company||-_-||

DROP TABLE IF EXISTS `qs_personal_jobs_apply`;
CREATE TABLE `qs_personal_jobs_apply` (
  `did` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` int(10) unsigned NOT NULL,
  `resume_name` varchar(60) NOT NULL,
  `personal_uid` int(10) unsigned NOT NULL,
  `jobs_id` int(10) unsigned NOT NULL,
  `jobs_name` varchar(60) NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `company_name` varchar(60) NOT NULL,
  `company_uid` int(10) unsigned NOT NULL,
  `apply_addtime` int(10) unsigned NOT NULL,
  `personal_look` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `notes` varchar(200) NOT NULL,
  `is_reply` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_apply` tinyint(1) NOT NULL DEFAULT '0',
  `reply_time` int(11) NOT NULL,
  `admin_username` varchar(30) DEFAULT '' NOT NULL,
  PRIMARY KEY (`did`),
  KEY `personal_uid_id` (`personal_uid`,`resume_id`),
  KEY `company_uid_jobid` (`company_uid`,`jobs_id`),
  KEY `company_uid_look` (`company_uid`,`personal_look`),
  KEY `personal_uid_addtime` (`personal_uid`,`apply_addtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_jobs_apply||-_-||

DROP TABLE IF EXISTS `qs_personal_jobs_subscribe`;
CREATE TABLE `qs_personal_jobs_subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `key` varchar(100) NOT NULL,
  `trade` int(11) NOT NULL,
  `trade_cn` varchar(255) NOT NULL,
  `intention_jobs_id` varchar(50) NOT NULL,
  `intention_jobs` varchar(255) NOT NULL,
  `district` varchar(50) NOT NULL,
  `district_cn` varchar(255) NOT NULL,
  `wage` int(11) NOT NULL,
  `wage_cn` varchar(255) NOT NULL,
  `email` varchar(30) NOT NULL,
  `addtime` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_jobs_subscribe||-_-||

DROP TABLE IF EXISTS `qs_personal_service_stick`;
CREATE TABLE `qs_personal_service_stick` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `days` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_service_stick||-_-||

DROP TABLE IF EXISTS `qs_personal_service_stick_log`;
CREATE TABLE `qs_personal_service_stick_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` int(10) unsigned NOT NULL,
  `resume_uid` int(10) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_service_stick_log||-_-||

DROP TABLE IF EXISTS `qs_personal_service_tag`;
CREATE TABLE `qs_personal_service_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `days` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_service_tag||-_-||

DROP TABLE IF EXISTS `qs_personal_service_tag_category`;
CREATE TABLE `qs_personal_service_tag_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_service_tag_category||-_-||

DROP TABLE IF EXISTS `qs_personal_service_tag_log`;
CREATE TABLE `qs_personal_service_tag_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` int(10) unsigned NOT NULL,
  `resume_uid` int(10) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_service_tag_log||-_-||

DROP TABLE IF EXISTS `qs_personal_shield_company`;
CREATE TABLE `qs_personal_shield_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `comkeyword` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_personal_shield_company||-_-||

DROP TABLE IF EXISTS `qs_pms`;
CREATE TABLE `qs_pms` (
  `pmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `msgtype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `msgfrom` varchar(30) NOT NULL,
  `msgfromuid` int(10) unsigned NOT NULL,
  `msgtouid` int(10) unsigned NOT NULL,
  `msgtoname` varchar(30) NOT NULL,
  `message` varchar(250) NOT NULL,
  `dateline` int(10) NOT NULL,
  `new` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `reply` varchar(255) NOT NULL,
  `replytime` int(10) NOT NULL,
  `replyuid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `msgfromuid` (`msgfromuid`),
  KEY `msgtouid` (`msgtouid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_pms||-_-||

DROP TABLE IF EXISTS `qs_pms_sys`;
CREATE TABLE `qs_pms_sys` (
  `spmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spms_usertype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `spms_type` tinyint(1) NOT NULL DEFAULT '1',
  `message` varchar(250) NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`spmid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_pms_sys||-_-||

DROP TABLE IF EXISTS `qs_promotion`;
CREATE TABLE `qs_promotion` (
  `cp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cp_uid` int(10) unsigned NOT NULL,
  `cp_ptype` varchar(10) NOT NULL,
  `cp_jobid` int(10) unsigned NOT NULL,
  `cp_days` int(10) unsigned NOT NULL,
  `cp_starttime` int(10) unsigned NOT NULL,
  `cp_endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cp_id`),
  KEY `cp_endtime` (`cp_endtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_promotion||-_-||

DROP TABLE IF EXISTS `qs_queue_auto_refresh`;
CREATE TABLE `qs_queue_auto_refresh` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_queue_auto_refresh||-_-||

DROP TABLE IF EXISTS `qs_refresh_log`;
CREATE TABLE `qs_refresh_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `mode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_refresh_log||-_-||

DROP TABLE IF EXISTS `qs_report`;
CREATE TABLE `qs_report` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `jobs_id` int(10) unsigned NOT NULL,
  `jobs_name` varchar(150) NOT NULL,
  `jobs_addtime` int(10) unsigned NOT NULL,
  `report_type` int(10) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `audit` int(10) NOT NULL DEFAULT '1',
  `content` varchar(250) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_report||-_-||

DROP TABLE IF EXISTS `qs_report_resume`;
CREATE TABLE `qs_report_resume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  `resume_realname` varchar(30) NOT NULL DEFAULT '',
  `resume_addtime` int(10) unsigned NOT NULL,
  `report_type` int(10) NOT NULL,
  `audit` int(10) NOT NULL DEFAULT '1',
  `content` varchar(250) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_report_resume||-_-||

DROP TABLE IF EXISTS `qs_resume`;
CREATE TABLE `qs_resume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `display_name` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `audit` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `stick` tinyint(1) unsigned NOT NULL,
  `strong_tag` int(10) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `fullname` varchar(15) NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL,
  `sex_cn` varchar(3) NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `nature_cn` varchar(30) NOT NULL,
  `trade` varchar(60) NOT NULL,
  `trade_cn` varchar(100) NOT NULL,
  `birthdate` smallint(4) unsigned NOT NULL,
  `residence` varchar(30) NOT NULL DEFAULT '',
  `height` varchar(5) NOT NULL,
  `marriage` tinyint(3) unsigned NOT NULL,
  `marriage_cn` varchar(5) NOT NULL,
  `experience` smallint(5) NOT NULL,
  `experience_cn` varchar(30) NOT NULL,
  `district` varchar(100) NOT NULL,
  `district_cn` varchar(255) NOT NULL DEFAULT '',
  `wage` smallint(5) unsigned NOT NULL,
  `wage_cn` varchar(30) NOT NULL,
  `householdaddress` varchar(30) NOT NULL DEFAULT '',
  `education` smallint(5) unsigned NOT NULL,
  `education_cn` varchar(30) NOT NULL,
  `major` smallint(5) NOT NULL,
  `major_cn` varchar(50) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `tag_cn` varchar(100) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `email_notify` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `intention_jobs_id` varchar(100) NOT NULL,
  `intention_jobs` varchar(255) NOT NULL,
  `specialty` varchar(1000) NOT NULL,
  `photo` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `photo_img` varchar(255) NOT NULL,
  `photo_audit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `photo_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `qq` varchar(30) NOT NULL,
  `weixin` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `stime` int(10) NOT NULL,
  `entrust` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `talent` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL,
  `complete_percent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `current` smallint(5) unsigned NOT NULL,
  `current_cn` varchar(50) NOT NULL DEFAULT '',
  `word_resume` varchar(255) NOT NULL,
  `word_resume_title` varchar(255) NOT NULL,
  `word_resume_addtime` int(10) unsigned NOT NULL,
  `key_full` text NOT NULL,
  `key_precise` text NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `tpl` varchar(60) NOT NULL,
  `resume_from_pc` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `def` tinyint(1) NOT NULL,
  `mobile_audit` tinyint(1) NOT NULL,
  `comment_content` varchar(255) NOT NULL,
  `is_quick` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `idcard` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `refreshtime` (`refreshtime`),
  KEY `addtime` (`addtime`),
  KEY `audit_addtime` (`audit`, `addtime`),
  KEY `audit_display` (`audit`, `display`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume||-_-||

DROP TABLE IF EXISTS `qs_resume_credent`;
CREATE TABLE `qs_resume_credent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `images` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_credent||-_-||

DROP TABLE IF EXISTS `qs_resume_education`;
CREATE TABLE `qs_resume_education` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `startyear` smallint(4) unsigned NOT NULL,
  `startmonth` smallint(2) unsigned NOT NULL,
  `endyear` smallint(4) unsigned NOT NULL,
  `endmonth` smallint(2) unsigned NOT NULL,
  `school` varchar(50) NOT NULL,
  `speciality` varchar(50) NOT NULL,
  `education` smallint(5) unsigned NOT NULL,
  `education_cn` varchar(30) NOT NULL DEFAULT '',
  `todate` int(10) unsigned NOT NULL,
  `campus_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_education||-_-||

DROP TABLE IF EXISTS `qs_resume_entrust`;
CREATE TABLE `qs_resume_entrust` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `fullname` varchar(15) NOT NULL,
  `entrust` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `entrust_start` int(10) unsigned NOT NULL,
  `entrust_end` int(10) unsigned NOT NULL,
  `isshield` tinyint(1) unsigned NOT NULL,
  `resume_addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_entrust||-_-||

DROP TABLE IF EXISTS `qs_resume_img`;
CREATE TABLE `qs_resume_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `resume_id` int(10) unsigned NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL,
  `audit` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `resume_id` (`resume_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_img||-_-||

DROP TABLE IF EXISTS `qs_resume_language`;
CREATE TABLE `qs_resume_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `language` smallint(5) NOT NULL,
  `language_cn` varchar(50) NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `level_cn` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_language||-_-||

DROP TABLE IF EXISTS `qs_resume_search_full`;
CREATE TABLE `qs_resume_search_full` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `key` text NOT NULL,
  `stime` int(10) NOT NULL,
  `refreshtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stime` (`stime`),
  KEY `refrehtime` (`refreshtime`),
  KEY `uid` (`uid`) USING BTREE,
  FULLTEXT KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_search_full||-_-||

DROP TABLE IF EXISTS `qs_resume_search_precise`;
CREATE TABLE `qs_resume_search_precise` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `key` text NOT NULL,
  `stime` int(10) NOT NULL,
  `refreshtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stime` (`stime`),
  KEY `refrehtime` (`refreshtime`),
  KEY `uid` (`uid`) USING BTREE,
  FULLTEXT KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_search_precise||-_-||

DROP TABLE IF EXISTS `qs_resume_tpl`;
CREATE TABLE `qs_resume_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `tplid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_tpl||-_-||

DROP TABLE IF EXISTS `qs_resume_training`;
CREATE TABLE `qs_resume_training` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `startyear` smallint(4) unsigned NOT NULL,
  `startmonth` smallint(2) unsigned NOT NULL,
  `endyear` smallint(4) unsigned NOT NULL,
  `endmonth` smallint(2) unsigned NOT NULL,
  `agency` varchar(50) NOT NULL,
  `course` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `todate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_training||-_-||

DROP TABLE IF EXISTS `qs_resume_work`;
CREATE TABLE `qs_resume_work` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `startyear` smallint(4) unsigned NOT NULL,
  `startmonth` smallint(2) unsigned NOT NULL,
  `endyear` smallint(4) unsigned NOT NULL,
  `endmonth` smallint(2) unsigned NOT NULL,
  `companyname` varchar(50) NOT NULL,
  `jobs` varchar(30) NOT NULL,
  `achievements` varchar(1000) NOT NULL,
  `todate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_resume_work||-_-||

DROP TABLE IF EXISTS `qs_rongyun_token`;
CREATE TABLE `qs_rongyun_token` (
  `uid` int(10) unsigned NOT NULL,
  `token` varchar(100) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_rongyun_token||-_-||

DROP TABLE IF EXISTS `qs_setmeal`;
CREATE TABLE `qs_setmeal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `apply` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `setmeal_name` varchar(200) NOT NULL,
  `days` int(10) unsigned NOT NULL DEFAULT '0',
  `expense` int(10) unsigned NOT NULL,
  `jobs_meanwhile` int(10) unsigned NOT NULL DEFAULT '0',
  `refresh_jobs_free` int(10) unsigned NOT NULL,
  `download_resume` int(10) unsigned NOT NULL DEFAULT '0',
  `download_resume_max` int(10) unsigned NOT NULL DEFAULT '0',
  `added` varchar(255) NOT NULL,
  `show_order` int(10) unsigned NOT NULL DEFAULT '0',
  `set_sms` int(10) unsigned NOT NULL,
  `set_points` int(10) unsigned NOT NULL,
  `setmeal_img` varchar(200) NOT NULL,
  `show_apply_contact` tinyint(1) unsigned NOT NULL,
  `is_free` tinyint(1) unsigned NOT NULL,
  `discount_download_resume` double(2,1) unsigned NOT NULL,
  `discount_sms` double(2,1) unsigned NOT NULL,
  `discount_stick` double(2,1) unsigned NOT NULL,
  `discount_emergency` double(2,1) unsigned NOT NULL,
  `discount_tpl` double(2,1) unsigned NOT NULL,
  `discount_auto_refresh_jobs` double(2,1) unsigned NOT NULL,
  `show_contact_direct` tinyint(1) unsigned DEFAULT 0 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_setmeal||-_-||

DROP TABLE IF EXISTS `qs_setmeal_increment`;
CREATE TABLE `qs_setmeal_increment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `value` int(10) unsigned NOT NULL,
  `price` varchar(10) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_setmeal_increment||-_-||

DROP TABLE IF EXISTS `qs_sms`;
CREATE TABLE `qs_sms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `config` text NOT NULL,
  `alias` varchar(20) NOT NULL,
  `replace` varchar(255) NOT NULL,
  `filing` tinyint(1) NOT NULL,
  `remark` text NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `ordid` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`create_time`,`update_time`,`ordid`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_sms||-_-||

DROP TABLE IF EXISTS `qs_smsqueue`;
CREATE TABLE `qs_smsqueue` (
  `s_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `s_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `s_addtime` int(10) unsigned NOT NULL,
  `s_sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  `s_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `s_mobile` text,
  `s_body` varchar(100) NOT NULL,
  `s_tplid` varchar(30) NOT NULL,
  PRIMARY KEY (`s_id`),
  KEY `s_uid` (`s_uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_smsqueue||-_-||

DROP TABLE IF EXISTS `qs_sms_config`;
CREATE TABLE `qs_sms_config` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` varchar(200) NOT NULL,
  `module` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_sms_config||-_-||

DROP TABLE IF EXISTS `qs_sms_oauth`;
CREATE TABLE `qs_sms_oauth` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL,
  `tid` int(11) NOT NULL,
  `tpl_id` varchar(30) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `value` text NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `ordid` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_sms_oauth||-_-||

DROP TABLE IF EXISTS `qs_sms_templates`;
CREATE TABLE `qs_sms_templates` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(30) NOT NULL,
  `tpl_id` varchar(50) NOT NULL,
  `params` text NOT NULL,
  `module` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_sms_templates||-_-||

DROP TABLE IF EXISTS `qs_syslog`;
CREATE TABLE `qs_syslog` (
  `l_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `l_type` tinyint(1) unsigned NOT NULL,
  `l_type_name` varchar(30) NOT NULL,
  `l_time` int(10) unsigned NOT NULL,
  `l_ip` varchar(20) NOT NULL,
  `l_address` varchar(50) NOT NULL,
  `l_page` text NOT NULL,
  `l_str` text NOT NULL,
  PRIMARY KEY (`l_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_syslog||-_-||

DROP TABLE IF EXISTS `qs_sys_email_log`;
CREATE TABLE `qs_sys_email_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `send_from` varchar(50) NOT NULL,
  `send_to` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `state` smallint(3) NOT NULL,
  `sendtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_sys_email_log||-_-||

DROP TABLE IF EXISTS `qs_task`;
CREATE TABLE `qs_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `t_alias` varchar(30) NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `once` tinyint(1) unsigned NOT NULL,
  `becount` tinyint(1) unsigned NOT NULL,
  `times` tinyint(3) NOT NULL,
  `utype` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `dayly` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned DEFAULT 1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_task||-_-||

DROP TABLE IF EXISTS `qs_task_log`;
CREATE TABLE `qs_task_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `taskid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `once` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid_taskid_addtime` (`uid`,`taskid`,`addtime`),
  KEY `uid_taskid` (`uid`,`taskid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_task_log||-_-||

DROP TABLE IF EXISTS `qs_text`;
CREATE TABLE `qs_text` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_text||-_-||

DROP TABLE IF EXISTS `qs_tpl`;
CREATE TABLE `qs_tpl` (
  `tpl_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_type` tinyint(1) NOT NULL,
  `tpl_name` varchar(80) NOT NULL,
  `tpl_display` tinyint(1) NOT NULL DEFAULT '1',
  `tpl_dir` varchar(80) NOT NULL,
  `tpl_val` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_tpl||-_-||


DROP TABLE IF EXISTS `qs_unbind_mobile`;
CREATE TABLE `qs_unbind_mobile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `utype` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '用户类型',
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '会员用户名',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '解绑的手机号',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '解绑时间',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_unbind_mobile||-_-||

DROP TABLE IF EXISTS `qs_view_jobs`;
CREATE TABLE `qs_view_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `jobs_uid` int(10) NOT NULL,
  `jobsid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_view_jobs||-_-||

DROP TABLE IF EXISTS `qs_view_resume`;
CREATE TABLE `qs_view_resume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `resumeid` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
||-_-||qs_view_resume||-_-||

DROP TABLE IF EXISTS `qs_weixin_msg_queue_list`;
CREATE TABLE `qs_weixin_msg_queue_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `utype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `username` varchar(60) NOT NULL DEFAULT '',
  `openid` varchar(50) DEFAULT NULL,
  `content` text NOT NULL,
  `sendtime` int(10) unsigned NOT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
||-_-||qs_weixin_msg_queue_list||-_-||