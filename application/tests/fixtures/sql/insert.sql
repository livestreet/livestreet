-- --------------------------------------------------------

--
-- Дамп данных таблицы `prefix_user`
--

INSERT INTO `prefix_user` (`user_id`, `user_login`, `user_password`, `user_mail`, `user_skill`, `user_date_register`, `user_date_activate`, `user_date_comment_last`, `user_ip_register`, `user_rating`, `user_count_vote`, `user_activate`, `user_activate_key`, `user_profile_name`, `user_profile_sex`, `user_profile_country`, `user_profile_region`, `user_profile_city`, `user_profile_birthday`, `user_profile_about`, `user_profile_date`, `user_profile_avatar`, `user_profile_foto`, `user_settings_notice_new_topic`, `user_settings_notice_new_comment`, `user_settings_notice_new_talk`, `user_settings_notice_reply_comment`, `user_settings_notice_new_friend`, `user_settings_timezone`) VALUES
(1, 'admin', 'd8578edf8458ce06fbc5bb76a58c5ca4', 'admin@admin.adm', 0.000, '2012-10-1 00:00:00', NULL, NULL, '127.0.0.1', 0.000, 0, 1, NULL, NULL, 'other', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, 1, 1, 1, 1, 1, NULL);

-- --------------------------------------------------------


--
-- Дамп данных таблицы `prefix_user_administrator`
--

INSERT INTO `prefix_user_administrator` (`user_id`) VALUES
(1);

-- --------------------------------------------------------


--
-- Дамп данных таблицы `prefix_user_field`
--

INSERT INTO `prefix_user_field` (`id`, `type`, `name`, `title`, `pattern`) VALUES
(1, 'contact', 'phone', 'Телефон', ''),
(2, 'contact', 'mail', 'E-mail', '<a href="mailto:{*}" rel="nofollow">{*}</a>'),
(3, 'contact', 'skype', 'Skype', '<a href="skype:{*}" rel="nofollow">{*}</a>'),
(4, 'contact', 'icq', 'ICQ', '<a href="http://www.icq.com/people/about_me.php?uin={*}" rel="nofollow">{*}</a>'),
(5, 'contact', 'www', 'Сайт', '<a href="http://{*}" rel="nofollow">{*}</a>'),
(6, 'social', 'twitter', 'Twitter', '<a href="http://twitter.com/{*}/" rel="nofollow">{*}</a>'),
(7, 'social', 'facebook', 'Facebook', '<a href="http://facebook.com/{*}" rel="nofollow">{*}</a>'),
(8, 'social', 'vkontakte', 'ВКонтакте', '<a href="http://vk.com/{*}" rel="nofollow">{*}</a>'),
(9, 'social', 'odnoklassniki', 'Одноклассники', '<a href="http://www.odnoklassniki.ru/profile/{*}/" rel="nofollow">{*}</a>');

-- --------------------------------------------------------

--
-- Дамп данных таблицы `prefix_blog`
--

INSERT INTO `prefix_blog` (`blog_id`, `user_owner_id`, `blog_title`, `blog_description`, `blog_type`, `blog_date_add`, `blog_date_edit`, `blog_rating`, `blog_count_vote`, `blog_count_user`, `blog_count_topic`, `blog_limit_rating_topic`, `blog_url`, `blog_avatar`) VALUES
(1, 1, 'Blog by admin', 'This is Admin personal blog.', 'personal', '2012-11-07 09:20:00', NULL, 0.000, 0, 0, 0, -1000.000, NULL, '0');
