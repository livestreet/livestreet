--
-- Структура таблицы `ls_menu`
--

CREATE TABLE `prefix_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `title` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `state` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `ls_menu_item`
--

CREATE TABLE `prefix_menu_item` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `url` VARCHAR(1000) NULL DEFAULT NULL,
  `menu_id` int(10) UNSIGNED NOT NULL,
  `pid` INT UNSIGNED NULL DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `state` tinyint(3) UNSIGNED NOT NULL,
  `priority` INT NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ls_menu`
--
ALTER TABLE `prefix_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `ls_menu_item`
--
ALTER TABLE `prefix_menu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `state` (`state`),
  ADD KEY `menu_id` (`menu_id`),

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `ls_menu`
--
ALTER TABLE `prefix_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ls_menu_item`
--
ALTER TABLE `prefix_menu_item`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;