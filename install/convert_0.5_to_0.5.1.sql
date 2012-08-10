ALTER TABLE `prefix_stream_event` ADD `publish` TINYINT( 1 ) NOT NULL DEFAULT '1', ADD INDEX ( `publish` ) ;
ALTER TABLE `prefix_stream_event` ADD INDEX ( `target_id` ) ;
ALTER TABLE `prefix_comment` CHANGE `comment_left` `comment_left` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `prefix_comment` CHANGE `comment_right` `comment_right` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `prefix_comment` CHANGE `comment_level` `comment_level` INT( 11 ) NOT NULL DEFAULT '0';