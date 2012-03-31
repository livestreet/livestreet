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
  `wall_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `count_reply` int(11) NOT NULL DEFAULT '0',
  `last_reply` varchar(100) NOT NULL,
  `date_add` datetime NOT NULL,
  `ip` varchar(20) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `wall_user_id` (`wall_user_id`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `prefix_user_field` ADD `type` VARCHAR( 50 ) NOT NULL AFTER `id` ,
ADD INDEX ( `type` );


CREATE TABLE IF NOT EXISTS `prefix_user_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `target_user_id` (`target_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


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

ALTER TABLE `prefix_topic` ADD INDEX ( `topic_count_comment` );