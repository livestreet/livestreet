ALTER TABLE `prefix_subscribe` ADD `user_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL AFTER `target_id` ,
ADD INDEX ( `user_id` ) ;