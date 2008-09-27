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

  
  
-- Меняем индекс в таблице комментов  
ALTER TABLE `prefix_topic_comment` DROP INDEX `comment_date_rating`    ;
ALTER TABLE `prefix_topic_comment` ADD INDEX `rating_date_id` ( `comment_rating` , `comment_date` , `comment_id` ) ;   
ALTER TABLE `prefix_topic_comment` ADD INDEX ( `comment_date` )  ;
  
  
   
  
  
  
--
-- ВНИМАНИЕ!!! То что ниже нужно выполнить только после запуска скрипта convert.php !!!! иначе УДАЛЯТСЯ ВСЕ ТОПИКИ!!!!!
--  

ALTER TABLE `prefix_topic` DROP `topic_text`  ;
ALTER TABLE `prefix_topic` DROP `topic_text_short`  ;
ALTER TABLE `prefix_topic` DROP `topic_text_source`  ;
ALTER TABLE `prefix_topic` CHANGE `topic_tags` `topic_tags` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'через запятую перечислены теги' ;