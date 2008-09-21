-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 18 2008 г., 23:29
-- Версия сервера: 5.0.45
-- Версия PHP: 5.2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `social`
--

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_blog`
--

CREATE TABLE IF NOT EXISTS `prefix_blog` (
  `blog_id` int(11) unsigned NOT NULL auto_increment,
  `user_owner_id` int(11) unsigned NOT NULL,
  `blog_title` varchar(200) collate utf8_bin NOT NULL,
  `blog_description` text collate utf8_bin NOT NULL,
  `blog_type` enum('personal','open','invate','close') collate utf8_bin NOT NULL default 'personal',
  `blog_date_add` datetime NOT NULL,
  `blog_date_edit` datetime default NULL,
  `blog_rating` float(9,3) NOT NULL default '0.000',
  `blog_count_vote` int(11) unsigned NOT NULL default '0',
  `blog_count_user` int(11) unsigned NOT NULL default '0',
  `blog_limit_rating_topic` float(9,3) NOT NULL default '0.000',
  `blog_url` varchar(200) collate utf8_bin default NULL,
  PRIMARY KEY  (`blog_id`),
  KEY `user_owner_id` (`user_owner_id`),
  KEY `blog_type` (`blog_type`),
  KEY `blog_url` (`blog_url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_favourite_topic`
--

CREATE TABLE IF NOT EXISTS `prefix_favourite_topic` (
  `user_id` int(11) unsigned NOT NULL,
  `topic_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id_topic_id` (`user_id`,`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_frend`
--

CREATE TABLE IF NOT EXISTS `prefix_frend` (
  `user_id` int(11) unsigned NOT NULL,
  `user_frend_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id_fren_id` (`user_id`,`user_frend_id`),
  KEY `user_id` (`user_id`),
  KEY `user_frend_id` (`user_frend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
  `topic_text` text collate utf8_bin NOT NULL,
  `topic_text_short` text collate utf8_bin NOT NULL COMMENT 'короткий текст путем отрезания КАТа',
  `topic_text_source` text collate utf8_bin NOT NULL,
  `topic_tags` text collate utf8_bin NOT NULL COMMENT 'через запятую перечислены теги',
  `topic_date_add` datetime NOT NULL,
  `topic_date_edit` datetime default NULL,
  `topic_user_ip` varchar(20) collate utf8_bin NOT NULL,
  `topic_publish` tinyint(1) NOT NULL default '0',
  `topic_rating` float(9,3) NOT NULL default '0.000',
  `topic_count_vote` int(11) unsigned NOT NULL default '0',
  `topic_count_read` int(11) unsigned NOT NULL default '0',
  `topic_count_comment` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`topic_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_date_add` (`topic_date_add`),
  KEY `topic_rating` (`topic_rating`),
  KEY `topic_publish` (`topic_publish`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_comment`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_comment` (
  `comment_id` int(11) unsigned NOT NULL auto_increment,
  `comment_pid` int(11) unsigned default NULL,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `comment_text` text collate utf8_bin NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_user_ip` varchar(20) collate utf8_bin NOT NULL,
  `comment_rating` float(9,3) NOT NULL default '0.000',
  `comment_count_vote` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `comment_pid` (`comment_pid`),
  KEY `comment_date_rating` (`comment_date`,`comment_rating`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_read`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_read` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_read` datetime NOT NULL,
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_id_user_id` (`topic_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_tag`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_tag` (
  `topic_tag_id` int(11) unsigned NOT NULL auto_increment,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `blog_id` int(11) unsigned NOT NULL,
  `topic_tag_text` varchar(50) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`topic_tag_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `topic_tag_text` (`topic_tag_text`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user`
--

CREATE TABLE IF NOT EXISTS `prefix_user` (
  `user_id` int(11) unsigned NOT NULL auto_increment,
  `user_login` varchar(30) collate utf8_bin NOT NULL,
  `user_password` varchar(50) collate utf8_bin NOT NULL,
  `user_key` varchar(32) collate utf8_bin default NULL,
  `user_mail` varchar(50) collate utf8_bin NOT NULL,
  `user_skill` float(9,3) unsigned NOT NULL default '0.000',
  `user_date_register` datetime NOT NULL,
  `user_date_last` datetime default NULL,
  `user_date_activate` datetime default NULL,
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
  `user_profile_avatar_type` varchar(3) collate utf8_bin default NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_mail` (`user_mail`),
  UNIQUE KEY `user_key` (`user_key`),
  KEY `user_activate_key` (`user_activate_key`),
  KEY `user_activate` (`user_activate`),
  KEY `user_rating` (`user_rating`),
  KEY `user_date_last` (`user_date_last`,`user_activate`),
  KEY `user_profile_sex` (`user_profile_sex`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user_administrator`
--

CREATE TABLE IF NOT EXISTS `prefix_user_administrator` (
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
-- Ограничения внешнего ключа таблицы `prefix_favourite_topic`
--
ALTER TABLE `prefix_favourite_topic`
  ADD CONSTRAINT `prefix_favourite_topic_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_favourite_topic_fk1` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_frend`
--
ALTER TABLE `prefix_frend`
  ADD CONSTRAINT `prefix_frend_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_frend_fk1` FOREIGN KEY (`user_frend_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Ограничения внешнего ключа таблицы `prefix_topic_comment_vote`
--
ALTER TABLE `prefix_topic_comment_vote`
  ADD CONSTRAINT `prefix_topic_comment_vote_fk` FOREIGN KEY (`comment_id`) REFERENCES `prefix_topic_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_comment_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
