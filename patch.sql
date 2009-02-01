-------------------------------------------------
------------------Changeset [96]-----------------
-------------------------------------------------

--
-- Структура таблицы `prefix_reminder`
--

CREATE TABLE IF NOT EXISTS `prefix_reminder` (
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
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_reminder`
--
ALTER TABLE `prefix_reminder`
  ADD CONSTRAINT `prefix_reminder_fk` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;






ALTER TABLE `prefix_user` ADD `user_profile_foto` VARCHAR( 250 ) NULL AFTER `user_profile_avatar_type` ;

ALTER TABLE `prefix_topic_read` ADD `comment_id_last` INT( 11 ) NOT NULL DEFAULT '0' AFTER `comment_count_last` ;

ALTER TABLE `prefix_talk` ADD `talk_date_last` DATETIME NOT NULL AFTER `talk_date` ;