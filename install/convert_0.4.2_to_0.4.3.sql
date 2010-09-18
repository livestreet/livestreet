ALTER TABLE  `prefix_comment` ADD  `comment_left` INT NOT NULL AFTER  `comment_pid`;
ALTER TABLE  `prefix_comment` ADD  `comment_right` INT NOT NULL AFTER  `comment_left`;
ALTER TABLE  `prefix_comment` ADD  `comment_level` INT NOT NULL AFTER  `comment_right`;
ALTER TABLE  `prefix_comment` ADD INDEX (  `comment_left` );
ALTER TABLE  `prefix_comment` ADD INDEX (  `comment_right` );
ALTER TABLE  `prefix_comment` ADD INDEX (  `comment_level` );