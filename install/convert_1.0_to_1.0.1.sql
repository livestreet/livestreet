ALTER TABLE `prefix_user` ADD `user_settings_timezone` VARCHAR( 6 ) NULL DEFAULT NULL AFTER `user_settings_notice_new_friend`;

--
-- Структура таблицы `prefix_user_changemail`
--

CREATE TABLE IF NOT EXISTS `prefix_user_changemail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `date_add` datetime NOT NULL,
  `date_used` datetime DEFAULT NULL,
  `date_expired` datetime NOT NULL,
  `mail_from` varchar(50) NOT NULL,
  `mail_to` varchar(50) NOT NULL,
  `code_from` varchar(32) NOT NULL,
  `code_to` varchar(32) NOT NULL,
  `confirm_from` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_to` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `code_from` (`code_from`),
  KEY `code_to` (`code_to`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_user_changemail`
--
ALTER TABLE `prefix_user_changemail`
  ADD CONSTRAINT `prefix_user_changemail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;