-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 04 2008 г., 00:55
-- Версия сервера: 5.0.45
-- Версия PHP: 5.2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `live_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_blog`
--

CREATE TABLE IF NOT EXISTS `prefix_blog` (
  `blog_id` int(11) unsigned NOT NULL auto_increment,
  `user_owner_id` int(11) unsigned NOT NULL,
  `blog_title` varchar(200) character set utf8 NOT NULL,
  `blog_description` text collate utf8_bin NOT NULL,
  `blog_type` enum('personal','open','invate','close') collate utf8_bin NOT NULL default 'personal',
  `blog_date_add` datetime NOT NULL,
  `blog_date_edit` datetime default NULL,
  `blog_rating` float(9,3) NOT NULL default '0.000',
  `blog_count_vote` int(11) unsigned NOT NULL default '0',
  `blog_count_user` int(11) unsigned NOT NULL default '0',
  `blog_limit_rating_topic` float(9,3) NOT NULL default '0.000',
  `blog_url` varchar(200) character set utf8 default NULL,
  `blog_avatar` tinyint(1) unsigned NOT NULL default '0',
  `blog_avatar_type` varchar(5) character set utf8 default NULL,
  PRIMARY KEY  (`blog_id`),
  KEY `user_owner_id` (`user_owner_id`),
  KEY `blog_type` (`blog_type`),
  KEY `blog_url` (`blog_url`),
  KEY `blog_title` (`blog_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `prefix_blog`
--

INSERT INTO `prefix_blog` (`blog_id`, `user_owner_id`, `blog_title`, `blog_description`, `blog_type`, `blog_date_add`, `blog_date_edit`, `blog_rating`, `blog_count_vote`, `blog_count_user`, `blog_limit_rating_topic`, `blog_url`, `blog_avatar`, `blog_avatar_type`) VALUES
(1, 1, 'Блог им. admin', 0xd0add182d0be20d0b2d0b0d18820d0bfd0b5d180d181d0bed0bdd0b0d0bbd18cd0bdd18bd0b920d0b1d0bbd0bed0b32e, 'personal', '2008-11-04 00:50:21', NULL, 0.000, 0, 0, -1000.000, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_blog_user`
--

CREATE TABLE IF NOT EXISTS `prefix_blog_user` (
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `is_moderator` tinyint(1) unsigned NOT NULL default '0',
  `is_administrator` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `blog_id_user_id_uniq` (`blog_id`,`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_blog_user`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_blog_vote`
--

CREATE TABLE IF NOT EXISTS `prefix_blog_vote` (
  `blog_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_delta` float(9,3) NOT NULL default '0.000',
  UNIQUE KEY `blog_id_user_voter_id_uniq` (`blog_id`,`user_voter_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_blog_vote`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_city`
--

CREATE TABLE IF NOT EXISTS `prefix_city` (
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

CREATE TABLE IF NOT EXISTS `prefix_city_user` (
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

CREATE TABLE IF NOT EXISTS `prefix_country` (
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

CREATE TABLE IF NOT EXISTS `prefix_country_user` (
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

CREATE TABLE IF NOT EXISTS `prefix_favourite_topic` (
  `user_id` int(11) unsigned NOT NULL,
  `topic_id` int(11) unsigned NOT NULL,
  `topic_publish` tinyint(1) NOT NULL default '1',
  UNIQUE KEY `user_id_topic_id` (`user_id`,`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`),
  KEY `topic_publish` (`topic_publish`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_favourite_topic`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_friend`
--

CREATE TABLE IF NOT EXISTS `prefix_friend` (
  `user_id` int(11) unsigned NOT NULL,
  `user_frend_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id_fren_id` (`user_id`,`user_frend_id`),
  KEY `user_id` (`user_id`),
  KEY `user_frend_id` (`user_frend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_friend`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_invite`
--

CREATE TABLE IF NOT EXISTS `prefix_invite` (
  `invite_id` int(11) unsigned NOT NULL auto_increment,
  `invite_code` varchar(32) collate utf8_bin NOT NULL,
  `user_from_id` int(11) unsigned NOT NULL,
  `user_to_id` int(11) unsigned default NULL,
  `invite_date_add` datetime NOT NULL,
  `invite_date_used` datetime NOT NULL,
  `invite_used` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`invite_id`),
  UNIQUE KEY `invite_code` (`invite_code`),
  KEY `user_from_id` (`user_from_id`),
  KEY `user_to_id` (`user_to_id`),
  KEY `invite_date_add` (`invite_date_add`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `prefix_invite`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_page`
--

CREATE TABLE IF NOT EXISTS `prefix_page` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `prefix_page`
--

INSERT INTO `prefix_page` (`page_id`, `page_pid`, `page_url`, `page_url_full`, `page_title`, `page_text`, `page_date_add`, `page_date_edit`, `page_seo_keywords`, `page_seo_description`, `page_active`) VALUES
(1, NULL, 'about', 'about', 'О проекте', 'это страница о проекте, отредактировать эту страницу можно по адресу http://ваш_сайт/page/admin/', '2008-11-05 01:03:46', NULL, '', '', 1);



-- --------------------------------------------------------

--
-- Структура таблицы `prefix_talk`
--

CREATE TABLE IF NOT EXISTS `prefix_talk` (
  `talk_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL,
  `talk_title` varchar(200) collate utf8_bin NOT NULL,
  `talk_text` text collate utf8_bin NOT NULL,
  `talk_date` datetime NOT NULL,
  `talk_user_ip` varchar(20) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`talk_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `prefix_talk`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_talk_comment`
--

CREATE TABLE IF NOT EXISTS `prefix_talk_comment` (
  `talk_comment_id` int(11) unsigned NOT NULL auto_increment,
  `talk_comment_pid` int(11) unsigned default NULL,
  `talk_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `talk_comment_date` datetime NOT NULL,
  `talk_comment_user_ip` varchar(20) collate utf8_bin NOT NULL,
  `talk_comment_text` text collate utf8_bin NOT NULL,
  PRIMARY KEY  (`talk_comment_id`),
  KEY `talk_id` (`talk_id`),
  KEY `user_id` (`user_id`),
  KEY `talk_comment_pid` (`talk_comment_pid`),
  KEY `talk_comment_date` (`talk_comment_date`),
  KEY `talk_id_user_id` (`talk_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `prefix_talk_comment`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_talk_user`
--

CREATE TABLE IF NOT EXISTS `prefix_talk_user` (
  `talk_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_last` datetime default NULL,
  UNIQUE KEY `talk_id_user_id` (`talk_id`,`user_id`),
  KEY `talk_id` (`talk_id`),
  KEY `user_id` (`user_id`),
  KEY `date_last` (`date_last`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_talk_user`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic`
--

CREATE TABLE IF NOT EXISTS `prefix_topic` (
  `topic_id` int(11) unsigned NOT NULL auto_increment,
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `topic_type` enum('topic','link','question') collate utf8_bin NOT NULL default 'topic',
  `topic_title` varchar(200) collate utf8_bin NOT NULL,
  `topic_tags` varchar(250) character set utf8 NOT NULL COMMENT 'через запятую перечислены теги',
  `topic_date_add` datetime NOT NULL,
  `topic_date_edit` datetime default NULL,
  `topic_user_ip` varchar(20) collate utf8_bin NOT NULL,
  `topic_publish` tinyint(1) NOT NULL default '0',
  `topic_publish_index` tinyint(1) NOT NULL default '0',
  `topic_rating` float(9,3) NOT NULL default '0.000',
  `topic_count_vote` int(11) unsigned NOT NULL default '0',
  `topic_count_read` int(11) unsigned NOT NULL default '0',
  `topic_count_comment` int(11) unsigned NOT NULL default '0',
  `topic_cut_text` varchar(100) collate utf8_bin default NULL,
  `topic_forbid_comment` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_date_add` (`topic_date_add`),
  KEY `topic_rating` (`topic_rating`),
  KEY `topic_publish` (`topic_publish`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `prefix_topic`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_comment`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_comment` (
  `comment_id` int(11) unsigned NOT NULL auto_increment,
  `comment_pid` int(11) unsigned default NULL,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `comment_text` text character set utf8 NOT NULL,
  `comment_text_hash` varchar(32) collate utf8_bin NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_user_ip` varchar(20) collate utf8_bin NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `prefix_topic_comment`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_comment_online`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_comment_online` (
  `comment_online_id` int(11) unsigned NOT NULL auto_increment,
  `topic_id` int(11) unsigned NOT NULL,
  `comment_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`comment_online_id`),
  UNIQUE KEY `topic_id` (`topic_id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `prefix_topic_comment_online`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_comment_vote`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_comment_vote` (
  `comment_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_delta` float(9,3) NOT NULL,
  UNIQUE KEY `comment_id_user_voter_id_uniq` (`comment_id`,`user_voter_id`),
  KEY `comment_id` (`comment_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_topic_comment_vote`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_content`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_content` (
  `topic_id` int(11) unsigned NOT NULL,
  `topic_text` text character set utf8 NOT NULL,
  `topic_text_short` text character set utf8 NOT NULL,
  `topic_text_source` text character set utf8 NOT NULL,
  `topic_extra` text character set utf8 NOT NULL,
  PRIMARY KEY  (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_topic_content`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_question_vote`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_question_vote` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `answer` tinyint(4) NOT NULL,
  UNIQUE KEY `topic_id_user_id` (`topic_id`,`user_voter_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_topic_question_vote`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_read`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_read` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_read` datetime NOT NULL,
  `comment_count_last` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `topic_id_user_id` (`topic_id`,`user_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_topic_read`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_tag`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_tag` (
  `topic_tag_id` int(11) unsigned NOT NULL auto_increment,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `blog_id` int(11) unsigned NOT NULL,
  `topic_tag_text` varchar(50) character set utf8 NOT NULL,
  PRIMARY KEY  (`topic_tag_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `topic_tag_text` (`topic_tag_text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `prefix_topic_tag`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_vote`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_vote` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_delta` float(9,3) NOT NULL default '0.000',
  UNIQUE KEY `topic_id_user_voter_id_uniq` (`topic_id`,`user_voter_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_topic_vote`
--


-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user`
--

CREATE TABLE IF NOT EXISTS `prefix_user` (
  `user_id` int(11) unsigned NOT NULL auto_increment,
  `user_login` varchar(30) character set utf8 NOT NULL,
  `user_password` varchar(50) collate utf8_bin NOT NULL,
  `user_key` varchar(32) collate utf8_bin default NULL,
  `user_mail` varchar(50) character set utf8 NOT NULL,
  `user_skill` float(9,3) unsigned NOT NULL default '0.000',
  `user_date_register` datetime NOT NULL,
  `user_date_last` datetime default NULL,
  `user_date_activate` datetime default NULL,
  `user_date_comment_last` datetime default NULL,
  `user_ip_register` varchar(20) collate utf8_bin NOT NULL,
  `user_ip_last` varchar(20) collate utf8_bin default NULL,
  `user_rating` float(9,3) NOT NULL default '0.000',
  `user_count_vote` int(11) unsigned NOT NULL default '0',
  `user_activate` tinyint(1) unsigned NOT NULL default '0',
  `user_activate_key` varchar(32) collate utf8_bin default NULL,
  `user_profile_name` varchar(50) collate utf8_bin default NULL,
  `user_profile_sex` enum('man','woman','other') collate utf8_bin NOT NULL default 'other',
  `user_profile_country` varchar(30) collate utf8_bin default NULL,
  `user_profile_region` varchar(30) collate utf8_bin default NULL,
  `user_profile_city` varchar(30) collate utf8_bin default NULL,
  `user_profile_birthday` datetime default NULL,
  `user_profile_site` varchar(200) collate utf8_bin default NULL,
  `user_profile_site_name` varchar(50) collate utf8_bin default NULL,
  `user_profile_icq` bigint(20) unsigned default NULL,
  `user_profile_about` text collate utf8_bin,
  `user_profile_date` datetime default NULL,
  `user_profile_avatar` tinyint(1) unsigned NOT NULL default '0',
  `user_profile_avatar_type` varchar(5) character set utf8 default NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `prefix_user`
--

INSERT INTO `prefix_user` (`user_id`, `user_login`, `user_password`, `user_key`, `user_mail`, `user_skill`, `user_date_register`, `user_date_last`, `user_date_activate`, `user_date_comment_last`, `user_ip_register`, `user_ip_last`, `user_rating`, `user_count_vote`, `user_activate`, `user_activate_key`, `user_profile_name`, `user_profile_sex`, `user_profile_country`, `user_profile_region`, `user_profile_city`, `user_profile_birthday`, `user_profile_site`, `user_profile_site_name`, `user_profile_icq`, `user_profile_about`, `user_profile_date`, `user_profile_avatar`, `user_profile_avatar_type`, `user_settings_notice_new_topic`, `user_settings_notice_new_comment`, `user_settings_notice_new_talk`, `user_settings_notice_reply_comment`, `user_settings_notice_new_friend`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'admin@admin.adm', 0.000, '2008-11-04 00:50:21', NULL, NULL, NULL, '127.0.0.1', NULL, 0.000, 0, 1, NULL, NULL, 'other', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user_administrator`
--

CREATE TABLE IF NOT EXISTS `prefix_user_administrator` (
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_user_administrator`
--

INSERT INTO `prefix_user_administrator` (`user_id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user_vote`
--

CREATE TABLE IF NOT EXISTS `prefix_user_vote` (
  `user_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_delta` float(9,3) NOT NULL default '0.000',
  UNIQUE KEY `user_id_2` (`user_id`,`user_voter_id`),
  KEY `user_id` (`user_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_user_vote`
--


--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_blog`
--
ALTER TABLE `prefix_blog`
  ADD CONSTRAINT `prefix_blog_fk` FOREIGN KEY (`user_owner_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_blog_user`
--
ALTER TABLE `prefix_blog_user`
  ADD CONSTRAINT `prefix_blog_user_fk` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_blog_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_blog_vote`
--
ALTER TABLE `prefix_blog_vote`
  ADD CONSTRAINT `prefix_blog_vote_fk` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_blog_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_city_user`
--
ALTER TABLE `prefix_city_user`
  ADD CONSTRAINT `prefix_city_user_fk` FOREIGN KEY (`city_id`) REFERENCES `prefix_city` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_city_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_country_user`
--
ALTER TABLE `prefix_country_user`
  ADD CONSTRAINT `prefix_country_user_fk` FOREIGN KEY (`country_id`) REFERENCES `prefix_country` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_country_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_favourite_topic`
--
ALTER TABLE `prefix_favourite_topic`
  ADD CONSTRAINT `prefix_favourite_topic_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_favourite_topic_fk1` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_friend`
--
ALTER TABLE `prefix_friend`
  ADD CONSTRAINT `prefix_frend_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_frend_fk1` FOREIGN KEY (`user_frend_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_invite`
--
ALTER TABLE `prefix_invite`
  ADD CONSTRAINT `prefix_invite_fk` FOREIGN KEY (`user_from_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_invite_fk1` FOREIGN KEY (`user_to_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_page`
--
ALTER TABLE `prefix_page`
  ADD CONSTRAINT `prefix_page_fk` FOREIGN KEY (`page_pid`) REFERENCES `prefix_page` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_talk`
--
ALTER TABLE `prefix_talk`
  ADD CONSTRAINT `prefix_talk_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_talk_comment`
--
ALTER TABLE `prefix_talk_comment`
  ADD CONSTRAINT `prefix_talk_comment_fk` FOREIGN KEY (`talk_id`) REFERENCES `prefix_talk` (`talk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_talk_comment_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_talk_comment_fk2` FOREIGN KEY (`talk_comment_pid`) REFERENCES `prefix_talk_comment` (`talk_comment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_talk_user`
--
ALTER TABLE `prefix_talk_user`
  ADD CONSTRAINT `prefix_talk_user_fk` FOREIGN KEY (`talk_id`) REFERENCES `prefix_talk` (`talk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_talk_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_topic`
--
ALTER TABLE `prefix_topic`
  ADD CONSTRAINT `prefix_topic_fk` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_topic_comment`
--
ALTER TABLE `prefix_topic_comment`
  ADD CONSTRAINT `prefix_topic_comment_fk` FOREIGN KEY (`comment_pid`) REFERENCES `prefix_topic_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `topic_comment_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `topic_comment_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_topic_comment_online`
--
ALTER TABLE `prefix_topic_comment_online`
  ADD CONSTRAINT `prefix_topic_comment_online_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_comment_online_fk1` FOREIGN KEY (`comment_id`) REFERENCES `prefix_topic_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_topic_comment_vote`
--
ALTER TABLE `prefix_topic_comment_vote`
  ADD CONSTRAINT `prefix_topic_comment_vote_fk` FOREIGN KEY (`comment_id`) REFERENCES `prefix_topic_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_comment_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_topic_content`
--
ALTER TABLE `prefix_topic_content`
  ADD CONSTRAINT `prefix_topic_content_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_topic_question_vote`
--
ALTER TABLE `prefix_topic_question_vote`
  ADD CONSTRAINT `prefix_topic_question_vote_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_question_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_topic_read`
--
ALTER TABLE `prefix_topic_read`
  ADD CONSTRAINT `prefix_topic_read_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_read_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_topic_tag`
--
ALTER TABLE `prefix_topic_tag`
  ADD CONSTRAINT `prefix_topic_tag_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_tag_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_tag_fk2` FOREIGN KEY (`blog_id`) REFERENCES `prefix_blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_topic_vote`
--
ALTER TABLE `prefix_topic_vote`
  ADD CONSTRAINT `prefix_topic_vote_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_user_administrator`
--
ALTER TABLE `prefix_user_administrator`
  ADD CONSTRAINT `user_administrator_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_user_vote`
--
ALTER TABLE `prefix_user_vote`
  ADD CONSTRAINT `user_vote_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
