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