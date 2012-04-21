ALTER TABLE `prefix_topic` ADD `topic_count_favourite` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `topic_count_comment`;
ALTER TABLE `prefix_comment` ADD `comment_count_favourite` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `comment_count_vote`;

ALTER TABLE `prefix_topic` ADD `topic_count_vote_up` INT NOT NULL DEFAULT '0' AFTER `topic_count_vote` ,
ADD `topic_count_vote_down` INT NOT NULL DEFAULT '0' AFTER `topic_count_vote_up` ,
ADD `topic_count_vote_abstain` INT NOT NULL DEFAULT '0' AFTER `topic_count_vote_down`;

ALTER TABLE `prefix_blog` ADD `blog_count_topic` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `blog_count_user` ,
ADD INDEX ( `blog_count_topic` );

CREATE TABLE IF NOT EXISTS `prefix_subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_type` varchar(20) NOT NULL,
  `target_id` int(11) DEFAULT NULL,
  `mail` varchar(50) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_remove` datetime DEFAULT NULL,
  `ip` varchar(20) NOT NULL,
  `key` varchar(32) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `type` (`target_type`),
  KEY `mail` (`mail`),
  KEY `status` (`status`),
  KEY `key` (`key`),
  KEY `target_id` (`target_id`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `wall_user_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `count_reply` int(11) NOT NULL DEFAULT '0',
  `last_reply` varchar(100) NOT NULL,
  `date_add` datetime NOT NULL,
  `ip` varchar(20) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `wall_user_id` (`wall_user_id`),
  KEY `ip` (`ip`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `prefix_wall`
  ADD CONSTRAINT `prefix_wall_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_wall_ibfk_1` FOREIGN KEY (`wall_user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `prefix_user_field` ADD `type` VARCHAR( 50 ) NOT NULL AFTER `id` ,
ADD INDEX ( `type` );


CREATE TABLE IF NOT EXISTS `prefix_user_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_user_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `text` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `target_user_id` (`target_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `prefix_user_note`
  ADD CONSTRAINT `prefix_user_note_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prefix_user_note_ibfk_1` FOREIGN KEY (`target_user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;



ALTER TABLE `prefix_favourite` ADD `tags` VARCHAR( 250 ) NOT NULL;
CREATE TABLE IF NOT EXISTS `prefix_favourite_tag` (
  `user_id` int(10) unsigned NOT NULL,
  `target_id` int(11) NOT NULL,
  `target_type` enum('topic','comment','talk') NOT NULL,
  `is_user` tinyint(1) NOT NULL DEFAULT '0',
  `text` varchar(50) NOT NULL,
  KEY `user_id_target_type_id` (`user_id`,`target_type`,`target_id`),
  KEY `target_type_id` (`target_type`,`target_id`),
  KEY `is_user` (`is_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `prefix_favourite_tag`
  ADD CONSTRAINT `prefix_favourite_tag_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `prefix_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `prefix_topic` ADD INDEX ( `topic_count_comment` );

ALTER TABLE `prefix_talk` ADD `talk_user_id_last` INT NOT NULL AFTER `talk_date_last` ,
ADD INDEX ( `talk_user_id_last` );

ALTER TABLE `prefix_talk` ADD `talk_comment_id_last` INT NULL DEFAULT NULL AFTER `talk_user_ip`;
ALTER TABLE `prefix_talk_user` ADD INDEX ( `comment_count_new` );

DROP TABLE `prefix_country_user`;
DROP TABLE `prefix_country`;
DROP TABLE `prefix_city_user`;
DROP TABLE `prefix_city`;

INSERT INTO `prefix_user_field` (`type`, `name`, `title`, `pattern`) VALUES
('contact', 'phone', 'Телефон', ''),
('contact', 'mail', 'E-mail', '<a href="mailto:{*}" rel="nofollow">{*}</a>'),
('contact', 'skype', 'Skype', '<a href="skype:{*}" rel="nofollow">{*}</a>'),
('contact', 'icq', 'ICQ', '<a href="http://www.icq.com/people/about_me.php?uin={*}" rel="nofollow">{*}</a>'),
('contact', 'www', 'Сайт', '<a href="http://{*}" rel="nofollow">{*}</a>'),
('social', 'twitter', 'Twitter', '<a href="http://twitter.com/{*}/" rel="nofollow">{*}</a>'),
('social', 'facebook', 'Facebook', '<a href="http://facebook.com/{*}" rel="nofollow">{*}</a>'),
('social', 'vkontakte', 'ВКонтакте', '<a href="http://vk.com/{*}" rel="nofollow">{*}</a>'),
('social', 'odnoklassniki', 'Одноклассники', '<a href="http://www.odnoklassniki.ru/profile/{*}/" rel="nofollow">{*}</a>');

ALTER TABLE `prefix_favourite_tag` ADD INDEX ( `text` );

ALTER TABLE `prefix_vote` ADD `vote_ip` VARCHAR( 15 ) NOT NULL DEFAULT '',
ADD INDEX ( `vote_ip` );