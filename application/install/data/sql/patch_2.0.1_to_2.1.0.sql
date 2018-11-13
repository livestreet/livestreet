--
-- Структура таблицы `prefix_menu`
--

CREATE TABLE `prefix_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `title` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `state` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `prefix_menu`
--

INSERT INTO `ls_menu` (`id`, `name`, `title`, `state`) VALUES
(1, 'main', 'Главное', 1),
(2, 'user', 'Пользователь', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_menu_item`
--

CREATE TABLE `prefix_menu_item` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `url` varchar(1000) DEFAULT NULL,
  `menu_id` int(10) UNSIGNED NOT NULL,
  `pid` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `state` tinyint(3) UNSIGNED NOT NULL,
  `priority` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prefix_menu_item`
--

INSERT INTO `ls_menu_item` (`id`, `name`, `url`, `menu_id`, `pid`, `title`, `state`, `priority`) VALUES
(1, 'blog', '/', 1, 0, 'topic.topics', 1, 100),
(2, 'people', 'people', 1, 0, 'user.users', 1, 98),
(5, 'blogs', 'blogs', 1, 0, 'blog.blogs', 1, 99),
(8, 'stream', 'stream', 1, 0, 'activity.title', 1, 97),
(11, 'settings', 'settings', 2, 0, 'user.profile.nav.settings', 1, 20);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `prefix_menu`
--
ALTER TABLE `prefix_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `prefix_menu_item`
--
ALTER TABLE `prefix_menu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `state` (`state`),
  ADD KEY `menu_id` (`menu_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `prefix_menu`
--
ALTER TABLE `prefix_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `prefix_menu_item`
--
ALTER TABLE `prefix_menu_item`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;