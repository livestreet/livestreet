ALTER TABLE `prefix_topic` ADD `topic_count_favourite` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `topic_count_comment`;
ALTER TABLE `prefix_comment` ADD `comment_count_favourite` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `comment_count_vote`;
ALTER TABLE `prefix_favourite` DROP INDEX `user_id_target_id_type`, ADD PRIMARY KEY  USING BTREE(`user_id`, `target_id`, `target_type`);
