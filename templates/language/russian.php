<?php
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
	'blogs' => 'Блоги',
	'blogs_title' => 'Название и смотритель',
	'blogs_readers' => 'Читателей',
	'blogs_rating' => 'Рейтинг',
	'blogs_owner' => 'Смотритель',
	'blogs_personal_title' => 'Блог им.',
	'blogs_personal_description' => 'Это ваш персональный блог.',
	
	'blog_no_topic' => 'Сюда еще никто не успел написать',
	'blog_rss' => 'RSS лента',
	'blog_rating' => 'Рейтинг',
	'blog_vote_count' => 'голосов',
	'blog_about' => 'О блоге',
	/**
	 * Популярные блоги
	 */
	'blog_popular' => 'Популярные блоги',
	'blog_popular_rating' => 'Рейтинг',
	'blog_popular_all' => 'все блоги',
	/**
	 * Пользователи блога
	 */
	'blog_user_count' => 'подписчиков',
	'blog_user_administrators' => 'Администраторы',
	'blog_user_moderators' => 'Модераторы',
	'blog_user_moderators_empty' => 'Модераторов здесь не замечено',
	'blog_user_readers' => 'Читатели',	
	'blog_user_readers_empty' => 'Читателей здесь не замечено',	
	/**
	 * Голосование за блог
	 */
	'blog_vote_up' => 'нравится',
	'blog_vote_down' => 'не нравится',
	'blog_vote_count_text' => 'всего проголосовавших:',
	'blog_vote_error_already' => 'Вы уже голосовали за этот блог!',
	'blog_vote_error_self' => 'Вы не можете голосовать за свой блог!',
	'blog_vote_error_acl' => 'У вас не хватает рейтинга и силы для голосования!',
	'blog_vote_error_close' => 'Вы не можете голосовать за закрытый блог',
	'blog_vote_ok' => 'Ваш голос учтен',
	/**
	 * Вступление и выход из блога
	 */
	'blog_join' => 'вступить в блог',	
	'blog_join_ok' => 'Вы вступили в блог',	
	'blog_join_error_invite' => 'Присоединиться к этому блогу можно только по приглашению!',	
	'blog_join_error_self' => 'Зачем вы хотите вступить в этот блог? Вы и так его хозяин!',	
	'blog_leave' => 'покинуть блог',	
	'blog_leave_ok' => 'Вы покинули блог',	
	/**
	 * Меню блогов
	 */
	'blog_menu_all' => 'Все',
	'blog_menu_all_good' => 'Хорошие',
	'blog_menu_all_new' => 'Новые',
	'blog_menu_all_list' => 'Все блоги',
	'blog_menu_collective' => 'Коллективные',
	'blog_menu_collective_good' => 'Хорошие',
	'blog_menu_collective_new' => 'Новые',
	'blog_menu_collective_bad' => 'Плохие',
	'blog_menu_personal' => 'Персональные',
	'blog_menu_personal_good' => 'Хорошие',
	'blog_menu_personal_new' => 'Новые',
	'blog_menu_personal_bad' => 'Плохие',
	'blog_menu_top' => 'TOP',
	'blog_menu_top_blog' => 'Блоги',
	'blog_menu_top_topic' => 'Топики',
	'blog_menu_top_comment' => 'Комментарии',
	'blog_menu_top_period_24h' => 'За 24 часа',
	'blog_menu_top_period_7d' => 'За 7 дней',
	'blog_menu_top_period_30d' => 'За 30 дней',
	'blog_menu_top_period_all' => 'За все время',	
	'blog_menu_create' => 'Создать блог',
	/**
	 * Создание/редактирование блога
	 */
	'blog_edit' => 'Редактировать',
	'blog_delete' => 'Удалить',
	'blog_create' => 'Создание нового блога',
	'blog_create_acl' => 'Вы еще не достаточно окрепли, чтобы создавать свой блог',
	'blog_create_title' => 'Название блога',
	'blog_create_title_notice' => 'Название блога должно быть наполнено смыслом, чтобы можно было понять, о чем будет блог.',
	'blog_create_title_error' => 'Название блога должно быть от 2 до 200 символов',
	'blog_create_title_error_unique' => 'Блог с таким названием уже существует',
	'blog_create_url' => 'URL блога',
	'blog_create_url_notice' => 'URL блога, по которому он будет доступен. Может содержать только буквы латинского алфавита, цифры, дефис; пробелы будут заменены на "_". По смыслу URL  должен совпадать с названием блога, после его создания редактирование этого параметра будет недоступно',
	'blog_create_url_error' => 'URL блога должен быть от 2 до 50 символов и только на латинице + цифры и знаки "-", "_"',
	'blog_create_url_error_badword' => 'URL блога должен отличаться от:',
	'blog_create_url_error_unique' => 'Блог с таким URL уже существует',
	'blog_create_description' => 'Описание блога',
	'blog_create_description_notice' => 'Между прочим, можно использовать html-теги',
	'blog_create_description_error' => 'Текст описания блога должен быть от 10 до 3000 символов',
	'blog_create_type' => 'Тип блога',
	'blog_create_type_open' => 'Открытый',
	'blog_create_type_close' => 'Закрытый',
	'blog_create_type_open_notice' => 'Открытый — к этому блогу может присоединиться любой желающий, топики видны всем',
	'blog_create_type_close_notice' => 'Закрытый — присоединиться можно только по приглашению администрации блога, топики видят только подписчики',
	'blog_create_type_error' => 'Неизвестный тип блога',
	'blog_create_rating' => 'Ограничение по рейтингу',
	'blog_create_rating_notice' => 'Рейтинг, который необходим пользователю, чтобы написать в этот блог',
	'blog_create_rating_error' => 'Значение ограничения рейтинга должно быть числом',
	'blog_create_avatar' => 'Аватар',
	'blog_create_avatar_error' => 'Не удалось загрузить аватар',
	'blog_create_avatar_delete' => 'удалить',
	'blog_create_submit' => 'Сохранить',
	'blog_create_submit_notice' => 'После нажатия на кнопку «Сохранить» блог будет создан',
	/**
	 * Управление блогом
	 */
	'blog_admin' => 'Управление блогом',
	'blog_admin_not_authorization' => 'Для того, чтобы изменить блог, сначала нужно войти под своим аккаунтом.',
	'blog_admin_profile' => 'Профиль',
	'blog_admin_users' => 'Пользователи',
	'blog_admin_users_administrator' => 'администратор',
	'blog_admin_users_moderator' => 'модератор',
	'blog_admin_users_reader' => 'читатель',
	'blog_admin_users_bun' => 'забаненный',
	'blog_admin_users_current_administrator' => 'это вы &mdash; настоящий администратор!',
	'blog_admin_users_empty' => 'в блоге никто не состоит',
	'blog_admin_users_submit' => 'сохранить',
	'blog_admin_users_submit_notice' => 'После нажатия на кнопку «Сохранить» права пользователей будут сохранены',
	'blog_admin_users_submit_ok' => 'Права сохранены',
	'blog_admin_users_submit_error' => 'Что-то не так',
	
	'blog_admin_delete_confirm' => 'Вы уверены, что хотите удалить блог?',
	'blog_admin_delete_move' => 'Переместить топики в блог',
	'blog_delete_clear' => 'Удалить топики',
	'blog_admin_delete_success' => 'Блог успешно удален',
	'blog_admin_delete_not_empty' => 'Вы не можете удалить блок с записями. Предварительно удалите из блога все записи.',
	'blog_admin_delete_move_error' => 'Не удалось переместить топики из удаляемого блога',
	'blog_admin_delete_move_personal' => 'Нельзя перемещать топики в персональный блог',
	
	'blog_admin_user_add_label' => 'Пригласить пользователей:',
	'blog_admin_user_invited' => 'Список приглашенных:',
	'blog_close_show' => 'Это закрытый блог, у вас нет прав на просмотр контента',
	'blog_user_invite_add_self' => 'Нельзя отправить инвайт самому себе',
	'blog_user_invite_add_ok' => 'Пользователю %%login%% отправлено приглашение',
	'blog_user_already_invited' => 'Пользователю %%login%% уже отправлен инвайт',
	'blog_user_already_exists' => 'Пользователь %%login%% уже состоит в блоге',
	'blog_user_already_reject' => 'Пользователь %%login%% отклонил инвайт',
	'blog_user_invite_title' => "Приглашение стать читателем блога '%%blog_title%%'",
	'blog_user_invite_text' => "Пользователь %%login%% приглашает вас стать читателем закрытого блога '%%blog_title%%'.<br/><br/><a href='%%accept_path%%'>Принять</a> - <a href='%%reject_path%%'>Отклонить</a>",
	'blog_user_invite_already_done' => 'Вы уже являетесь пользователем этого блога',
	'blog_user_invite_accept' => 'Приглашение принято',
	'blog_user_invite_reject' => 'Приглашение отклонено',
	'blog_user_invite_readd' => 'повторить',
	
	/**
	 * Топики
	 */
	'topic_title' => 'Топики',
	'topic_read_more' => 'Читать дальше',
	'topic_date' => 'дата',
	'topic_user' => 'авторский текст',
	'topic_time_limit' => 'Вам нельзя создавать топики слишком часто',
	'topic_comment_read' => 'читать комментарии',
	'topic_comment_add' => 'комментировать',
	'topic_comment_add_title' => 'написать комментарий',
	'topic_comment_add_text_error' => 'Текст комментария должен быть от 2 до 3000 символов и не содержать разного рода каку',
	'topic_comment_acl' => 'Ваш рейтинг слишком мал для написания комментариев',
	'topic_comment_limit' => 'Вам нельзя писать комментарии слишком часто',
	'topic_comment_notallow' => 'Автор топика запретил добавлять комментарии',
	'topic_comment_spam' => 'Стоп! Спам!',
	'topic_unpublish' => 'топик находится в черновиках',
	'topic_favourite_add' => 'добавить в избранное',
	'topic_favourite_add_ok' => 'Топик добавлен в избранное',
	'topic_favourite_add_no' => 'Этого топика нет в вашем избранном',
	'topic_favourite_add_already' => 'Этот топик уже есть в вашем избранном',
	'topic_favourite_del' => 'удалить из избранного',
	'topic_favourite_del_ok' => 'Топик удален из избранного',
	
	'block_stream_comments_all' => 'Весь эфир',
	'block_stream_topics_all' => 'Весь эфир',
	'comments_all' => 'Прямой эфир',
	/**
	 * Меню топиков
	 */
	'topic_menu_add' => 'Новые',
	'topic_menu_add_topic' => 'Топик',
	'topic_menu_add_question' => 'Опрос',
	'topic_menu_add_link' => 'Ссылка',
	'topic_menu_saved' => 'Черновики',
	'topic_menu_published' => 'Опубликованные',
	/**
	 * Создание топика
	 */
	'topic_topic_create' => 'Создание топика',
	'topic_topic_edit' => 'Редактирование топика',
	'topic_create' => 'написать',
	'topic_create_blog' => 'В какой блог публикуем?',
	'topic_create_blog_personal' => 'мой персональный блог',
	'topic_create_blog_error_unknown' => 'Пытаетесь запостить топик в неизвестный блог?',
	'topic_create_blog_error_nojoin' => 'Вы не состоите в этом блоге!',
	'topic_create_blog_error_noacl' => 'Вы еще недостаточно окрепли, чтобы постить в этот блог',
	'topic_create_blog_error_noallow' => 'Вы не можете писать в этот блог',
	'topic_create_title' => 'Заголовок',
	'topic_create_title_notice' => 'Заголовок должен быть наполнен смыслом, чтобы можно было понять, о чем будет топик.',
	'topic_create_title_error' => 'Название топика должно быть от 2 до 200 символов',
	'topic_create_text' => 'Текст',
	'topic_create_text_notice' => 'Доступны html-теги',
	'topic_create_text_error' => 'Текст топика должен быть от 2 до 15000 символов',
	'topic_create_text_error_unique' => 'Вы уже писали топик с таким содержанием',
	'topic_create_tags' => 'Метки',
	'topic_create_tags_notice' => 'Метки нужно разделять запятой. Например: клон хабры, блоги, рейтинг, google, сиськи, кирпич.',
	'topic_create_tags_error_bad' => 'Проверьте правильность меток',
	'topic_create_tags_error' => 'Метки топика должны быть от 2 до 50 символов с общей длиной не более 500 символов',
	'topic_create_forbid_comment' => 'запретить комментировать',
	'topic_create_forbid_comment_notice' => 'Если отметить эту галку, то нельзя будет оставлять комментарии к топику',
	'topic_create_publish_index' => 'принудительно вывести на главную',
	'topic_create_publish_index_notice' => 'Если отметить эту галку, то топик сразу попадёт на главную страницу (опция доступна только администраторам)',
	'topic_create_submit_publish' => 'опубликовать',
	'topic_create_submit_save' => 'сохранить в черновиках',
	'topic_create_submit_preview' => 'предпросмотр',
	'topic_create_submit_notice' => 'Если нажать кнопку «Сохранить в черновиках», текст топика будет виден только Вам, а рядом с его заголовком будет отображаться замочек. Чтобы топик был виден всем, нажмите «Опубликовать».',
	'topic_create_notice' => 'Не забывайте: тег <cut> сокращает длинные записи, скрывая их целиком или частично под ссылкой («читать дальше»). Скрытая часть не видна в блоге, но доступна в полной записи на странице топика.',
	'topic_create_error' => 'Возникли технические неполадки при добавлении топика. Пожалуйста, повторите позже.',
	
	'topic_edit' => 'Редактировать',
	'topic_delete' => 'Удалить',
	'topic_delete_confirm' => 'Вы действительно хотите удалить топик?',
	/**
	 * Топик-ссылка
	 */
	'topic_link' => 'топик-ссылка',
	'topic_link_title' => 'Ссылки',
	'topic_link_title_edit' => 'Редактирование ссылки',
	'topic_link_title_create' => 'Добавление ссылки',
	'topic_link_create' => 'Создание топика-ссылки',
	'topic_link_edit' => 'Редактирование топика-ссылки',
	'topic_link_count_jump' => 'переходов по ссылке:',
	'topic_link_create_url' => 'Ссылка',
	'topic_link_create_url_notice' => 'Например, http://livestreet.ru/blog/dev_livestreet/113.html',
	'topic_link_create_url_error' => 'Ссылка должна быть от 2 до 200 символов',
	'topic_link_create_text' => 'Краткое описание (максимум 500 символов, HTML-теги запрещены)',
	'topic_link_create_text_notice' => 'HTML-теги запрещены',
	'topic_link_create_text_error' => 'Описание ссылки должно быть от 10 до 500 символов',
	/**
	 * Топик-опрос
	 */
	'topic_question_title' => 'Опросы',
	'topic_question_title_edit' => 'Редактирование опроса',
	'topic_question_title_create' => 'Добавление опроса',
	'topic_question_vote' => 'голосовать',
	'topic_question_vote_ok' => 'Ваш голос учтен.',
	'topic_question_vote_already' => 'Ваш голос уже учтен!',
	'topic_question_vote_result' => 'Проголосовало',
	'topic_question_abstain' => 'воздержаться',
	'topic_question_abstain_result' => 'Воздержалось',
	'topic_question_create' => 'Создание топика-опроса',
	'topic_question_edit' => 'Редактирование топика-опроса',
	'topic_question_create_title' => 'Вопрос',
	'topic_question_create_title_notice' => 'Вопрос должен быть наполнен смыслом, чтобы можно было понять, о чем будет опрос.',
	'topic_question_create_title_error' => 'Вопрос должен быть от 2 до 200 символов',
	'topic_question_create_answers' => 'Варианты ответов',	
	'topic_question_create_answers_error' => 'Ответ должен быть от 1 до 100 символов',	
	'topic_question_create_answers_error_min' => 'Вариантов ответа должно быть как минимум два',	
	'topic_question_create_answers_error_max' => 'Максимально возможное число вариантов ответа 20',	
	'topic_question_create_text' => 'Краткое описание (максимум 500 символов, HTML-теги запрещены)',
	'topic_question_create_text_notice' => 'HTML-теги запрещены',
	'topic_question_create_text_error' => 'Описание опроса должно быть не более 500 символов',
	/**
	 * Голосование за топик
	 */
	'topic_vote_up' => 'нравится',
	'topic_vote_down' => 'не нравится',	
	'topic_vote_error_already' => 'Вы уже голосовали за этот топик!',
	'topic_vote_error_self' => 'Вы не можете голосовать за свой топик!',
	'topic_vote_error_guest' => 'для голосования необходимо авторизоваться',
	'topic_vote_error_time' => 'Срок голосования за топик истёк!',
	'topic_vote_error_acl' => 'У вас не хватает рейтинга и силы для голосования!',
	'topic_vote_no' => 'пока никто не голосовал',
	'topic_vote_ok' => 'Ваш голос учтен',
	'topic_vote_ok_abstain' => 'Вы воздержались для просмотра рейтинга топика',
	'topic_vote_count' => 'всего проголосовало',
	
	/**
	 * Комментарии
	 */
	'comment_title' => 'Комментарии',
	'comment_collapse' => 'свернуть',
	'comment_expand' => 'развернуть',
	'comment_goto_parent' => 'Ответ на',
	'comment_goto_child' => 'Обратно к ответу',
	'comment_bad_open' => 'раскрыть комментарий',
	'comment_answer' => 'Ответить',
	'comment_delete' => 'Удалить',
	'comment_delete_ok' => 'Комментарий удален',
	'comment_repair' => 'Восстановить',
	'comment_repair_ok' => 'Комментарий восстановлен',
	'comment_was_delete' => 'комментарий был удален',
	'comment_add' => 'добавить',
	'comment_preview' => 'предпросмотр',
	'comment_unregistered' => 'Только зарегистрированные и авторизованные пользователи могут оставлять комментарии.',
	/**
	 * Голосование за комментарий
	 */
	'comment_vote_error' => 'Попробуйте проголосовать позже',
	'comment_vote_error_value' => 'Голосовать можно только +1 либо -1!',
	'comment_vote_error_acl' => 'У вас не хватает рейтинга и силы для голосования!',
	'comment_vote_error_already' => 'Вы уже голосовали за этот комментарий!',
	'comment_vote_error_time' => 'Срок голосования за комментарий истёк!',
	'comment_vote_error_self' => 'Вы не можете голосовать за свой комментарий!',
	'comment_vote_error_noexists' => 'Вы голосуете за несуществующий комментарий!',
	'comment_vote_ok' => 'Ваш голос учтен',

	'comment_favourite_add' => 'добавить в избранное',
	'comment_favourite_add_ok' => 'Комментарий добавлен в избранное',
	'comment_favourite_add_no' => 'Этого комментария нет в вашем избранном',
	'comment_favourite_add_already' => 'Этот комментарий уже есть в вашем избранном',
	'comment_favourite_del' => 'удалить из избранного',
	'comment_favourite_del_ok' => 'Комментарий удален из избранного',

	
	/**
	 * Люди
	 */
	'people' => 'Люди',
	
	
	/**
	 * Пользователь
	 */
	'user' => 'Пользователь',
	'user_list' => 'Пользователи',
	'user_list_new' => 'Новые пользователи',
	'user_list_online_last' => 'Недавно были на сайте',
	'user_good' => 'Позитивные',
	'user_bad' => 'Негативные',
	'user_privat_messages' => 'Личные сообщения',
	'user_privat_messages_new' => 'У вас есть новые сообщения',
	'user_settings' => 'Настройки',
	'user_settings_profile' => 'профиля',
	'user_settings_tuning' => 'сайта',
	'user_login' => 'Логин или эл. почта',
	'user_login_submit' => 'Войти',
	'user_login_remember' => 'Запомнить меня',
	'user_login_bad' => 'Что-то не так! Вероятно, неправильно указан логин (e-mail) или пароль.',
	'user_password' => 'Пароль',
	'user_password_reminder' => 'Напомнить пароль',
	'user_exit_notice' => 'Обязательно приходите еще.',
	'user_authorization' => 'Авторизация',
	'user_registration' => 'Регистрация',
	'user_write_prvmsg' => 'Написать письмо',

	'user_friend_add' => 'Добавить в друзья',
	'user_friend_add_ok' => 'У вас появился новый друг',
	'user_friend_add_self' => 'Ваш друг - это вы!',
	'user_friend_del' => 'Удалить из друзей',
	'user_friend_del_ok' => 'У вас больше нет этого друга',
	'user_friend_del_no' => 'Друг не найден!',
	'user_friend_offer_reject' => 'Заявка отклонена',
	'user_friend_offer_send' => 'Заявка отправлена',	
	'user_friend_already_exist' => 'Пользователь уже является вашим другом',
	'user_friend_offer_title' => 'Пользователь %%login%% приглашает вас дружить',
	'user_friend_offer_text' => "Пользователь %%login%% желает добавить вас в друзья.<br/><br/>%%user_text%%<br/><br/><a href='%%accept_path%%'>Принять</a> - <a href='%%reject_path%%'>Отклонить</a>",
	'user_friend_add_deleted' => 'Этот пользователь отказался с вами дружить',
	'user_friend_add_text_label' => 'Представьтесь:',
	'user_friend_add_submit' => 'Отправить',
	'user_friend_add_cansel' => 'Отмена',
	'user_friend_offer_not_found' => 'Заявка не найдена',
	'user_friend_offer_already_done' => 'Заявка уже обработана',
	'user_friend_accept_notice_title' => 'Ваша заявка одобрена',
	'user_friend_accept_notice_text' => 'Пользователь %%login%% согласился с вами дружить',
	'user_friend_reject_notice_title' => 'Ваша заявка отклонена',
	'user_friend_reject_notice_text' => 'Пользователь %%login%% отказался с вами дружить',	
	'user_friend_del_notice_title' => 'Вас удалили из друзей',
	'user_friend_del_notice_text' => 'У вас больше нет друга %%login%%',
	
	'user_rating' => 'Рейтинг',
	'user_skill' => 'Сила',
	'user_date_last' => 'Последний визит',
	'user_date_registration' => 'Дата регистрации',
	'user_empty' => 'нет таких',
	'user_stats' => 'Статистика',
	'user_stats_all' => 'Всего пользователей',
	'user_stats_active' => 'Активные',
	'user_stats_noactive' => 'Заблудившиеся',
	'user_stats_sex_man' => 'Мужчины',
	'user_stats_sex_woman' => 'Женщины',
	'user_stats_sex_other' => 'Пол не указан',
	
	'user_not_found' => 'Пользователь <b>%%login%%</b> не найден',
	'user_not_found_by_id' => 'Пользователь <b>#%%id%%</b> не найден',
	
	/**
	 * Меню профиля пользователя
	 */
	'people_menu_users' => 'Пользователи',
	'people_menu_users_all' => 'Все',
	'people_menu_users_online' => 'Онлайн',
	'people_menu_users_new' => 'Новые',
	
	/**
	 * Регистрация
	 */
	'registration_invite' => 'Регистрация по приглашению',
	'registration_invite_code' => 'Код приглашения',
	'registration_invite_code_error' => 'Неверный код приглашения',
	'registration_invite_check' => 'Проверить код',
	'registration_activate_ok' => 'Поздравляем! Ваш аккаунт успешно активирован.',
	'registration_activate_error_code' => 'Неверный код активации!',
	'registration_activate_error_reactivate' => 'Ваш аккаунт уже активирован',
	'registration_confirm_header' => 'Активация аккаунта',
	'registration_confirm_text' => 'Вы почти зарегистрировались, осталось только активировать аккаунт. Инструкции по активации отправлены по электронной почте на адрес, указанный при регистрации.',
	'registration' => 'Регистрация',
	'registration_is_authorization' => 'Вы уже зарегистрированы у нас и даже авторизованы!',
	'registration_login' => 'Логин',
	'registration_login_error' => 'Неверный логин, допустим от 3 до 30 символов',
	'registration_login_error_used' => 'Этот логин уже занят',
	'registration_login_notice' => 'Может состоять только из букв (A-Z a-z), цифр (0-9). Знак подчеркивания (_) лучше не использовать. Длина логина не может быть меньше 3 и больше 30 символов.',
	'registration_mail' => 'Электропочта',
	'registration_mail_error' => 'Неверный формат e-mail',
	'registration_mail_error_used' => 'Этот e-mail уже используется',
	'registration_mail_notice' => 'Для проверки регистрации и в целях безопасности нам нужен адрес вашей электропочты.',
	'registration_password' => 'Пароль',
	'registration_password_error' => 'Неверный пароль, допустим от 5 символов',
	'registration_password_error_different' => 'Пароли не совпадают',
	'registration_password_notice' => 'Должен содержать не менее 5 символов и не может совпадать с логином. Не используйте простые пароли, будьте разумны.',
	'registration_password_retry' => 'Повторите пароль',
	'registration_captcha' => 'Введите цифры и буквы',
	'registration_captcha_error' => 'Неверный код',
	'registration_submit' => 'Зарегистрироваться',
	'registration_ok' => 'Поздравляем! Регистрация прошла успешно',
			
	/**
	 * Голосование за пользователя
	 */
	'user_vote_up' => 'нравится',
	'user_vote_down' => 'не нравится',	
	'user_vote_error_already' => 'Вы уже голосовали за этого пользователя!',
	'user_vote_error_self' => 'Вы не можете голосовать за себя!',
	'user_vote_error_guest' => 'для голосования необходимо авторизоваться',	
	'user_vote_error_acl' => 'У вас не хватает рейтинга и силы для голосования!',	
	'user_vote_ok' => 'Ваш голос учтен',	
	'user_vote_count' => 'голосов',
	
	/**
	 * Меню профиля пользователя
	 */
	'user_menu_profile' => 'Профиль',
	'user_menu_profile_whois' => 'Whois',
	
	'user_menu_profile_favourites' => 'Избранные топики',
	'user_menu_profile_favourites_comments' => 'Избранные комментарии',
	
	'user_menu_profile_tags' => 'Метки',
	'user_menu_publication' => 'Публикации',
	'user_menu_publication_blog' => 'Блог',
	'user_menu_publication_comment' => 'Комментарии',
	'user_menu_publication_comment_rss' => 'RSS лента',
	
	/**
	 * Профиль
	 */
	'profile_privat' => 'Личное',
	'profile_sex' => 'Пол',
	'profile_sex_man' => 'мужской',
	'profile_sex_woman' => 'женский',
	'profile_birthday' => 'Дата рождения',
	'profile_place' => 'Местоположение',
	'profile_about' => 'О себе',
	'profile_site' => 'Сайт',
	'profile_activity' => 'Активность',
	'profile_friends' => 'Друзья',
	'profile_friends_self' => 'В друзьях у',
	'profile_invite_from' => 'Пригласил',
	'profile_invite_to' => 'Приглашенные',
	'profile_blogs_self' => 'Создал',
	'profile_blogs_join' => 'Состоит в',
	'profile_blogs_moderation' => 'Модерирует',
	'profile_blogs_administration' => 'Администрирует',
	'profile_date_registration' => 'Зарегистрирован',
	'profile_date_last' => 'Последний визит',
	'profile_social_contacts' => 'Контакты и социальные сервисы',
	
	
	
	/**
	 * Настройки
	 */
	'settings_profile_edit' => 'Изменение профиля',
	'settings_profile_name' => 'Имя',
	'settings_profile_name_notice' => 'Длина имени не может быть меньше 2 и больше 20 символов.',
	'settings_profile_mail' => 'E-mail',
	'settings_profile_mail_error' => 'Неверный формат e-mail',
	'settings_profile_mail_error_used' => 'Этот емайл уже занят',
	'settings_profile_mail_notice' => 'Ваш реальный почтовый адрес, на него будут приходить уведомления',
	'settings_profile_sex' => 'Пол',
	'settings_profile_sex_man' => 'мужской',
	'settings_profile_sex_woman' => 'женский',
	'settings_profile_sex_other' => 'не скажу',
	'settings_profile_birthday' => 'Дата рождения',
	'settings_profile_country' => 'Страна',
	'settings_profile_city' => 'Город',
	'settings_profile_icq' => 'ICQ',
	'settings_profile_site' => 'Сайт',
	'settings_profile_site_url' => 'URL сайта',
	'settings_profile_site_name' => 'название сайта',
	'settings_profile_about' => 'О себе',
	'settings_profile_password_current' => 'Текущий пароль',
	'settings_profile_password_current_error' => 'Неверный текущий пароль',
	'settings_profile_password_new' => 'Новый пароль',
	'settings_profile_password_new_error' => 'Неверный пароль, допустим от 5 символов',
	'settings_profile_password_confirm' => 'Еще раз новый пароль',
	'settings_profile_password_confirm_error' => 'Пароли не совпадают',
	'settings_profile_avatar' => 'Аватар',
	'settings_profile_avatar_error' => 'Не удалось загрузить аватар',
	'settings_profile_avatar_delete' => 'удалить',
	'settings_profile_foto' => 'Фото',
	'settings_profile_foto_error' => 'Не удалось загрузить фото',
	'settings_profile_foto_delete' => 'удалить',
	'settings_profile_submit' => 'сохранить профиль',
	'settings_profile_submit_ok' => 'Профиль успешно сохранён',
	'settings_invite' => 'Управление приглашениями',
	'settings_invite_available' => 'Доступно',
	'settings_invite_available_no' => 'У вас пока нет доступных инвайтов',
	'settings_invite_used' => 'Использовано',
	'settings_invite_mail' => 'Пригласить по e-mail адресу',
	'settings_invite_mail_error' => 'Неверный формат e-mail',
	'settings_invite_mail_notice' => 'На этот e-mail будет выслано приглашение для регистрации',
	'settings_invite_many' => 'много',
	'settings_invite_submit' => 'отправить приглашение',
	'settings_invite_submit_ok' => 'Приглашение отправлено',
	'settings_tuning' => 'Настройки сайта',
	'settings_tuning_notice' => 'Уведомления на e-mail',
	'settings_tuning_notice_new_topic' => 'при новом топике в блоге',
	'settings_tuning_notice_new_comment' => 'при новом комментарии в топике',
	'settings_tuning_notice_new_talk' => 'при новом личном сообщении',
	'settings_tuning_notice_reply_comment' => 'при ответе на комментарий',
	'settings_tuning_notice_new_friend' => 'при добавлении вас в друзья',
	'settings_tuning_submit' => 'сохранить настройки',
	'settings_tuning_submit_ok' => 'Настройки успешно сохранены',
	
	
	/**
	 * Меню настроек
	 */
	'settings_menu' => 'Настройки',
	'settings_menu_profile' => 'Профиль',
	'settings_menu_tuning' => 'Тюнинг',
	'settings_menu_invite' => 'Инвайты',
	
	/**
	 * Восстановление пароля
	 */
	'password_reminder' => 'Восстановление пароля',
	'password_reminder_email' => 'Ваш e-mail',
	'password_reminder_submit' => 'Получить ссылку на изменение пароля',
	'password_reminder_send_password' => 'Новый пароль отправлен на ваш адрес электронной почты.',
	'password_reminder_send_link' => 'Ссылка для восстановления пароля отправлена на ваш адрес электронной почты.',
	'password_reminder_bad_code' => 'Неверный код на восстановление пароля.',
	'password_reminder_bad_email' => 'Пользователь с таким e-mail не найден',
	
	/**
	 * Панель
	 */
	'panel_b' => 'жирный',
	'panel_i' => 'курсив',
	'panel_u' => 'подчеркнутый',
	'panel_s' => 'зачеркнутый',
	'panel_url' => 'вставить ссылку',
	'panel_url_promt' => 'Введите ссылку',
	'panel_code' => 'код',
	'panel_video' => 'видео',
	'panel_image' => 'изображение',
	'panel_cut' => 'кат',
	'panel_quote' => 'цитировать',
	'panel_list' => 'Список',
	'panel_list_ul' => 'UL LI',
	'panel_list_ol' => 'OL LI',
	'panel_title' => 'Заголовок',
	'panel_title_h4' => 'H4',
	'panel_title_h5' => 'H5',
	'panel_title_h6' => 'H6',
	
	/**
	 * Блоки
	 */
	'block_city_tags' => 'Города',
	'block_country_tags' => 'Страны',
	'block_blog_info' => 'Описание блога',
	'block_blog_info_note' => 'Заметка',
	'block_blog_info_note_text' => '<strong>Тег &lt;cut&gt; сокращает длинные записи</strong>, скрывая их целиком или частично под ссылкой («читать дальше»). Скрытая часть не видна в блоге, но доступна в полной записи на странице топика.',
	'block_blogs' => 'Блоги',
	'block_blogs_top' => 'Топ',
	'block_blogs_join' => 'Подключенные',
	'block_blogs_join_error' => 'Вы не состоите в коллективных блогах',
	'block_blogs_self' => 'Мои',
	'block_blogs_self_error' => 'У вас нет своих коллективных блогов',
	'block_blogs_all' => 'Все блоги',
	'block_stream' => 'Прямой эфир',
	'block_stream_topics' => 'Публикации',
	'block_stream_topics_no' => 'Нет топиков.',
	'block_stream_comments' => 'Комментарии',
	'block_stream_comments_no' => 'Нет комментариев.',
	'block_stream_comments_all' => 'Весь эфир',
	
	'block_friends' => 'Друзья',
	'block_friends_check' => 'Отметить всех',
	'block_friends_uncheck' => 'Снять отметку',
	'block_friends_empty' => 'Список ваших друзей пуст',
	
	'site_history_back' => 'Вернуться назад',
	'site_go_main' => 'перейти на главную',
	
	/**
	 * Поиск
	 */
	'search' => 'Поиск',
	'search_submit' => 'Найти',
	'search_results' => 'Результаты поиска',
	'search_results_empty' => 'Удивительно, но поиск не дал результатов',
	'search_results_count_topics' => 'топиков',
	'search_results_count_comments' => 'комментариев',
	
	/**
	 * Почта
	 */
	'talk_menu_inbox' => 'Почтовый ящик',
	'talk_menu_inbox_list' => 'Переписка',
	'talk_menu_inbox_create' => 'Новое письмо',
	'talk_menu_inbox_favourites' => 'Избранное',
	'talk_inbox' => 'Почтовый ящик',
	'talk_inbox_target' => 'Адресаты',
	'talk_inbox_title' => 'Тема',
	'talk_inbox_date' => 'Дата',
	'talk_inbox_delete' => 'Удалить переписку',
	'talk_inbox_delete_confirm' => 'Действительно удалить переписку?',
	'talk_comments' => 'Переписка',
	'talk_comment_add_text_error' => 'Текст комментария должен быть от 2 до 3000 символов',
	'talk_create' => 'Новое письмо',
	'talk_create_users' => 'Кому',
	'talk_create_users_error' => 'Необходимо указать, кому вы хотите отправить сообщение',
	'talk_create_users_error_not_found' => 'У нас нет пользователя с логином',
	'talk_create_title' => 'Заголовок',
	'talk_create_title_error' => 'Заголовок сообщения должен быть от 2 до 200 символов',
	'talk_create_text' => 'Сообщение',
	'talk_create_text_error' => 'Текст сообщения должен быть от 2 до 3000 символов',
	'talk_create_submit' => 'Отправить',
	'talk_time_limit' => 'Вам нельзя отправлять инбоксы слишком часто',
	
	'talk_favourite_inbox' => 'Избранные письма',
	'talk_favourite_add' => 'добавить в избранное',
	'talk_favourite_add_ok' => 'Письмо добавлено в избранное',
	'talk_favourite_add_no' => 'Этого письма нет в вашем избранном',
	'talk_favourite_add_already' => 'Это письмо уже есть в вашем избранном',
	'talk_favourite_del' => 'удалить из избранного',
	'talk_favourite_del_ok' => 'Письмо удалено из избранного',	
	
	'talk_filter_title' => 'Фильтровать',
	'talk_filter_erase' => 'Сбросить фильтр',
	'talk_filter_erase_form' => 'Очистить форму',
	'talk_filter_label_sender' => 'Отправитель',
	'talk_filter_label_keyword' => 'Искать в заголовке',
	'talk_filter_label_date' => 'Ограничения по дате',
	'talk_filter_notice_sender' => 'Укажите логин отправителя',
	'talk_filter_notice_keyword' => 'Введите одно или несколько слов',
	'talk_filter_notice_date' => 'Дата вводится в формате 25.12.2008',
	'talk_filter_submit' => 'Отфильтровать',
	'talk_filter_error' => 'Ошибка фильтрации',
	'talk_filter_error_date_format' => 'Указан неверный формат даты',
	'talk_filter_result_count' => 'Найдено писем: %%count%%',
	'talk_filter_result_empty' => 'По вашим критериям писем не найдено',
	
	'talk_user_in_blacklist' => 'Пользователь <b>%%login%%</b> не принимает от вас писем',
	'talk_blacklist_title' => 'Не принимать писем от:',
	'talk_blacklist_empty' => 'Принимать от всех',
	'talk_balcklist_add_label' => 'Добавить пользователей',
	'talk_balcklist_add_notice' => 'Введите один или несколько логинов',
	'talk_balcklist_add_submit' => 'Не принимать',
	'talk_blacklist_add_ok' => 'Пользователь <b>%%login%%</b> успешно добавлен',
	'talk_blacklist_user_already_have' => 'Пользователь <b>%%login%%</b> есть в вашем black list`е',
	'talk_blacklist_delete_ok' => 'Пользователь <b>%%login%%</b> успешно удален',
	'talk_blacklist_user_not_found' => 'Пользователя <b>%%login%%</b> нет в вашем black list`е',
	'talk_blacklist_add_self' => 'Нельзя добавлять в black list себя',
	
	'talk_speaker_title' => 'Участники разговора',
	'talk_speaker_add_label' => 'Добавить пользователя',
	'talk_speaker_delete_ok' => 'Участник <b>%%login%%</b> успешно удален',
	'talk_speaker_user_not_found' => 'Пользователь <b>%%login%%</b> не участвует в разговоре',
	'talk_speaker_user_already_exist' => ' <b>%%login%%</b> уже участник разговора',
	'talk_speaker_add_ok' => 'Участник <b>%%login%%</b> успешно добавлен',
	'talk_speaker_delete_by_self' => 'Участник <b>%%login%%</b> удалил этот разговор',
	'talk_speaker_add_self' => 'Нельзя добавлять в участники себя',
	
	'talk_not_found' => 'Разговор не найден',
	
	/**
	 * Рейтинг TOP
	 */
	'top' => 'Рейтинг',
	'top_blogs' => 'TOP Блогов',
	'top_topics' => 'TOP топиков',
	'top_comments' => 'TOP комментариев',
	
	/**
	 * Поиск по тегам
	 */
	'tag_title' => 'Поиск по тегам',
	
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
	'uploadimg_submit' => 'Загрузить',
	'uploadimg_cancel' => 'Отмена',
	'uploadimg_title' => 'Описание',
	
	/**
	 * Уведомления
	 */
	'notify_subject_comment_new' => 'К вашему топику оставили новый комментарий',
	'notify_subject_comment_reply' => 'Вам ответили на ваш комментарий',
	'notify_subject_topic_new' => 'Новый топик в блоге',
	'notify_subject_registration_activate' => 'Регистрация',
	'notify_subject_registration' => 'Регистрация',
	'notify_subject_invite' => 'Приглашение на регистрацию',
	'notify_subject_talk_new' => 'У вас новое письмо',
	'notify_subject_talk_comment_new' => 'У вас новый комментарий к письму',
	'notify_subject_user_friend_new' => 'Вас добавили в друзья',
	'notify_subject_blog_invite_new' => 'Вас пригласили вступить в блог',
	'notify_subject_reminder_code' => 'Восстановление пароля',
	'notify_subject_reminder_password' => 'Новый пароль',
	
	/**
	 * Страница администрирования плагинов
	 */
	'plugins_administartion_title' => 'Администрирование плагинов',
	'plugins_plugin_name' => 'Название',
	'plugins_plugin_author' => 'Автор',
	'plugins_plugin_version' => 'Версия',
	'plugins_plugin_action' => '',
	'plugins_plugin_activate' => 'Активировать',
	'plugins_plugin_deactivate' => 'Деактивировать',
	'plugins_unknown_action' => 'Указано неизвестное действие',
	'plugins_action_ok' => 'Успешно выполнено',
	'plugins_activation_overlap' => 'Конфликт с активированным плагином. Ресурс %%resource%% переопределен на %%delegate%% плагином %%plugin%%.',
	'plugins_activation_overlap_inherit' => 'Конфликт с активированным плагином. Ресурс %%resource%% используется как наследник в плагине %%plugin%%.',
	'plugins_activation_file_not_found' => 'Файл плагина не найден',
	'plugins_activation_version_error' => 'Для работы плагина необходимо ядро LiveStreet версии не ниже %%version%%',
	'plugins_activation_requires_error' => 'Для работы плагина необходим активированный плагин <b>%%plugin%%</b>',
	'plugins_submit_delete' => 'Удалить плагины',
	'plugins_delete_confirm' => 'Вы уверены, что желаете удалить указанные плагины?',
	
	
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
	'exit' => 'выход',
	'need_authorization' => 'Необходимо авторизоваться!',
	'or' => 'или',
	'window_close' => 'закрыть',
	'not_access' => 'Нет доступа',	
	'install_directory_exists' => 'Для работы с сайтом удалите директорию /install.',	
	'login' => 'Вход на сайт',	
	'date_day' => 'день',
	'date_month' => 'месяц',
	
	'month_array' => array(
		1=>array('январь','января','январе'),
		2=>array('февраль','февраля','феврале'),
		3=>array('март','марта','марте'),
		4=>array('апрель','апреля','апреле'),
		5=>array('май','мая','мае'),
		6=>array('июнь','июня','июне'),
		7=>array('июль','июля','июле'),
		8=>array('август','августа','августе'),
		9=>array('сентябрь','сентября','сентябре'),
		10=>array('октябрь','октября','октябре'),
		11=>array('ноябрь','ноября','ноябре'),
		12=>array('декабрь','декабря','декабре'),	
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
);

?>