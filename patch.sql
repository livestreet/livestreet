CREATE TABLE `soc_userfeed_subscribe` (
  `user_id` int(11) NOT NULL,
  `subscribe_type` tinyint(4) NOT NULL,
  `target_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8
