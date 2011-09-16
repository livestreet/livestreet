ALTER TABLE `prefix_stream_event` ADD `publish` TINYINT( 1 ) NOT NULL DEFAULT '1', ADD INDEX ( `publish` ) ;
ALTER TABLE `prefix_stream_event` ADD INDEX ( `target_id` ) ;