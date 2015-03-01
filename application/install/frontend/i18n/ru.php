<?php

return array(
    'groups'        => array(
        'install' => array(
            'title'       => 'Новая установка',
            'description' => '',
        ),
        'update'  => array(
            'title'       => 'Обновление со старой версии',
            'description' => '',
        ),
    ),
    'steps'         => array(
        'checkRequirements' => array(
            'title'        => 'Проверка требований для установки',
            'requirements' => array(
                'install_completed'      => array(
                    'title'    => 'В конфигурационном файле установлена настройка завершения установки',
                    'solution' => 'Удалите настройку install_completed или поменяйте её значение на false',
                ),
                'php_version'            => array(
                    'title'    => 'Версия PHP',
                    'solution' => 'Минимально допустимая версия PHP 5.3.2. Обратитесь к хостингу для обновления версии.',
                ),
                'safe_mode'              => array(
                    'title'    => 'SAFE MODE',
                    'solution' => 'Для корректной работы необходимо отключить SAFE MODE. Обратитесь к хостингу.',
                ),
                'utf8'                   => array(
                    'title'    => 'Поддержка UTF-8',
                    'solution' => 'Для корректной работы необходима поддержка UTF-8. Обратитесь к хостингу.',
                ),
                'mbstring'               => array(
                    'title'    => 'Поддержка многобайтовых строк',
                    'solution' => 'Для корректной работы необходимо расширение mbstring. Обратитесь к хостингу.',
                ),
                'mbstring_func_overload' => array(
                    'title'    => 'Режим перегрузки строковых функций (func_overload)',
                    'solution' => 'Для корректной работы необходимо отключить перегрузку строковых функций. Обратитесь к хостингу.',
                ),
                'xml'                    => array(
                    'title'    => 'Поддержка XML (SimpleXML)',
                    'solution' => 'Для корректной работы необходимо расширение SimpleXML. Обратитесь к хостингу.',
                ),
                'dir_uploads'            => array(
                    'title'    => 'Каталог /uploads',
                    'solution' => 'Необходимо дать каталогу права на запись.',
                ),
                'dir_plugins'            => array(
                    'title'    => 'Каталог /application/plugins',
                    'solution' => 'Необходимо дать каталогу права на запись.',
                ),
                'dir_tmp'                => array(
                    'title'    => 'Каталог /application/tmp',
                    'solution' => 'Необходимо дать каталогу права на запись.',
                ),
                'dir_logs'               => array(
                    'title'    => 'Каталог /application/logs',
                    'solution' => 'Необходимо дать каталогу права на запись.',
                ),
                'file_config_local'      => array(
                    'title'    => 'Файл /application/config/config.local.php',
                    'solution' => 'Файл должен существовать и быть доступен для записи',
                ),
            ),
        ),
        'installDb'         => array(
            'title'  => 'Настройка базы данных',
            'form'   => array(
                'db_host'   => array(
                    'title' => 'Имя сервера БД'
                ),
                'db_port'   => array(
                    'title' => 'Порт сервера БД'
                ),
                'db_name'   => array(
                    'title' => 'Название БД'
                ),
                'db_create' => array(
                    'title' => 'Автоматически создать БД'
                ),
                'db_user'   => array(
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
                'db_not_found'    => 'Не удалось выбрать необходимую базу данных, проверьте имя базы данных',
                'db_not_create'   => 'Не удалось создать базу данных, проверьте права доступа к БД',
                'db_table_prefix' => 'Неверный формат префикс таблиц, допустимы только латински буквы, цифры и знак "_"',
            ),
        ),
        'installAdmin'      => array(
            'title'  => 'Данные администратора сайта',
            'form'   => array(
                'mail'   => array(
                    'title' => 'E-mail'
                ),
                'passwd' => array(
                    'title' => 'Пароль'
                ),
            ),
            'errors' => array(
                'mail'   => 'Неверный формат e-mail адреса',
                'passwd' => 'Пароль должен быть от 3-х символов',
            ),
        ),
        'installComplete'     => array(
            'title'  => 'Установка завершена!',
        ),
        'updateVersion'     => array(
            'title'  => 'Выбор текущей версии',
            'errors' => array(
                'not_found_convert' => 'Для данной версии нет возможности обновления',
            ),
        ),
        'updateDb'     => array(
            'title'  => 'Настройка базы данных',
        ),
        'updateComplete'     => array(
            'title'  => 'Обновление успешно завершено!',
        ),
    ),
    'config'        => array(
        'errors' => array(
            'file_not_found'    => 'Файл конфига не найден',
            'file_not_writable' => 'Файл конфига не доступен для записи',
        ),
    ),
    'db'            => array(
        'errors' => array(
            'db_connect' => 'Не удалось установить соединение с БД. Проверьте параметры подключения к БД.',
            'db_version' => 'Версия сервера БД должна быть от 5.0.0',
            'db_query'   => 'Не удалось выполнить запрос к БД',
        ),
    ),
    'console' => array(
        'command_empty' => 'Необходимо указать команду. Сейчас поддерживается только команда "run"',
        'command_successful' => 'Команда успешно выполнена',
        'command_failed' => 'Не удалось выполнить команду',
        'command' => array(
            'run' => array(
                'params_step_empty' => 'Необходимо указать параметр: название шага',
                'params_version_empty' => 'Необходимо указать параметр: номер текущей версии',
            )
        ),
    ),
    'install_reset' => 'Начать сначала',
    'yes'           => 'Да',
    'no'            => 'Нет',
);
