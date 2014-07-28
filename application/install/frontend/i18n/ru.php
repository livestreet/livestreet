<?php

return array(
	'groups' => array(
		'install' =>array(
			'title' => 'Новая установка',
			'description' => '',
		),
		'update' =>array(
			'title' => 'Обновление со старой версии',
			'description' => '',
		),
	),

	'steps' =>array(
		'install1' => array(
			'title' => 'Проверка требований для установки',
			'requirements' => array(
				'php_version' => array(
					'title' => 'Версия PHP',
					'solution' => 'Минимально допустимая версия PHP 5.3.2. Обратитесь к хостингу для обновления версии.',
				),
				'safe_mode' => array(
					'title' => 'SAFE MODE',
					'solution' => 'Для корректной работы необходимо отключить SAFE MODE. Обратитесь к хостингу.',
				),
				'utf8' => array(
					'title' => 'Поддержка UTF-8',
					'solution' => 'Для корректной работы необходима поддержка UTF-8. Обратитесь к хостингу.',
				),
				'mbstring' => array(
					'title' => 'Поддержка многобайтовых строк',
					'solution' => 'Для корректной работы необходимо расширение mbstring. Обратитесь к хостингу.',
				),
				'mbstring_func_overload' => array(
					'title' => 'Режим перегрузки строковых функций (func_overload)',
					'solution' => 'Для корректной работы необходимо отключить перегрузку строковых функций. Обратитесь к хостингу.',
				),
				'xml' => array(
					'title' => 'Поддержка XML (SimpleXML)',
					'solution' => 'Для корректной работы необходимо расширение SimpleXML. Обратитесь к хостингу.',
				),
				'dir_uploads' => array(
					'title' => 'Каталог /uploads',
					'solution' => 'Необходимо дать каталогу права на запись.',
				),
				'dir_plugins' => array(
					'title' => 'Каталог /application/plugins',
					'solution' => 'Необходимо дать каталогу права на запись.',
				),
				'dir_tmp' => array(
					'title' => 'Каталог /application/tmp',
					'solution' => 'Необходимо дать каталогу права на запись.',
				),
				'dir_logs' => array(
					'title' => 'Каталог /application/logs',
					'solution' => 'Необходимо дать каталогу права на запись.',
				),
				'file_config_local' => array(
					'title' => 'Файл /application/config/config.local.php',
					'solution' => 'Файл должен существовать и быть доступен для записи',
				),
			),
		),
		'install2' => array(
			'title' => 'Настройка базы данных',
			'form' => array(
				'db_host' => array(
					'title' => 'Имя сервера БД'
				),
				'db_port' => array(
					'title' => 'Порт сервера БД'
				),
				'db_name' => array(
					'title' => 'Название БД'
				),
				'db_create' => array(
					'title' => 'Автоматически создать БД'
				),
				'db_user' => array(
					'title' => 'Пользователь БД'
				),
				'db_passwd' => array(
					'title' => 'Пароль от пользователя БД'
				),
				'db_prefix' => array(
					'title' => 'Префикс таблиц в БД'
				),
			),
			'errors' => array(
				'db_not_found' => 'Не удалось выбрать необходимую базу данных, проверьте имя базы данных',
				'db_not_create' => 'Не удалось создать базу данных, проверьте права доступа к БД',
				'db_table_prefix' => 'Неверный формат префикс таблиц, допустимы только латински буквы, цифры и знак "_"',
			),
		),
	),

	'config' => array(
		'errors' => array(
			'file_not_found' => 'Файл конфига не найден',
			'file_not_writable' => 'Файл конфига не доступен для записи',
		),
	),

	'db' => array(
		'errors' => array(
			'db_connect' => 'Не удалось установить соединение с БД. Проверьте параметры подключения к БД.',
			'db_version' => 'Версия сервера БД должна быть от 5.0.0',
			'db_query' => 'Неудалось выполнить запрос к БД',
		),
	),

	'install_reset' => 'Начать установку сначала',
	'yes' => 'Да',
	'no' => 'Нет',
);