--
-- Структура таблицы `prefix_geo_city`
--

CREATE TABLE IF NOT EXISTS `prefix_geo_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `name_ru` varchar(50) NOT NULL,
  `name_en` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `country_id` (`country_id`),
  KEY `region_id` (`region_id`),
  KEY `name_ru` (`name_ru`),
  KEY `name_en` (`name_en`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17590 ;


--
-- Структура таблицы `prefix_geo_country`
--

CREATE TABLE IF NOT EXISTS `prefix_geo_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_ru` varchar(50) NOT NULL,
  `name_en` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `name_ru` (`name_ru`),
  KEY `name_en` (`name_en`),
  KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=219 ;

--
-- Структура таблицы `prefix_geo_region`
--

CREATE TABLE IF NOT EXISTS `prefix_geo_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name_ru` varchar(50) NOT NULL,
  `name_en` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `country_id` (`country_id`),
  KEY `name_ru` (`name_ru`),
  KEY `name_en` (`name_en`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1612 ;



--
-- Структура таблицы `prefix_geo_target`
--

CREATE TABLE IF NOT EXISTS `prefix_geo_target` (
  `geo_type` varchar(20) NOT NULL,
  `geo_id` int(11) NOT NULL,
  `target_type` varchar(20) NOT NULL,
  `target_id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`geo_type`,`geo_id`,`target_type`,`target_id`),
  KEY `target_type` (`target_type`,`target_id`),
  KEY `country_id` (`country_id`),
  KEY `region_id` (`region_id`),
  KEY `city_id` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_geo_target`
--


--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `prefix_geo_city`
--
ALTER TABLE `prefix_geo_city`
  ADD CONSTRAINT `prefix_geo_city_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `prefix_geo_region` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_geo_city_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `prefix_geo_country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_geo_region`
--
ALTER TABLE `prefix_geo_region`
  ADD CONSTRAINT `prefix_geo_region_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `prefix_geo_country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prefix_geo_target`
--
ALTER TABLE `prefix_geo_target`
  ADD CONSTRAINT `prefix_geo_target_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `prefix_geo_city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_geo_target_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `prefix_geo_country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_geo_target_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `prefix_geo_region` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
