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
	'common' => array(
		'add'    => 'Добавить',
		'remove' => 'Удалить',
		'edit'   => 'Редактировать',
		'save'   => 'Сохранить',
		'create' => 'Создать',
		'cancel' => 'Отменить',
		'empty'  => 'Тут ничего нет',
		'form_reset'  => 'Очистить форму',
		'preview_text'  => 'Предпросмотр',
		'error'  => array(
			'add'  => 'При добавлении возникла ошибка',
			'save'  => 'Ошибка сохранения',
			'remove'  => 'При удалении возникла ошибка',
			'system' => array(
				'code' => array(
					'404' => 'К сожалению, такой страницы не существует. Вероятно, она была удалена с сервера, либо ее здесь никогда не было.',
					'403' => 'Доступ к странице запрещен.',
					'500' => 'Произошла внутренняя ошибка сервера.',
				),
			)
		),
		'success'  => array(
			'add'  => 'Успешно добавлено',
			'save'  => 'Успешно сохранено',
			'remove'  => 'Удаление прошло успешно',
		)
	),

	/**
	 * Голосование
	 */
	'vote' => array(
		'up'     => 'Нравится',
		'down'   => 'Не нравится',
		'count'  => 'Всего проголосовало',
		'rating' => 'Рейтинг',

		// Всплывающие сообщения
		'notices' => array(
			'success'             => 'Ваш голос учтен',
			'error_time'          => 'Срок голосования истёк!',
			'error_already_voted' => 'Вы уже голосовали!',
			'error_acl'           => 'У вас не хватает рейтинга для голосования!',
		),
	),

	/**
	 * Избранное
	 */
	'favourite' => array(
		'favourite' => 'Избранное',

		'add'      => 'Добавить в избранное',
		'remove'   => 'Удалить из избранного',

		// Всплывающие сообщения
		'notices' => array(
			'add_success' => 'Добавлено в избранное',
			'remove_success' => 'Удалено из избранного'
		),
	),

	/**
	 * Поиск
	 */
	'search' => array(
		'search'   => 'Поиск',
		'find'     => 'Найти',

		// Сообщения
		'alerts' => array(
			'empty' => 'Поиск не дал результатов',
		),
	),

	/**
	 * Сортировка
	 */
	'sort' => array(
		'label'     => 'Сортировать',
		'by_name'   => 'по имени',
		'by_date'   => 'по дате',
		'by_rating' => 'по рейтингу',
	),

	/**
	 * Заметка пользователя
	 */
	'user_note' => array(
		'add'          => 'Написать заметку',

		// Всплывающие сообщения
		'notices' => array(
			'target_error' => 'Неверный пользователь для заметки', // TODO: Remove?
		),
	),

	/**
	 * Жалобы
	 */
	'report' => array(
		// Всплывающие сообщения
		'notices' => array(

		),
	),

	/**
	 * Блог
	 */
	'blog' => array(
		'blog'                 => 'Блог',
		'blogs'                => 'Блоги',
		'readers_declension'   => 'читатель;читателя;читателей',
		'administrators'       => 'Администраторы',
		'moderators'           => 'Модераторы',
		'owner'                => 'Создатель',
		'create_blog'          => 'Создать блог',
		'can_add'              => 'Вы можете создать свой блог!',
		'cant_add'             => 'Для возможности создавать блоги, ваш рейтинг должен быть больше %%rating%%.',
		'private'              => 'Закрытый блог',
		'personal_prefix'      => 'Блог им.',
		'personal_description' => 'Это ваш персональный блог.',
		'topics_total'         => 'Топиков',
		'date_created'         => 'Дата создания',
		'rating_limit'         => 'Ограничение на постинг',

		// Сообщения
		'alerts' => array(
			'private' => 'Это закрытый блог, у вас нет прав на просмотр контента',
			'banned'  => 'Вы забанены в этом блоге',
			'empty'   => 'Список блогов пуст',
		),


		/**
		 * Поиск
		 */
		'search' => array(
			'placeholder' => 'Поиск по названию',
		),

		/**
		 * Приглашения
		 */
		'invite' => array(
			'invite_users' => 'Пригласить пользователей',
			'repeat'       => 'Повторить',
			'empty'        => 'Нет приглашенных пользователей',

			// Поля
			'fields' => array(
				'add' => array(
					'label' => 'Список пользователей',
					'note'  => 'Введите один или несколько логинов'
				),
			),

			// Письмо с приглашением
			'email' => array(
				'title' => "Приглашение стать читателем блога '%%blog_title%%'",
				'text'  => "Пользователь %%login%% приглашает вас стать читателем закрытого блога '%%blog_title%%'.<br/><br/><a href='%%accept_path%%'>Принять</a> - <a href='%%reject_path%%'>Отклонить</a>"
			),

			// Всплывающие сообщения
			'notices' => array(
				'add'             => 'Пользователю %%login%% отправлено приглашение',
				'add_self'        => 'Нельзя отправить инвайт самому себе',
				'already_invited' => 'Пользователю %%login%% уже отправлен инвайт',
				'already_joined'  => 'Пользователь %%login%% уже состоит в блоге',
				'remove'          => 'Приглашение для пользователя %%login%% удалено',
				'reject'          => 'Пользователь %%login%% отклонил инвайт',
			),

			// Сообщения
			'alerts' => array(
				'already_joined' => 'Вы уже являетесь пользователем этого блога',
				'accepted'       => 'Приглашение принято',
				'rejected'       => 'Приглашение отклонено',
			)
		),

		/**
		 * Страница добавления/редактирования блога
		 */
		'add' => array(
			'title' => 'Создание нового блога',

			// Поля
			'fields' => array(
				'title' => array(
					'label'        => 'Название блога',
					'note'         => 'Название блога должно быть наполнено смыслом, чтобы можно было понять, о чем будет блог.',
					'error'        => 'Название блога должно быть от 2 до 200 символов',
					'error_unique' => 'Блог с таким названием уже существует',
				),
				'url' => array(
					'label'         => 'URL блога',
					'note'          => 'URL блога, по которому он будет доступен. Может содержать только буквы латинского алфавита, цифры, дефис; пробелы будут заменены на "_". По смыслу URL должен совпадать с названием блога, после его создания редактирование этого параметра будет недоступно',
					'error'         => 'URL блога должен быть от 2 до 50 символов и только на латинице + цифры и знаки "-", "_"',
					'error_badword' => 'URL блога должен отличаться от:',
					'error_unique'  => 'Блог с таким URL уже существует',
				),
				'category' => array(
					'label'               => 'Категория блога',
					'note'                => 'Блогу можно назначить категорию, что позволяет более глубоко структурировать сайт',
					'error'               => 'Не удалось найти категорию блога',
					'error_only_children' => 'Можно выбрать только конечную категорию (без дочерних)',
				),
				'type' => array(
					'label'       => 'Тип блога',
					'note_open'   => 'Открытый — к этому блогу может присоединиться любой желающий, топики видны всем',
					'note_close'  => 'Закрытый — присоединиться можно только по приглашению администрации блога, топики видят только подписчики',
					'value_open'  => 'Открытый',
					'value_close' => 'Закрытый',
					'error'       => 'Неизвестный тип блога',
				),
				'description' => array(
					'label' => 'Описание блога',
					'error' => 'Текст описания блога должен быть от 10 до 3000 символов',
				),
				'rating' => array(
					'label' => 'Ограничение по рейтингу',
					'note'  => 'Рейтинг, который необходим пользователю, чтобы написать в этот блог',
					'error' => 'Значение ограничения рейтинга должно быть числом',
				),
				'avatar' => array(
					'label' => 'Аватар',
					'error' => 'Не удалось загрузить аватар',
				),
			),

			// Сообщения
			'alerts' => array(
				'acl' => 'Вы еще не достаточно окрепли, чтобы создавать свой блог', // TODO: Remove?
			)
		),

		/**
		 * Страница удаления блога
		 */
		'remove' => array(
			'title'         => 'Удаление блога',
			'remove_topics' => 'Удалить топики',
			'move_to'       => 'Переместить топики в блог',
			'confirm'       => 'Вы уверены, что хотите удалить блог?',

			// Сообщения
			'alerts' => array(
				'success'             => 'Блог успешно удален',
				'not_empty'           => 'Вы не можете удалить блок с записями. Предварительно удалите из блога все записи.',
				'move_error'          => 'Не удалось переместить топики из удаляемого блога',
				'move_personal_error' => 'Нельзя перемещать топики в персональный блог', // TODO: Remove?
			)
		),

		/**
		 * Управление блогом
		 */
		'admin' => array(
			'title'              => 'Редактирование блога',
			'role_administrator' => 'Администратор',
			'role_moderator'     => 'Модератор',
			'role_reader'        => 'Читатель',
			'role_banned'        => 'Забаненный',

			// Навигация
			'nav' => array(
				'profile' => 'Профиль',
				'users'   => 'Пользователи',
			),

			// Сообщения
			'alerts' => array(
				'empty'          => 'В блоге никто не состоит', // TODO: Remove?
				'submit_success' => 'Права сохранены', // TODO: Remove?
			)
		),

		/**
		 * Голосование
		 */
		'vote' => array(
			// Всплывающие сообщения
			'notices' => array(
				'error_already' => 'Вы уже голосовали за этот блог!',
				'error_self'    => 'Вы не можете голосовать за свой блог!',
				'error_acl'     => 'У вас не хватает рейтинга для голосования!',
				'error_close'   => 'Вы не можете голосовать за закрытый блог',
			),
		),

		/**
		 * Вступить / покинуть блог
		 */
		'join' => array(
			'join'  => 'Вступить',
			'leave' => 'Покинуть',

			// Всплывающие сообщения
			'notices' => array(
				'join_success'  => 'Вы вступили в блог',
				'leave_success' => 'Вы покинули блог',
				'error_invite'  => 'Присоединиться к этому блогу можно только по приглашению!', // Remove?
				'error_self'    => 'Зачем вы хотите вступить в этот блог? Вы и так его хозяин!', // Remove?
			),
		),

		/**
		 * Категории
		 */
		'categories' => array(
			'category'   => 'Категория',
			'categories' => 'Категории',
			'empty'      => 'В данной категории нет блогов',
		),

		/**
		 * Список пользователей
		 */
		'users' => array(
			'readers'       => 'Читатели',
			'readers_all'   => 'Все читатели блога',
			'readers_total' => 'Читателей',
			'empty'         => 'Нет читателей',
		),

		/**
		 * Сортировка
		 */
		'sort' => array(
			'by_users' => 'по кол-ву читателей',
		),
	),

	/**
	 * Личные сообщения
	 */
	'messages' => array(

		// Форма поиска
		'search' => array(
			'title' => 'Поиск по сообщениям',

			// Поля
			'fields' => array(
				'sender' => array(
					'label' => 'Отправитель',
					'note'  => 'Укажите логин отправителя'
				),
				'keyword' => array(
					'label' => 'Искать в заголовке',
				),
				'keyword_text' => array(
					'label' => 'Искать в тексте',
				),
				'start' => array(
					'label'       => 'Ограничения по дате',
					'placeholder' => 'С числа'
				),
				'end' => array(
					'placeholder' => 'По число'
				),
				'favourite' => array(
					'label' => 'Искать только в избранном'
				),
			)
		),

		// Черный список
		'blacklist' => array(
			// Поля
			'fields' => array(
				'talk_blacklist_add' => array(
					'label' => 'Список пользователей',
					'note'  => 'Введите один или несколько логинов'
				),
			),

			// Сообщения
			'alerts' => array(
				'blocked' => 'Пользователь <b>%%login%%</b> не принимает от вас писем'
			),
		),

		// Всплывающие сообщения
		'notices' => array(

		),

		// Сообщения
		'alerts' => array(
			'empty' => 'Нет писем'
		),
	),

	/**
	 * Опросы
	 */
	'poll' => array(
		'polls' => 'Опросы',
		'answer' => 'Вопрос',
		'vote' => 'Голосовать',
		'abstain' => 'Воздержаться',
		'only_auth' => 'Голосование доступно только авторизованным пользователям',

		// Результат
		'result' => array(
			'voted_total'     => 'Проголосовало',
			'abstained_total' => 'Воздержалось',
			'sort'            => 'Включить\выключить сортировку',
		),

		// Форма добавления
		'form' => array(
			'title' => array(
				'add'  => 'Добавление опроса',
				'edit' => 'Редактирование опроса',
			),
			'answers_title' => 'Варианты ответов',

			// Поля
			'fields' => array(
				'type' => array(
					'label'      => 'Пользователь может выбрать',
					'label_one'  => 'Один вариант',
					'label_many' => 'Несколько вариантов'
				),
			),
		),

		// Всплывающие сообщения
		'notices' => array(
			// TODO: Fix max number
			'error_answers_max' => 'Максимально возможное число вариантов ответа 20',
		),
	),

	/**
	 * Комментарии
	 */
	'comments' => array(
		'comments_declension' => 'комментарий;комментария;комментариев',
		'count_new'          => 'Число новых комментариев',
		'title'              => 'Комментарии',
		'subscribe'          => 'Подписаться на новые комментарии',

		// Комментарий
		'comment' => array(
			'deleted'          => 'Комментарий был удален',
			'restore'          => 'Восстановить',
			'reply'            => 'Ответить',
			'scroll_to_parent' => 'Ответ на',
			'scroll_to_child'  => 'Обратно к ответу',
			'target_author'    => 'Автор',
			'url'              => 'Ссылка на комментарий',
		),

		// Сворачивание
		'folding' => array(
			'fold'       => 'Свернуть',
			'unfold'     => 'Развернуть',
			'fold_all'   => 'Свернуть все',
			'unfold_all' => 'Развернуть все',
		),

		// Всплывающие сообщения
		'notices' => array(
			'success_restore' => 'Комментарий восстановлен',
		),

		// Сообщения
		'alerts' => array(
			'unregistered' => 'Только зарегистрированные и авторизованные пользователи могут оставлять комментарии'
		),
	),

	/**
	 * Почта
	 */
	'talk_filter_error' => 'Ошибка фильтрации',
	'talk_filter_error_date_format' => 'Указан неверный формат даты',
	'talk_filter_result_count' => 'Найдено писем: %%count%%',
	'talk_filter_result_empty' => 'По вашим критериям писем не найдено',

	'talk_user_in_blacklist' => 'Пользователь <b>%%login%%</b> не принимает от вас писем',
	'talk_blacklist_user_already_have' => 'Пользователь <b>%%login%%</b> есть в вашем black list`е',
	'talk_blacklist_user_not_found' => 'Пользователя <b>%%login%%</b> нет в вашем black list`е',
	'talk_blacklist_add_self' => 'Нельзя добавлять в black list себя',

	'talk_favourite_inbox' => 'Избранные письма',

	'talk_menu_inbox' => 'Сообщения',
	'talk_menu_inbox_new' => 'Только новые',
	'talk_menu_inbox_list' => 'Переписка',
	'talk_menu_inbox_create' => 'Новое письмо',
	'talk_menu_inbox_favourites' => 'Избранное',
	'talk_menu_inbox_blacklist' => 'Блокировать',

	'talk_inbox' => 'Почтовый ящик',
	'talk_inbox_target' => 'Адресаты',
	'talk_inbox_title' => 'Тема',
	'talk_inbox_date' => 'Дата',
	'talk_inbox_make_read' => 'Отметить как прочитанное',
	'talk_inbox_delete' => 'Удалить выделенное',
	'talk_inbox_delete_confirm' => 'Действительно удалить переписку?',

	'talk_comments' => 'Переписка',
	'talk_comment_add_text_error' => 'Текст комментария должен быть от 2 до 3000 символов',

	'talk_create' => 'Новое письмо',
	'talk_create_users' => 'Кому',
	'talk_create_users_error' => 'Необходимо указать, кому вы хотите отправить сообщение',
	'talk_create_users_error_not_found' => 'У нас нет пользователя с логином',
	'talk_create_users_error_many' => 'Слишком много адресатов',
	'talk_create_title' => 'Заголовок',
	'talk_create_title_error' => 'Заголовок сообщения должен быть от 2 до 200 символов',
	'talk_create_text' => 'Сообщение',
	'talk_create_text_error' => 'Текст сообщения должен быть от 2 до 3000 символов',
	'talk_create_submit' => 'Отправить',

	'talk_time_limit' => 'Вам нельзя отправлять инбоксы слишком часто',

	'talk_speaker_title' => 'Участники разговора',
	'talk_speaker_edit' => 'Редактировать список',
	'talk_speaker_add_label' => 'Добавить пользователя',
	'talk_speaker_delete_ok' => 'Участник <b>%%login%%</b> успешно удален',
	'talk_speaker_user_not_found' => 'Пользователь <b>%%login%%</b> не участвует в разговоре',
	'talk_speaker_user_already_exist' => ' <b>%%login%%</b> уже участник разговора',
	'talk_speaker_not_found' => 'Пользователь не участвует в разговоре',
	'talk_speaker_add_ok' => 'Участник <b>%%login%%</b> успешно добавлен',
	'talk_speaker_delete_by_self' => 'Участник <b>%%login%%</b> удалил этот разговор',
	'talk_speaker_add_self' => 'Нельзя добавлять в участники себя',

	'talk_not_found' => 'Разговор не найден',
	'talk_deleted' => 'Отправитель удалил сообщение',

	/**
	 * Блоги
	 */
	'blog_no_topic' => 'Сюда еще никто не успел написать',

	/**
	 * Поиск
	 */
	'search_submit' => 'Найти',
	'search_results' => 'Результаты поиска',
	'search_results_empty' => 'Удивительно, но поиск не дал результатов',
	'search_results_count_topics' => 'топиков',
	'search_results_count_comments' => 'комментариев',


	/**
	 * Declensions
	 */

	'topic_declension' => 'топик;топика;топиков',
	'draft_declension' => 'черновик;черновика;черновиков',
	/**
	 * Меню блогов
	 */
	'blog_menu_all' => 'Все',
	'blog_menu_all_good' => 'Интересные',
	'blog_menu_all_discussed' => 'Обсуждаемые',
	'blog_menu_all_top' => 'TOP',
	'blog_menu_all_new' => 'Новые',
	'blog_menu_all_list' => 'Все блоги',
	'blog_menu_collective' => 'Коллективные',
	'blog_menu_collective_good' => 'Интересные',
	'blog_menu_collective_new' => 'Новые',
	'blog_menu_collective_discussed' => 'Обсуждаемые',
	'blog_menu_collective_top' => 'TOP',
	'blog_menu_personal' => 'Персональные',
	'blog_menu_personal_good' => 'Хорошие',
	'blog_menu_personal_new' => 'Новые',
	'blog_menu_personal_discussed' => 'Обсуждаемые',
	'blog_menu_personal_top' => 'TOP',
	'blog_menu_top_period_24h' => 'За 24 часа',
	'blog_menu_top_period_7d' => 'За 7 дней',
	'blog_menu_top_period_30d' => 'За 30 дней',
	'blog_menu_top_period_all' => 'За все время',
	'blog_menu_create' => 'Блог',


	/**
	 * Топики
	 */
	'topic_title' => 'Топики',
	'topic_read_more' => 'Читать дальше',
	'topic_author' => 'Автор топика',
	'topic_date' => 'дата',
	'topic_tags' => 'Теги',
	'topic_tags_empty' => 'нет',
	'topic_user' => 'авторский текст',
	'topic_share' => 'Поделиться',
	'topic_time_limit' => 'Вам нельзя создавать топики слишком часто',
	'topic_comment_read' => 'читать комментарии',
	'topic_comment_add' => 'Оставить комментарий',
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
	'topic_favourite_tags_block' => 'Теги избранного',
	'topic_favourite_tags_block_all' => 'Все теги',
	'topic_favourite_tags_block_user' => 'Мои теги',
	'error_favorite_topic_is_draft' => 'Топик из черновиков нельзя добавить в избранное',
	'block_stream_comments_all' => 'Весь эфир',
	'block_stream_topics_all' => 'Весь эфир',
	'comments_all' => 'Прямой эфир',
	'add_favourite_tags' => 'Добавить свои теги',
	/**
	 * Меню топиков
	 */
	'topic_menu_add' => 'Топик',
	'topic_menu_drafts' => 'Черновики',
	'topic_menu_published' => 'Опубликованные',
	/**
	 * Создание топика
	 */
	'topic_topic_create' => 'Создание топика',
	'topic_topic_edit' => 'Редактирование топика',
	'topic_create' => 'Написать',
	'topic_create_blog' => 'В какой блог публикуем?',
	'topic_create_blog_personal' => 'мой персональный блог',
	'topic_create_blog_error_unknown' => 'Пытаетесь запостить топик в неизвестный блог?',
	'topic_create_blog_error_nojoin' => 'Вы не состоите в этом блоге!',
	'topic_create_blog_error_noacl' => 'Вы еще недостаточно окрепли, чтобы постить в этот блог',
	'topic_create_blog_error_noallow' => 'Вы не можете писать в этот блог',
	'topic_create_blog_notice' => 'Для того чтобы написать в определенный блог, вы должны, для начала, вступить в него.',
	'topic_create_title' => 'Заголовок',
	'topic_create_title_notice' => 'Заголовок должен быть наполнен смыслом, чтобы можно было понять, о чем будет топик.',
	'topic_create_title_error' => 'Название топика должно быть от 2 до 200 символов',
	'topic_create_text' => 'Текст',
	'topic_create_text_notice' => 'Доступны html-теги',
	'topic_create_text_error' => 'Текст топика должен быть от 2 до 15000 символов',
	'topic_create_text_error_unique' => 'Вы уже писали топик с таким содержанием',
	'topic_create_type_error' => 'Неверный тип топика',
	'topic_create_tags' => 'Теги',
	'topic_create_tags_notice' => 'Теги нужно разделять запятой. Например: google, вконтакте, кирпич',
	'topic_create_tags_error_bad' => 'Проверьте правильность меток',
	'topic_create_tags_error' => 'Метки топика должны быть от 2 до 50 символов с общей длиной не более 500 символов',
	'topic_create_forbid_comment' => 'Запретить комментировать',
	'topic_create_forbid_comment_notice' => 'Если отметить эту галку, то нельзя будет оставлять комментарии к топику',
	'topic_create_publish_index' => 'Принудительно вывести на главную',
	'topic_create_publish_index_notice' => 'Если отметить эту галку, то топик сразу попадёт на главную страницу (опция доступна только администраторам)',
	'topic_create_submit_publish' => 'Опубликовать',
	'topic_create_submit_update' => 'Сохранить изменения',
	'topic_create_submit_save' => 'Сохранить в черновиках',
	'topic_create_submit_preview' => 'Предпросмотр',
	'topic_create_submit_preview_close' => 'Свернуть',
	'topic_create_submit_notice' => 'Если нажать кнопку «Сохранить в черновиках», текст топика будет виден только Вам, а рядом с его заголовком будет отображаться замочек. Чтобы топик был виден всем, нажмите «Опубликовать».',
	'topic_create_notice' => 'Не забывайте: тег <cut> сокращает длинные записи, скрывая их целиком или частично под ссылкой («читать дальше»). Скрытая часть не видна в блоге, но доступна в полной записи на странице топика.',
	'topic_create_error' => 'Возникли технические неполадки при добавлении топика. Пожалуйста, повторите позже.',
	'topic_edit' => 'Редактировать',
	'topic_preview' => 'Предпросмотр',
	'topic_delete' => 'Удалить',
	'topic_delete_confirm' => 'Вы действительно хотите удалить топик?',
	/**
	 * Голосование за топик
	 */
	'topic_vote_up' => 'нравится',
	'topic_vote_down' => 'не нравится',
	'topic_vote_abstain' => 'Воздержаться от голосования и посмотреть рейтинг',
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
	'user_privat_messages' => 'Сообщения',
	'user_privat_messages_new' => 'У вас есть новые сообщения',
	'user_settings' => 'Настройки',
	'user_settings_profile' => 'профиля',
	'user_settings_tuning' => 'сайта',
	'user_login' => 'Логин или эл. почта',
	'user_login_submit' => 'Войти',
	'user_login_remember' => 'Запомнить меня',
	'user_login_bad' => 'Что-то не так! Вероятно, неправильно указан логин (e-mail) или пароль.',
	'user_not_activated' => 'Вы не активировали вашу учетную запись. <br/> <a href="%%reactivation_path%%">Повторный запрос активации</a>',
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
	'user_friend_add_time_limit' => 'Вы слишком часто отправляете личные сообщения, попробуйте добавить в друзья позже',
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
	'user_search_title_hint' => 'Поиск по логину',
	'user_search_filter_all' => 'Все',
	'user_search_empty' => 'Поиск не дал результатов',
	'user_status_online' => 'Онлайн',
	'user_status_offline' => 'Оффлайн',
	'user_status_was_online_male' => 'Заходил',
	'user_status_was_online_female' => 'Заходила',
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
	'registration_mail' => 'E-mail',
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
	 * Повторный запрос активации
	 */
	"reactivation" => "Повторный запрос активации",
	"reactivation_submit" => "Получить ссылку на активацию",
	"reactivation_send_link" => "Ссылка для активации отправлена на ваш адрес электронной почты.",

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
	'user_menu_profile_whois' => 'Информация',
	'user_menu_profile_wall' => 'Стена',
	'user_menu_profile_friends' => 'Друзья',
	'user_menu_profile_stream' => 'Активность',
	'user_menu_profile_notes' => 'Заметки',
	'user_menu_profile_favourites' => 'Избранное',
	'user_menu_profile_favourites_topics' => 'Избранные топики',
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
	'profile_contacts' => 'Контакты',
	'profile_social' => 'Аккаунты',
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
	'profile_add_friend' => 'Добавить друга',
	'profile_user_follow' => 'Подписаться',
	'profile_user_unfollow' => 'Отписаться',
	/**
	 * UserFields
	 */
	'user_field_admin_title' => 'Поля контактов пользователей',
	'user_field_admin_title_add' => 'Добавить поле',
	'user_field_add' => 'Добавить',
	'user_field_cancel' => 'Отмена',
	'user_field_added' => 'Поле успешно добавлено',
	'user_field_update' => 'Изменить',
	'user_field_updated' => 'Поле успешно изменено',
	'user_field_delete' => 'Удалить',
	'user_field_delete_confirm' => 'Удалить поле?',
	'user_field_deleted' => 'Поле удалено',
	'userfield_form_name' => 'Имя',
	'userfield_form_type' => 'Тип',
	'userfield_form_title' => 'Заголовок',
	'userfield_form_pattern' => 'Шаблон (значение подставляется в токен {*})',
	'user_field_error_add_no_name' => 'Необходимо указать название поля',
	'user_field_error_add_no_title' => 'Необходимо указать заголовок поля',
	'user_field_error_name_exists' => 'Поле с таким именем уже существует',
	/**
	 * Жалобы на пользователя
	 */
	'user_complaint_title' => 'Пожаловаться',
	'user_complaint_type_title' => 'Причина',
	'user_complaint_text_title' => 'Текст жалобы',
	'user_complaint_target_error' => 'Неверный пользователь для жалобы',
	'user_complaint_type_error' => 'Неверный тип жалобы',
	'user_complaint_submit_result' => 'Ваша жалоба отправлена администрации',
	'user_complaint_type_list' => array(
		'spam'=>'Спам',
		'obscene'=>'Непристойное поведение',
		'other'=>'Другое',
	),
	/**
	 * Стена
	 */
	'wall_add_pid_error' => 'На данное сообщение невозможно ответить',
	'wall_add_error' => 'Ошибка добавления записи на стены',
	'wall_add_time_limit' => 'Вам нельзя слишком часто писать на стене',
	'wall_add_title' => 'Написать на стене',
	'wall_add_submit' => 'Отправить',
	'wall_add_quest' => 'Для возможности оставлять записи на стене необходимо зарегистрироваться.',
	'wall_list_empty' => 'Записей на стене нет, вы можете стать первым!',
	'wall_load_more' => 'К предыдущим записям',
	'wall_load_reply_more' => 'Показать все',
	'wall_action_delete' => 'Удалить',
	'wall_action_reply' => 'Ответить',
	'wall_reply_placeholder' => 'Ответить...',
	'wall_reply_submit' => 'Отправить',
	/**
	 * Настройки
	 */
	'settings_profile_edit' => 'Изменение профиля',
	'settings_profile_section_base' => 'Основная информация',
	'settings_profile_section_contacts' => 'Контакты',
	'settings_profile_name' => 'Имя',
	'settings_profile_name_notice' => 'Длина имени не может быть меньше 2 и больше 20 символов.',
	'settings_profile_mail' => 'E-mail',
	'settings_profile_mail_error' => 'Неверный формат e-mail',
	'settings_profile_mail_error_used' => 'Этот емайл уже занят',
	'settings_profile_mail_notice' => 'Ваш реальный почтовый адрес, на него будут приходить уведомления',
	'settings_profile_mail_change_from_notice' => 'На вашу старую почту отправлено подтверждение для смены емайла',
	'settings_profile_mail_change_to_notice' => 'Спасибо! <br/> На ваш новый емайл адрес отправлено подтверждение для смены старого емайла.',
	'settings_profile_mail_change_ok' => 'Ваш емайл изменен на <b>%%mail%%</b>',
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
	'settings_profile_avatar_delete' => 'Удалить',
	'settings_profile_avatar_change' => 'Изменить аватар',
	'settings_profile_avatar_upload' => 'Загрузить аватар',
	'settings_profile_avatar_resize_title' => 'Выбор области',
	'settings_profile_avatar_resize_apply' => 'Применить',
	'settings_profile_avatar_resize_cancel' => 'Отменить',
	'settings_profile_foto' => 'Фото',
	'settings_profile_foto_error' => 'Не удалось загрузить фото',
	'settings_profile_foto_delete' => 'Удалить',
	'settings_profile_photo_change' => 'Изменить фотографию',
	'settings_profile_photo_upload' => 'Загрузить фотографию',
	'settings_profile_field_error_max' => 'Нельзя добавить больше %%count%% одинаковых контактов',
	'settings_profile_submit' => 'Сохранить',
	'settings_profile_submit_ok' => 'Профиль успешно сохранён',
	'settings_invite' => 'Управление приглашениями',
	'settings_invite_notice' => 'Вы можете пригласить на сайт своих друзей и знакомых, для этого просто укажите их e-mail и нажмите кнопку',
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
	'settings_tuning_notice_new_topic' => 'При новом топике в блоге',
	'settings_tuning_notice_new_comment' => 'При новом комментарии в топике',
	'settings_tuning_notice_new_talk' => 'При новом личном сообщении',
	'settings_tuning_notice_reply_comment' => 'При ответе на комментарий',
	'settings_tuning_notice_new_friend' => 'При добавлении вас в друзья',
	'settings_tuning_general' => 'Общие настройки',
	'settings_tuning_general_timezone' => 'Часовой пояс',
	'settings_tuning_submit' => 'сохранить настройки',
	'settings_tuning_submit_ok' => 'Настройки успешно сохранены',
	'settings_account' => 'Настройки акаунта',
	'settings_account_password' => 'Пароль',
	'settings_account_password_notice' => 'Оставьте поля пустыми если не хотите изменять пароль.',
	'settings_account_submit' => 'Сохранить изменения',
	'settings_account_submit_ok' => 'Аккаунт сохранен',
	/**
	 * Меню настроек
	 */
	'settings_menu' => 'Настройки',
	'settings_menu_profile' => 'Профиль',
	'settings_menu_tuning' => 'Настройки сайта',
	'settings_menu_invite' => 'Инвайты',
	'settings_menu_account' => 'Аккаунт',
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
	/**
	 * Блоки
	 */
	'block_tags' => 'Теги',
	'block_tags_empty' => 'Нет тегов',
	'block_tags_search' => 'Поиск тегов',
	'block_city_tags' => 'Города',
	'block_country_tags' => 'Страны',
	'block_blog_info' => 'Описание блога',
	'block_blog_info_note' => 'Совет',
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
	'block_friends' => 'Выбрать получателей из списка друзей',
	'block_friends_check' => 'Отметить всех',
	'block_friends_uncheck' => 'Снять отметку',
	'block_friends_empty' => 'Список ваших друзей пуст',
	'block_category_blog' => 'Категории',
	'block_category_blog_all' => 'Все',
	'block_blog_navigator' => 'Навигация по блогам',
	'block_blog_navigator_button' => 'Смотреть',
	'site_history_back' => 'Вернуться назад',
	'site_go_main' => 'перейти на главную',
	/**
	 * Userfeed
	 */
	'userfeed_block_blogs_title' => 'Блоги',
	'userfeed_block_users_title' => 'Люди',
	'userfeed_block_users_append' => 'Добавить',
	'userfeed_block_users_friends' => 'Друзья',
	'userfeed_subscribes_already_subscribed' => 'Вы уже подписаны на топики этого пользователя',
	'userfeed_subscribes_updated' => 'Настройки ленты сохранены',
	'userfeed_get_more' => 'Получить ещё топики',
	'userfeed_title' => 'Лента',
	'userfeed_settings_note_follow_blogs' => 'Выберите блоги которые вы хотели бы читать',
	'userfeed_settings_note_follow_user' => 'Добавьте людей, топики которых вы хотели бы читать',
	'userfeed_settings_note_follow_friend' => 'Выберите друзей, топики которых вы хотели бы читать',
	'userfeed_no_subscribed_users' => 'Вы ещё не подписались на пользователей, чьи топики хотите видеть',
	'userfeed_no_blogs' => 'Вы не вступили ни в один блог',
	'userfeed_error_subscribe_to_yourself' => 'Вы не можете подписаться на себя',
	/**
	 * Stream
	 */
	'stream_block_config_title' => 'Настройка событий',
	'stream_block_users_title' => 'Люди',
	'stream_block_config_append' => 'Добавить',
	'stream_block_users_friends' => 'Друзья',
	'stream_subscribes_already_subscribed' => 'Вы уже подписаны на этого пользователя',
	'stream_subscribes_updated' => 'Настройки ленты сохранены',
	'stream_get_more' => 'Получить ещё события',
	'stream_event_type_add_wall' => 'Добавление записи на стену',
	'stream_event_type_add_topic' => 'Добавление топика',
	'stream_event_type_add_comment' => 'Добавление комментария',
	'stream_event_type_add_blog' => 'Добавление блога',
	'stream_event_type_vote_topic' => 'Голосование за топик',
	'stream_event_type_vote_comment' => 'Голосование за комментарий',
	'stream_event_type_vote_blog' => 'Голосование за блог',
	'stream_event_type_vote_user' => 'Голосование за пользователя',
	'stream_event_type_add_friend' => 'Добавление в друзья',
	'stream_event_type_join_blog' => 'Вступление в блог',
	'stream_no_subscribed_users' => 'Вы ещё не подписались на пользователей, чью активность хотите видеть',
	'stream_no_events' => 'Лента активности пуста',
	'stream_error_subscribe_to_yourself' => 'Вы не можете подписаться на себя',
	'stream_list_user' => 'Пользователь',
	'stream_list_event_add_wall' => 'добавил запись на стену',
	'stream_list_event_add_topic' => 'добавил новый топик',
	'stream_list_event_add_blog' => 'добавил новый блог',
	'stream_list_event_add_comment' => 'прокомментировал топик',
	'stream_list_event_vote_topic' => 'оценил топик',
	'stream_list_event_vote_blog' => 'оценил блог',
	'stream_list_event_vote_user' => 'оценил пользователя',
	'stream_list_event_vote_comment' => 'оценил комментарий к топику',
	'stream_list_event_join_blog' => 'вступил в блог',
	'stream_list_event_add_friend' => 'добавил в друзья пользователя',

	'stream_list_event_add_wall_female' => 'добавила запись на стену',
	'stream_list_event_add_topic_female' => 'добавила новый топик',
	'stream_list_event_add_blog_female' => 'добавила новый блог',
	'stream_list_event_add_comment_female' => 'прокомментировала топик',
	'stream_list_event_vote_topic_female' => 'оценила топик',
	'stream_list_event_vote_blog_female' => 'оценила блог',
	'stream_list_event_vote_user_female' => 'оценила пользователя',
	'stream_list_event_vote_comment_female' => 'оценила комментарий к топику',
	'stream_list_event_join_blog_female' => 'вступила в блог',
	'stream_list_event_add_friend_female' => 'добавила в друзья пользователя',

	'stream_menu' => 'Активность',
	'stream_menu_all' => 'Вся',
	'stream_menu_user' => 'Я слежу',
	'stream_settings_note_filter' => 'Выберите действия которые будут отслеживаться',
	'stream_settings_note_follow_user' => 'Добавьте людей за активностью которых вы хотели бы следить',
	'stream_settings_note_follow_friend' => 'Выберите друзей за активностью которых вы хотели бы следить',
	/**
	 * Админка
	 */
	'admin_header' => 'Админка',
	'admin_list_plugins' => 'Управление плагинами',
	'admin_list_userfields' => 'Настройка пользовательских полей',
	'admin_list_blogcategory' => 'Настройка категорий блогов',
	'admin_list_restorecomment' => 'Перестроение дерева комментариев',
	'admin_list_recalcfavourite' => 'Пересчитать счетчики избранных',
	'admin_list_recalcvote' => 'Пересчитать счетчики голосований',
	'admin_list_recalctopic' => 'Пересчитать количество топиков в блогах',
	/**
	 * Управление категориями блогов
	 */
	'admin_blogcategory_add' => 'Добавить новую категорию',
	'admin_blogcategory_items_title' => 'Название',
	'admin_blogcategory_items_url' => 'УРЛ',
	'admin_blogcategory_items_action' => 'Действие',
	'admin_blogcategory_items_delete_confirm' => 'Действительно удалить категорию со всеми вложенными?',
	'admin_blogcategory_form_add' => 'Добавление категории',
	'admin_blogcategory_form_edit' => 'Редактирование категории',
	'admin_blogcategory_form_field_parent' => 'Вложить в',
	'admin_blogcategory_form_field_title' => 'Название',
	'admin_blogcategory_form_field_url' => 'УРЛ',
	'admin_blogcategory_form_field_sort' => 'Сортировка',
	'admin_blogcategory_form_add_submit' => 'Добавить',
	'admin_blogcategory_form_edit_submit' => 'Сохранить',
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
	 * Уведомления
	 */
	'notify_subject_comment_new' => 'Новый комментарий к топику',
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
	'notify_subject_wall_reply' => 'Ответ на ваше сообщение на стене',
	'notify_subject_wall_new' => 'Новое сообщение на вашей стене',
	'notify_subject_reactvation' => 'Повторный запрос активации',
	'notify_subject_user_changemail' => 'Подтверждение смены емайла',
	'notify_subject_user_complaint' => 'Жалоба на пользователя',
	'notify_regards' => 'С уважением, администрация сайта',
	/**
	 * Админка
	 */
	'admin_title' => 'Админка',
	'admin_comment_restore_tree' => 'Дерево комментариев перестроенно',
	'admin_favourites_recalculated' => 'Счетчики избранных пересчитаны',
	'admin_votes_recalculated' => 'Счетчики голосований пересчитаны',
	'admin_topics_recalculated' => 'Количество топиков пересчитанно',
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
	'plugins_plugin_settings' => 'Настройки',
	'plugins_unknown_action' => 'Указано неизвестное действие',
	'plugins_action_ok' => 'Успешно выполнено',
	'plugins_activation_overlap' => 'Конфликт с активированным плагином. Ресурс %%resource%% переопределен на %%delegate%% плагином %%plugin%%.',
	'plugins_activation_overlap_inherit' => 'Конфликт с активированным плагином. Ресурс %%resource%% используется как наследник в плагине %%plugin%%.',
	'plugins_activation_file_not_found' => 'Файл плагина не найден',
	'plugins_activation_file_write_error' => 'Файл плагина не доступен для записи',
	'plugins_activation_version_error' => 'Для работы плагина необходимо ядро LiveStreet версии не ниже %%version%%',
	'plugins_activation_requires_error' => 'Для работы плагина необходим активированный плагин <b>%%plugin%%</b>',
	'plugins_submit_delete' => 'Удалить плагины',
	'plugins_delete_confirm' => 'Вы уверены, что желаете удалить указанные плагины?',
	/**
	 * Валидация данных
	 */
	'validate_empty_error' => 'Необходимо заполнить поле %%field%%',
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
	'validate_regexp_not_valid' => 'Поле %%field%% неверное',
	'validate_regexp_invalid_pattern' => 'У поля %%field%% неверное регулярное выражение',
	'validate_tags_count_more' => 'Поле %%field%% содержит слишком много тегов (максимально допустимо %%count%%)',
	'validate_tags_empty' => 'Поле %%field%% не содержит тегов, либо содержит неверные теги (размер тега допустим от %%min%% до %%max%% символов)',
	/**
	 * Подписка
	 */
	'subscribe_change_ok' => 'Изменение подписки прошло успешно',
	/**
	 * Toolbar
	 */
	'toolbar_scrollup_go' => 'Вверх',
	'toolbar_topic_next' => 'Следующий топик',
	'toolbar_topic_prev' => 'Предыдущий топик',
	/**
	 * География
	 */
	'geo_select_country' => 'Выберите страну',
	'geo_select_region' => 'Укажите регион',
	'geo_select_city' => 'Укажите город',
	/**
	 * Избранное, общее
	 */
	'favourite_form_tags_button_save' => 'Сохранить',
	'favourite_form_tags_button_cancel' => 'Отмена',
	'favourite_form_tags_button_show' => 'изменить свои теги',
	/**
	 * Создание
	 */
	'block_create' => 'Создать',
	'block_create_topic_topic' => 'Топик',
	'block_create_blog' => 'Блог',
	'block_create_talk' => 'Сообщение',
	/**
	 * Описание HTML тегов
	 */
	'tags_help_link_show' => 'Доступны html-теги',
	'tags_help_special' => 'Специальные теги',
	'tags_help_special_cut' => 'Используется для больших текстов, скрывает под кат часть текста, следующую за тегом (будет написано «Читать дальше»).',
	'tags_help_special_cut_name' => 'Так можно превратить надпись «Читать дальше» в любой текст.',
	'tags_help_special_cut_name_example_name' => 'Подробности',
	'tags_help_special_video' => 'Добавляет в пост видео со следующих хостингов: YouTube, RuTube, Vimeo и Я.Видео. <br/>Вставляйте между тегами только прямую ссылку на видеоролик.',
	'tags_help_special_ls_user' => 'Выводит имя пользователя посреди текста.',
	'tags_help_special_ls_user_example_user' => 'Ник',
	'tags_help_standart' => 'Стандартные теги',
	'tags_help_standart_h' => 'Заголовки разного уровня.',
	'tags_help_standart_img' => 'Вставка изображения, в атрибуте src нужно указывать полный путь к изображению. Возможно выравнивание картинки атрибутом align.',
	'tags_help_standart_a' => 'Вставка ссылки, в атрибуте href указывается желаемый интернет-адрес или якорь (anchor) для навигации по странице.',
	'tags_help_standart_a_example_href' => 'Ссылка',
	'tags_help_standart_b' => 'Выделение важного текста, на странице выделяется жирным начертанием.',
	'tags_help_standart_i' => 'Выделение важного текста, на странице выделяется курсивом.',
	'tags_help_standart_s' => 'Текст между этими тегами будет отображаться как зачеркнутый.',
	'tags_help_standart_u' => 'Текст между этими тегами будет отображаться как подчеркнутый.',
	'tags_help_standart_hr' => 'Тег для вставки горизонтальной линии.',
	'tags_help_standart_blockquote' => 'Используйте этот тег для выделения цитат.',
	'tags_help_standart_table' => 'Набор тегов для создания таблицы. Тег &lt;td&gt; обозначает ячейку таблицы, тег &lt;th&gt; - ячейку в заголовке, &lt;tr&gt; - строчку таблицы. Все содержимое таблицы помещайте в тег &lt;table&gt;.',
	'tags_help_standart_ul' => 'Ненумерованный список; каждый элемент списка задается тегом &lt;li&gt;, набор элементов списка помещайте в тег &lt;ul&gt;.',
	'tags_help_standart_ol' => 'Нумерованный список; каждый элемент списка задается тегом &lt;li&gt;, набор элементов списка помещайте в тег &lt;ol&gt;.',
	/**
	 * Системные сообщения
	 */
	'system_error_event_args' => 'Некорректное число аргументов при добавлении евента',
	'system_error_event_method' => 'Добавляемый метод евента не найден',
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