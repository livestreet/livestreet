--
-- Структура таблицы `prefix_session`
--

CREATE TABLE IF NOT EXISTS `prefix_session` (
  `session_key` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
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