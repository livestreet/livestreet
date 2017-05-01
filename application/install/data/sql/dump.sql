--
-- База данных LiveStreet CMS 2.0.0
--

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_blog`
--

CREATE TABLE IF NOT EXISTS `prefix_blog` (
  `blog_id` int(11) unsigned NOT NULL,
  `user_owner_id` int(11) unsigned NOT NULL,
  `blog_title` varchar(200) NOT NULL,
  `blog_description` text NOT NULL,
  `blog_type` varchar(50) DEFAULT 'personal',
  `blog_date_add` datetime NOT NULL,
  `blog_date_edit` datetime DEFAULT NULL,
  `blog_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `blog_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `blog_count_user` int(11) unsigned NOT NULL DEFAULT '0',
  `blog_count_topic` int(10) unsigned NOT NULL DEFAULT '0',
  `blog_limit_rating_topic` float(9,3) NOT NULL DEFAULT '0.000',
  `blog_url` varchar(200) DEFAULT NULL,
  `blog_avatar` varchar(250) DEFAULT NULL,
  `blog_skip_index` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_blog`
--

INSERT INTO `prefix_blog` (`blog_id`, `user_owner_id`, `blog_title`, `blog_description`, `blog_type`, `blog_date_add`, `blog_date_edit`, `blog_rating`, `blog_count_vote`, `blog_count_user`, `blog_count_topic`, `blog_limit_rating_topic`, `blog_url`, `blog_avatar`, `blog_skip_index`) VALUES
(1, 1, 'Blog by admin', 'This is your personal blog.', 'personal', NOW(), NULL, 0.000, 0, 0, 0, -1000.000, NULL, '0', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_blog_user`
--

CREATE TABLE IF NOT EXISTS `prefix_blog_user` (
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `user_role` int(3) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_category`
--

CREATE TABLE IF NOT EXISTS `prefix_category` (
  `id` int(11) unsigned NOT NULL,
  `pid` int(11) unsigned DEFAULT NULL,
  `type_id` int(11) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(250) NOT NULL,
  `url_full` varchar(250) NOT NULL,
  `date_create` datetime NOT NULL,
  `order` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `count_target` int(11) NOT NULL DEFAULT '0',
  `data` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_category_target`
--

CREATE TABLE IF NOT EXISTS `prefix_category_target` (
  `id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `type_id` int(11) unsigned NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` int(11) NOT NULL,
  `date_create` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_category_type`
--

CREATE TABLE IF NOT EXISTS `prefix_category_type` (
  `id` int(11) unsigned NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `date_create` datetime NOT NULL,
  `date_update` datetime DEFAULT NULL,
  `params` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_category_type`
--

INSERT INTO `prefix_category_type` (`id`, `target_type`, `title`, `state`, `date_create`, `date_update`, `params`) VALUES
(1, 'blog', 'Блоги', 1, NOW(), NULL, '');

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_comment`
--

CREATE TABLE IF NOT EXISTS `prefix_comment` (
  `comment_id` int(11) unsigned NOT NULL,
  `comment_pid` int(11) unsigned DEFAULT NULL,
  `comment_left` int(11) NOT NULL DEFAULT '0',
  `comment_right` int(11) NOT NULL DEFAULT '0',
  `comment_level` int(11) NOT NULL DEFAULT '0',
  `target_id` int(11) unsigned DEFAULT NULL,
  `target_type` varchar(50) NOT NULL DEFAULT 'topic',
  `target_parent_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL,
  `comment_text` text NOT NULL,
  `comment_text_source` text NOT NULL,
  `comment_text_hash` varchar(32) NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_date_edit` datetime DEFAULT NULL,
  `comment_user_ip` varchar(40) NOT NULL,
  `comment_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `comment_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count_favourite` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count_edit` int(11) NOT NULL DEFAULT '0',
  `comment_delete` tinyint(4) NOT NULL DEFAULT '0',
  `comment_publish` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_comment_online`
--

CREATE TABLE IF NOT EXISTS `prefix_comment_online` (
  `comment_online_id` int(11) unsigned NOT NULL,
  `target_id` int(11) unsigned DEFAULT NULL,
  `target_type` varchar(50) NOT NULL DEFAULT 'topic',
  `target_parent_id` int(11) NOT NULL DEFAULT '0',
  `comment_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_cron_task`
--

CREATE TABLE IF NOT EXISTS `prefix_cron_task` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(500) NOT NULL,
  `method` varchar(500) NOT NULL,
  `plugin` varchar(50) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `count_run` int(11) NOT NULL DEFAULT '0',
  `period_run` int(11) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_run_last` datetime DEFAULT NULL,
  `time_start` time DEFAULT NULL,
  `time_end` time DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_cron_task`
--

INSERT INTO `prefix_cron_task` (`id`, `title`, `method`, `plugin`, `state`, `count_run`, `period_run`, `date_create`, `date_run_last`) VALUES
(1, 'Отложенная отправка емайлов', 'Tools_SystemTaskNotify', '', 1, 0, 2, NOW(), NULL),
(2, 'Удаление старого кеша данных', 'Cache_ClearOldCache', '', 1, 0, 1500, NOW(), NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_favourite`
--

CREATE TABLE IF NOT EXISTS `prefix_favourite` (
  `user_id` int(11) unsigned NOT NULL,
  `target_id` int(11) unsigned DEFAULT NULL,
  `target_type` varchar(50) DEFAULT 'topic',
  `target_publish` tinyint(1) DEFAULT '1',
  `tags` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_favourite_tag`
--

CREATE TABLE IF NOT EXISTS `prefix_favourite_tag` (
  `user_id` int(10) unsigned NOT NULL,
  `target_id` int(11) NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `is_user` tinyint(1) NOT NULL DEFAULT '0',
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_friend`
--

CREATE TABLE IF NOT EXISTS `prefix_friend` (
  `user_from` int(11) unsigned NOT NULL DEFAULT '0',
  `user_to` int(11) unsigned NOT NULL DEFAULT '0',
  `status_from` int(4) NOT NULL,
  `status_to` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_invite_code`
--

CREATE TABLE IF NOT EXISTS `prefix_invite_code` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `code` varchar(32) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_expired` datetime DEFAULT NULL,
  `count_allow_use` int(11) NOT NULL DEFAULT '1',
  `count_use` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_invite_use`
--

CREATE TABLE IF NOT EXISTS `prefix_invite_use` (
  `id` int(11) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `code_id` int(11) unsigned DEFAULT NULL,
  `from_user_id` int(11) unsigned DEFAULT NULL,
  `to_user_id` int(11) unsigned NOT NULL,
  `date_create` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_media`
--

CREATE TABLE IF NOT EXISTS `prefix_media` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `type` int(11) NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_name` varchar(500) NOT NULL,
  `file_size` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_media_target`
--

CREATE TABLE IF NOT EXISTS `prefix_media_target` (
  `id` int(11) unsigned NOT NULL,
  `media_id` int(11) unsigned NOT NULL,
  `target_id` int(11) DEFAULT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_tmp` varchar(50) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `is_preview` tinyint(1) NOT NULL DEFAULT '0',
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_notify_task`
--

CREATE TABLE IF NOT EXISTS `prefix_notify_task` (
  `notify_task_id` int(10) unsigned NOT NULL,
  `user_login` varchar(30) DEFAULT NULL,
  `user_mail` varchar(50) DEFAULT NULL,
  `notify_subject` varchar(200) DEFAULT NULL,
  `notify_text` text,
  `notify_text_alt` text DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `notify_task_status` tinyint(2) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_plugin_migration`
--

CREATE TABLE IF NOT EXISTS `prefix_plugin_migration` (
  `id` int(11) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `version` varchar(50) NOT NULL,
  `date_create` datetime NOT NULL,
  `file` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_plugin_version`
--

CREATE TABLE IF NOT EXISTS `prefix_plugin_version` (
  `id` int(11) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `version` varchar(50) NOT NULL,
  `date_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_poll`
--

CREATE TABLE IF NOT EXISTS `prefix_poll` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` int(11) DEFAULT NULL,
  `target_tmp` varchar(50) DEFAULT NULL,
  `title` varchar(500) NOT NULL,
  `is_guest_allow` tinyint(1) NOT NULL DEFAULT '0',
  `is_guest_check_ip` tinyint(1) NOT NULL DEFAULT '0',
  `count_answer_max` tinyint(4) NOT NULL DEFAULT '1',
  `count_vote` int(11) NOT NULL DEFAULT '0',
  `count_abstain` int(11) NOT NULL DEFAULT '0',
  `date_create` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_poll_answer`
--

CREATE TABLE IF NOT EXISTS `prefix_poll_answer` (
  `id` int(11) unsigned NOT NULL,
  `poll_id` int(11) unsigned NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 NOT NULL,
  `count_vote` int(11) NOT NULL DEFAULT '0',
  `date_create` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_poll_vote`
--

CREATE TABLE IF NOT EXISTS `prefix_poll_vote` (
  `id` int(11) unsigned NOT NULL,
  `poll_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `guest_key` varchar(32) DEFAULT NULL,
  `ip` varchar(40) NOT NULL,
  `answers` varchar(500) NOT NULL,
  `date_create` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_property`
--

CREATE TABLE IF NOT EXISTS `prefix_property` (
  `id` int(11) unsigned NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'text',
  `code` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `validate_rules` varchar(500) DEFAULT NULL,
  `params` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_property_select`
--

CREATE TABLE IF NOT EXISTS `prefix_property_select` (
  `id` int(11) unsigned NOT NULL,
  `property_id` int(11) unsigned NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_property_target`
--

CREATE TABLE IF NOT EXISTS `prefix_property_target` (
  `id` int(11) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` datetime DEFAULT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1',
  `params` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_property_target`
--

INSERT INTO `prefix_property_target` (`id`, `type`, `date_create`, `date_update`, `state`, `params`) VALUES
(1, 'topic_topic', NOW(), NULL, 1, 'a:2:{s:6:"entity";s:23:"ModuleTopic_EntityTopic";s:4:"name";s:35:"Топик - Стандартный";}');

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_property_value`
--

CREATE TABLE IF NOT EXISTS `prefix_property_value` (
  `id` int(11) unsigned NOT NULL,
  `property_id` int(11) unsigned NOT NULL,
  `property_type` varchar(30) NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` int(11) NOT NULL,
  `value_int` int(11) DEFAULT NULL,
  `value_float` float(11,2) DEFAULT NULL,
  `value_varchar` varchar(250) DEFAULT NULL,
  `value_date` datetime DEFAULT NULL,
  `value_text` text,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_property_value_select`
--

CREATE TABLE IF NOT EXISTS `prefix_property_value_select` (
  `id` int(11) unsigned NOT NULL,
  `property_id` int(11) unsigned NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` int(11) NOT NULL,
  `select_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_property_value_tag`
--

CREATE TABLE IF NOT EXISTS `prefix_property_value_tag` (
  `id` int(11) unsigned NOT NULL,
  `property_id` int(11) unsigned NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` int(11) NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_rbac_group`
--

CREATE TABLE IF NOT EXISTS `prefix_rbac_group` (
  `id` int(11) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `date_create` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_rbac_group`
--

INSERT INTO `prefix_rbac_group` (`id`, `code`, `title`, `date_create`) VALUES
(1, 'topic', 'Топики', NOW()),
(2, 'blog', 'Блоги', NOW()),
(3, 'comment', 'Комментарии', NOW()),
(4, 'user', 'Пользователи', NOW());

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_rbac_permission`
--

CREATE TABLE IF NOT EXISTS `prefix_rbac_permission` (
  `id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `plugin` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `msg_error` varchar(250) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_rbac_permission`
--

INSERT INTO `prefix_rbac_permission` (`id`, `group_id`, `code`, `plugin`, `title`, `msg_error`, `date_create`, `state`) VALUES
(1, 1, 'create_topic', '', 'rbac.permission.create_topic.title', 'rbac.permission.create_topic.error', NOW(), 1),
(2, 2, 'create_blog', '', 'rbac.permission.create_blog.title', 'rbac.permission.create_blog.error', NOW(), 1),
(3, 1, 'create_topic_comment', '', 'rbac.permission.create_topic_comment.title', 'rbac.permission.create_topic_comment.error', NOW(), 1),
(4, 4, 'create_talk', '', 'rbac.permission.create_talk.title', 'rbac.permission.create_talk.error', NOW(), 1),
(5, 4, 'create_talk_comment', '', 'rbac.permission.create_talk_comment.title', 'rbac.permission.create_talk_comment.error', NOW(), 1),
(6, 3, 'vote_comment', '', 'rbac.permission.vote_comment.title', 'rbac.permission.vote_comment.error', NOW(), 1),
(7, 2, 'vote_blog', '', 'rbac.permission.vote_blog.title', 'rbac.permission.vote_blog.error', NOW(), 1),
(8, 1, 'vote_topic', '', 'rbac.permission.vote_topic.title', 'rbac.permission.vote_topic.error', NOW(), 1),
(9, 4, 'vote_user', '', 'rbac.permission.vote_user.title', 'rbac.permission.vote_user.error', NOW(), 1),
(10, 4, 'create_invite', '', 'rbac.permission.create_invite.title', 'rbac.permission.create_invite.error', NOW(), 1),
(11, 3, 'create_comment_favourite', '', 'rbac.permission.create_comment_favourite.title', 'rbac.permission.create_comment_favourite.error', NOW(), 1),
(12, 1, 'remove_topic', '', 'rbac.permission.remove_topic.title', 'rbac.permission.remove_topic.error', NOW(), 1);

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_rbac_role`
--

CREATE TABLE IF NOT EXISTS `prefix_rbac_role` (
  `id` int(11) unsigned NOT NULL,
  `pid` int(11) unsigned DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `date_create` datetime NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_rbac_role`
--

INSERT INTO `prefix_rbac_role` (`id`, `pid`, `code`, `title`, `date_create`, `state`) VALUES
(1, NULL, 'guest', 'Гость', NOW(), 1),
(2, NULL, 'user', 'Пользователь', NOW(), 1);

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_rbac_role_permission`
--

CREATE TABLE IF NOT EXISTS `prefix_rbac_role_permission` (
  `id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL,
  `date_create` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_rbac_role_permission`
--

INSERT INTO `prefix_rbac_role_permission` (`id`, `role_id`, `permission_id`, `date_create`) VALUES
(1, 2, 2, NOW()),
(2, 2, 7, NOW()),
(3, 2, 11, NOW()),
(4, 2, 6, NOW()),
(5, 2, 10, NOW()),
(6, 2, 4, NOW()),
(7, 2, 5, NOW()),
(8, 2, 9, NOW()),
(9, 2, 1, NOW()),
(10, 2, 3, NOW()),
(11, 2, 12, NOW()),
(12, 2, 8, NOW());

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_rbac_role_user`
--

CREATE TABLE IF NOT EXISTS `prefix_rbac_role_user` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `date_create` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_rbac_role_user`
--

INSERT INTO `prefix_rbac_role_user` (`id`, `user_id`, `role_id`, `date_create`) VALUES
(1, 1, 2, NOW());

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_reminder`
--

CREATE TABLE IF NOT EXISTS `prefix_reminder` (
  `reminder_code` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `reminder_date_add` datetime NOT NULL,
  `reminder_date_used` datetime DEFAULT NULL,
  `reminder_date_expire` datetime NOT NULL,
  `reminde_is_used` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_session`
--

CREATE TABLE IF NOT EXISTS `prefix_session` (
  `session_key` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `session_ip_create` varchar(40) NOT NULL,
  `session_ip_last` varchar(40) NOT NULL,
  `session_date_create` datetime DEFAULT NULL,
  `session_date_last` datetime NOT NULL,
  `session_date_close` datetime DEFAULT NULL,
  `session_extra` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_storage`
--

CREATE TABLE IF NOT EXISTS `prefix_storage` (
  `id` int(11) unsigned NOT NULL,
  `key` varchar(50) NOT NULL,
  `value` mediumtext NOT NULL,
  `instance` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_stream_event`
--

CREATE TABLE IF NOT EXISTS `prefix_stream_event` (
  `id` int(11) unsigned NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `target_id` int(11) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `publish` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_stream_subscribe`
--

CREATE TABLE IF NOT EXISTS `prefix_stream_subscribe` (
  `user_id` int(11) unsigned NOT NULL,
  `target_user_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_stream_user_type`
--

CREATE TABLE IF NOT EXISTS `prefix_stream_user_type` (
  `user_id` int(11) unsigned NOT NULL,
  `event_type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_subscribe`
--

CREATE TABLE IF NOT EXISTS `prefix_subscribe` (
  `id` int(11) unsigned NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` varchar(50) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `mail` varchar(50) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_remove` datetime DEFAULT NULL,
  `ip` varchar(40) NOT NULL,
  `key` varchar(32) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_talk`
--

CREATE TABLE IF NOT EXISTS `prefix_talk` (
  `talk_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `talk_title` varchar(200) NOT NULL,
  `talk_text` text NOT NULL,
  `talk_date` datetime NOT NULL,
  `talk_date_last` datetime NOT NULL,
  `talk_user_id_last` int(11) NOT NULL,
  `talk_user_ip` varchar(40) NOT NULL,
  `talk_comment_id_last` int(11) DEFAULT NULL,
  `talk_count_comment` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_talk_blacklist`
--

CREATE TABLE IF NOT EXISTS `prefix_talk_blacklist` (
  `user_id` int(10) unsigned NOT NULL,
  `user_target_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_talk_user`
--

CREATE TABLE IF NOT EXISTS `prefix_talk_user` (
  `talk_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_last` datetime DEFAULT NULL,
  `comment_id_last` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count_new` int(11) NOT NULL DEFAULT '0',
  `talk_user_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic`
--

CREATE TABLE IF NOT EXISTS `prefix_topic` (
  `topic_id` int(11) unsigned NOT NULL,
  `blog_id` int(11) unsigned NOT NULL,
  `blog_id2` int(10) unsigned DEFAULT NULL,
  `blog_id3` int(10) unsigned DEFAULT NULL,
  `blog_id4` int(10) unsigned DEFAULT NULL,
  `blog_id5` int(10) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `topic_type` varchar(50) NOT NULL DEFAULT 'topic',
  `topic_title` varchar(200) NOT NULL,
  `topic_slug` varchar(500) NOT NULL DEFAULT '',
  `topic_tags` varchar(250) NOT NULL COMMENT 'tags separated by a comma',
  `topic_date_add` datetime NOT NULL,
  `topic_date_edit` datetime DEFAULT NULL,
  `topic_date_edit_content` datetime DEFAULT NULL,
  `topic_date_publish` datetime NOT NULL,
  `topic_user_ip` varchar(40) NOT NULL,
  `topic_publish` tinyint(1) NOT NULL DEFAULT '0',
  `topic_publish_draft` tinyint(1) NOT NULL DEFAULT '1',
  `topic_publish_index` tinyint(1) NOT NULL DEFAULT '0',
  `topic_skip_index` tinyint(1) NOT NULL DEFAULT '0',
  `topic_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `topic_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_count_vote_up` int(11) NOT NULL DEFAULT '0',
  `topic_count_vote_down` int(11) NOT NULL DEFAULT '0',
  `topic_count_vote_abstain` int(11) NOT NULL DEFAULT '0',
  `topic_count_read` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_count_comment` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_count_favourite` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_cut_text` varchar(100) DEFAULT NULL,
  `topic_forbid_comment` tinyint(1) NOT NULL DEFAULT '0',
  `topic_text_hash` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_content`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_content` (
  `topic_id` int(11) unsigned NOT NULL,
  `topic_text` longtext NOT NULL,
  `topic_text_short` text NOT NULL,
  `topic_text_source` longtext NOT NULL,
  `topic_extra` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_read`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_read` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_read` datetime NOT NULL,
  `comment_count_last` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_id_last` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_tag`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_tag` (
  `topic_tag_id` int(11) unsigned NOT NULL,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `blog_id` int(11) unsigned NOT NULL,
  `topic_tag_text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_topic_type`
--

CREATE TABLE IF NOT EXISTS `prefix_topic_type` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `name_many` varchar(250) NOT NULL,
  `code` varchar(50) NOT NULL,
  `allow_remove` tinyint(1) NOT NULL DEFAULT '0',
  `date_create` datetime NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '0',
  `params` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_topic_type`
--

INSERT INTO `prefix_topic_type` (`id`, `name`, `name_many`, `code`, `allow_remove`, `date_create`, `state`, `sort`, `params`) VALUES
(1, 'Топик', 'Топики', 'topic', 0, NOW(), 1, 0, 'a:3:{s:10:"allow_poll";b:1;s:10:"allow_text";b:1;s:10:"allow_tags";b:1;}');

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user`
--

CREATE TABLE IF NOT EXISTS `prefix_user` (
  `user_id` int(11) unsigned NOT NULL,
  `user_login` varchar(30) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_mail` varchar(50) DEFAULT NULL,
  `user_admin` tinyint(1) NOT NULL DEFAULT '0',
  `user_skill` float(9,3) unsigned NOT NULL DEFAULT '0.000',
  `user_date_register` datetime NOT NULL,
  `user_date_activate` datetime DEFAULT NULL,
  `user_date_comment_last` datetime DEFAULT NULL,
  `user_ip_register` varchar(40) NOT NULL,
  `user_rating` float(9,3) NOT NULL DEFAULT '0.000',
  `user_count_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `user_activate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_activate_key` varchar(32) DEFAULT NULL,
  `user_referral_code` varchar(32) DEFAULT NULL,
  `user_profile_name` varchar(50) DEFAULT NULL,
  `user_profile_sex` varchar(50) NOT NULL DEFAULT 'other',
  `user_profile_country` varchar(30) DEFAULT NULL,
  `user_profile_region` varchar(30) DEFAULT NULL,
  `user_profile_city` varchar(30) DEFAULT NULL,
  `user_profile_birthday` datetime DEFAULT NULL,
  `user_profile_about` text,
  `user_profile_date` datetime DEFAULT NULL,
  `user_profile_avatar` varchar(250) DEFAULT NULL,
  `user_profile_foto` varchar(250) DEFAULT NULL,
  `user_settings_notice_new_topic` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_new_comment` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_new_talk` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_reply_comment` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_notice_new_friend` tinyint(1) NOT NULL DEFAULT '1',
  `user_settings_timezone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_user`
--

INSERT INTO `prefix_user` (`user_id`, `user_login`, `user_password`, `user_mail`, `user_admin`, `user_skill`, `user_date_register`, `user_date_activate`, `user_date_comment_last`, `user_ip_register`, `user_rating`, `user_count_vote`, `user_activate`, `user_activate_key`, `user_referral_code`, `user_profile_name`, `user_profile_sex`, `user_profile_country`, `user_profile_region`, `user_profile_city`, `user_profile_birthday`, `user_profile_about`, `user_profile_date`, `user_profile_avatar`, `user_profile_foto`, `user_settings_notice_new_topic`, `user_settings_notice_new_comment`, `user_settings_notice_new_talk`, `user_settings_notice_reply_comment`, `user_settings_notice_new_friend`, `user_settings_timezone`) VALUES
(1, 'admin', '', 'admin@admin.adm', 1, 0.000, NOW(), NULL, NULL, '127.0.0.1', 0.000, 0, 1, NULL, NULL, NULL, 'other', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, 1, 1, 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_userfeed_subscribe`
--

CREATE TABLE IF NOT EXISTS `prefix_userfeed_subscribe` (
  `user_id` int(11) unsigned NOT NULL,
  `subscribe_type` tinyint(4) NOT NULL,
  `target_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user_changemail`
--

CREATE TABLE IF NOT EXISTS `prefix_user_changemail` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date_add` datetime NOT NULL,
  `date_used` datetime DEFAULT NULL,
  `date_expired` datetime NOT NULL,
  `mail_from` varchar(50) NOT NULL,
  `mail_to` varchar(50) NOT NULL,
  `code_from` varchar(32) NOT NULL,
  `code_to` varchar(32) NOT NULL,
  `confirm_from` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_to` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user_complaint`
--

CREATE TABLE IF NOT EXISTS `prefix_user_complaint` (
  `id` int(11) unsigned NOT NULL,
  `target_user_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `date_add` datetime NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user_field`
--

CREATE TABLE IF NOT EXISTS `prefix_user_field` (
  `id` int(11) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pattern` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_user_field`
--

INSERT INTO `prefix_user_field` (`id`, `type`, `name`, `title`, `pattern`) VALUES
(1, 'contact', 'phone', 'Телефон', ''),
(2, 'contact', 'mail', 'E-mail', '<a href="mailto:{*}" rel="nofollow">{*}</a>'),
(3, 'contact', 'skype', 'Skype', '<a href="skype:{*}" rel="nofollow">{*}</a>'),
(4, 'contact', 'icq', 'ICQ', '<a href="http://www.icq.com/people/about_me.php?uin={*}" rel="nofollow">{*}</a>'),
(5, 'contact', 'jabber', 'Jabber', '<a href="xmpp:{*}" rel="nofollow">{*}</a>'),
(6, 'contact', 'www', 'Сайт', '<a href="http://{*}" rel="nofollow">{*}</a>'),
(7, 'social', 'twitter', 'Twitter', '<a href="http://twitter.com/{*}/" rel="nofollow">{*}</a>'),
(8, 'social', 'facebook', 'Facebook', '<a href="http://facebook.com/{*}" rel="nofollow">{*}</a>'),
(9, 'social', 'vkontakte', 'ВКонтакте', '<a href="http://vk.com/{*}" rel="nofollow">{*}</a>'),
(10, 'social', 'odnoklassniki', 'Одноклассники', '<a href="http://www.odnoklassniki.ru/profile/{*}/" rel="nofollow">{*}</a>');

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user_field_value`
--

CREATE TABLE IF NOT EXISTS `prefix_user_field_value` (
  `user_id` int(11) unsigned NOT NULL,
  `field_id` int(11) unsigned DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_user_note`
--

CREATE TABLE IF NOT EXISTS `prefix_user_note` (
  `id` int(11) unsigned NOT NULL,
  `target_user_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `text` text NOT NULL,
  `date_add` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_vote`
--

CREATE TABLE IF NOT EXISTS `prefix_vote` (
  `target_id` int(11) unsigned NOT NULL DEFAULT '0',
  `target_type` varchar(50) NOT NULL DEFAULT 'topic',
  `user_voter_id` int(11) unsigned NOT NULL,
  `vote_direction` tinyint(2) DEFAULT '0',
  `vote_value` float(9,3) NOT NULL DEFAULT '0.000',
  `vote_date` datetime NOT NULL,
  `vote_ip` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_wall`
--

CREATE TABLE IF NOT EXISTS `prefix_wall` (
  `id` int(11) unsigned NOT NULL,
  `pid` int(11) unsigned DEFAULT NULL,
  `wall_user_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `count_reply` int(11) NOT NULL DEFAULT '0',
  `last_reply` varchar(100) NOT NULL,
  `date_add` datetime NOT NULL,
  `ip` varchar(40) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `prefix_blog`
--
ALTER TABLE `prefix_blog`
  ADD PRIMARY KEY (`blog_id`),
  ADD KEY `user_owner_id` (`user_owner_id`),
  ADD KEY `blog_type` (`blog_type`),
  ADD KEY `blog_url` (`blog_url`),
  ADD KEY `blog_title` (`blog_title`),
  ADD KEY `blog_count_topic` (`blog_count_topic`),
  ADD KEY `blog_skip_index` (`blog_skip_index`);

--
-- Индексы таблицы `prefix_blog_user`
--
ALTER TABLE `prefix_blog_user`
  ADD UNIQUE KEY `blog_id_user_id_uniq` (`blog_id`,`user_id`),
  ADD KEY `blog_id` (`blog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `prefix_category`
--
ALTER TABLE `prefix_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `title` (`title`),
  ADD KEY `order` (`order`),
  ADD KEY `state` (`state`),
  ADD KEY `url` (`url`),
  ADD KEY `url_full` (`url_full`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `count_target` (`count_target`);

--
-- Индексы таблицы `prefix_category_target`
--
ALTER TABLE `prefix_category_target`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_type` (`target_type`),
  ADD KEY `target_id` (`target_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Индексы таблицы `prefix_category_type`
--
ALTER TABLE `prefix_category_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `state` (`state`),
  ADD KEY `target_type` (`target_type`);

--
-- Индексы таблицы `prefix_comment`
--
ALTER TABLE `prefix_comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_pid` (`comment_pid`),
  ADD KEY `type_date_rating` (`target_type`,`comment_date`,`comment_rating`),
  ADD KEY `id_type` (`target_id`,`target_type`),
  ADD KEY `type_delete_publish` (`target_type`,`comment_delete`,`comment_publish`),
  ADD KEY `user_type` (`user_id`,`target_type`),
  ADD KEY `target_parent_id` (`target_parent_id`),
  ADD KEY `comment_left` (`comment_left`),
  ADD KEY `comment_right` (`comment_right`),
  ADD KEY `comment_level` (`comment_level`),
  ADD KEY `comment_date_edit` (`comment_date_edit`),
  ADD KEY `comment_count_edit` (`comment_count_edit`);

--
-- Индексы таблицы `prefix_comment_online`
--
ALTER TABLE `prefix_comment_online`
  ADD PRIMARY KEY (`comment_online_id`),
  ADD UNIQUE KEY `id_type` (`target_id`,`target_type`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `type_parent` (`target_type`,`target_parent_id`);

--
-- Индексы таблицы `prefix_cron_task`
--
ALTER TABLE `prefix_cron_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `count_run` (`count_run`),
  ADD KEY `state` (`state`),
  ADD KEY `plugin` (`plugin`),
  ADD KEY `time_start` (`time_start`),
  ADD KEY `time_end` (`time_end`),
  ADD KEY `method` (`method`(255)),
  ADD KEY `period_run` (`period_run`);

--
-- Индексы таблицы `prefix_favourite`
--
ALTER TABLE `prefix_favourite`
  ADD UNIQUE KEY `user_id_target_id_type` (`user_id`,`target_id`,`target_type`),
  ADD KEY `target_publish` (`target_publish`),
  ADD KEY `id_type` (`target_id`,`target_type`);

--
-- Индексы таблицы `prefix_favourite_tag`
--
ALTER TABLE `prefix_favourite_tag`
  ADD KEY `user_id_target_type_id` (`user_id`,`target_type`,`target_id`),
  ADD KEY `target_type_id` (`target_type`,`target_id`),
  ADD KEY `is_user` (`is_user`),
  ADD KEY `text` (`text`);

--
-- Индексы таблицы `prefix_friend`
--
ALTER TABLE `prefix_friend`
  ADD PRIMARY KEY (`user_from`,`user_to`),
  ADD KEY `user_to` (`user_to`);

--
-- Индексы таблицы `prefix_invite_code`
--
ALTER TABLE `prefix_invite_code`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `count_allow_use` (`count_allow_use`),
  ADD KEY `count_use` (`count_use`),
  ADD KEY `active` (`active`),
  ADD KEY `date_create` (`date_create`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `prefix_invite_use`
--
ALTER TABLE `prefix_invite_use`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `code_id` (`code_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`);

--
-- Индексы таблицы `prefix_media`
--
ALTER TABLE `prefix_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type` (`type`),
  ADD KEY `file_size` (`file_size`),
  ADD KEY `width` (`width`),
  ADD KEY `height` (`height`),
  ADD KEY `date_add` (`date_add`),
  ADD KEY `target_type` (`target_type`);

--
-- Индексы таблицы `prefix_media_target`
--
ALTER TABLE `prefix_media_target`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_id` (`media_id`),
  ADD KEY `target_id` (`target_id`),
  ADD KEY `target_type` (`target_type`),
  ADD KEY `target_tmp` (`target_tmp`),
  ADD KEY `date_add` (`date_add`),
  ADD KEY `is_preview` (`is_preview`);

--
-- Индексы таблицы `prefix_notify_task`
--
ALTER TABLE `prefix_notify_task`
  ADD PRIMARY KEY (`notify_task_id`),
  ADD KEY `date_created` (`date_created`);

--
-- Индексы таблицы `prefix_plugin_migration`
--
ALTER TABLE `prefix_plugin_migration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file` (`file`(255)),
  ADD KEY `code` (`code`),
  ADD KEY `version` (`version`);

--
-- Индексы таблицы `prefix_plugin_version`
--
ALTER TABLE `prefix_plugin_version`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `version` (`version`);

--
-- Индексы таблицы `prefix_poll`
--
ALTER TABLE `prefix_poll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `target_type_target_id` (`target_type`,`target_id`),
  ADD KEY `target_tmp` (`target_tmp`),
  ADD KEY `count_vote` (`count_vote`),
  ADD KEY `count_abstain` (`count_abstain`);

--
-- Индексы таблицы `prefix_poll_answer`
--
ALTER TABLE `prefix_poll_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poll_id` (`poll_id`),
  ADD KEY `count_vote` (`count_vote`);

--
-- Индексы таблицы `prefix_poll_vote`
--
ALTER TABLE `prefix_poll_vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poll_id` (`poll_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `guest_key` (`guest_key`),
  ADD KEY `ip` (`ip`);

--
-- Индексы таблицы `prefix_property`
--
ALTER TABLE `prefix_property`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_type` (`target_type`),
  ADD KEY `code` (`code`),
  ADD KEY `type` (`type`),
  ADD KEY `date_create` (`date_create`),
  ADD KEY `sort` (`sort`);

--
-- Индексы таблицы `prefix_property_select`
--
ALTER TABLE `prefix_property_select`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `target_type` (`target_type`),
  ADD KEY `sort` (`sort`);

--
-- Индексы таблицы `prefix_property_target`
--
ALTER TABLE `prefix_property_target`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `date_create` (`date_create`),
  ADD KEY `date_update` (`date_update`),
  ADD KEY `state` (`state`);

--
-- Индексы таблицы `prefix_property_value`
--
ALTER TABLE `prefix_property_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `target_type` (`target_type`),
  ADD KEY `target_id` (`target_id`),
  ADD KEY `value_int` (`value_int`),
  ADD KEY `property_type` (`property_type`),
  ADD KEY `value_float` (`value_float`),
  ADD KEY `value_varchar` (`value_varchar`),
  ADD KEY `value_date` (`value_date`);

--
-- Индексы таблицы `prefix_property_value_select`
--
ALTER TABLE `prefix_property_value_select`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_type` (`target_type`),
  ADD KEY `target_id` (`target_id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `select_id` (`select_id`);

--
-- Индексы таблицы `prefix_property_value_tag`
--
ALTER TABLE `prefix_property_value_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_type` (`target_type`),
  ADD KEY `target_id` (`target_id`),
  ADD KEY `text` (`text`),
  ADD KEY `property_id` (`property_id`);

--
-- Индексы таблицы `prefix_rbac_group`
--
ALTER TABLE `prefix_rbac_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`);

--
-- Индексы таблицы `prefix_rbac_permission`
--
ALTER TABLE `prefix_rbac_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `date_create` (`date_create`),
  ADD KEY `state` (`state`),
  ADD KEY `plugin` (`plugin`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `prefix_rbac_role`
--
ALTER TABLE `prefix_rbac_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `state` (`state`),
  ADD KEY `date_create` (`date_create`),
  ADD KEY `code` (`code`);

--
-- Индексы таблицы `prefix_rbac_role_permission`
--
ALTER TABLE `prefix_rbac_role_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`),
  ADD KEY `date_create` (`date_create`);

--
-- Индексы таблицы `prefix_rbac_role_user`
--
ALTER TABLE `prefix_rbac_role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `date_create` (`date_create`);

--
-- Индексы таблицы `prefix_reminder`
--
ALTER TABLE `prefix_reminder`
  ADD PRIMARY KEY (`reminder_code`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Индексы таблицы `prefix_session`
--
ALTER TABLE `prefix_session`
  ADD PRIMARY KEY (`session_key`),
  ADD KEY `session_date_last` (`session_date_last`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `session_date_close` (`session_date_close`);

--
-- Индексы таблицы `prefix_storage`
--
ALTER TABLE `prefix_storage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_instance` (`key`,`instance`),
  ADD KEY `instance` (`instance`);

--
-- Индексы таблицы `prefix_stream_event`
--
ALTER TABLE `prefix_stream_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_type` (`event_type`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `publish` (`publish`),
  ADD KEY `target_id` (`target_id`);

--
-- Индексы таблицы `prefix_stream_subscribe`
--
ALTER TABLE `prefix_stream_subscribe`
  ADD KEY `user_id` (`user_id`,`target_user_id`);

--
-- Индексы таблицы `prefix_stream_user_type`
--
ALTER TABLE `prefix_stream_user_type`
  ADD KEY `user_id` (`user_id`,`event_type`);

--
-- Индексы таблицы `prefix_subscribe`
--
ALTER TABLE `prefix_subscribe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`target_type`),
  ADD KEY `mail` (`mail`),
  ADD KEY `status` (`status`),
  ADD KEY `key` (`key`),
  ADD KEY `target_id` (`target_id`),
  ADD KEY `ip` (`ip`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `prefix_talk`
--
ALTER TABLE `prefix_talk`
  ADD PRIMARY KEY (`talk_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `talk_title` (`talk_title`),
  ADD KEY `talk_date` (`talk_date`),
  ADD KEY `talk_date_last` (`talk_date_last`),
  ADD KEY `talk_user_id_last` (`talk_user_id_last`);

--
-- Индексы таблицы `prefix_talk_blacklist`
--
ALTER TABLE `prefix_talk_blacklist`
  ADD PRIMARY KEY (`user_id`,`user_target_id`),
  ADD KEY `prefix_talk_blacklist_fk_target` (`user_target_id`);

--
-- Индексы таблицы `prefix_talk_user`
--
ALTER TABLE `prefix_talk_user`
  ADD UNIQUE KEY `talk_id_user_id` (`talk_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date_last` (`date_last`),
  ADD KEY `date_last_2` (`date_last`),
  ADD KEY `talk_user_active` (`talk_user_active`),
  ADD KEY `comment_count_new` (`comment_count_new`);

--
-- Индексы таблицы `prefix_topic`
--
ALTER TABLE `prefix_topic`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `blog_id` (`blog_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `topic_date_add` (`topic_date_add`),
  ADD KEY `topic_rating` (`topic_rating`),
  ADD KEY `topic_publish` (`topic_publish`),
  ADD KEY `topic_text_hash` (`topic_text_hash`),
  ADD KEY `topic_count_comment` (`topic_count_comment`),
  ADD KEY `topic_date_edit_content` (`topic_date_edit_content`),
  ADD KEY `topic_skip_index` (`topic_skip_index`),
  ADD KEY `blog_id2` (`blog_id2`),
  ADD KEY `blog_id3` (`blog_id3`),
  ADD KEY `blog_id4` (`blog_id4`),
  ADD KEY `blog_id5` (`blog_id5`),
  ADD KEY `topic_slug` (`topic_slug`(255)),
  ADD KEY `topic_date_publish` (`topic_date_publish`);

--
-- Индексы таблицы `prefix_topic_content`
--
ALTER TABLE `prefix_topic_content`
  ADD PRIMARY KEY (`topic_id`);

--
-- Индексы таблицы `prefix_topic_read`
--
ALTER TABLE `prefix_topic_read`
  ADD UNIQUE KEY `topic_id_user_id` (`topic_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `prefix_topic_tag`
--
ALTER TABLE `prefix_topic_tag`
  ADD PRIMARY KEY (`topic_tag_id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `blog_id` (`blog_id`),
  ADD KEY `topic_tag_text` (`topic_tag_text`);

--
-- Индексы таблицы `prefix_topic_type`
--
ALTER TABLE `prefix_topic_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `state` (`state`),
  ADD KEY `sort` (`sort`);

--
-- Индексы таблицы `prefix_user`
--
ALTER TABLE `prefix_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_login` (`user_login`),
  ADD UNIQUE KEY `user_mail` (`user_mail`),
  ADD KEY `user_activate_key` (`user_activate_key`),
  ADD KEY `user_activate` (`user_activate`),
  ADD KEY `user_rating` (`user_rating`),
  ADD KEY `user_profile_sex` (`user_profile_sex`),
  ADD KEY `user_admin` (`user_admin`),
  ADD KEY `user_referal_code` (`user_referral_code`);

--
-- Индексы таблицы `prefix_userfeed_subscribe`
--
ALTER TABLE `prefix_userfeed_subscribe`
  ADD KEY `user_id` (`user_id`,`subscribe_type`,`target_id`);

--
-- Индексы таблицы `prefix_user_changemail`
--
ALTER TABLE `prefix_user_changemail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `code_from` (`code_from`),
  ADD KEY `code_to` (`code_to`);

--
-- Индексы таблицы `prefix_user_complaint`
--
ALTER TABLE `prefix_user_complaint`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `target_user_id` (`target_user_id`),
  ADD KEY `type` (`type`),
  ADD KEY `state` (`state`);

--
-- Индексы таблицы `prefix_user_field`
--
ALTER TABLE `prefix_user_field`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `type` (`type`);

--
-- Индексы таблицы `prefix_user_field_value`
--
ALTER TABLE `prefix_user_field_value`
  ADD KEY `user_id` (`user_id`,`field_id`),
  ADD KEY `field_id` (`field_id`);

--
-- Индексы таблицы `prefix_user_note`
--
ALTER TABLE `prefix_user_note`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `target_user_id` (`target_user_id`);

--
-- Индексы таблицы `prefix_vote`
--
ALTER TABLE `prefix_vote`
  ADD PRIMARY KEY (`target_id`,`target_type`,`user_voter_id`),
  ADD KEY `user_voter_id` (`user_voter_id`),
  ADD KEY `vote_ip` (`vote_ip`);

--
-- Индексы таблицы `prefix_wall`
--
ALTER TABLE `prefix_wall`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `wall_user_id` (`wall_user_id`),
  ADD KEY `ip` (`ip`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `prefix_blog`
--
ALTER TABLE `prefix_blog`
  MODIFY `blog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `prefix_category`
--
ALTER TABLE `prefix_category`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_category_target`
--
ALTER TABLE `prefix_category_target`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_category_type`
--
ALTER TABLE `prefix_category_type`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `prefix_comment`
--
ALTER TABLE `prefix_comment`
  MODIFY `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_comment_online`
--
ALTER TABLE `prefix_comment_online`
  MODIFY `comment_online_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_cron_task`
--
ALTER TABLE `prefix_cron_task`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `prefix_invite_code`
--
ALTER TABLE `prefix_invite_code`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_invite_use`
--
ALTER TABLE `prefix_invite_use`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_media`
--
ALTER TABLE `prefix_media`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_media_target`
--
ALTER TABLE `prefix_media_target`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_notify_task`
--
ALTER TABLE `prefix_notify_task`
  MODIFY `notify_task_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_plugin_migration`
--
ALTER TABLE `prefix_plugin_migration`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_plugin_version`
--
ALTER TABLE `prefix_plugin_version`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_poll`
--
ALTER TABLE `prefix_poll`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_poll_answer`
--
ALTER TABLE `prefix_poll_answer`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_poll_vote`
--
ALTER TABLE `prefix_poll_vote`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_property`
--
ALTER TABLE `prefix_property`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_property_select`
--
ALTER TABLE `prefix_property_select`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_property_target`
--
ALTER TABLE `prefix_property_target`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `prefix_property_value`
--
ALTER TABLE `prefix_property_value`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_property_value_select`
--
ALTER TABLE `prefix_property_value_select`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_property_value_tag`
--
ALTER TABLE `prefix_property_value_tag`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_rbac_group`
--
ALTER TABLE `prefix_rbac_group`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `prefix_rbac_permission`
--
ALTER TABLE `prefix_rbac_permission`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT для таблицы `prefix_rbac_role`
--
ALTER TABLE `prefix_rbac_role`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `prefix_rbac_role_permission`
--
ALTER TABLE `prefix_rbac_role_permission`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT для таблицы `prefix_rbac_role_user`
--
ALTER TABLE `prefix_rbac_role_user`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `prefix_storage`
--
ALTER TABLE `prefix_storage`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_stream_event`
--
ALTER TABLE `prefix_stream_event`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_subscribe`
--
ALTER TABLE `prefix_subscribe`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_talk`
--
ALTER TABLE `prefix_talk`
  MODIFY `talk_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_topic`
--
ALTER TABLE `prefix_topic`
  MODIFY `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_topic_tag`
--
ALTER TABLE `prefix_topic_tag`
  MODIFY `topic_tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_topic_type`
--
ALTER TABLE `prefix_topic_type`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `prefix_user`
--
ALTER TABLE `prefix_user`
  MODIFY `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `prefix_user_changemail`
--
ALTER TABLE `prefix_user_changemail`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_user_complaint`
--
ALTER TABLE `prefix_user_complaint`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_user_field`
--
ALTER TABLE `prefix_user_field`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `prefix_user_note`
--
ALTER TABLE `prefix_user_note`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `prefix_wall`
--
ALTER TABLE `prefix_wall`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;


-- patch from 2.0.1
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