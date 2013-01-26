<?php

/* -------------------------------------------------------
 *
 *   LiveStreet Engine Social Networking
 *   Copyright © 2008 Mzhelskiy Maxim
 *
 * --------------------------------------------------------
 *
 *   Official site: www.livestreet.ru
 *   Contact e-mail: rus.engine@gmail.com
 *
 *   GNU General Public License, version 2:
 *   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
  ---------------------------------------------------------
 */

/**
 * Русский языковой файл.
 * Содержит все текстовки движка.
 */
return array(
	/**
	 * Панель
	 */
	'panel_b' => 'жирный',
	'panel_i' => 'курсив',
	'panel_u' => 'подчеркнутый',
	'panel_s' => 'зачеркнутый',
	'panel_url' => 'вставить ссылку',
	'panel_url_promt' => 'Введите ссылку',
	'panel_image_promt' => 'Введите ссылку на изображение',
	'panel_code' => 'код',
	'panel_video' => 'видео',
	'panel_video_promt' => 'Введите ссылку на видео',
	'panel_image' => 'изображение',
	'panel_cut' => 'кат',
	'panel_quote' => 'цитировать',
	'panel_list' => 'Список',
	'panel_list_ul' => 'UL LI',
	'panel_list_ol' => 'OL LI',
	'panel_list_li' => 'пункт списка',
	'panel_title' => 'Заголовок',
	'panel_title_h4' => 'H4',
	'panel_title_h5' => 'H5',
	'panel_title_h6' => 'H6',
	'panel_clear_tags' => 'очистить от тегов',
	'panel_user' => 'вставить пользователя',
	'panel_user_promt' => 'Введите логин пользователя',

	'site_history_back' => 'Вернуться назад',
	'site_go_main' => 'перейти на главную',

	/**
	 * Постраничность
	 */
	'paging_next' => 'следующая',
	'paging_previos' => 'предыдущая',
	'paging_last' => 'последняя',
	'paging_first' => 'первая',
	'paging' => 'Страницы',
	/**
	 * Загрузка изображений
	 */
	'uploadimg' => 'Вставка изображения',
	'uploadimg_from_pc' => 'С компьютера',
	'uploadimg_from_link' => 'Из интернета',
	'uploadimg_file' => 'Файл',
	'uploadimg_file_error' => 'Невозможно обработать файл, проверьте тип и размер файла',
	'uploadimg_url' => 'Ссылка на изображение',
	'uploadimg_url_error_type' => 'Файл не является изображением',
	'uploadimg_url_error_read' => 'Невозможно прочитать внешний файл',
	'uploadimg_url_error_size' => 'Размер файла превышает максимальный в 500кБ',
	'uploadimg_url_error' => 'Невозможно обработать внешний файл',
	'uploadimg_align' => 'Выравнивание',
	'uploadimg_align_no' => 'нет',
	'uploadimg_align_left' => 'слева',
	'uploadimg_align_right' => 'справа',
	'uploadimg_align_center' => 'по центру',
	'uploadimg_submit' => 'Загрузить',
	'uploadimg_link_submit_load' => 'Загрузить',
	'uploadimg_link_submit_paste' => 'Вставить как ссылку',
	'uploadimg_cancel' => 'Отмена',
	'uploadimg_title' => 'Описание',
	/**
	 * Валидация данных
	 */
	'validate_string_too_long' => 'Поле %%field%% слишком длинное (максимально допустимо %%max%% символов)',
	'validate_string_too_short' => 'Поле %%field%% слишком короткое (минимально допустимо %%min%% символов)',
	'validate_string_no_lenght' => 'Поле %%field%% неверной длины (необходимо %%length%% символов)',
	'validate_email_not_valid' => 'Поле %%field%% не соответствует формату email адреса',
	'validate_number_must_integer' => 'Поле %%field%% должно быть целым числом',
	'validate_number_must_number' => 'Поле %%field%% должно быть числом',
	'validate_number_too_small' => 'Поле %%field%% слишком маленькое (минимально допустимо число %%min%%)',
	'validate_number_too_big' => 'Поле %%field%% слишком большое (максимально допустимо число %%max%%)',
	'validate_type_error' => 'Поле %%field%% должно иметь тип %%type%%',
	'validate_date_format_invalid' => 'Поле %%field%% имеет неверный формат даты',
	'validate_boolean_invalid' => 'Поле %%field%% должно быть %%true%% или %%false%%',
	'validate_required_must_be' => 'Поле %%field%% должно иметь значение %%value%%',
	'validate_required_cannot_blank' => 'Поле %%field%% не может быть пустым',
	'validate_url_not_valid' => 'Поле %%field%% не соответствует формату URL адреса',
	'validate_captcha_not_valid' => 'Поле %%field%% содержит неверный код',
	'validate_compare_must_repeated' => 'Поле %%field%% должно повторять %%compare_field%%',
	'validate_compare_must_not_equal' => 'Поле %%field%% не должно повторять %%compare_value%%',
	'validate_compare_must_greater' => 'Поле %%field%% должно быть больше чем %%compare_value%%',
	'validate_compare_must_greater_equal' => 'Поле %%field%% должно быть больше или равно %%compare_value%%',
	'validate_compare_must_less' => 'Поле %%field%% должно быть меньше чем %%compare_value%%',
	'validate_compare_must_less_equal' => 'Поле %%field%% должно быть меньше или равно %%compare_value%%',
	'validate_compare_invalid_operator' => 'У поля %%field%% неверный оператор сравнения %%operator%%',
	'validate_regexp_not_valid' => 'Поля %%field%% неверное',
	'validate_regexp_invalid_pattern' => 'У поля %%field%% неверное регулярное выражение',
	'validate_tags_count_more' => 'Поле %%field%% содержит слишком много тегов (максимально допустимо %%count%%)',
	'validate_tags_empty' => 'Поле %%field%% не содержит тегов, либо содержит неверные теги (размер тега допустим от %%min%% до %%max%% символов)',
	/**
	 * Системные сообщения
	 */
	'system_error_event_args' => 'Некорректное число аргументов при добавлении евента',
	'system_error_event_method' => 'Добавляемый метод евента не найден',
	'system_error_404' => 'К сожалению, такой страницы не существует. Вероятно, она была удалена с сервера, либо ее здесь никогда не было.',
	'system_error_module' => 'Не найден класс модуля',
	'system_error_module_no_method' => 'В модуле нет необходимого метода',
	'system_error_cache_type' => 'Неверный тип кеширования',
	'system_error_template' => 'Не найден шаблон',
	'system_error_template_block' => 'Не найден шаблон подключаемого блока',
	'error' => 'Ошибка',
	'attention' => 'Внимание',
	'system_error' => 'Системная ошибка, повторите позже',
	'exit' => 'Выход',
	'need_authorization' => 'Необходимо авторизоваться!',
	'or' => 'или',
	'window_close' => 'закрыть',
	'not_access' => 'Нет доступа',
	'install_directory_exists' => 'Для работы с сайтом удалите директорию /install.',
	'login' => 'Вход на сайт',
	'delete' => 'Удалить',
	'date_day' => 'день',
	'date_month' => 'месяц',
	'month_array' => array(
		1 => array('январь', 'января', 'январе'),
		2 => array('февраль', 'февраля', 'феврале'),
		3 => array('март', 'марта', 'марте'),
		4 => array('апрель', 'апреля', 'апреле'),
		5 => array('май', 'мая', 'мае'),
		6 => array('июнь', 'июня', 'июне'),
		7 => array('июль', 'июля', 'июле'),
		8 => array('август', 'августа', 'августе'),
		9 => array('сентябрь', 'сентября', 'сентябре'),
		10 => array('октябрь', 'октября', 'октябре'),
		11 => array('ноябрь', 'ноября', 'ноябре'),
		12 => array('декабрь', 'декабря', 'декабре'),
	),
	'date_year' => 'год',
	'date_now' => 'Только что',
	'date_today' => 'Сегодня в',
	'date_yesterday' => 'Вчера в',
	'date_tomorrow' => 'Завтра в',
	'date_minutes_back' => '%%minutes%% минута назад; %%minutes%% минуты назад; %%minutes%% минут назад',
	'date_minutes_back_less' => 'Менее минуты назад',
	'date_hours_back' => '%%hours%% час назад; %%hours%% часа назад; %%hours%% часов назад',
	'date_hours_back_less' => 'Менее часа назад',
	'today' => 'Сегодня',
	'more' => 'еще',

	'timezone_list'=> array(
		'-12' => '[UTC − 12] Меридиан смены дат (запад)',
		'-11' => '[UTC − 11] о. Мидуэй, Самоа',
		'-10' => '[UTC − 10] Гавайи',
		'-9.5' => '[UTC − 9:30] Маркизские острова',
		'-9' => '[UTC − 9] Аляска',
		'-8' => '[UTC − 8] Тихоокеанское время (США и Канада) и Тихуана',
		'-7' => '[UTC − 7] Аризона',
		'-6' => '[UTC − 6] Мехико, Центральная Америка, Центральное время (США и Канада)',
		'-5' => '[UTC − 5] Индиана (восток), Восточное время (США и Канада)',
		'-4.5' => '[UTC − 4:30] Венесуэла',
		'-4' => '[UTC − 4] Сантьяго, Атлантическое время (Канада)',
		'-3.5' => '[UTC − 3:30] Ньюфаундленд',
		'-3' => '[UTC − 3] Бразилия, Гренландия',
		'-2' => '[UTC − 2] Среднеатлантическое время',
		'-1' => '[UTC − 1] Азорские острова, острова Зелёного мыса',
		'0' => '[UTC] Время по Гринвичу: Дублин, Лондон, Лиссабон, Эдинбург',
		'1' => '[UTC + 1] Берлин, Мадрид, Париж, Рим, Западная Центральная Африка',
		'2' => '[UTC + 2] Афины, Вильнюс, Киев, Рига, Таллин, Центральная Африка',
		'3' => '[UTC + 3] Калининград, Минск',
		'3.5' => '[UTC + 3:30] Тегеран',
		'4' => '[UTC + 4] Волгоград, Москва, Самара, Санкт-Петербург, Баку, Ереван, Тбилиси',
		'4.5' => '[UTC + 4:30] Кабул',
		'5' => '[UTC + 5] Исламабад, Карачи, Оренбург, Ташкент',
		'5.5' => '[UTC + 5:30] Бомбей, Калькутта, Мадрас, Нью-Дели',
		'5.75' => '[UTC + 5:45] Катманду',
		'6' => '[UTC + 6] Екатеринбург, Алматы, Астана',
		'6.5' => '[UTC + 6:30] Рангун',
		'7' => '[UTC + 7] Бангкок, Новосибирск, Омск',
		'8' => '[UTC + 8] Гонконг, Красноярск, Пекин, Сингапур',
		'8.75' => '[UTC + 8:45] Юго-восточная Западная Австралия',
		'9' => '[UTC + 9] Токио, Сеул, Иркутск',
		'9.5' => '[UTC + 9:30] Дарвин',
		'10' => '[UTC + 10] Чита, Якутск, Канберра, Мельбурн, Сидней',
		'10.5' => '[UTC + 10:30] Лорд-Хау',
		'11' => '[UTC + 11] Владивосток, Соломоновы о-ва',
		'11.5' => '[UTC + 11:30] Остров Норфолк',
		'12' => '[UTC + 12] Камчатка, Магадан, Сахалин, Новая Зеландия, Фиджи',
		'12.75' => '[UTC + 12:45] Острова Чатем',
		'13' => '[UTC + 13] Острова Феникс, Тонга',
		'14' => '[UTC + 14] Остров Лайн'
	)
);
?>