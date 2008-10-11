CREATE TABLE IF NOT EXISTS `prefix_topic_content` (
  `topic_id` int(11) unsigned NOT NULL,
  `topic_text` text collate utf8_bin NOT NULL,
  `topic_text_short` text collate utf8_bin NOT NULL,
  `topic_text_source` text collate utf8_bin NOT NULL,
  `topic_extra` text collate utf8_bin NOT NULL,
  PRIMARY KEY  (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_topic_content`
--
ALTER TABLE `prefix_topic_content`
  ADD CONSTRAINT `prefix_topic_content_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE; 

  
  
  
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
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_topic_question_vote`
--
ALTER TABLE `prefix_topic_question_vote`
  ADD CONSTRAINT `prefix_topic_question_vote_fk1` FOREIGN KEY (`user_voter_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_question_vote_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  
   
-- Меняем индекс в таблице комментов  
ALTER TABLE `prefix_topic_comment` DROP INDEX `comment_date_rating`    ;
ALTER TABLE `prefix_topic_comment` ADD INDEX `rating_date_id` ( `comment_rating` , `comment_date` , `comment_id` ) ;   
ALTER TABLE `prefix_topic_comment` ADD INDEX ( `comment_date` )  ;
  
  

ALTER TABLE `prefix_topic_comment` ADD `comment_delete` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `prefix_topic_comment` ADD INDEX ( `comment_delete` )  ;
  
  



--
-- Структура таблицы `prefix_topic_comment_online`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_comment_online` (
  `comment_online_id` int(11) unsigned NOT NULL auto_increment,
  `topic_id` int(11) unsigned NOT NULL,
  `comment_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`comment_online_id`),
  KEY `topic_id` (`topic_id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_topic_comment_online`
--
ALTER TABLE `prefix_topic_comment_online`
  ADD CONSTRAINT `prefix_topic_comment_online_fk1` FOREIGN KEY (`comment_id`) REFERENCES `prefix_topic_comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_topic_comment_online_fk` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;


-- перевод некоторых полей в регистронезависимую кодировку
 ALTER TABLE `prefix_topic_comment` CHANGE `comment_text` `comment_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ;
 ALTER TABLE `prefix_topic` CHANGE `topic_tags` `topic_tags` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'через запятую перечислены теги' ;
 ALTER TABLE `prefix_topic_content` CHANGE `topic_text` `topic_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ;
 ALTER TABLE `prefix_topic_content` CHANGE `topic_text_short` `topic_text_short` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ;
 ALTER TABLE `prefix_topic_content` CHANGE `topic_text_source` `topic_text_source` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ;
 ALTER TABLE `prefix_topic_content` CHANGE `topic_extra` `topic_extra` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ;
 ALTER TABLE `prefix_topic_tag` CHANGE `topic_tag_text` `topic_tag_text` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ;
 ALTER TABLE `prefix_user` CHANGE `user_mail` `user_mail` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ;
 ALTER TABLE `prefix_user` CHANGE `user_login` `user_login` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ;
 ALTER TABLE `prefix_blog` CHANGE `blog_title` `blog_title` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ;
 ALTER TABLE `prefix_blog` CHANGE `blog_url` `blog_url` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL  ;
  
  
-- новое поле для принудительного вывода топика на главную страницу
ALTER TABLE `prefix_topic` ADD `topic_publish_index` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `topic_publish` ; 



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
  KEY `user_to_id` (`user_to_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Ограничения внешнего ключа таблицы `prefix_invite`
--
ALTER TABLE `prefix_invite`
  ADD CONSTRAINT `prefix_invite_fk` FOREIGN KEY (`user_from_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_invite_fk1` FOREIGN KEY (`user_to_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;






  
--
-- ВНИМАНИЕ!!! То что ниже нужно выполнить только после запуска скрипта convert.php !!!! иначе УДАЛЯТСЯ ВСЕ ТОПИКИ!!!!!
--  

ALTER TABLE `prefix_topic` DROP `topic_text`  ;
ALTER TABLE `prefix_topic` DROP `topic_text_short`  ;
ALTER TABLE `prefix_topic` DROP `topic_text_source`  ;
ALTER TABLE `prefix_topic` CHANGE `topic_tags` `topic_tags` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'через запятую перечислены теги' ;