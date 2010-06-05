--
-- Database LiveStreet version 0.4
--

-- --------------------------------------------------------

--
-- Table structure for table `prefix_blog`
--

CREATE TABLE IF NOT EXISTS `prefix_blog` (
  `blog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_owner_id` int(11) unsigned NOT NULL,
  `blog_title` varchar(200) NOT NULL,
  `blog_description` text NOT NULL,
  `blog_type` enum('personal','open','invite','close') DEFAULT 'personal',
  `blog_date_add` datetime NOT NULL,
  `blog_date_edit` datetime DEFAULT NULL,
  `blog_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `blog_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `blog_count_user` int(11) unsigned NOT NULL DEFAULT '0',
  `blog_limit_rating_topic` float(9,3) NOT NULL DEFAULT '0.000',
  `blog_url` varchar(200) DEFAULT NULL,
  `blog_avatar` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`blog_id`),
  KEY `user_owner_id` (`user_owner_id`),
  KEY `blog_type` (`blog_type`),
  KEY `blog_url` (`blog_url`),
  KEY `blog_title` (`blog_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `prefix_blog`
--

INSERT INTO `prefix_blog` (`blog_id`, `user_owner_id`, `blog_title`, `blog_description`, `blog_type`, `blog_date_add`, `blog_date_edit`, `blog_rating`, `blog_count_vote`, `blog_count_user`, `blog_limit_rating_topic`, `blog_url`, `blog_avatar`) VALUES
(1, 1, 'Blog by admin', 'This is your personal blog.', 'personal', '2009-05-10 00:00:00', NULL, 0.000, 0, 0, -1000.000, NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `prefix_blog_user`
--

CREATE TABLE IF NOT EXISTS `prefix_blog_user` (
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `user_role` int(3) DEFAULT '1',
  UNIQUE KEY `blog_id_user_id_uniq` (`blog_id`,`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_blog_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_city`
--

CREATE TABLE IF NOT EXISTS `prefix_city` (
  `city_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_name` varchar(30) NOT NULL,
  PRIMARY KEY (`city_id`),
  KEY `city_name` (`city_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prefix_city`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_city_user`
--

CREATE TABLE IF NOT EXISTS `prefix_city_user` (
  `city_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  KEY `city_id` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_city_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_comment`
--

CREATE TABLE IF NOT EXISTS `prefix_comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comment_pid` int(11) unsigned DEFAULT NULL,
  `target_id` int(11) unsigned DEFAULT NULL,
  `target_type` enum('topic','talk') NOT NULL DEFAULT 'topic',
  `target_parent_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL,
  `comment_text` text NOT NULL,
  `comment_text_hash` varchar(32) NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_user_ip` varchar(20) NOT NULL,
  `comment_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `comment_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_delete` tinyint(4) NOT NULL DEFAULT '0',
  `comment_publish` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`comment_id`),
  KEY `comment_pid` (`comment_pid`),
  KEY `type_date_rating` (`target_type`,`comment_date`,`comment_rating`),
  KEY `id_type` (`target_id`,`target_type`),
  KEY `type_delete_publish` (`target_type`,`comment_delete`,`comment_publish`),
  KEY `user_type` (`user_id`,`target_type`),
  KEY `target_parent_id` (`target_parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prefix_comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_comment_online`
--

CREATE TABLE IF NOT EXISTS `prefix_comment_online` (
  `comment_online_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `target_id` int(11) unsigned DEFAULT NULL,
  `target_type` enum('topic','talk') NOT NULL DEFAULT 'topic',
  `target_parent_id` int(11) NOT NULL DEFAULT '0',
  `comment_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`comment_online_id`),
  UNIQUE KEY `id_type` (`target_id`,`target_type`),
  KEY `comment_id` (`comment_id`),
  KEY `type_parent` (`target_type`,`target_parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prefix_comment_online`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_country`
--

CREATE TABLE IF NOT EXISTS `prefix_country` (
  `country_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_name` varchar(30) NOT NULL,
  PRIMARY KEY (`country_id`),
  KEY `country_name` (`country_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prefix_country`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_country_user`
--

CREATE TABLE IF NOT EXISTS `prefix_country_user` (
  `country_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_country_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_favourite`
--

CREATE TABLE IF NOT EXISTS `prefix_favourite` (
  `user_id` int(11) unsigned NOT NULL,
  `target_id` int(11) unsigned DEFAULT NULL,
  `target_type` enum('topic','comment','talk') DEFAULT 'topic',
  `target_publish` tinyint(1) DEFAULT '1',
  UNIQUE KEY `user_id_target_id_type` (`user_id`,`target_id`,`target_type`),
  KEY `target_publish` (`target_publish`),
  KEY `id_type` (`target_id`,`target_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_favourite`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_friend`
--

CREATE TABLE IF NOT EXISTS `prefix_friend` (
  `user_from` int(11) unsigned NOT NULL DEFAULT '0',
  `user_to` int(11) unsigned NOT NULL DEFAULT '0',
  `status_from` int(4) NOT NULL,
  `status_to` int(4) NOT NULL,
  PRIMARY KEY (`user_from`,`user_to`),
  KEY `user_to` (`user_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_friend`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_invite`
--

CREATE TABLE IF NOT EXISTS `prefix_invite` (
  `invite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invite_code` varchar(32) NOT NULL,
  `user_from_id` int(11) unsigned NOT NULL,
  `user_to_id` int(11) unsigned DEFAULT NULL,
  `invite_date_add` datetime NOT NULL,
  `invite_date_used` datetime DEFAULT NULL,
  `invite_used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`invite_id`),
  UNIQUE KEY `invite_code` (`invite_code`),
  KEY `user_from_id` (`user_from_id`),
  KEY `user_to_id` (`user_to_id`),
  KEY `invite_date_add` (`invite_date_add`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prefix_invite`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_notify_task`
--

CREATE TABLE IF NOT EXISTS `prefix_notify_task` (
  `notify_task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(30) DEFAULT NULL,
  `user_mail` varchar(50) DEFAULT NULL,
  `notify_subject` varchar(200) DEFAULT NULL,
  `notify_text` text,
  `date_created` datetime DEFAULT NULL,
  `notify_task_status` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`notify_task_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prefix_notify_task`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_page`
--

CREATE TABLE IF NOT EXISTS `prefix_page` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_pid` int(11) unsigned DEFAULT NULL,
  `page_url` varchar(50) NOT NULL,
  `page_url_full` varchar(254) NOT NULL,
  `page_title` varchar(200) NOT NULL,
  `page_text` text NOT NULL,
  `page_date_add` datetime NOT NULL,
  `page_date_edit` datetime DEFAULT NULL,
  `page_seo_keywords` varchar(250) DEFAULT NULL,
  `page_seo_description` varchar(250) DEFAULT NULL,
  `page_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `page_main` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `page_sort` int(11) NOT NULL,
  PRIMARY KEY (`page_id`),
  KEY `page_pid` (`page_pid`),
  KEY `page_url_full` (`page_url_full`,`page_active`),
  KEY `page_title` (`page_title`),
  KEY `page_sort` (`page_sort`),
  KEY `page_main` (`page_main`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `prefix_page`
--

INSERT INTO `prefix_page` (`page_id`, `page_pid`, `page_url`, `page_url_full`, `page_title`, `page_text`, `page_date_add`, `page_date_edit`, `page_seo_keywords`, `page_seo_description`, `page_active`, `page_main`, `page_sort`) VALUES
(1, NULL, 'about', 'about', 'about', 'edit this page http://yousite/page/admin/', '2010-06-06 02:29:28', NULL, '', '', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `prefix_reminder`
--

CREATE TABLE IF NOT EXISTS `prefix_reminder` (
  `reminder_code` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `reminder_date_add` datetime NOT NULL,
  `reminder_date_used` datetime DEFAULT '0000-00-00 00:00:00',
  `reminder_date_expire` datetime NOT NULL,
  `reminde_is_used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`reminder_code`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_reminder`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_session`
--

CREATE TABLE IF NOT EXISTS `prefix_session` (
  `session_key` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `session_ip_create` varchar(15) NOT NULL,
  `session_ip_last` varchar(15) NOT NULL,
  `session_date_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `session_date_last` datetime NOT NULL,
  PRIMARY KEY (`session_key`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `session_date_last` (`session_date_last`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_session`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_talk`
--

CREATE TABLE IF NOT EXISTS `prefix_talk` (
  `talk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `talk_title` varchar(200) NOT NULL,
  `talk_text` text NOT NULL,
  `talk_date` datetime NOT NULL,
  `talk_date_last` datetime NOT NULL,
  `talk_user_ip` varchar(20) NOT NULL,
  `talk_count_comment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`talk_id`),
  KEY `user_id` (`user_id`),
  KEY `talk_title` (`talk_title`),
  KEY `talk_date` (`talk_date`),
  KEY `talk_date_last` (`talk_date_last`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prefix_talk`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_talk_blacklist`
--

CREATE TABLE IF NOT EXISTS `prefix_talk_blacklist` (
  `user_id` int(10) unsigned NOT NULL,
  `user_target_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`user_target_id`),
  KEY `prefix_talk_blacklist_fk_target` (`user_target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_talk_blacklist`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_talk_user`
--

CREATE TABLE IF NOT EXISTS `prefix_talk_user` (
  `talk_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_last` datetime DEFAULT NULL,
  `comment_id_last` int(11) NOT NULL DEFAULT '0',
  `comment_count_new` int(11) NOT NULL DEFAULT '0',
  `talk_user_active` tinyint(1) DEFAULT '1',
  UNIQUE KEY `talk_id_user_id` (`talk_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `date_last` (`date_last`),
  KEY `date_last_2` (`date_last`),
  KEY `talk_user_active` (`talk_user_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_talk_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_topic`
--

CREATE TABLE IF NOT EXISTS `prefix_topic` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `topic_type` enum('topic','link','question') NOT NULL DEFAULT 'topic',
  `topic_title` varchar(200) NOT NULL,
  `topic_tags` varchar(250) NOT NULL COMMENT 'tags separated by a comma',
  `topic_date_add` datetime NOT NULL,
  `topic_date_edit` datetime DEFAULT NULL,
  `topic_user_ip` varchar(20) NOT NULL,
  `topic_publish` tinyint(1) NOT NULL DEFAULT '0',
  `topic_publish_draft` tinyint(1) NOT NULL DEFAULT '1',
  `topic_publish_index` tinyint(1) NOT NULL DEFAULT '0',
  `topic_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `topic_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_count_read` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_count_comment` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_cut_text` varchar(100) DEFAULT NULL,
  `topic_forbid_comment` tinyint(1) NOT NULL DEFAULT '0',
  `topic_text_hash` varchar(32) NOT NULL,
  PRIMARY KEY (`topic_id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_date_add` (`topic_date_add`),
  KEY `topic_rating` (`topic_rating`),
  KEY `topic_publish` (`topic_publish`),
  KEY `topic_text_hash` (`topic_text_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prefix_topic`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_topic_content`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_content` (
  `topic_id` int(11) unsigned NOT NULL,
  `topic_text` longtext NOT NULL,
  `topic_text_short` text NOT NULL,
  `topic_text_source` longtext NOT NULL,
  `topic_extra` text NOT NULL,
  PRIMARY KEY (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_topic_content`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_topic_question_vote`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_question_vote` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_voter_id` int(11) unsigned NOT NULL,
  `answer` tinyint(4) NOT NULL,
  UNIQUE KEY `topic_id_user_id` (`topic_id`,`user_voter_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_topic_question_vote`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_topic_read`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_read` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_read` datetime NOT NULL,
  `comment_count_last` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_id_last` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `topic_id_user_id` (`topic_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_topic_read`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_topic_tag`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_tag` (
  `topic_tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `blog_id` int(11) unsigned NOT NULL,
  `topic_tag_text` varchar(50) NOT NULL,
  PRIMARY KEY (`topic_tag_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `blog_id` (`blog_id`),
  KEY `topic_tag_text` (`topic_tag_text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prefix_topic_tag`
--


-- --------------------------------------------------------

--
-- Table structure for table `prefix_user`
--

CREATE TABLE IF NOT EXISTS `prefix_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(30) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_mail` varchar(50) NOT NULL,
  `user_skill` float(9,3) unsigned NOT NULL DEFAULT '0.000',
  `user_date_register` datetime NOT NULL,
  `user_date_activate` datetime DEFAULT NULL,
  `user_date_comment_last` datetime DEFAULT NULL,
  `user_ip_register` varchar(20) NOT NULL,
  `user_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `user_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `user_activate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_activate_key` varchar(32) DEFAULT NULL,
  `user_profile_name` varchar(50) DEFAULT NULL,
  `user_profile_sex` enum('man','woman','other') NOT NULL DEFAULT 'other',
  `user_profile_country` varchar(30) DEFAULT NULL,
  `user_profile_region` varchar(30) DEFAULT NULL,
  `user_profile_city` varchar(30) DEFAULT NULL,
  `user_profile_birthday` datetime DEFAULT NULL,
  `user_profile_site` varchar(200) DEFAULT NULL,
  `user_profile_site_name` varchar(50) DEFAULT NULL,
  `user_profile_icq` bigint(20) unsigned DEFAULT NULL,
  `user_profile_about` text,
  `user_profile_date` datetime DEFAULT NULL,
  `user_profile_avatar` varchar(250) DEFAULT NULL,
  `user_profile_foto` varchar(250) DEFAULT NULL,
  `user_settings_notice_new_topic` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_new_comment` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_new_talk` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_reply_comment` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_new_friend` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_mail` (`user_mail`),
  KEY `user_activate_key` (`user_activate_key`),
  KEY `user_activate` (`user_activate`),
  KEY `user_rating` (`user_rating`),
  KEY `user_profile_sex` (`user_profile_sex`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `prefix_user`
--

INSERT INTO `prefix_user` (`user_id`, `user_login`, `user_password`, `user_mail`, `user_skill`, `user_date_register`, `user_date_activate`, `user_date_comment_last`, `user_ip_register`, `user_rating`, `user_count_vote`, `user_activate`, `user_activate_key`, `user_profile_name`, `user_profile_sex`, `user_profile_country`, `user_profile_region`, `user_profile_city`, `user_profile_birthday`, `user_profile_site`, `user_profile_site_name`, `user_profile_icq`, `user_profile_about`, `user_profile_date`, `user_profile_avatar`, `user_profile_foto`, `user_settings_notice_new_topic`, `user_settings_notice_new_comment`, `user_settings_notice_new_talk`, `user_settings_notice_reply_comment`, `user_settings_notice_new_friend`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.adm', 0.000, '2009-05-10 00:00:00', NULL, NULL, '127.0.0.1', 0.000, 0, 1, NULL, NULL, 'other', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `prefix_user_administrator`
--

CREATE TABLE IF NOT EXISTS `prefix_user_administrator` (
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_user_administrator`
--

INSERT INTO `prefix_user_administrator` (`user_id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `prefix_vote`
--

CREATE TABLE IF NOT EXISTS `prefix_vote` (
  `target_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_type` enum('topic','blog','user','comment') NOT NULL DEFAULT 'topic',
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_direction` tinyint(2) DEFAULT '0',
  `vote_value` float(9,3) NOT NULL DEFAULT '0.000',
  `vote_date` datetime NOT NULL,
  PRIMARY KEY (`target_id`,`target_type`,`user_voter_id`),
  KEY `user_voter_id` (`user_voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prefix_vote`
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
-- Constraints for table `prefix_city_user`
--
ALTER TABLE `prefix_city_user`
  ADD CONSTRAINT `prefix_city_user_fk` FOREIGN KEY (`city_id`) REFERENCES `prefix_city` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_city_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prefix_comment`
--
ALTER TABLE `prefix_comment`
  ADD CONSTRAINT `prefix_topic_comment_fk` FOREIGN KEY (`comment_pid`) REFERENCES `prefix_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `topic_comment_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prefix_comment_online`
--
ALTER TABLE `prefix_comment_online`
  ADD CONSTRAINT `prefix_topic_comment_online_fk1` FOREIGN KEY (`comment_id`) REFERENCES `prefix_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prefix_country_user`
--
ALTER TABLE `prefix_country_user`
  ADD CONSTRAINT `prefix_country_user_fk` FOREIGN KEY (`country_id`) REFERENCES `prefix_country` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_country_user_fk1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prefix_favourite`
--
ALTER TABLE `prefix_favourite`
  ADD CONSTRAINT `prefix_favourite_target_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prefix_friend`
--
ALTER TABLE `prefix_friend`
  ADD CONSTRAINT `prefix_friend_from_fk` FOREIGN KEY (`user_from`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_friend_to_fk` FOREIGN KEY (`user_to`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `prefix_session`
--
ALTER TABLE `prefix_session`
  ADD CONSTRAINT `prefix_session_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prefix_talk`
--
ALTER TABLE `prefix_talk`
  ADD CONSTRAINT `prefix_talk_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prefix_talk_blacklist`
--
ALTER TABLE `prefix_talk_blacklist`
  ADD CONSTRAINT `prefix_talk_blacklist_fk_target` FOREIGN KEY (`user_target_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_talk_blacklist_fk_user` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `prefix_user_administrator`
--
ALTER TABLE `prefix_user_administrator`
  ADD CONSTRAINT `user_administrator_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prefix_vote`
--
ALTER TABLE `prefix_vote`
  ADD CONSTRAINT `prefix_topic_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
