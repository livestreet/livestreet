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

ALTER TABLE  `prefix_topic` CHANGE  `topic_tags`  `topic_tags` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;




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

ALTER TABLE  `prefix_talk` ADD INDEX (  `talk_title` );
ALTER TABLE  `prefix_talk` ADD INDEX (  `talk_date` );
ALTER TABLE  `prefix_talk` ADD INDEX (  `talk_date_last` );

ALTER TABLE  `prefix_talk_user` DROP INDEX  `talk_id`;
ALTER TABLE  `prefix_talk_user` ADD INDEX (  `date_last` );
ALTER TABLE  `prefix_talk_user` ADD INDEX (  `talk_user_active` );

ALTER TABLE  `prefix_topic_read` DROP INDEX  `topic_id`;
ALTER TABLE  `prefix_topic_question_vote` DROP INDEX  `topic_id`;
ALTER TABLE  `prefix_topic` ADD INDEX (  `topic_text_hash` );

ALTER TABLE  `prefix_session` ADD INDEX (  `session_date_last` );
ALTER TABLE  `prefix_user` DROP INDEX  `user_date_last`;