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