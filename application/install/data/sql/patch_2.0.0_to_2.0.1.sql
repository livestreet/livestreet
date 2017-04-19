-- 01.02.2017
ALTER TABLE `prefix_user` CHANGE `user_password` `user_password` VARCHAR(255) NOT NULL;

-- 15.04.2017
ALTER TABLE `prefix_topic_content` CHANGE `topic_text_source` `topic_text_source` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `prefix_topic_content` CHANGE `topic_text` `topic_text` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `prefix_topic_content` CHANGE `topic_text_short` `topic_text_short` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `prefix_topic_content` CHANGE `topic_extra` `topic_extra` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `prefix_talk` CHANGE `talk_text` `talk_text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `prefix_property_value` CHANGE `value_text` `value_text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
ALTER TABLE `prefix_blog` CHANGE `blog_description` `blog_description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `prefix_comment` CHANGE `comment_text` `comment_text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `prefix_comment` CHANGE `comment_text_source` `comment_text_source` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `prefix_wall` CHANGE `text` `text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;