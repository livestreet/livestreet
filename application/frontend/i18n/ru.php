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
		'remove_confirm' => 'Действительно удалить?',
		'edit'   => 'Редактировать',
		'save'   => 'Сохранить',
		'create' => 'Создать',
		'cancel' => 'Отменить',
		'empty'  => 'Тут ничего нет',
		'send'   => 'Отправить',
		'form_reset'  => 'Очистить форму',
		'preview_text'  => 'Предпросмотр',
		'times_declension'   => 'раз;раза;раз',
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
		'up'      => 'Нравится',
		'down'    => 'Не нравится',
		'abstain' => 'Воздержаться от голосования и посмотреть рейтинг',
		'count'   => 'Всего проголосовало',
		'rating'  => 'Рейтинг',

		// Всплывающие сообщения
		'notices' => array(
			'success'             => 'Ваш голос учтен',
			'success_abstain'             => 'Вы воздержались для просмотра рейтинга',
			'error_time'          => 'Срок голосования истёк!',
			'error_already_voted' => 'Вы уже голосовали!',
			'error_acl'           => 'У вас не хватает рейтинга для голосования!',
			'error_auth'           => 'Для голосования необходимо авторизоваться',
			'error_self'           => 'Вы не можете голосовать за свое',
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
			'remove_success' => 'Удалено из избранного',
			'already_added' => 'Уже добавлено в избранное!',
			'already_removed' => 'Уже удалено из избранного!',
		),
	),

	/**
	 * Поиск
	 */
	'search' => array(
		'search'   => 'Поиск',
		'find'     => 'Найти',
		'result'   => array(
			'topics'=>'Топики',
			'comments'=>'Комментарии',
		),
		// Сообщения
		'alerts' => array(
			'empty' => 'Поиск не дал результатов',
			'query_incorrect' => 'Поисковый запрос должен быть от 3-х символов',
		),
	),

	/**
	 * Сортировка
	 */
	'sort' => array(
		'label'     => 'Сортировать',
		'by_login'   => 'по логину',
		'by_name'   => 'по имени',
		'by_title'   => 'по названию',
		'by_date'   => 'по дате',
		'by_date_registration'   => 'по дате регистрации',
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
		'rss'                  => 'RSS',

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
			'placeholder'  => 'Поиск по названию',
			'result_title' => 'Найден %%count%% блог;Найдено %%count%% блога;Найдено %%count%% блогов'
		),

		/**
		 * Приглашения
		 */
		'invite' => array(
			'invite_users' => 'Пригласить пользователей',
			'repeat'       => 'Повторить',
			'empty'        => 'Нет приглашенных пользователей',

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
		 * Удаление блога
		 */
		'remove' => array(
			'title'         => 'Удаление блога',
			'remove_topics' => 'Удалить топики',
			'move_to'       => 'Переместить топики в блог',

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
			'by_topics' => 'по кол-ву топиков',
		),

		/**
		 * Меню со списокм топиков
		 */
		 'menu' => array(
			 'all' => 'Все',
			 'all_good' => 'Интересные',
			 'all_discussed' => 'Обсуждаемые',
			 'all_top' => 'TOP',
			 'all_new' => 'Новые',
			 'all_list' => 'Все блоги',
			 'top_period_1' => 'За 24 часа',
			 'top_period_7' => 'За 7 дней',
			 'top_period_30' => 'За 30 дней',
			 'top_period_all' => 'За все время',
		 ),
	),

	/**
	 * Личные сообщения
	 */
	'talk' => array(
		'title' => 'Сообщения',
		'participants' => '%%count%% участник;%%count%% участника;%%count%% участников',
		'new_messages' => 'У вас есть новые сообщения',
		'send_message' => 'Отправить сообщение',

		// Меню
		'nav' => array(
			'inbox'      => 'Сообщения',
			'new'        => 'Только новые',
			'add'        => 'Новое письмо',
			'favourites' => 'Избранное',
			'blacklist'  => 'Блокировать'
		),

		// Форма добавления
		'add' => array(
			'title' => 'Новое письмо',

			// Поля
			'fields' => array(
				'users' => array(
					'label' => 'Кому'
				),
				'title' => array(
					'label' => 'Заголовок',
				),
				'text' => array(
					'label' => 'Сообщение',
				),
			),

			// Сообщения
			'notices' => array(
				'users_error'           => 'Необходимо указать, кому вы хотите отправить сообщение',
				'users_error_not_found' => 'У нас нет пользователя с логином', // TODO: Move to common
				'users_error_many'      => 'Слишком много адресатов',

				'title_error' => 'Заголовок сообщения должен быть от 2 до 200 символов',

				'text_error' => 'Текст сообщения должен быть от 2 до 3000 символов',
			)
		),

		// Сообщение
		'message' => array(
			// Сообщения
			'notices' => array(
				'error_text' => 'Текст сообщения должен быть от 2 до 3000 символов',
			)
		),

		// Экшнбар
		'actionbar' => array(
			'read'         => 'Прочитанные',
			'unread'       => 'Не прочитанные',
			'mark_as_read' => 'Отметить как прочитанное',
		),

		// Форма поиска
		'search' => array(
			'title' => 'Поиск по сообщениям',

			// Поля
			'fields' => array(
				'sender' => array(
					'label' => 'Отправитель',
					'note'  => 'Укажите логин отправителя'
				),
				'receiver' => array(
					'label' => 'Получатель',
					'note'  => 'Укажите логин получателя'
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
			),

			// Сообщения
			'notices' => array(
				'error'             => 'При поиске произошла ошибка',
				'error_date_format' => 'Указан неверный формат даты',
				'result_count'      => 'Найдено писем: %%count%%',
				'result_empty'      => 'По вашим критериям писем не найдено'
			)
		),

		// Черный список
		'blacklist' => array(
			'title' => 'Черный список',
			'note'  => 'Добавьте пользователей от которых вы не хотите получать сообщения',

			// Сообщения
			'notices' => array(
				'blocked' => 'Пользователь <b>%%login%%</b> не принимает от вас писем',
				'user_not_found' => 'Пользователя <b>%%login%%</b> нет в вашем black list`е',
			),
		),

		// Список участников разговора
		'users' => array(
			'title'          => 'Участники разговора',
			'user_not_found' => 'Пользователь не участвует в разговоре',

			// Сообщения
			'notices' => array(
				'user_not_found' => 'Пользователь <b>%%login%%</b> не участвует в разговоре',
				'deleted'        => 'Участник <b>%%login%%</b> удалил этот разговор',
			)
		),

		// Сообщения
		'notices' => array(
			'time_limit' => 'Вам нельзя отправлять сообщения слишком часто',
			'empty'      => 'Нет писем',
			'deleted'    => 'Отправитель удалил сообщение',
			'not_found'  => 'Разговор не найден'
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
		'comments_declension' => '%%count%% комментарий;%%count%% комментария;%%count%% комментариев',
		'count_new'           => 'Число новых комментариев',
		'title'               => 'Комментарии',
		'subscribe'           => 'Подписаться',
		'unsubscribe'         => 'Отписаться',

		// Комментарий
		'comment' => array(
			'deleted'          => 'Комментарий был удален',
			'restore'          => 'Восстановить',
			'reply'            => 'Ответить',
			'scroll_to_parent' => 'Ответ на',
			'scroll_to_child'  => 'Обратно к ответу',
			'target_author'    => 'Автор',
			'url'              => 'Ссылка на комментарий',
			'edit_info'      => 'Комментарий отредактирован',
		),

		// Сворачивание
		'folding' => array(
			'fold'       => 'Свернуть',
			'unfold'     => 'Развернуть',
			'fold_all'   => 'Свернуть все',
			'unfold_all' => 'Развернуть все',
		),

		// Форма добавления
		'form' => array(
			'title' => 'Оставить комментарий',
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
	 * Пополняемый список пользователей
	 */
	'user_list_add' => array(
		// Форма добавления
		'form' => array(
			// Поля
			'fields' => array(
				'add' => array(
					'label' => 'Список пользователей',
					'note'  => 'Введите один или несколько логинов'
				),
			),
		),

		// Всплывающие сообщения
		'notices' => array(
			'success_add' => 'Пользователь %%login%% успешно добавлен',
			'error_already_added' => 'Пользователь %%login%% уже есть в списке',
			'error_self' => 'Нельзя добавлять себя',
		),
	),

	/**
	 * Мэйлы
	 */
	'emails' => array(
		'common' => array(
			'comment_text' => 'Текст комментария',
			'regards' => 'С уважением, администрация сайта',
		),

		// Приглашение в закрытый блог
		'blog_invite_new' => array(
			'subject' => 'Вас пригласили вступить в блог',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				приглашает вас вступить в блог <a href="%%blog_url%%">%%blog_name%%</a>.
				<br><br>
				<a href="%%invite_url%%">Посмотреть приглашение</a>
				<br>
				Не забудьте предварительно авторизоваться!',
		),

		// Оповещение о новом комментарии в топике
		'comment_new' => array(
			'subject' => 'Новый комментарий к топику',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				оставил новый комментарий к топику <b>%%topic_name%%</b>,
				прочитать его можно перейдя по <a href="%%comment_url%%">этой ссылке</a>
				<br><br>
				%%comment_text%%
				%%unsubscribe%%',
			'unsubscribe' => '<a href="%%unsubscribe_url%%">Отписаться от новых комментариев к этому топику</a>'
		),

		// Оповещение об ответе на комментарий
		'comment_reply' => array(
			'subject' => 'Вам ответили на ваш комментарий',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				ответил на ваш комментарий в топике <b>%%topic_name%%</b>,
				прочитать его можно перейдя по <a href="%%comment_url%%">этой ссылке</a>
				<br><br>
				%%comment_text%%'
		),

		// Приглашение на сайт
		'invite' => array(
			'subject' => 'Приглашение на регистрацию',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				пригласил вас зарегистрироваться на сайте <a href="%%website_url%%">%%website_name%%</a>
				<br><br>
				Код приглашения:  <b>%%invite_code%%</b>
				<br><br>
				Для регистрации вам будет необходимо ввести код приглашения на <a href="%%login_url%%">странице входа</a>'
		),

		// Повторная активация
		'reactivation' => array(
			'subject' => 'Повторный запрос активации',
			'text' =>
				'Вы запросили повторную активацию на сайте <a href="%%website_url%%">%%website_name%%</a>
				<br><br>
				Ссылка на активацию аккаунта:
				<br>
				<a href="%%activation_url%%">%%activation_url%%</a>'
		),

		// Регистрация
		'registration' => array(
			'subject' => 'Регистрация',
			'text' =>
				'Вы зарегистрировались на сайте <a href="%%website_url%%">%%website_name%%</a>
				<br><br>
				Ваши регистрационные данные:
				<br><br>
				Логин: <b>%%user_name%%</b><br>
				Пароль: <b>%%user_password%%</b>'
		),

		// Подтверждение регистрации
		'registration_activate' => array(
			'subject' => 'Регистрация',
			'text' =>
				'Вы зарегистрировались на сайте <a href="%%website_url%%">%%website_name%%</a>
				<br><br>
				Ваши регистрационные данные:
				<br><br>
				Логин: <b>%%user_name%%</b><br>
				Пароль: <b>%%user_password%%</b>
				<br><br>
				Для завершения регистрации вам необходимо активировать аккаунт пройдя по ссылке:<br>
				<a href="%%activation_url%%">%%activation_url%%</a>'
		),

		// Смена пароля
		'reminder_code' => array(
			'subject' => 'Восстановление пароля',
			'text' =>
				'Если вы хотите сменить себе пароль на сайте <a href="%%website_url%%">%%website_name%%</a>, то перейдите по ссылке ниже:<br>
				<a href="%%recover_url%%">%%recover_url%%</a>'
		),

		// Новый пароль
		'reminder_password' => array(
			'subject' => 'Новый пароль',
			'text' =>
				'Вам присвоен новый пароль: <b>%%password%%</b>'
		),

		// Оповещение о новом сообщении в диалоге
		'talk_comment_new' => array(
			'subject' => 'У вас новый комментарий к письму',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				оставил новый комментарий к письму <b>%%talk_name%%</b>,
				прочитать его можно перейдя по <a href="%%message_url%%">этой ссылке</a>
				<br><br>
				%%message_text%%
				<br><br>
				Не забудьте предварительно авторизоваться!'
		),

		// Оповещение о новом сообщении
		'talk_new' => array(
			'subject' => 'У вас новое письмо',
			'text' =>
				'Вам пришло новое письмо от пользователя <a href="%%user_url%%">%%user_name%%</a>,
				прочитать его можно перейдя по <a href="%%talk_url%%">этой ссылке</a>
				<br><br>
				Тема письма: <b>%%talk_name%%</b><br>
				%%talk_text%%
				<br><br>
				Не забудьте предварительно авторизоваться!'
		),

		// Оповещение о новом топике
		'topic_new' => array(
			'subject' => 'Новый топик в блоге',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				опубликовал в блоге <b>%%blog_name%%</b>,
				новый топик &mdash; <a href="%%topic_url%%">%%topic_name%%</a>'
		),

		// Смена почты
		'user_changemail' => array(
			'subject' => 'Подтверждение смены емайла',
			'text' =>
				'Вами отправлен запрос на смену e-mail адреса пользователя <a href="%%user_url%%">%%user_name%%</a>
				на сайте <a href="%%website_url%%">%%website_name%%</a>.
				<br><br>
				Старый e-mail: <b>%%mail_old%%</b><br>
				Новый e-mail: <b>%%mail_new%%</b>
				<br><br>
				Для подтверждения смены емайла пройдите по ссылке:<br>
				<a href="%%change_url%%">%%change_url%%</a>'
		),

		// Жалоба
		'user_complaint' => array(
			'subject' => 'Жалоба на пользователя',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				пожаловался на пользователя <a href="%%user_target_url%%">%%user_target_url%%</a>.
				<br><br>
				<b>Причина:</b> %%complaint_title%%<br>
				%%complaint_text%%',
			'more' => 'Подробнее'
		),

		// Заявка в друзья
		'user_friend_new' => array(
			'subject' => 'Вас добавили в друзья',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				<br><br>
				<em>%%text%%</em>
				<br><br>
				<a href="%%url%%">Посмотреть заявку</a>
				<br><br>
				Не забудьте предварительно авторизоваться!'
		),

		// Новое сообщение на стене
		'wall_new' => array(
			'subject' => 'Новое сообщение на вашей стене',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				оставил сообщение на <a href="%%wall_url%%">вашей стене</a>
				<br><br>
				Текст сообщения:<br>
				%%message_text%%'
		),

		// Ответ на сообщение на стене
		'wall_reply' => array(
			'subject' => 'Ответ на ваше сообщение на стене',
			'text' =>
				'Пользователь <a href="%%user_url%%">%%user_name%%</a>
				ответил на ваше сообщение на <a href="%%wall_url%%">стене</a>
				<br><br>
				<b>Ваше сообщение:</b><br>
				<em>%%message_parent_text%%</em>
				<br><br>
				Текст ответа:<br>
				<em>%%message_text%%</em>'
		)
	),

	/**
	 * Стена
	 */
	'wall' => array(
		'title' => 'Стена',

		// Форма
		'form' => array(
			// Поля
			'fields' => array(
				'text' => array(
					'placeholder' => 'Написать на стене',
					'placeholder_reply' => 'Ответить...',
				),
			),
		),

		// Всплывающие сообщения
		'notices' => array(
			'error_add_pid'        => 'На данное сообщение невозможно ответить',
			'error_add_time_limit' => 'Вам нельзя слишком часто писать на стене'
		),

		// Сообщения
		'alerts' => array(
			'unregistered' => 'Только зарегистрированные и авторизованные пользователи могут оставлять записи на стене'
		),
	),

	/**
	 * Авторизация
	 */
	'auth' => array(
		'authorization' => 'Авторизация',

		// Вход
		'login' => array(
			'title' => 'Войти',

			'form' => array(
				// Поля
				'fields' => array(
					'login' => array(
						'label' => 'Логин или эл. почта'
					),
					'remember' => array(
						'label' => 'Запомнить меня'
					),
					'submit' => array(
						'text' => 'Войти'
					)
				)
			),

			// Всплывающие сообщения
			'notices' => array(
				'error_login'         => 'Неправильно указан логин (e-mail) или пароль!',
				'error_not_activated' => 'Вы не активировали вашу учетную запись. <br/> <a href="%%reactivation_path%%">Повторный запрос активации</a>'
			),
		),

		// Повторный запрос активации
		'reactivation' => array(
			'title' => 'Повторный запрос активации',

			'form' => array(
				// Поля
				'fields' => array(
					'mail' => array(
						'label' => 'Ваш e-mail'
					),
					'submit' => array(
						'text' => 'Получить ссылку на активацию'
					)
				)
			),

			// Всплывающие сообщения
			'notices' => array(
				'success' => 'Ссылка для активации отправлена на ваш адрес электронной почты',
			)
		),

		// Сброс пароля
		'reset' => array(
			'title' => 'Восстановление пароля',

			'form' => array(
				// Поля
				'fields' => array(
					'mail' => array(
						'label' => 'Ваш e-mail'
					),
					'submit' => array(
						'text' => 'Получить ссылку на изменение пароля'
					)
				)
			),

			// Всплывающие сообщения
			'notices' => array(
				'success_send_password' => 'Новый пароль отправлен на ваш адрес электронной почты',
				'success_send_link'     => 'Ссылка для восстановления пароля отправлена на ваш адрес электронной почты',
			),

			// Сообщения
			'alerts' => array(
				'error_bad_code' => 'Неверный код на восстановление пароля.',
			)
		),

		// Регистрация по приглашению
		'invite' => array(
			'title' => 'Регистрация по приглашению',

			'form' => array(
				// Поля
				'fields' => array(
					'code' => array(
						'label' => 'Код приглашения'
					),
					'submit' => array(
						'text' => 'Проверить код'
					)
				),
			),

			// Сообщения
			'alerts' => array(
				'error_code' => 'Неверный код приглашения',
			)
		),

		// Регистрация
		'registration' => array(
			'title' => 'Регистрация',

			'form' => array(
				// Поля
				'fields' => array(
					'password_confirm' => array(
						'label' => 'Повторите пароль'
					),
					'submit' => array(
						'text' => 'Зарегистрироваться'
					)
				)
			),

			'confirm' => array(
				'title' => 'Активация аккаунта',
				'text'  => 'Вы почти зарегистрировались, осталось только активировать аккаунт. Инструкции по активации отправлены по электронной почте на адрес, указанный при регистрации.'
			),

			// Сообщения
			'notices' => array(
				'already_registered' => 'Вы уже зарегистрированы у нас и даже авторизованы!',
				'success'            => 'Поздравляем! Регистрация прошла успешно',
				'success_activate'   => 'Поздравляем! Ваш аккаунт успешно активирован.',
				'error_login'        => 'Неверный логин, допустим от 3 до 30 символов',
				'error_login_used'   => 'Этот логин уже занят',
				'error_mail_used'    => 'Этот e-mail уже используется',
				'error_reactivate'   => 'Ваш аккаунт уже активирован',
				'error_code'         => 'Неверный код активации!'
			),
		),

		// Общие лэйблы
		'labels' => array(
			'login'    => 'Логин',
			'password' => 'Пароль',
			'captcha'  => 'Введите цифры и буквы',
		),

		// Общие всплывающие сообщения
		'notices' => array(
			'error_bad_email' => 'Пользователь с таким e-mail не найден',
		),
	),

	/**
	 * Активность
	 */
	'activity' => array(
		'title' => 'Активность',

		// Навигация
		'nav' => array(
			'all'      => 'Вся',
			'personal' => 'Персональная'
		),

		// Настройки
		'settings' => array(
			'title' => 'Настройка событий',
			'note'  => 'Выберите действия которые будут отслеживаться',
			'options' => array(
				'add_wall'           => 'Добавление записи на стену',
				'add_topic'          => 'Добавление топика',
				'add_comment'        => 'Добавление комментария',
				'add_blog'           => 'Добавление блога',
				'vote_topic'         => 'Голосование за топик',
				'vote_comment_topic' => 'Голосование за комментарий к топику',
				'vote_blog'          => 'Голосование за блог',
				'vote_user'          => 'Голосование за пользователя',
				'add_friend'         => 'Добавление в друзья',
				'join_blog'          => 'Вступление в блог',
			)
		),

		// Пользователи
		'users' => array(
			'title' => 'Пользователи',
			'note'  => 'Добавьте людей за активностью которых вы хотели бы следить',
		),

		'events' => array(
			'add_wall_male'             => 'добавил запись на <a href="%%url%%">стену</a> %%user%%',
			'add_wall_female'           => 'добавила запись на <a href="%%url%%">стену</a> %%user%%',

			'add_wall_self_male'        => 'добавил запись себе на <a href="%%url%%">стену</a>',
			'add_wall_self_female'      => 'добавила запись себе на <a href="%%url%%">стену</a>',

			'add_topic_male'            => 'добавил новый топик %%topic%%',
			'add_topic_female'          => 'добавила новый топик %%topic%%',

			'add_comment_male'          => 'прокомментировал топик %%topic%%',
			'add_comment_female'        => 'прокомментировала топик %%topic%%',

			'add_blog_male'             => 'добавил новый блог %%blog%%',
			'add_blog_female'           => 'добавила новый блог %%blog%%',

			'vote_topic_male'           => 'оценил топик %%topic%%',
			'vote_topic_female'         => 'оценила топик %%topic%%',

			'vote_comment_topic_male'   => 'оценил комментарий к топику %%topic%%',
			'vote_comment_topic_female' => 'оценила комментарий к топику %%topic%%',

			'vote_blog_male'            => 'оценил блог %%blog%%',
			'vote_blog_female'          => 'оценила блог %%blog%%',

			'vote_user_male'            => 'оценил пользователя %%user%%',
			'vote_user_female'          => 'оценила пользователя %%user%%',

			'join_blog_male'            => 'вступил в блог %%blog%%',
			'join_blog_female'          => 'вступила в блог %%blog%%',

			'add_friend_male'           => 'добавил в друзья пользователя %%user%%',
			'add_friend_female'         => 'добавила в друзья пользователя %%user%%'
		),

		// Сообщения
		'notices' => array(
			'error_already_subscribed' => 'Вы уже подписаны на этого пользователя',
		)
	),

	/**
	 * Лента
	 */
	'feed' => array(
		'title' => 'Лента',

		// Блоги
		'blogs' => array(
			'title' => 'Блоги',
			'note'  => 'Выберите блоги которые вы хотели бы читать',
			'empty' => 'Вы не вступили ни в один блог'
		),

		// Пользователи
		'users' => array(
			'title' => 'Пользователи',
			'note'  => 'Добавьте людей, топики которых вы хотели бы читать'
		)
	),

	/**
	 * Топик
	 */
	'topic' => array(
		'topics'             => 'Топики',
		'topic_plural'       => 'топик;топика;топиков',
		'drafts'             => 'Черновики',
		'read_more'          => 'Читать дальше',
		'author'             => 'Автор топика',
		'tags'               => 'Теги', // Move to component tags
		'share'              => 'Поделиться',
		'is_draft'           => 'Топик находится в черновиках',
		'add_favourite_tags' => 'Добавить свои теги',

		// Навигация
		'nav' => array(
			'drafts' => 'Черновики', // TODO: Remove duplication
			'published' => 'Опубликованные'
		),

		// Форма добавления
		'add' => array(
			'title' => array(
				'add' => 'Создание топика',
				'edit' => 'Редактирование топика',
			),

			// Поля
			'fields' => array(
				'blog' => array(
					'label'           => 'В какой блог публикуем?',
					'note'            => 'Для того чтобы написать в определенный блог, вы должны, для начала, вступить в него.',
					'option_personal' => 'Мой персональный блог',
				),
				'title' => array(
					'label' => 'Заголовок'
				),
				'text' => array(
					'label' => 'Текст'
				),
				'tags' => array(
					'label' => 'Текст',
					'note'  => 'Теги нужно разделять запятой. Например: google, вконтакте, кирпич'
				),
				'forbid_comments' => array(
					'label' => 'Запретить комментировать',
					'note'  => 'Если отметить эту галку, то нельзя будет оставлять комментарии к топику'
				),
				'publish_index' => array(
					'label' => 'Принудительно вывести на главную',
					'note'  => 'Если отметить эту галку, то топик сразу попадёт на главную страницу (опция доступна только администраторам)'
				),
			),

			// Кнопки
			'button' => array(
				'publish'       => 'Опубликовать',
				'update'        => 'Сохранить изменения',
				'save_as_draft' => 'Сохранить в черновиках',
				'mark_as_draft' => 'Перенести в черновики',
			),

			// Сообщения
			'notices' => array(
				'error_blog_not_found'   => 'Выбранный вами блог не существует',
				'error_blog_not_allowed' => 'Вы не можете писать в этот блог',
				'error_text_unique'      => 'Вы уже писали топик с таким содержанием',
				'error_type'             => 'Неверный тип топика', // TODO: Remove?
				'error_favourite_draft'  => 'Топик из черновиков нельзя добавить в избранное',
				'time_limit'             => 'Вам нельзя создавать топики слишком часто',
			)
		),

		// Комментарии
		'comments' => array(
			// Сообщения
			'notices' => array(
				'error_text'  => 'Текст комментария должен быть от 2 до 3000 символов и не содержать неразрешенных тегов',
				'acl'         => 'Ваш рейтинг слишком мал для написания комментариев',
				'limit'       => 'Вам нельзя писать комментарии слишком часто',
				'not_allowed' => 'Автор топика запретил добавлять комментарии',
				'spam'        => 'Стоп! Спам!',
			)
		)
	),


	/**
	 * Пользователь
	 * !user
	 */
	'user' => array(
		'user' => 'Пользователь',
		'users' => 'Пользователи',
		'rating' => '___vote.rating___',

		'date_last_session' => 'Последний визит',
		'date_registration' => 'Дата регистрации',

		// Пол
		'actions' => array(
			'send_message' => '___talk.send_message___',
			'follow'       => 'Подписаться',
			'unfollow'     => 'Отписаться',
		),

		// Пол
		'gender' => array(
			'gender' => 'Пол',
			'male'   => 'Мужской',
			'female' => 'Женский',
			'men'    => 'Мужчины',
			'women'  => 'Женщины',
			'none'   => 'Пол не указан'
		),

		// Статус
		'status' => array(
			'online'            => 'Онлайн',
			'offline'           => 'Оффлайн',
			'was_online_male'   => 'Заходил %%date%%',
			'was_online_female' => 'Заходила %%date%%'
		),

		// Друзья
		'friends' => array(
			'title' => 'Друзья',

			'add'      => 'Добавить в друзья',
			'remove'   => 'Удалить из друзей',
			'rejected' => 'Заявка отклонена',
			'sent'     => 'Заявка отправлена',

			// Статусы
			'status' => array(
				'notfriends' => '___user.friends.add___',
				'added'      => '___user.friends.remove___',
				'pending'    => '___user.friends.status.notfriends___',
				'rejected'   => '___user.friends.rejected___',
				'sent'       => '___user.friends.sent___',
				'linked'     => '___user.friends.status.notfriends___',
			),

			// Форма добавления в друзья
			'form' => array(
				'title' => '___user.friends.add___',

				'fields' => array(
					'text' => array(
						'label' => 'Представьтесь',
					),
					'submit' => array(
						'text' => '___common.send___',
					)
				),
			),

			// Сообщения
			'messages' => array(
				'offer' => array(
					'title' => 'Пользователь %%login%% приглашает вас дружить',
					'text'  => "Пользователь %%login%% желает добавить вас в друзья.<br/><br/>%%user_text%%<br/><br/><a href='%%accept_path%%'>Принять</a> - <a href='%%reject_path%%'>Отклонить</a>",
				),
				'accept' => array(
					'title' => 'Ваша заявка одобрена',
					'text'  => 'Пользователь %%login%% согласился с вами дружить',
				),
				'reject' => array(
					'title' => 'Ваша заявка отклонена',
					'text'  => 'Пользователь %%login%% отказался с вами дружить',
				),
				'deleted' => array(
					'title' => 'Вас удалили из друзей',
					'text'  => 'У вас больше нет друга %%login%%',
				),
			),

			'notices' => array(
				'add_success'        => 'У вас появился новый друг',
				'remove_success'     => 'У вас больше нет этого друга',
				'not_found'          => 'Друг не найден!', // TODO: Remove?
				'already_exist'      => 'Пользователь уже является вашим другом',
				'rejected'           => 'Этот пользователь отказался с вами дружить',
				'time_limit'         => 'Вы слишком часто отправляете личные сообщения, попробуйте добавить в друзья позже',
				'offer_not_found'    => 'Заявка не найдена', // TODO: Remove?
				'offer_already_done' => 'Заявка уже обработана',
			)
		),

		// Поиск
		'search' => array(
			'placeholder'  => 'Поиск по логину',
			'result_title' => 'Найден %%count%% пользователь;Найдено %%count%% пользователя;Найдено %%count%% пользователей'
		),

		// Публикации
		'publications' => array(
			'title' => 'Публикации',

			// Меню
			'nav' => array(
				'topics'   => '___topic.topics___',
				'comments' => '___comments.title___',
				'notes'    => 'Заметки'
			),
		),

		// Избранное
		'favourites' => array(
			'title' => '___favourite.favourite___',

			// Меню
			'nav' => array(
				'topics'   => '___topic.topics___',
				'comments' => '___comments.title___'
			),
		),

		// Профиль
		'profile' => array(
			'title' => 'Профиль',
			'social_networks' => 'Социальные сети',
			'contact' => 'Контакты',

			// Меню
			'nav' => array(
				'info'         => '___user.profile.title___',
				'wall'         => '___wall.title___',
				'publications' => '___user.publications.title___',
				'favourite'    => '___favourite.favourite___',
				'friends'      => '___user.friends.title___',
				'activity'     => '___activity.title___',
				'messages'     => '___talk.title___',
				'settings'     => 'Настройки',
			),

			'about' => array(
				'title' => 'О себе'
			),

			'personal' => array(
				'title' => 'Личное',

				'birthday'      => 'Дата рождения',
				'place'         => 'Местоположение',
				'gender'        => '___user.gender.gender___',
				'gender_male'   => '___user.gender.male___',
				'gender_female' => '___user.gender.female___',
			),

			'activity' => array(
				'title' => '___activity.title___',

				'blogs_joined' => 'Состоит в блогах',
				'blogs_created' => 'Создал блоги',
				'blogs_admin' => 'Администрирует',
				'blogs_mod' => 'Модерирует',
				'invited_by' => 'Приглашен',
				'invited' => 'Приглашенные',
			)
		),

		// Статистика
		'stats' => array(
			'title'      => 'Статистика',

			'all'        => 'Всего пользователей',

			'active'     => 'Активные',
			'not_active' => 'Заблудившиеся',

			'men'        => '___user.gender.men___',
			'women'      => '___user.gender.women___',
			'none'       => '___user.gender.none___'
		),

		// Настройки
		'settings' => array(
			'title' => 'Настройки',

			// Меню
			'nav' => array(
				'profile' => '___user.profile.title___',
				'account' => 'Аккаунт',
				'tuning'  => 'Настройки сайта',
				'invites' => 'Инвайты',
			),

			// Настройки профиля
			'profile' => array(
				'generic' => 'Основная информация',
				'contact' => '___user.profile.contact___',

				'fields' => array(
					'name' => array(
						'label' => 'Имя',
					),
					'sex' => array(
						'label' => '___user.gender.gender___',
					),
					'birthday' => array(
						'label' => '___user.profile.personal.birthday___',
					),
					'place' => array(
						'label' => '___user.profile.personal.place___',
					),
					'about' => array(
						'label' => '___user.profile.about.title___',
					),
				),
			),

			// Настройки аккаунта
			'account' => array(
				'account' => 'Настройки аккаунта',
				'password' => 'Пароль',
				'password_note' => 'Оставьте поля пустыми если не хотите изменять пароль.',

				'fields' => array(
					'email' => array(
						'note' => 'Ваш реальный почтовый адрес, на него будут приходить уведомления',
						'notices' => array(
							'error_used'         => 'Этот емайл уже занят',
							'change_from_notice' => 'На вашу старую почту отправлено подтверждение для смены емайла',
							'change_to_notice'   => 'Спасибо! <br/> На ваш новый емайл адрес отправлено подтверждение для смены старого емайла.',
							'change_ok'          => 'Ваш емайл изменен на <b>%%mail%%</b>',
						)
					),
					'password' => array(
						'label' => '___auth.labels.password___',
						'notices' => array(
							'error' => 'Неверный текущий пароль',
						)
					),
					'password_new' => array(
						'label' => 'Новый пароль',
						'notices' => array(
							'error' => 'Неверный пароль, допустим от 5 символов',
						)
					),
					'password_confirm' => array(
						'label' => '___auth.registration.form.fields.password_confirm.label___',
						'notices' => array(
							'confirm_error' => 'Пароли не совпадают',
						)
					),
				),
			),

			// Настройки сайта
			'tuning' => array(
				'email_notices' => 'Уведомления на e-mail',
				'general' => 'Общие настройки',

				'fields' => array(
					'new_topic'     => 'При новом топике в блоге',
					'new_comment'   => 'При новом комментарии в топике',
					'new_talk'      => 'При новом личном сообщении',
					'reply_comment' => 'При ответе на комментарий',
					'new_friend'    => 'При добавлении вас в друзья',
					'timezone'    => array(
						'label' => 'Часовой пояс'
					),
				)
			),

			// Инвайты
			'invites' => array(
				'note'         => 'Вы можете пригласить на сайт своих друзей и знакомых, для этого просто укажите их e-mail и нажмите кнопку',
				'available'    => 'Доступно',
				'available_no' => 'У вас пока нет доступных инвайтов',
				'used'         => 'Использовано',
				'many'         => 'много',

				'fields' => array(
					'email'    => array(
						'label' => 'Пригласить по e-mail адресу',
						'note' => 'На этот e-mail будет выслано приглашение для регистрации',
					),
					'submit' => array(
						'text' => 'Отправить приглашение',
					),
				),

				'notices' => array(
					'success' => 'Приглашение отправлено'
				)
			),
		),

		// Сообщения
		'notices' => array(
			'empty'           => '___common.empty___',
			'not_found'       => 'Пользователь <b>%%login%%</b> не найден',
			'not_found_by_id' => 'Пользователь <b>#%%id%%</b> не найден'
		),
	),


	/**
	 * Поля
	 */
	'field' => array(
		'email' => array(
			'label' => 'E-mail',
			'notices' => array(
				'error' => 'Неверный формат e-mail',
			),
		),
		'geo' => array(
			'select_country' => 'Выберите страну',
			'select_region' => 'Укажите регион',
			'select_city' => 'Укажите город',
		),
	),


	/**
	 * Редактор
	 */
	'editor' => array(
		'markup' => array(
			'help' => array(
				'link_show'                     => 'Доступны html-теги',
				'special'                       => 'Специальные теги',
				'special_cut'                   => 'Используется для больших текстов, скрывает под кат часть текста, следующую за тегом (будет написано «Читать дальше»).',
				'special_cut_name'              => 'Так можно превратить надпись «Читать дальше» в любой текст.',
				'special_cut_name_example_name' => 'Подробности',
				'special_video'                 => 'Добавляет в пост видео со следующих хостингов: YouTube, RuTube, Vimeo и Я.Видео. <br/>Вставляйте между тегами только прямую ссылку на видеоролик.',
				'special_ls_user'               => 'Выводит имя пользователя посреди текста.',
				'special_ls_user_example_user'  => 'Ник',
				'standart'                      => 'Стандартные теги',
				'standart_h'                    => 'Заголовки разного уровня.',
				'standart_img'                  => 'Вставка изображения, в атрибуте src нужно указывать полный путь к изображению. Возможно выравнивание картинки атрибутом align.',
				'standart_a'                    => 'Вставка ссылки, в атрибуте href указывается желаемый интернет-адрес или якорь (anchor) для навигации по странице.',
				'standart_a_example_href'       => 'Ссылка',
				'standart_b'                    => 'Выделение важного текста, на странице выделяется жирным начертанием.',
				'standart_i'                    => 'Выделение важного текста, на странице выделяется курсивом.',
				'standart_s'                    => 'Текст между этими тегами будет отображаться как зачеркнутый.',
				'standart_u'                    => 'Текст между этими тегами будет отображаться как подчеркнутый.',
				'standart_hr'                   => 'Тег для вставки горизонтальной линии.',
				'standart_blockquote'           => 'Используйте этот тег для выделения цитат.',
				'standart_table'                => 'Набор тегов для создания таблицы. Тег &lt;td&gt; обозначает ячейку таблицы, тег &lt;th&gt; - ячейку в заголовке, &lt;tr&gt; - строчку таблицы. Все содержимое таблицы помещайте в тег &lt;table&gt;.',
				'standart_ul'                   => 'Ненумерованный список; каждый элемент списка задается тегом &lt;li&gt;, набор элементов списка помещайте в тег &lt;ul&gt;.',
				'standart_ol'                   => 'Нумерованный список; каждый элемент списка задается тегом &lt;li&gt;, набор элементов списка помещайте в тег &lt;ol&gt;.',
			),
			'toolbar' => array(
				'b'           => 'Жирный',
				'i'           => 'Курсив',
				'u'           => 'Подчеркнутый',
				's'           => 'Зачеркнутый',
				'url'         => 'Вставить ссылку',
				'url_promt'   => 'Введите ссылку',
				'image_promt' => 'Введите ссылку на изображение',
				'code'        => 'Код',
				'video'       => 'Видео',
				'video_promt' => 'Введите ссылку на видео',
				'image'       => 'Изображение',
				'cut'         => 'Кат',
				'quote'       => 'Цитировать',
				'list'        => 'Список',
				'list_ul'     => 'UL LI',
				'list_ol'     => 'OL LI',
				'list_li'     => 'Пункт списка',
				'title'       => 'Заголовок',
				'title_h4'    => 'H4',
				'title_h5'    => 'H5',
				'title_h6'    => 'H6',
				'clear_tags'  => 'Очистить от тегов',
				'user'        => 'Вставить пользователя',
				'user_promt'  => 'Введите логин пользователя',
			),
		),
	),


	/**
	 * Админка
	 */
	'admin' => array(
		'title' => 'Админка',
		'items' => array(
			'plugins' => '___admin.plugins.title___',
		),
		'install_plugin_admin' => 'Установить расширенную админ-панель',

		// Страница администрирования плагинов
		'plugins' => array(
			'title' => 'Управление плагинами',

			'plugin_name' => 'Название',
			'plugin_author' => 'Автор',
			'plugin_version' => 'Версия',
			'plugin_activate' => 'Активировать',
			'plugin_deactivate' => 'Деактивировать',
			'plugin_settings' => 'Настройки',
			'submit_delete' => '___common.remove___',

			// Сообщения
			'notices' => array(
				'unknown_action' => 'Указано неизвестное действие',
				'action_ok' => 'Успешно выполнено',
				'activation_overlap' => 'Конфликт с активированным плагином. Ресурс %%resource%% переопределен на %%delegate%% плагином %%plugin%%.',
				'activation_overlap_inherit' => 'Конфликт с активированным плагином. Ресурс %%resource%% используется как наследник в плагине %%plugin%%.',
				'activation_file_not_found' => 'Файл плагина не найден',
				'activation_file_write_error' => 'Файл плагина не доступен для записи',
				'activation_version_error' => 'Для работы плагина необходимо ядро LiveStreet версии не ниже %%version%%',
				'activation_requires_error' => 'Для работы плагина необходим активированный плагин <b>%%plugin%%</b>',
				'deactivation_requires_error' => 'От плагина зависит другой плагин, сначала отключите его -  <b>%%plugin%%</b>',
			)
		),
	),


	/**
	 * Жалобы
	 */
	'report' => array(
		'report' => 'Пожаловаться',

		'form' => array(
			'title' => '___report.report___',

			'fields' => array(
				'type' => array(
					'label' => 'Причина'
				),
				'text' => array(
					'label' => 'Текст жалобы'
				)
			)
		),

		// TODO: Move to 'user'
		'type_list' => array(
			'spam'    => 'Спам',
			'obscene' => 'Непристойное поведение',
			'other'   => 'Другое',
		),

		'notices' => array(
			'target_error' => 'Неверный пользователь для жалобы', // TODO: Move to 'user'
			'error_type'   => 'Неверный тип жалобы', // TODO: Remove?
			'success'      => 'Ваша жалоба отправлена администрации',
		)
	),

	/**
	 * Настройки
	 */
	'settings_profile_avatar' => 'Аватар',
	'settings_profile_avatar_error' => 'Не удалось загрузить аватар',
	'settings_profile_avatar_delete' => 'Удалить',
	'settings_profile_avatar_change' => 'Изменить аватар',
	'settings_profile_avatar_upload' => 'Загрузить аватар',
	'settings_profile_avatar_resize_title' => 'Выбор области',
	'settings_profile_avatar_resize_apply' => 'Применить',

	'settings_profile_foto' => 'Фото',
	'settings_profile_foto_error' => 'Не удалось загрузить фото',
	'settings_profile_foto_delete' => 'Удалить',
	'settings_profile_photo_change' => 'Изменить фотографию',
	'settings_profile_photo_upload' => 'Загрузить фотографию',

	'settings_profile_field_error_max' => 'Нельзя добавить больше %%count%% одинаковых контактов',

	/**
	 * Избранные теги
	 */
	'topic_favourite_tags_block' => 'Теги избранного',
	'topic_favourite_tags_block_all' => 'Все теги',
	'topic_favourite_tags_block_user' => 'Мои теги',
	'favourite_form_tags_button_show' => 'изменить свои теги',

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
	'block_friends' => 'Выбрать получателей из списка друзей',
	'block_category_blog' => 'Категории',
	'block_category_blog_all' => 'Все',
	'block_blog_navigator' => 'Навигация по блогам',
	'block_blog_navigator_button' => 'Смотреть',
	'site_history_back' => 'Вернуться назад',
	'site_go_main' => 'перейти на главную',

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
	'uploadimg_title' => 'Описание',
	/**
	 * Toolbar
	 */
	'toolbar_scrollup_go' => 'Вверх',
	'toolbar_topic_next' => 'Следующий топик',
	'toolbar_topic_prev' => 'Предыдущий топик',
	/**
	 * Создание
	 */
	'block_create' => 'Создать',
	'block_create_topic_topic' => 'Топик',
	'block_create_blog' => 'Блог',
	'block_create_talk' => 'Сообщение',
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
	'not_access' => 'Нет доступа',
	'install_directory_exists' => 'Для работы с сайтом удалите директорию /application/install.',
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
	),

	/**
	 * Temp
	 */
	'topic_create' => 'Написать',
	'draft_declension' => 'черновик;черновика;черновиков',
	'blog_menu_create' => 'Блог',
	'user_search_filter_all' => 'Все',
	'user_complaint_title' => 'Пожаловаться',
);
