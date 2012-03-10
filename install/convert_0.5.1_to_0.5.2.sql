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