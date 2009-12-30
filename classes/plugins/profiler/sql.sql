CREATE TABLE IF NOT EXISTS `prefix_profiler` (
 `request_date` DATETIME NOT NULL ,
 `request_id` VARCHAR( 32 ) NOT NULL ,
 `time_full` DOUBLE( 9,6 ) NOT NULL ,
 `time_start` DOUBLE( 17,7 ) NOT NULL ,
 `time_stop` DOUBLE( 17,7 ) NOT NULL ,
 `time_id` INT NOT NULL ,
 `time_pid` INT NOT NULL ,
 `time_name` VARCHAR( 250 ) NOT NULL ,
 `time_comment` VARCHAR( 250 ) NOT NULL,
 PRIMARY KEY (`request_id` ,  `time_id`)
);