CREATE TABLE IF NOT EXISTS `prefix_stream_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type` varchar(100) NOT NULL,
  `target_id` int(11) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `event_type` (`event_type`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_stream_subscribe` (
  `user_id` int(11) unsigned NOT NULL,
  `target_user_id` int(11) NOT NULL,
  KEY `user_id` (`user_id`,`target_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_stream_user_type` (
  `user_id` int(11) unsigned NOT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  KEY `user_id` (`user_id`,`event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_topic_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `description` text,
  `target_tmp` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `target_tmp` (`target_tmp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_userfeed_subscribe` (
  `user_id` int(11) unsigned NOT NULL,
  `subscribe_type` tinyint(4) NOT NULL,
  `target_id` int(11) NOT NULL,
  KEY `user_id` (`user_id`,`subscribe_type`,`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_user_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pattern` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_user_field_value` (
  `user_id` int(11) unsigned NOT NULL,
  `field_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  KEY `user_id` (`user_id`,`field_id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



ALTER TABLE  `prefix_comment` ADD  `comment_left` INT NOT NULL AFTER  `comment_pid`;
ALTER TABLE  `prefix_comment` ADD  `comment_right` INT NOT NULL AFTER  `comment_left`;
ALTER TABLE  `prefix_comment` ADD  `comment_level` INT NOT NULL AFTER  `comment_right`;
ALTER TABLE  `prefix_comment` ADD INDEX (  `comment_left` );
ALTER TABLE  `prefix_comment` ADD INDEX (  `comment_right` );
ALTER TABLE  `prefix_comment` ADD INDEX (  `comment_level` );

-- Добавляет новый тип топика 'photoset', этот запрос автоматически выполняется через инсталлятор при конвертации БД (для сохранения ваших кастомных типов топиков)
-- ALTER TABLE  `prefix_topic` CHANGE topic_type topic_type ENUM('topic','link','question','photoset') NOT NULL DEFAULT 'topic';
-- 



ALTER TABLE `prefix_stream_event`
  ADD CONSTRAINT `prefix_stream_event_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `prefix_stream_subscribe`
  ADD CONSTRAINT `prefix_stream_subscribe_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `prefix_stream_user_type`
  ADD CONSTRAINT `prefix_stream_user_type_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `prefix_topic_photo`
  ADD CONSTRAINT `prefix_topic_photo_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `prefix_topic` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `prefix_userfeed_subscribe`
  ADD CONSTRAINT `prefix_userfeed_subscribe_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `prefix_user_field_value`
  ADD CONSTRAINT `prefix_user_field_value_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `prefix_user_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_user_field_value_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;