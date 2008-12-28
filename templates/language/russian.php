<?
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
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
	 * Блоги
	 */
	'blogs' => 'Блоги_$$',
	'blog_no_topic' => 'Сюда еще никто не успел написать_$$',
	'blog_rss' => 'RSS лента_$$',
	/**
	 * Популярные блоги
	 */
	'blog_popular' => 'Популярные блоги_$$',
	'blog_popular_rating' => 'Рейтинг_$$',
	'blog_popular_all' => 'все блоги_$$',
	/**
	 * Пользователи блога
	 */
	'blog_user_count' => 'подписчиков_$$',
	'blog_user_administrators' => 'Администраторы_$$',
	'blog_user_moderators' => 'Модераторы_$$',
	'blog_user_moderators_empty' => 'Модераторов здесь не замеченно_$$',
	'blog_user_readers' => 'Читатели_$$',	
	'blog_user_readers_empty' => 'Читателей здесь не замеченно_$$',	
	/**
	 * Голосование за блог
	 */
	'blog_vote_up' => 'нравится_$$',
	'blog_vote_down' => 'не нравится_$$',
	'blog_vote_count_text' => 'всего проголосовавших:_$$',
	'blog_vote_already' => 'вы уже голосовали за этот блог_$$',
	/**
	 * Вступление и выход из блога
	 */
	'blog_join' => 'вступить в блог_$$',
	'blog_leave' => 'покинуть блог_$$',	
	/**
	 * Меню блогов
	 */
	'blog_menu_all' => 'Все_$$',
	'blog_menu_all_good' => 'Хорошие_$$',
	'blog_menu_all_new' => 'Новые_$$',
	'blog_menu_collective' => 'Коллективные_$$',
	'blog_menu_collective_good' => 'Хорошие_$$',
	'blog_menu_collective_new' => 'Новые_$$',
	'blog_menu_collective_bad' => 'Плохие_$$',
	'blog_menu_personal' => 'Персональные_$$',
	'blog_menu_personal_good' => 'Хорошие_$$',
	'blog_menu_personal_new' => 'Новые_$$',
	'blog_menu_personal_bad' => 'Плохие_$$',
	'blog_menu_top' => 'TOP_$$',
	'blog_menu_top_blog' => 'Блоги_$$',
	'blog_menu_top_topic' => 'Топики_$$',
	'blog_menu_top_comment' => 'Комментарии_$$',
	'blog_menu_top_period_24h' => 'Популярные, за последние 24 часа_$$',
	'blog_menu_top_period_7d' => 'Популярные, за последние 7 дней_$$',
	'blog_menu_top_period_30d' => 'Популярные, за последние 30 дней_$$',
	'blog_menu_top_period_all' => 'Популярные навсегда, за все время_$$',	
	'blog_menu_create' => 'Создать блог_$$',
	/**
	 * Создание/редактирование блога
	 */
	'blog_edit' => 'отредактировать блог_$$',
	'blog_create' => 'Создание нового блога_$$',
	'blog_create_acl' => 'Вы еще не достаточно окрепли чтобы создавать свой блог_$$',
	'blog_create_title' => 'Название блога_$$',
	'blog_create_title_notice' => 'Название блога должно быть наполнено смыслом, чтобы можно было понять, о чем будет блог._$$',
	'blog_create_title_error' => 'Название блога должно быть от 2 до 200 символов_$$',
	'blog_create_title_error_unique' => 'Блог с таким названием уже существует_$$',
	'blog_create_url' => 'URL блога_$$',
	'blog_create_url_notice' => 'URL блога по которому он будет доступен, по смыслу должен совпадать с названием блога и быть на латинице. Пробелы заменяться на "_". Внимание! URL нельзя изменить после создания блога!_$$',
	'blog_create_url_error' => 'URL блога должен быть от 2 до 50 символов и только на латинице + цифры и знаки "-", "_"_$$',
	'blog_create_url_error_badword' => 'URL блога должен отличаться от:_$$',
	'blog_create_url_error_unique' => 'Блог с таким URL уже существует_$$',
	'blog_create_description' => 'Описание блога_$$',
	'blog_create_description_notice' => 'Между прочим, можно использовать html-теги_$$',
	'blog_create_description_error' => 'Текст описания блога должен быть от 10 до 3000 символов_$$',
	'blog_create_type' => 'Тип блога_$$',
	'blog_create_type_open' => 'Открытый_$$',
	'blog_create_type_notice' => 'Открытый — к этому блогу может присоедениться любой желающий, топики видны всем_$$',
	'blog_create_type_error' => 'Неизвестный тип блога_$$',
	'blog_create_rating' => 'Ограничение по рейтингу_$$',
	'blog_create_rating_notice' => 'Рейтинг который необходим пользователю, чтобы написать в этот блог_$$',
	'blog_create_rating_error' => 'Значение ограничения рейтинга должно быть числом_$$',
	'blog_create_avatar' => 'Аватар_$$',
	'blog_create_avatar_error' => 'Не удалось загрузить аватар_$$',
	'blog_create_avatar_delete' => 'удалить_$$',
	'blog_create_submit' => 'Сохранить_$$',
	'blog_create_submit_notice' => 'После нажатия на кнопку «Сохранить», блог будет создан_$$',
	/**
	 * Управление блогом
	 */
	'blog_admin' => 'Управление блогом_$$',
	'blog_admin_not_authorization' => 'Для того чтобы изменить блог, сначало нужно войти под своим аккаунтом._$$',
	'blog_admin_profile' => 'Профиль_$$',
	'blog_admin_users' => 'Пользователи_$$',
	'blog_admin_users_administrator' => 'администратор_$$',
	'blog_admin_users_moderator' => 'модератор_$$',
	'blog_admin_users_reader' => 'читатель_$$',
	'blog_admin_users_current_administrator' => 'это вы &mdash; настоящий администратор!_$$',
	'blog_admin_users_empty' => 'в блоге никто не состоит_$$',
	'blog_admin_users_submit' => 'сохранить_$$',
	'blog_admin_users_submit_notice' => 'После нажатия на кнопку «Сохранить», права пользователей будут сохранены_$$',
	'blog_admin_users_submit_ok' => 'Права сохранены_$$',
	'blog_admin_users_submit_error' => 'Что то не так_$$',
	/**
	 * Топики
	 */
	'topic_read_more' => 'Читать дальше_$$',
	'topic_date' => 'дата_$$',
	'topic_user' => 'авторский текст_$$',
	'topic_comment_read' => 'читать комментарии_$$',
	'topic_comment_add' => 'комментировать_$$',
	'topic_comment_add_title' => 'написать комментарий_$$',
	'topic_comment_add_text_error' => 'Текст комментария должен быть от 2 до 3000 символов и не содержать разного рода каку_$$',
	'topic_comment_acl' => 'Ваш рейтинг слишком мал для написания комментариев_$$',
	'topic_comment_limit' => 'Вам нельзя писать комментарии слишком часто_$$',
	'topic_comment_notallow' => 'Автор топика запретил добавлять комментарии_$$',
	'topic_comment_spam' => 'Стоп! Спам!_$$',
	'topic_unpublish' => 'топик находится в черновиках_$$',
	'topic_favourite_add' => 'добавить в избранное_$$',
	'topic_favourite_del' => 'удалить из избранного_$$',
	/**
	 * Меню топиков
	 */
	'topic_menu_add' => 'Новые_$$',
	'topic_menu_add_topic' => 'Топик_$$',
	'topic_menu_add_question' => 'Вопрос_$$',
	'topic_menu_add_link' => 'Ссылка_$$',
	'topic_menu_saved' => 'Черновики_$$',
	'topic_menu_published' => 'Опубликованные_$$',
	/**
	 * Создание топика
	 */
	'topic_create' => 'написать_$$',
	'topic_create_blog' => 'В какой блог публикуем?_$$',
	'topic_create_blog_personal' => 'мой персональный блог_$$',
	'topic_create_title' => 'Заголовок_$$',
	'topic_create_title_notice' => 'Заголовок должен быть наполнен смыслом, чтобы можно было понять, о чем будет топик._$$',
	'topic_create_title_error' => 'Название топика должно быть от 2 до 200 символов_$$',
	'topic_create_text' => 'Текст_$$',
	'topic_create_text_notice' => 'Между прочим, можно использовать html-теги_$$',
	'topic_create_text_error' => 'Текст топика должен быть от 2 до 15000 символов_$$',
	'topic_create_tags' => 'Метки_$$',
	'topic_create_tags_notice' => 'Метки нужно разделять запятой. Например: клон хабры, блоги, рейтинг, google, сиськи, кирпич._$$',
	'topic_create_tags_error_bad' => 'Проверьте правильность меток_$$',
	'topic_create_tags_error' => 'Метки топика должны быть от 2 до 50 символов с общей диной не более 500 символов_$$',
	'topic_create_forbid_comment' => 'запретить комментировать_$$',
	'topic_create_forbid_comment_notice' => 'Если отметить эту галку, то нельзя будет оставлять комментарии к топику_$$',
	'topic_create_publish_index' => 'принудительно вывести на главную_$$',
	'topic_create_publish_index_notice' => 'Если отметить эту галку, то топик сразу попадёт на главную страницу(опция доступна только администраторам)_$$',
	'topic_create_submit_publish' => 'опубликовать_$$',
	'topic_create_submit_save' => 'сохранить в черновиках_$$',
	'topic_create_submit_preview' => 'предпросмотр_$$',
	'topic_create_submit_notice' => 'Если нажать кнопку «Сохранить в черновиках», текст топика будет виден только Вам, а рядом с его заголовком будет отображаться замочек. Чтобы топик был виден всем, нажмите «Опубликовать»._$$',
	'topic_create_notice' => 'Не забывайте: тег <cut> сокращает длинные записи, скрывая их целиком или частично под ссылкой («читать дальше»). Скрытая часть не видна в блоге, но доступна в полной записи на странице топика._$$',
	
	'topic_edit' => 'отредактировать топик_$$',
	'topic_delete' => 'удалить топик_$$',
	'topic_delete_confirm' => 'Вы действительно хотите удалить топик?_$$',
	/**
	 * Топик-ссылка
	 */
	'topic_link' => 'топик-ссылка_$$',
	'topic_link_count_jump' => 'переходов по ссылке:_$$',
	/**
	 * Топик-опрос
	 */
	'topic_question_vote' => 'голосовать_$$',
	'topic_question_vote_result' => 'Проголосовало_$$',
	'topic_question_abstain' => 'воздержаться_$$',
	'topic_question_abstain_result' => 'Воздержалось_$$',
	/**
	 * Голосование за топик
	 */
	'topic_vote_up' => 'нравится_$$',
	'topic_vote_down' => 'не нравится_$$',	
	'topic_vote_already' => 'вы уже голосовали за этот топик_$$',
	'topic_vote_self' => 'нельзя голосовать за свой топик_$$',
	'topic_vote_guest' => 'для голосования необходимо авторизоваться_$$',
	'topic_vote_no' => 'пока никто не голосовал_$$',
	'topic_vote_count' => 'всего проголосовало_$$',
	
	/**
	 * Люди
	 */
	'people' => 'Люди_$$',
	
	
	/**
	 * Статический страницы
	 */
	'page_about' => 'О проекте_$$',
	'page_download' => 'Скачать_$$',
	
	
	/**
	 * Редактор текста
	 */
	
	
	/**
	 * Пользователь
	 */
	'user_privat_messages' => 'Личные сообщения_$$',
	'user_privat_messages_new' => 'У вас есть новые сообщения_$$',
	'user_settings' => 'Настройки_$$',
	'user_settings_profile' => 'профиля_$$',
	'user_settings_tuning' => 'сайта_$$',
	'user_login' => 'логин_$$',
	'user_login_submit' => 'Войти_$$',
	'user_password' => 'пароль_$$',
	'user_registration' => 'Регистрация_$$',
	'user_write_prvmsg' => 'Написать письмо_$$',
	'user_friend_add' => 'добавить в друзья_$$',
	'user_friend_del' => 'удалить из друзей_$$',
	'user_rating' => 'рейтинг_$$',
	'user_skill' => 'сила_$$',
	/**
	 * Голосование за пользователя
	 */
	'user_vote_up' => 'нравится_$$',
	'user_vote_down' => 'не нравится_$$',	
	'user_vote_already' => 'вы уже голосовали за этого пользователя_$$',
	'user_vote_self' => 'нельзя голосовать за себя_$$',
	'user_vote_guest' => 'для голосования необходимо авторизоваться_$$',	
	'user_vote_count' => 'голосов_$$',
	/**
	 * Меню профиля пользователя
	 */
	'user_menu_profile' => 'Профиль_$$',
	'user_menu_profile_whois' => 'Whois_$$',
	'user_menu_profile_favourites' => 'Избранное_$$',
	'user_menu_profile_tags' => 'Метки_$$',
	'user_menu_publication' => 'Публикации_$$',
	'user_menu_publication_blog' => 'Блог_$$',
	'user_menu_publication_comment' => 'Комментарии_$$',
	'user_menu_publication_comment_rss' => 'RSS лента_$$',
	
	/**
	 * Настройки
	 */
	'settings_profile_edit' => 'Изменение профиля_$$',
	'settings_profile_name' => 'Имя_$$',
	'settings_profile_name_notice' => 'Длина имени не может быть меньше 2 и больше 20 символов._$$',
	'settings_profile_mail' => 'E-mail_$$',
	'settings_profile_mail_notice' => 'Ваш реальный почтовый адрес, на него будут приходить уведомления_$$',
	'settings_profile_sex' => 'Пол_$$',
	'settings_profile_sex_man' => 'мужской_$$',
	'settings_profile_sex_woman' => 'женский_$$',
	'settings_profile_sex_other' => 'не скажу_$$',
	'settings_profile_birthday' => 'Дата рождения_$$',
	'settings_profile_country' => 'Страна_$$',
	'settings_profile_city' => 'Город_$$',
	'settings_profile_icq' => 'ICQ_$$',
	'settings_profile_site' => 'Сайт_$$',
	'settings_profile_site_url' => 'URL сайта_$$',
	'settings_profile_site_name' => 'название сайта_$$',
	'settings_profile_about' => 'О себе_$$',
	'settings_profile_password_current' => 'Текущий пароль_$$',
	'settings_profile_password_new' => 'Новый пароль_$$',
	'settings_profile_password_confirm' => 'Еще раз новый пароль_$$',
	'settings_profile_avatar' => 'Аватар_$$',
	'settings_profile_avatar_delete' => 'удалить_$$',
	'settings_profile_submit' => 'сохранить профиль_$$',
	'settings_invite' => 'Управление приглашениями_$$',
	'settings_invite_available' => 'Доступно_$$',
	'settings_invite_used' => 'Использовано_$$',
	'settings_invite_mail' => 'Пригласить по e-mail адресу_$$',
	'settings_invite_mail_notice' => 'На этот e-mail будет высланно приглашение для регистрации_$$',
	'settings_invite_many' => 'много_$$',
	'settings_invite_submit' => 'отправить приглашение_$$',
	'settings_tuning' => 'Настройки сайта_$$',
	'settings_tuning_notice' => 'Уведомления на e-mail_$$',
	'settings_tuning_notice_new_topic' => 'при новом топике в блоге_$$',
	'settings_tuning_notice_new_comment' => 'при новом комментарии в топике_$$',
	'settings_tuning_notice_new_talk' => 'при новом личном сообщении_$$',
	'settings_tuning_notice_reply_comment' => 'при ответе на комментарий_$$',
	'settings_tuning_notice_new_friend' => 'при добавлении вас в друзья_$$',
	'settings_tuning_submit' => 'сохранить настройки_$$',
	
	
	/**
	 * Меню настроек
	 */
	'settings_menu' => 'Настройки_$$',
	'settings_menu_profile' => 'Профиль_$$',
	'settings_menu_tuning' => 'Тюнинг_$$',
	'settings_menu_invite' => 'Инвайты_$$',
	
	
	
	'error' => 'Ошибка_$$',
	'system_error' => 'Системная ошибка, повторите позже_$$',
	'exit' => 'выход_$$',
	'window_close' => 'закрыть_$$',
	'not_access' => 'Нет доступа_$$',
	'page_next' => 'туда_$$',
	'page_previos' => 'сюда_$$',
	'date_day' => 'день_$$',
	'date_month' => 'месяц_$$',
	'date_month_1' => 'января_$$',
	'date_month_2' => 'февраля_$$',
	'date_month_3' => 'марта_$$',
	'date_month_4' => 'апреля_$$',
	'date_month_5' => 'мая_$$',
	'date_month_6' => 'июня_$$',
	'date_month_7' => 'июля_$$',
	'date_month_8' => 'августа_$$',
	'date_month_9' => 'сентября_$$',
	'date_month_10' => 'октября_$$',
	'date_month_11' => 'ноября_$$',
	'date_month_12' => 'декабря_$$',	
	'date_year' => 'год_$$',
	
);

?>