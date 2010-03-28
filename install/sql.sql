-- 
-- SQL-дамп базы данных ядра LiveStreet версии 0.4
-- 

-- 
-- Структура таблицы `prefix_blog`
-- 

CREATE TABLE `prefix_blog` (
  `blog_id` int(11) unsigned NOT NULL auto_increment,
  `user_owner_id` int(11) unsigned NOT NULL,
  `blog_title` varchar(200) NOT NULL,
  `blog_description` text NOT NULL,
  `blog_type` enum('personal','open','invite','close') default 'personal',
  `blog_date_add` datetime NOT NULL,
  `blog_date_edit` datetime default NULL,
  `blog_rating` float(9,3) NOT NULL default '0.000',
  `blog_count_vote` int(11) unsigned NOT NULL default '0',
  `blog_count_user` int(11) unsigned NOT NULL default '0',
  `blog_limit_rating_topic` float(9,3) NOT NULL default '0.000',
  `blog_url` varchar(200) default NULL,
  `blog_avatar` tinyint(1) unsigned NOT NULL default '0',
  `blog_avatar_type` varchar(5) default NULL,
  PRIMARY KEY  (`blog_id`),
  KEY `user_owner_id` (`user_owner_id`),
  KEY `blog_type` (`blog_type`),
  KEY `blog_url` (`blog_url`),
  KEY `blog_title` (`blog_title`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Дамп данных таблицы `prefix_blog`
-- 

INSERT INTO `prefix_blog` VALUES (1, 1, 'Blog by admin', 'This is your personal blog.', 'personal', '2009-05-10 00:00:00', NULL, 0.000, 0, 0, -1000.000, NULL, 0, NULL);

-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_blog_user`
-- 

CREATE TABLE `prefix_blog_user` (
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `is_moderator` tinyint(1) unsigned NOT NULL default '0',
  `is_administrator` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `blog_id_user_id_uniq` (`blog_id`,`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_blog_user`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_blog_vote`
-- 

CREATE TABLE `prefix_blog_vote` (
  `blog_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_delta` float(9,3) NOT NULL default '0.000',
  UNIQUE KEY `blog_id_user_voter_id_uniq` (`blog_id`,`user_voter_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_blog_vote`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_city`
-- 

CREATE TABLE `prefix_city` (
  `city_id` int(11) unsigned NOT NULL auto_increment,
  `city_name` varchar(30) NOT NULL,
  PRIMARY KEY  (`city_id`),
  KEY `city_name` (`city_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `prefix_city`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_city_user`
-- 

CREATE TABLE `prefix_city_user` (
  `city_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  KEY `city_id` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_city_user`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_country`
-- 

CREATE TABLE `prefix_country` (
  `country_id` int(11) unsigned NOT NULL auto_increment,
  `country_name` varchar(30) NOT NULL,
  PRIMARY KEY  (`country_id`),
  KEY `country_name` (`country_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `prefix_country`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_country_user`
-- 

CREATE TABLE `prefix_country_user` (
  `country_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_country_user`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_favourite_topic`
-- 

CREATE TABLE `prefix_favourite_topic` (
  `user_id` int(11) unsigned NOT NULL,
  `topic_id` int(11) unsigned NOT NULL,
  `topic_publish` tinyint(1) NOT NULL default '1',
  UNIQUE KEY `user_id_topic_id` (`user_id`,`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`),
  KEY `topic_publish` (`topic_publish`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_favourite_topic`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_friend`
-- 

CREATE TABLE `prefix_friend` (
  `user_id` int(11) unsigned NOT NULL,
  `user_frend_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id_fren_id` (`user_id`,`user_frend_id`),
  KEY `user_id` (`user_id`),
  KEY `user_frend_id` (`user_frend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_friend`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_invite`
-- 

CREATE TABLE `prefix_invite` (
  `invite_id` int(11) unsigned NOT NULL auto_increment,
  `invite_code` varchar(32) NOT NULL,
  `user_from_id` int(11) unsigned NOT NULL,
  `user_to_id` int(11) unsigned default NULL,
  `invite_date_add` datetime NOT NULL,
  `invite_date_used` datetime default NULL,
  `invite_used` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`invite_id`),
  UNIQUE KEY `invite_code` (`invite_code`),
  KEY `user_from_id` (`user_from_id`),
  KEY `user_to_id` (`user_to_id`),
  KEY `invite_date_add` (`invite_date_add`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `prefix_invite`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_page`
-- 

CREATE TABLE `prefix_page` (
  `page_id` int(11) unsigned NOT NULL auto_increment,
  `page_pid` int(11) unsigned default NULL,
  `page_url` varchar(50) NOT NULL,
  `page_url_full` varchar(254) NOT NULL,
  `page_title` varchar(200) NOT NULL,
  `page_text` text NOT NULL,
  `page_date_add` datetime NOT NULL,
  `page_date_edit` datetime default NULL,
  `page_seo_keywords` varchar(250) default NULL,
  `page_seo_description` varchar(250) default NULL,
  `page_active` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`page_id`),
  KEY `page_pid` (`page_pid`),
  KEY `page_url_full` (`page_url_full`,`page_active`),
  KEY `page_title` (`page_title`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Дамп данных таблицы `prefix_page`
-- 

INSERT INTO `prefix_page` VALUES (1, NULL, 'about', 'about', 'About', 'edit this page http://yousite/page/admin/', '2008-11-05 01:03:46', NULL, '', '', 1);

-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_reminder`
-- 

CREATE TABLE `prefix_reminder` (
  `reminder_code` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `reminder_date_add` datetime NOT NULL,
  `reminder_date_used` datetime default '0000-00-00 00:00:00',
  `reminder_date_expire` datetime NOT NULL,
  `reminde_is_used` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`reminder_code`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_reminder`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_talk`
-- 

CREATE TABLE `prefix_talk` (
  `talk_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL,
  `talk_title` varchar(200) NOT NULL,
  `talk_text` text NOT NULL,
  `talk_date` datetime NOT NULL,
  `talk_date_last` datetime NOT NULL,
  `talk_user_ip` varchar(20) NOT NULL,
  PRIMARY KEY  (`talk_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `prefix_talk`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_talk_comment`
-- 

CREATE TABLE `prefix_talk_comment` (
  `talk_comment_id` int(11) unsigned NOT NULL auto_increment,
  `talk_comment_pid` int(11) unsigned default NULL,
  `talk_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `talk_comment_date` datetime NOT NULL,
  `talk_comment_user_ip` varchar(20) NOT NULL,
  `talk_comment_text` text NOT NULL,
  PRIMARY KEY  (`talk_comment_id`),
  KEY `talk_id` (`talk_id`),
  KEY `user_id` (`user_id`),
  KEY `talk_comment_pid` (`talk_comment_pid`),
  KEY `talk_comment_date` (`talk_comment_date`),
  KEY `talk_id_user_id` (`talk_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `prefix_talk_comment`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_talk_user`
-- 

CREATE TABLE `prefix_talk_user` (
  `talk_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_last` datetime default NULL,
  UNIQUE KEY `talk_id_user_id` (`talk_id`,`user_id`),
  KEY `talk_id` (`talk_id`),
  KEY `user_id` (`user_id`),
  KEY `date_last` (`date_last`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_talk_user`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_topic`
-- 

CREATE TABLE `prefix_topic` (
  `topic_id` int(11) unsigned NOT NULL auto_increment,
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `topic_type` enum('topic','link','question') NOT NULL default 'topic',
  `topic_title` varchar(200) NOT NULL,
  `topic_tags` varchar(250) NOT NULL COMMENT 'tags separated by a comma',
  `topic_date_add` datetime NOT NULL,
  `topic_date_edit` datetime default NULL,
  `topic_user_ip` varchar(20) NOT NULL,
  `topic_publish` tinyint(1) NOT NULL default '0',
  `topic_publish_draft` tinyint(1) NOT NULL default '1',
  `topic_publish_index` tinyint(1) NOT NULL default '0',
  `topic_rating` float(9,3) NOT NULL default '0.000',
  `topic_count_vote` int(11) unsigned NOT NULL default '0',
  `topic_count_read` int(11) unsigned NOT NULL default '0',
  `topic_count_comment` int(11) unsigned NOT NULL default '0',
  `topic_cut_text` varchar(100) default NULL,
  `topic_forbid_comment` tinyint(1) NOT NULL default '0',
  `topic_text_hash` varchar(32) NOT NULL,
  PRIMARY KEY  (`topic_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_date_add` (`topic_date_add`),
  KEY `topic_rating` (`topic_rating`),
  KEY `topic_publish` (`topic_publish`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `prefix_topic`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_topic_comment`
-- 

CREATE TABLE `prefix_topic_comment` (
  `comment_id` int(11) unsigned NOT NULL auto_increment,
  `comment_pid` int(11) unsigned default NULL,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `comment_text` text NOT NULL,
  `comment_text_hash` varchar(32) NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_user_ip` varchar(20) NOT NULL,
  `comment_rating` float(9,3) NOT NULL default '0.000',
  `comment_count_vote` int(11) unsigned NOT NULL default '0',
  `comment_delete` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `comment_pid` (`comment_pid`),
  KEY `comment_delete` (`comment_delete`),
  KEY `rating_date_id` (`comment_rating`,`comment_date`,`comment_id`),
  KEY `comment_date` (`comment_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `prefix_topic_comment`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_topic_comment_online`
-- 

CREATE TABLE `prefix_topic_comment_online` (
  `comment_online_id` int(11) unsigned NOT NULL auto_increment,
  `topic_id` int(11) unsigned NOT NULL,
  `comment_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`comment_online_id`),
  UNIQUE KEY `topic_id` (`topic_id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `prefix_topic_comment_online`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_topic_comment_vote`
-- 

CREATE TABLE `prefix_topic_comment_vote` (
  `comment_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_delta` float(9,3) NOT NULL,
  UNIQUE KEY `comment_id_user_voter_id_uniq` (`comment_id`,`user_voter_id`),
  KEY `comment_id` (`comment_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_topic_comment_vote`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_topic_content`
-- 

CREATE TABLE `prefix_topic_content` (
  `topic_id` int(11) unsigned NOT NULL,
  `topic_text` text NOT NULL,
  `topic_text_short` text NOT NULL,
  `topic_text_source` text NOT NULL,
  `topic_extra` text NOT NULL,
  PRIMARY KEY  (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_topic_content`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_topic_question_vote`
-- 

CREATE TABLE `prefix_topic_question_vote` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `answer` tinyint(4) NOT NULL,
  UNIQUE KEY `topic_id_user_id` (`topic_id`,`user_voter_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_topic_question_vote`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_topic_read`
-- 

CREATE TABLE `prefix_topic_read` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_read` datetime NOT NULL,
  `comment_count_last` int(10) unsigned NOT NULL default '0',
  `comment_id_last` int(11) NOT NULL default '0',
  UNIQUE KEY `topic_id_user_id` (`topic_id`,`user_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_topic_read`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_topic_tag`
-- 

CREATE TABLE `prefix_topic_tag` (
  `topic_tag_id` int(11) unsigned NOT NULL auto_increment,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `blog_id` int(11) unsigned NOT NULL,
  `topic_tag_text` varchar(50) NOT NULL,
  PRIMARY KEY  (`topic_tag_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `topic_tag_text` (`topic_tag_text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `prefix_topic_tag`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_topic_vote`
-- 

CREATE TABLE `prefix_topic_vote` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_delta` float(9,3) NOT NULL default '0.000',
  UNIQUE KEY `topic_id_user_voter_id_uniq` (`topic_id`,`user_voter_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_topic_vote`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_user`
-- 

CREATE TABLE `prefix_user` (
  `user_id` int(11) unsigned NOT NULL auto_increment,
  `user_login` varchar(30) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_key` varchar(32) default NULL,
  `user_mail` varchar(50) NOT NULL,
  `user_skill` float(9,3) unsigned NOT NULL default '0.000',
  `user_date_register` datetime NOT NULL,
  `user_date_last` datetime default NULL,
  `user_date_activate` datetime default NULL,
  `user_date_comment_last` datetime default NULL,
  `user_ip_register` varchar(20) NOT NULL,
  `user_ip_last` varchar(20) default NULL,
  `user_rating` float(9,3) NOT NULL default '0.000',
  `user_count_vote` int(11) unsigned NOT NULL default '0',
  `user_activate` tinyint(1) unsigned NOT NULL default '0',
  `user_activate_key` varchar(32) default NULL,
  `user_profile_name` varchar(50) default NULL,
  `user_profile_sex` enum('man','woman','other') NOT NULL default 'other',
  `user_profile_country` varchar(30) default NULL,
  `user_profile_region` varchar(30) default NULL,
  `user_profile_city` varchar(30) default NULL,
  `user_profile_birthday` datetime default NULL,
  `user_profile_site` varchar(200) default NULL,
  `user_profile_site_name` varchar(50) default NULL,
  `user_profile_icq` bigint(20) unsigned default NULL,
  `user_profile_about` text,
  `user_profile_date` datetime default NULL,
  `user_profile_avatar` tinyint(1) unsigned NOT NULL default '0',
  `user_profile_avatar_type` varchar(5) default NULL,
  `user_profile_foto` varchar(250) default NULL,
  `user_settings_notice_new_topic` tinyint(1) NOT NULL default '1',
  `user_settings_notice_new_comment` tinyint(1) NOT NULL default '1',
  `user_settings_notice_new_talk` tinyint(1) NOT NULL default '1',
  `user_settings_notice_reply_comment` tinyint(1) NOT NULL default '1',
  `user_settings_notice_new_friend` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_mail` (`user_mail`),
  UNIQUE KEY `user_key` (`user_key`),
  KEY `user_activate_key` (`user_activate_key`),
  KEY `user_activate` (`user_activate`),
  KEY `user_rating` (`user_rating`),
  KEY `user_date_last` (`user_date_last`,`user_activate`),
  KEY `user_profile_sex` (`user_profile_sex`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Дамп данных таблицы `prefix_user`
-- 

INSERT INTO `prefix_user` VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'admin@admin.adm', 0.000, '2009-05-10 00:00:00', NULL, NULL, NULL, '127.0.0.1', NULL, 0.000, 0, 1, NULL, NULL, 'other', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_user_administrator`
-- 

CREATE TABLE `prefix_user_administrator` (
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_user_administrator`
-- 

INSERT INTO `prefix_user_administrator` VALUES (1);

-- --------------------------------------------------------

-- 
-- Структура таблицы `prefix_user_vote`
-- 

CREATE TABLE `prefix_user_vote` (
  `user_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_delta` float(9,3) NOT NULL default '0.000',
  UNIQUE KEY `user_id_2` (`user_id`,`user_voter_id`),
  KEY `user_id` (`user_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `prefix_user_vote`
-- 


-- 
-- Constraints for dumped tables
-- 

-- 
-- Constraints for table `prefix_blog`
-- 
ALTER TABLE `prefix_blog`
  ADD CONSTRAINT `prefix_blog_fk` FOREIGN KEY (`user_owner_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_blog_user`
-- 
ALTER TABLE `prefix_blog_user`
  ADD CONSTRAINT `prefix_blog_user_fk` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_blog_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_blog_vote`
-- 
ALTER TABLE `prefix_blog_vote`
  ADD CONSTRAINT `prefix_blog_vote_fk` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_blog_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_city_user`
-- 
ALTER TABLE `prefix_city_user`
  ADD CONSTRAINT `prefix_city_user_fk` FOREIGN KEY (`city_id`) REFERENCES `prefix_city` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_city_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_country_user`
-- 
ALTER TABLE `prefix_country_user`
  ADD CONSTRAINT `prefix_country_user_fk` FOREIGN KEY (`country_id`) REFERENCES `prefix_country` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_country_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_favourite_topic`
-- 
ALTER TABLE `prefix_favourite_topic`
  ADD CONSTRAINT `prefix_favourite_topic_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_favourite_topic_fk1` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_friend`
-- 
ALTER TABLE `prefix_friend`
  ADD CONSTRAINT `prefix_frend_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_frend_fk1` FOREIGN KEY (`user_frend_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_invite`
-- 
ALTER TABLE `prefix_invite`
  ADD CONSTRAINT `prefix_invite_fk` FOREIGN KEY (`user_from_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_invite_fk1` FOREIGN KEY (`user_to_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_page`
-- 
ALTER TABLE `prefix_page`
  ADD CONSTRAINT `prefix_page_fk` FOREIGN KEY (`page_pid`) REFERENCES `prefix_page` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_reminder`
-- 
ALTER TABLE `prefix_reminder`
  ADD CONSTRAINT `prefix_reminder_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_talk`
-- 
ALTER TABLE `prefix_talk`
  ADD CONSTRAINT `prefix_talk_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_talk_comment`
-- 
ALTER TABLE `prefix_talk_comment`
  ADD CONSTRAINT `prefix_talk_comment_fk` FOREIGN KEY (`talk_id`) REFERENCES `prefix_talk` (`talk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_talk_comment_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_talk_comment_fk2` FOREIGN KEY (`talk_comment_pid`) REFERENCES `prefix_talk_comment` (`talk_comment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_talk_user`
-- 
ALTER TABLE `prefix_talk_user`
  ADD CONSTRAINT `prefix_talk_user_fk` FOREIGN KEY (`talk_id`) REFERENCES `prefix_talk` (`talk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_talk_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_topic`
-- 
ALTER TABLE `prefix_topic`
  ADD CONSTRAINT `prefix_topic_fk` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_topic_comment`
-- 
ALTER TABLE `prefix_topic_comment`
  ADD CONSTRAINT `prefix_topic_comment_fk` FOREIGN KEY (`comment_pid`) REFERENCES `prefix_topic_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `topic_comment_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `topic_comment_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_topic_comment_online`
-- 
ALTER TABLE `prefix_topic_comment_online`
  ADD CONSTRAINT `prefix_topic_comment_online_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_comment_online_fk1` FOREIGN KEY (`comment_id`) REFERENCES `prefix_topic_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_topic_comment_vote`
-- 
ALTER TABLE `prefix_topic_comment_vote`
  ADD CONSTRAINT `prefix_topic_comment_vote_fk` FOREIGN KEY (`comment_id`) REFERENCES `prefix_topic_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_comment_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_topic_content`
-- 
ALTER TABLE `prefix_topic_content`
  ADD CONSTRAINT `prefix_topic_content_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_topic_question_vote`
-- 
ALTER TABLE `prefix_topic_question_vote`
  ADD CONSTRAINT `prefix_topic_question_vote_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_question_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_topic_read`
-- 
ALTER TABLE `prefix_topic_read`
  ADD CONSTRAINT `prefix_topic_read_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_read_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_topic_tag`
-- 
ALTER TABLE `prefix_topic_tag`
  ADD CONSTRAINT `prefix_topic_tag_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_tag_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_tag_fk2` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_topic_vote`
-- 
ALTER TABLE `prefix_topic_vote`
  ADD CONSTRAINT `prefix_topic_vote_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_user_administrator`
-- 
ALTER TABLE `prefix_user_administrator`
  ADD CONSTRAINT `user_administrator_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `prefix_user_vote`
-- 
ALTER TABLE `prefix_user_vote`
  ADD CONSTRAINT `user_vote_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
        
--
-- Структура таблицы `prefix_session`
--

CREATE TABLE IF NOT EXISTS `prefix_session` (
  `session_key` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `session_ip_create` varchar(15) NOT NULL,
  `session_ip_last` varchar(15) NOT NULL,
  `session_date_create` datetime NOT NULL default '0000-00-00 00:00:00',
  `session_date_last` datetime NOT NULL,
  PRIMARY KEY  (`session_key`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_session`
--
ALTER TABLE `prefix_session`
  ADD CONSTRAINT `prefix_session_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
ALTER TABLE `prefix_user` DROP `user_key` ;
ALTER TABLE `prefix_user` DROP `user_date_last` ;
ALTER TABLE `prefix_user` DROP `user_ip_last` ;

ALTER TABLE `prefix_friend` DROP FOREIGN KEY `prefix_frend_fk1`;
ALTER TABLE `prefix_friend` CHANGE `user_frend_id` `user_friend_id` INT( 11 ) UNSIGNED;
ALTER TABLE `prefix_friend` ADD CONSTRAINT `prefix_friend_ibfk_1` FOREIGN KEY (`user_friend_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `prefix_topic_comment` ADD `comment_publish` TINYINT( 1 ) DEFAULT '1' NOT NULL ;
ALTER TABLE `prefix_topic_comment` DROP FOREIGN KEY `topic_comment_fk`;
ALTER TABLE `prefix_topic_comment` CHANGE `topic_id` `target_id` INT( 11 ) UNSIGNED;
ALTER TABLE `prefix_topic_comment` ADD `target_type` ENUM( "topic", "talk" ) DEFAULT 'topic' NOT NULL AFTER `target_id` ;

ALTER TABLE `prefix_topic_comment_online` DROP FOREIGN KEY `prefix_topic_comment_online_fk`;
ALTER TABLE `prefix_topic_comment_online` CHANGE `topic_id` `target_id` INT( 11 ) UNSIGNED DEFAULT NULL ;
ALTER TABLE `prefix_topic_comment_online` ADD `target_type` ENUM( "topic", "talk" ) DEFAULT 'topic' NOT NULL AFTER `target_id` ;

ALTER TABLE `prefix_topic_comment` RENAME `prefix_comment` ;
ALTER TABLE `prefix_topic_comment_online` RENAME `prefix_comment_online` ;

ALTER TABLE `prefix_topic_vote` RENAME `prefix_vote` ;
ALTER TABLE `prefix_vote` DROP FOREIGN KEY `prefix_topic_vote_fk`;
ALTER TABLE `prefix_vote` CHANGE `topic_id` `target_id` INT( 11 ) UNSIGNED;
ALTER TABLE `prefix_vote` ADD `target_type` ENUM( "topic", "blog", "user", "comment" ) DEFAULT 'topic' NOT NULL AFTER `target_id` ;
ALTER TABLE `prefix_vote` CHANGE `vote_delta` `vote_direction` TINYINT( 2 ) DEFAULT '0';
ALTER TABLE `prefix_vote` ADD `vote_value` FLOAT( 9, 3 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `prefix_vote` ADD `vote_date` DATETIME NOT NULL ;
ALTER TABLE `prefix_vote` DROP INDEX `topic_id_user_voter_id_uniq` ;
ALTER TABLE `prefix_vote` DROP INDEX `topic_id` ;
ALTER TABLE `prefix_vote` ADD PRIMARY KEY ( `target_id` , `target_type` , `user_voter_id` ) ;


ALTER TABLE `prefix_talk` ADD `talk_count_comment` INT DEFAULT '0' NOT NULL ;
ALTER TABLE `prefix_talk_user` ADD `comment_id_last` INT DEFAULT '0' NOT NULL ;
ALTER TABLE `prefix_talk_user` ADD `comment_count_new` INT DEFAULT '0' NOT NULL ;
--
-- Переход на единую систему избранного
--
ALTER TABLE  `prefix_favourite_topic` RENAME  `prefix_favourite`;
ALTER TABLE  `prefix_favourite` DROP FOREIGN KEY `prefix_favourite_topic_fk1`;
ALTER TABLE  `prefix_favourite` DROP FOREIGN KEY `prefix_favourite_topic_fk`;
ALTER TABLE  `prefix_favourite` DROP INDEX  `topic_id`;
ALTER TABLE  `prefix_favourite` DROP INDEX  `topic_publish`;
ALTER TABLE  `prefix_favourite` CHANGE  `topic_id`  `target_id` INT( 11 ) UNSIGNED;
ALTER TABLE  `prefix_favourite` CHANGE  `topic_publish`  `target_publish` TINYINT( 1 ) DEFAULT  '1';
ALTER TABLE  `prefix_favourite` ADD  `target_type` ENUM(  'topic',  'comment' ) DEFAULT  'topic' NOT NULL AFTER  `target_id` ;
ALTER TABLE  `prefix_favourite` DROP INDEX  `user_id_topic_id`,
ADD UNIQUE  `user_id_target_id_type` (  `user_id` ,  `target_id` ,  `target_type` );
ALTER TABLE  `prefix_favourite` ADD INDEX  `target_publish` (  `target_publish` );
ALTER TABLE `prefix_favourite` ADD CONSTRAINT `prefix_favourite_target_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE  `prefix_favourite` CHANGE  `target_type`  `target_type` ENUM(  'topic',  'comment',  'talk' ) DEFAULT  'topic';

ALTER TABLE  `prefix_talk_user` ADD  `talk_user_active` TINYINT( 1 ) DEFAULT  '1';

CREATE TABLE  `prefix_talk_blacklist` (
 `user_id` INT UNSIGNED NOT NULL ,
 `user_target_id` INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  `prefix_talk_blacklist` ADD PRIMARY KEY (  `user_id` ,  `user_target_id` );
ALTER TABLE  `prefix_talk_blacklist` ADD CONSTRAINT  `prefix_talk_blacklist_fk_user` FOREIGN KEY (  `user_id` ) REFERENCES  `prefix_user` (  `user_id` ) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE  `prefix_talk_blacklist` ADD CONSTRAINT  `prefix_talk_blacklist_fk_target` FOREIGN KEY (  `user_target_id` ) REFERENCES  `prefix_user` (  `user_id` ) ON DELETE CASCADE ON UPDATE CASCADE;



ALTER TABLE `prefix_friend`
  DROP FOREIGN KEY `prefix_frend_fk`,
  DROP FOREIGN KEY `prefix_friend_ibfk_1`;

ALTER TABLE  `prefix_friend` DROP INDEX  `user_id`;
ALTER TABLE  `prefix_friend` DROP INDEX  `user_frend_id`;
ALTER TABLE  `prefix_friend` DROP INDEX  `user_id_fren_id`;
ALTER TABLE  `prefix_friend` CHANGE  `user_id`  `user_from` INT( 11 ) UNSIGNED;
ALTER TABLE  `prefix_friend` CHANGE  `user_friend_id`  `user_to` INT( 11 ) UNSIGNED DEFAULT NULL;
ALTER TABLE  `prefix_friend` ADD  `status_from` INT( 4 ) NOT NULL ;
ALTER TABLE  `prefix_friend` ADD  `status_to` INT( 4 ) NOT NULL ;
ALTER TABLE  `prefix_friend` ADD PRIMARY KEY ( `user_from` , `user_to` );
ALTER TABLE  `prefix_friend` ADD INDEX (  `user_from` );
ALTER TABLE  `prefix_friend` ADD INDEX (  `user_to` );
ALTER TABLE  `prefix_friend` ADD CONSTRAINT `prefix_friend_from_fk` FOREIGN KEY (`user_from`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE  `prefix_friend` ADD CONSTRAINT `prefix_friend_to_fk` FOREIGN KEY (`user_to`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Хранение заданий на отложенную отправку e-mail сообщений
--
CREATE TABLE  `prefix_notify_task` (
 `notify_task_id` INT UNSIGNED AUTO_INCREMENT ,
 `user_login` VARCHAR( 30 ) ,
 `user_mail` VARCHAR( 50 ) ,
 `notify_subject` VARCHAR( 200 ) ,
 `notify_text` TEXT,
 `date_created` DATETIME,
 `notify_task_status` TINYINT( 2 ) UNSIGNED,
PRIMARY KEY (  `notify_task_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE `prefix_blog_user`
  DROP `is_moderator`,
  DROP `is_administrator`;
 
ALTER TABLE  `prefix_blog_user` ADD  `user_role` INT( 3 ) NOT NULL ;
ALTER TABLE  `prefix_blog_user` CHANGE  `user_role`  `user_role` INT( 3 ) DEFAULT  '1';

ALTER TABLE  `prefix_user` CHANGE  `user_profile_avatar`  `user_profile_avatar` VARCHAR( 250 );
ALTER TABLE  `prefix_user` DROP  `user_profile_avatar_type`;
ALTER TABLE  `prefix_blog` CHANGE  `blog_avatar`  `blog_avatar` VARCHAR( 250 );
ALTER TABLE  `prefix_blog` DROP  `blog_avatar_type`;

ALTER TABLE  `prefix_user` ADD  `user_date_topic_last` DATETIME AFTER  `user_date_comment_last` ;
ALTER TABLE  `prefix_user` DROP  `user_date_topic_last`;

ALTER TABLE  `prefix_comment` ADD  `target_parent_id` INT DEFAULT  '0' NOT NULL AFTER  `target_type` ;
ALTER TABLE  `prefix_comment_online` ADD  `target_parent_id` INT DEFAULT  '0' NOT NULL AFTER  `target_type` ;



ALTER TABLE  `prefix_comment` DROP INDEX  `rating_date_id`;
ALTER TABLE  `prefix_comment` DROP INDEX  `topic_id`;
ALTER TABLE  `prefix_comment` DROP INDEX  `comment_delete`;
ALTER TABLE  `prefix_comment` DROP INDEX  `comment_date`;

ALTER TABLE  `prefix_comment` ADD INDEX  `type_date_rating` (  `target_type` ,  `comment_date` ,  `comment_rating` );
ALTER TABLE  `prefix_comment` ADD INDEX  `id_type` (  `target_id` ,  `target_type` );
ALTER TABLE  `prefix_comment` ADD INDEX  `type_delete_publish` (  `target_type` ,  `comment_delete` ,  `comment_publish` );
ALTER TABLE  `prefix_comment` ADD INDEX  `user_type` (  `user_id` ,  `target_type` );
ALTER TABLE  `prefix_comment` ADD INDEX (  `target_parent_id` );

ALTER TABLE  `prefix_comment` DROP INDEX  `user_id`;

ALTER TABLE  `prefix_comment_online` DROP INDEX  `topic_id`;
ALTER TABLE  `prefix_comment_online` ADD INDEX  `id_type` (  `target_id` ,  `target_type` );
ALTER TABLE  `prefix_comment_online` ADD INDEX  `type_parent` (  `target_type` ,  `target_parent_id` );

ALTER TABLE  `prefix_favourite` DROP INDEX  `user_id`;
ALTER TABLE  `prefix_favourite` ADD INDEX  `id_type` (  `target_id` ,  `target_type` );

ALTER TABLE  `prefix_friend` DROP INDEX  `user_from`;

ALTER TABLE  `prefix_notify_task` ADD INDEX (  `date_created` );

ALTER TABLE  `prefix_comment_online` DROP INDEX  `id_type` ,
ADD UNIQUE  `id_type` (  `target_id` ,  `target_type` );