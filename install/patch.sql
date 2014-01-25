ALTER TABLE `prefix_subscribe` ADD `user_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL AFTER `target_id` ,
ADD INDEX ( `user_id` ) ;


CREATE TABLE IF NOT EXISTS `prefix_blog_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `url` varchar(100) NOT NULL,
  `url_full` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `count_blogs` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `count_blogs` (`count_blogs`),
  KEY `title` (`title`),
  KEY `url` (`url`),
  KEY `url_full` (`url_full`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


ALTER TABLE `prefix_blog_category`
  ADD CONSTRAINT `prefix_blog_category_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `prefix_blog_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `prefix_blog` ADD `category_id` INT NULL DEFAULT NULL AFTER `user_owner_id` ,
ADD INDEX ( `category_id` ) ;

ALTER TABLE `prefix_blog` ADD FOREIGN KEY ( `category_id` ) REFERENCES `prefix_blog_category` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;



-- 01-10-2013

--
-- Структура таблицы `prefix_property`
--

CREATE TABLE IF NOT EXISTS `prefix_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_type` varchar(50) NOT NULL,
  `type` enum('int','float','varchar','text','checkbox','select','tags','video_link') NOT NULL DEFAULT 'text',
  `code` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `date_create` datetime NOT NULL,
  `sort` int(11) NOT NULL,
  `validate_rules` varchar(500) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `target_type` (`target_type`),
  KEY `code` (`code`),
  KEY `type` (`type`),
  KEY `date_create` (`date_create`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_property_value`
--

CREATE TABLE IF NOT EXISTS `prefix_property_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `property_type` varchar(30) NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` int(11) NOT NULL,
  `value_int` int(11) DEFAULT NULL,
  `value_float` float(11,2) DEFAULT NULL,
  `value_varchar` varchar(250) DEFAULT NULL,
  `value_text` text,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `target_type` (`target_type`),
  KEY `target_id` (`target_id`),
  KEY `value_int` (`value_int`),
  KEY `property_type` (`property_type`),
  KEY `value_float` (`value_float`),
  KEY `value_varchar` (`value_varchar`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_property_value_tag`
--

CREATE TABLE IF NOT EXISTS `prefix_property_value_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` int(11) NOT NULL,
  `text` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `target_type` (`target_type`),
  KEY `target_id` (`target_id`),
  KEY `text` (`text`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- 29-10-2013

--
-- Структура таблицы `prefix_property_select`
--

CREATE TABLE IF NOT EXISTS `prefix_property_select` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `target_type` (`target_type`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_property_value_select`
--

CREATE TABLE IF NOT EXISTS `prefix_property_value_select` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` int(11) NOT NULL,
  `select_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `target_type` (`target_type`),
  KEY `target_id` (`target_id`),
  KEY `property_id` (`property_id`),
  KEY `select_id` (`select_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `prefix_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_name` varchar(500) NOT NULL,
  `file_size` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `file_size` (`file_size`),
  KEY `width` (`width`),
  KEY `height` (`height`),
  KEY `date_add` (`date_add`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_media_target`
--

CREATE TABLE IF NOT EXISTS `prefix_media_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `target_id` int(11) DEFAULT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_tmp` varchar(50) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `media_id` (`media_id`),
  KEY `target_id` (`target_id`),
  KEY `target_type` (`target_type`),
  KEY `target_tmp` (`target_tmp`),
  KEY `date_add` (`date_add`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_media_target`
--
ALTER TABLE `prefix_media_target`
  ADD CONSTRAINT `prefix_media_target_ibfk_1` FOREIGN KEY (`media_id`) REFERENCES `prefix_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


-- 10-01-2014
ALTER TABLE `prefix_topic` CHANGE `topic_type` `topic_type` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'topic';

-- 11-01-2014
CREATE TABLE IF NOT EXISTS `prefix_topic_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `name_many` varchar(250) NOT NULL,
  `code` varchar(50) NOT NULL,
  `allow_remove` tinyint(1) NOT NULL DEFAULT '0',
  `date_create` datetime NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1',
  `params` text,
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_topic_type`
--
INSERT INTO `prefix_topic_type` (`id`, `name`, `name_many`, `code`, `allow_remove`, `date_create`, `state`, `params`) VALUES
(1, 'Топик', 'Топики', 'topic', 0, '2014-01-11 00:00:00', 1, NULL);

-- 12.01.2014
ALTER TABLE `prefix_topic_type` ADD `sort` INT NOT NULL DEFAULT '0' AFTER `state` ,
ADD INDEX ( `sort` ) ;

-- 12.01.2014
ALTER TABLE `prefix_property` ADD `description` VARCHAR( 500 ) NOT NULL AFTER `title` ;

-- 23.01.2014
--
-- Структура таблицы `prefix_property_target`
--

CREATE TABLE IF NOT EXISTS `prefix_property_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` datetime DEFAULT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `date_create` (`date_create`),
  KEY `date_update` (`date_update`),
  KEY `state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- 25.01.2014
--
-- Структура таблицы `prefix_user_complaint`
--

CREATE TABLE IF NOT EXISTS `prefix_user_complaint` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`target_user_id` int(11) unsigned NOT NULL,
	`user_id` int(11) unsigned NOT NULL,
	`type` varchar(50) NOT NULL,
	`text` text NOT NULL,
	`date_add` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`),
	KEY `target_user_id` (`target_user_id`),
	KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_user_complaint`
--
ALTER TABLE `prefix_user_complaint`
ADD CONSTRAINT `prefix_user_complaint_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `prefix_user_complaint_ibfk_1` FOREIGN KEY (`target_user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
