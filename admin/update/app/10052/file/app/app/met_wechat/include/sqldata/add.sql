CREATE TABLE IF NOT EXISTS `[TABLEPRE]lc_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `value` varchar(20000) DEFAULT '',
  `m_num` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[TABLEPRE]nwechat_keywords` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `replyid` varchar(255) DEFAULT NULL,
  `word` varchar(255) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `is_own` int(11) NOT NULL DEFAULT '0',
  `m_name` varchar(255) DEFAULT NULL,
  `m_class` varchar(255) DEFAULT NULL,
  `m_action` varchar(255) DEFAULT NULL,
  `own_url` varchar(255) DEFAULT NULL,
  `m_num` varchar(255) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `replyid` (`replyid`),
  KEY `m_name` (`m_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[TABLEPRE]nwechat_log_points` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[TABLEPRE]nwechat_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(255) DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `order_no` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[TABLEPRE]nwechat_msg_tmp` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `m_name` varchar(255) DEFAULT NULL,
  `m_class` varchar(255) DEFAULT NULL,
  `m_action` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[TABLEPRE]nwechat_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `isshow` int(11) NOT NULL DEFAULT '0',
  `content` text,
  `description` text,
  `url` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `all_read` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[TABLEPRE]nwechat_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `text` text,
  `url` text,
  `description` text,
  `msg_list` varchar(50) DEFAULT NULL,
  `columns` varchar(50) DEFAULT NULL,
  `isown` int(11) NOT NULL DEFAULT '0',
  `mediaid` text,
  `musicurl` text,
  `hqmusicurl` text,
  `level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[TABLEPRE]nwechat_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `met_uid` int(11) DEFAULT NULL,
  `subscribe` varchar(11) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `subscribe_time` int(11) DEFAULT NULL,
  `unionid` varchar(100) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `other` text,
  `location` varchar(255) DEFAULT '',
  `points` int(11) NOT NULL DEFAULT '0',
  `usetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`),
  KEY `met_uid` (`met_uid`),
  KEY `usetime` (`usetime`),
  KEY `groupid` (`groupid`),
  KEY `subscribe` (`subscribe`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[TABLEPRE]nwechat_msg_tmp` (`id`, `name`, `m_name`, `m_class`, `m_action`)
VALUES
  (1, '默认模板', 'met_wechat', 'met_wechat', 'domsg_tmp');

INSERT INTO `[TABLEPRE]nwechat_reply` (`id`, `name`, `type`, `text`, `url`, `description`, `msg_list`, `columns`, `isown`, `mediaid`, `musicurl`, `hqmusicurl`, `level`)
VALUES
  (1, '关注自动回复', 'text', 'ok', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0),
  (2, '未匹配关键词默认回复', 'text', 'ok', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0);