CREATE TABLE `prefix_userfeed_subscribe` (
  `user_id` int(11) NOT NULL,
  `subscribe_type` tinyint(4) NOT NULL,
  `target_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prefix_stream_subscribe` (
    `user_id` int(11) NOT NULL,
    `target_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prefix_stream_config` (
    `user_id` int(11) NOT NULL,
    `event_types` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prefix_stream_event` (
    `id` int(11) NOT NULL primary key auto_increment,
    `event_type` int(11) NOT NULL,
    `target_id` int(11) NOT NULL,
    `initiator` int(11) NOT NULL,
    `date_added` timestamp not null default current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prefix_user_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pattern` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prefix_user_field_value` (
  `user_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `prefix_topic_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `description` text,
  `target_tmp` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE `prefix_stream_config`;

CREATE TABLE `prefix_stream_user_type` (
  `user_id` int(11) NOT NULL,
  `event_type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `prefix_topic` CHANGE topic_type topic_type ENUM('topic','link','question','photoset') NOT NULL DEFAULT 'topic';

ALTER TABLE `prefix_stream_event` CHANGE event_type event_type varchar(100) not null;

ALTER TABLE `prefix_stream_event` CHANGE `initiator` `user_id` INT( 11 ) NOT NULL;

ALTER TABLE `prefix_topic_photo` CHANGE `topic_id` `topic_id` INT( 11 ) DEFAULT NULL;

ALTER TABLE `prefix_userfeed_subscribe` ADD INDEX ( `user_id` , `subscribe_type` , `target_id` ) ;
ALTER TABLE `prefix_stream_subscribe` ADD INDEX ( `user_id` , `target_user_id` ); 
ALTER TABLE `prefix_stream_event` ADD INDEX ( `event_type` , `user_id` ) ;
ALTER TABLE `prefix_user_field` ADD INDEX ( `name` ) ;
ALTER TABLE `prefix_user_field_value` ADD INDEX ( `user_id` , `field_id` ) ;
ALTER TABLE `prefix_user_field_value` ADD INDEX ( `field_id` ) ;
ALTER TABLE `prefix_topic_photo` ADD INDEX ( `topic_id` ) ;
ALTER TABLE `prefix_topic_photo` ADD INDEX ( `target_tmp` ) ;
ALTER TABLE `prefix_stream_user_type` ADD INDEX ( `user_id` , `event_type` ) ;

ALTER TABLE `prefix_userfeed_subscribe` CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL ;
ALTER TABLE `prefix_userfeed_subscribe` ADD FOREIGN KEY ( `user_id` ) REFERENCES `prefix_user` (
`user_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `prefix_stream_subscribe` CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL ;
ALTER TABLE `prefix_stream_subscribe` ADD FOREIGN KEY ( `user_id` ) REFERENCES `prefix_user` (
`user_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `prefix_user_field_value` CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL ;
ALTER TABLE `prefix_user_field_value` ADD FOREIGN KEY ( `user_id` ) REFERENCES `prefix_user` (
`user_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `prefix_topic_photo` CHANGE `topic_id` `topic_id` INT( 11 ) UNSIGNED NOT NULL ;
ALTER TABLE `prefix_topic_photo` ADD FOREIGN KEY ( `topic_id` ) REFERENCES `prefix_topic` (
`topic_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `prefix_stream_user_type` CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL ;
ALTER TABLE `prefix_stream_user_type` ADD FOREIGN KEY ( `user_id` ) REFERENCES `prefix_user` (
`user_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `prefix_user_field_value` ADD FOREIGN KEY ( `field_id` ) REFERENCES `prefix_user_field` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `prefix_stream_event` CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL ;
ALTER TABLE `prefix_stream_event` ADD INDEX ( `user_id` ) ;
ALTER TABLE `prefix_stream_event` ADD FOREIGN KEY ( `user_id` ) REFERENCES `prefix_user` (
`user_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
