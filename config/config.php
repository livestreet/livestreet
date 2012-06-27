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
 * Настройки HTML вида
 */
$config['view']['skin']        = 'synio';                                                              // шаблон(скин)
$config['view']['name']        = 'Your Site';                   // название сайта
$config['view']['description'] = 'Description your site'; // seo description
$config['view']['keywords']    = 'site, google, internet';      // seo keywords
$config['view']['tinymce']         = false;  // использовать или нет визуальный редактор TinyMCE
$config['view']['noindex']          = true;   // "прятать" или нет ссылки от поисковиков, оборачивая их в тег <noindex> и добавляя rel="nofollow"
$config['view']['img_resize_width'] = 570;    // до какого размера в пикселях ужимать картинку по щирине при загрузки её в топики и комменты
$config['view']['img_max_width'] = 5000;    // максимальная ширина загружаемых изображений в пикселях
$config['view']['img_max_height'] = 5000;    // максимальная высота загружаемых изображений в пикселях
$config['view']['img_max_size_url'] = 500;    // максимальный размер картинки в kB для загрузки по URL
$config['view']['no_assign']   = array('db'); // список групп конфигурации, которые необходимо исключить из передачи во Viewer. Только для системного пользования.

/**
 * Настройки СЕО для вывода топиков
 */
$config['seo']['description_words_count'] = 20;               // количество слов из топика для вывода в метатег description

/**
 * Настройка основных блоков
 */
$config['block']['stream']['row'] = 20;                       // сколько записей выводить в блоке "Прямой эфир"
$config['block']['blogs']['row']  = 10;                       // сколько записей выводить в блоке "Блоги"
$config['block']['tags']['tags_count'] = 70;                  // сколько тегов выводить в блоке "теги"
$config['block']['tags']['personal_tags_count'] = 70;         // сколько тегов пользователя выводить в блоке "теги"

/**
 * Настройка пагинации
 */
$config['pagination']['pages']['count'] = 4;                  // количество ссылок на другие страницы в пагинации


/**
 * Настройка путей
 * Если необходимо установить движек в директорию(не корень сайта) то следует сделать так:
 * $config['path']['root']['web']    = 'http://'.$_SERVER['HTTP_HOST'].'/subdir';
 * $config['path']['root']['server'] = $_SERVER['DOCUMENT_ROOT'].'/subdir';
 * и возможно придёться увеличить значение $config['path']['offset_request_url'] на число вложенных директорий,
 * например, для директории первой вложенности www.site.ru/livestreet/ поставить значение равное 1
 */
$config['path']['root']['web']        = 'http://'.$_SERVER['HTTP_HOST'];     // полный WEB адрес сайта
$config['path']['root']['server']     = dirname(dirname(__FILE__));           // полный путь до сайта в файловой системе
/**
 * Для CLI режима использовать
 * $config['path']['root']['server']     = dirname(dirname(__FILE__));           // полный путь до сайта в файловой системе
 */
$config['path']['root']['engine']     = '___path.root.server___/engine';  // полный путь до сайта в файловой системе;
$config['path']['root']['engine_lib'] = '___path.root.web___/engine/lib'; // полный путь до сайта в файловой системе
$config['path']['static']['root']     = '___path.root.web___';            // чтоб можно было статику засунуть на отдельный сервер
$config['path']['static']['skin']     = '___path.static.root___/templates/skin/___view.skin___';
$config['path']['uploads']['root']    = '/uploads';                          // директория для загрузки файлов
$config['path']['uploads']['images']  ='___path.uploads.root___/images';
$config['path']['offset_request_url'] = 0;                                   // иногда помогает если сервер использует внутренние реврайты
/**
 * Настройки шаблонизатора Smarty
 */
$config['path']['smarty']['template'] = '___path.root.server___/templates/skin/___view.skin___';
$config['path']['smarty']['compiled'] = '___path.root.server___/templates/compiled';
$config['path']['smarty']['cache']    = '___path.root.server___/templates/cache';
$config['path']['smarty']['plug']     = '___path.root.engine___/modules/viewer/plugs';
/**
 * Настройки плагинов
 */
$config['sys']['plugins']['activation_file'] = 'plugins.dat'; // файл со списком активных плагинов в каталоге /plugins/
/**
 * Настройки куков
 */
$config['sys']['cookie']['host'] = null;                    // хост для установки куков
$config['sys']['cookie']['path'] = '/';                     // путь для установки куков
$config['sys']['cookie']['time'] = 60 * 60 * 24 * 3;        // время жизни куки когда пользователь остается залогиненым на сайте, 3 дня
/**
 * Настройки сессий
 */
$config['sys']['session']['standart'] = true;                             // Использовать или нет стандартный механизм сессий
$config['sys']['session']['name']     = 'PHPSESSID';                      // название сессии
$config['sys']['session']['timeout']  = null;                             // Тайм-аут сессии в секундах
$config['sys']['session']['host']     = '___sys.cookie.host___'; // хост сессии в куках
$config['sys']['session']['path']     = '___sys.cookie.path___'; // путь сессии в куках
/**
 * Настройки почтовых уведомлений
 */
$config['sys']['mail']['type']             = 'mail';                 // Какой тип отправки использовать
$config['sys']['mail']['from_email']       = 'admin@admin.adm';      // Мыло с которого отправляются все уведомления
$config['sys']['mail']['from_name']        = 'Почтовик Your Site';  // Имя с которого отправляются все уведомления
$config['sys']['mail']['charset']          = 'UTF-8';                // Какую кодировку использовать в письмах
$config['sys']['mail']['smtp']['host']     = 'localhost';            // Настройки SMTP - хост
$config['sys']['mail']['smtp']['port']     = 25;                     // Настройки SMTP - порт
$config['sys']['mail']['smtp']['user']     = '';                     // Настройки SMTP - пользователь
$config['sys']['mail']['smtp']['password'] = '';                     // Настройки SMTP - пароль
$config['sys']['mail']['smtp']['secure']   = '';                     // Настройки SMTP - протокол шифрования: tls, ssl
$config['sys']['mail']['smtp']['auth']     = true;                   // Использовать авторизацию при отправке
$config['sys']['mail']['include_comment']  = true;                   // Включает в уведомление о новых комментах текст коммента
$config['sys']['mail']['include_talk']     = true;                   // Включает в уведомление о новых личных сообщениях текст сообщения
/**
 * Настройки кеширования
 */
// Устанавливаем настройки кеширования
$config['sys']['cache']['use']    = true;               // использовать кеширование или нет
$config['sys']['cache']['type']   = 'file';             // тип кеширования: file и memory. memory использует мемкеш
$config['sys']['cache']['dir']    = '___path.root.server___/tmp/';       // каталог для файлового кеша, также используется для временных картинок. По умолчанию подставляем каталог для хранения сессий
$config['sys']['cache']['prefix'] = 'livestreet_cache'; // префикс кеширования, чтоб можно было на одной машине держать несколько сайтов с общим кешевым хранилищем
$config['sys']['cache']['directory_level'] = 1;         // уровень вложенности директорий файлового кеша
$config['sys']['cache']['solid']  = true;               // Настройка использования раздельного и монолитного кеша для отдельных операций

/**
 * Настройки логирования
 */
$config['sys']['logs']['file']           = 'log.log';       // файл общего лога
$config['sys']['logs']['sql_query']      = false;           // логировать или нет SQL запросы
$config['sys']['logs']['sql_query_file'] = 'sql_query.log'; // файл лога SQL запросов
$config['sys']['logs']['sql_error']      = true;            // логировать или нет ошибки SQl
$config['sys']['logs']['sql_error_file'] = 'sql_error.log'; // файл лога ошибок SQL
$config['sys']['logs']['cron']     		 = true;    	    // логировать или нет cron скрипты
$config['sys']['logs']['cron_file']      = 'cron.log';      // файл лога запуска крон-процессов
$config['sys']['logs']['profiler']       = false;           // логировать или нет профилирование процессов
$config['sys']['logs']['profiler_file']  = 'profiler.log';  // файл лога профилирования процессов
$config['sys']['logs']['hacker_console']  = false;  		// позволяет удобно выводить логи дебага через функцию dump(), использя "хакерскую" консоль Дмитрия Котерова
/**
 * Общие настройки
 */
$config['general']['close']             = false; // использовать закрытый режим работы сайта, сайт будет доступен только авторизованным пользователям
$config['general']['rss_editor_mail']   = '___sys.mail.from_email___'; // мыло редактора РСС
$config['general']['reg']['invite']     = false; // использовать режим регистрации по приглашению или нет. Если использовать, то регистрация будет доступна ТОЛЬКО по приглашениям!
$config['general']['reg']['activation'] = false; // использовать активацию при регистрации или нет
/**
 * Языковые настройки
 */
$config['lang']['current'] = 'russian';                                                // текущий язык текстовок
$config['lang']['default'] = 'russian';                                                // язык, который будет использовать на сайте по умолчанию
$config['lang']['path']    = '___path.root.server___/templates/language'; 				// полный путь до языковых файлов
$config['lang']['load_to_js'] = array();												// Массив текстовок, которые необходимо прогружать на страницу в виде JS хеша, позволяет использовать текстовки внутри js
/**
 * Настройки ACL(Access Control List — список контроля доступа)
 */
$config['acl']['create']['blog']['rating']                =  1;  // порог рейтинга при котором юзер может создать коллективный блог
$config['acl']['create']['comment']['rating']             = -10; // порог рейтинга при котором юзер может добавлять комментарии
$config['acl']['create']['comment']['limit_time']         =  10; // время в секундах между постингом комментариев, если 0 то ограничение по времени не будет работать
$config['acl']['create']['comment']['limit_time_rating']  = -1;  // рейтинг, выше которого перестаёт действовать ограничение по времени на постинг комментов. Не имеет смысла при $config['acl']['create']['comment']['limit_time']=0
$config['acl']['create']['topic']['limit_time']           =  240;// время в секундах между созданием записей, если 0 то ограничение по времени не будет работать
$config['acl']['create']['topic']['limit_time_rating']    =  5;  // рейтинг, выше которого перестаёт действовать ограничение по времени на создание записей
$config['acl']['create']['topic']['limit_rating']   	  =  -20;// порог рейтинга при котором юзер может создавать топики (учитываются любые блоги, включая персональные), как дополнительная защита от спама/троллинга
$config['acl']['create']['talk']['limit_time']        =  300; // время в секундах между отправкой инбоксов, если 0 то ограничение по времени не будет работать
$config['acl']['create']['talk']['limit_time_rating'] =  1;   // рейтинг, выше которого перестаёт действовать ограничение по времени на отправку инбоксов
$config['acl']['create']['talk_comment']['limit_time']        =  10; // время в секундах между отправкой инбоксов, если 0 то ограничение по времени не будет работать
$config['acl']['create']['talk_comment']['limit_time_rating'] =  5;   // рейтинг, выше которого перестаёт действовать ограничение по времени на отправку инбоксов
$config['acl']['create']['wall']['limit_time'] =  20;   // рейтинг, выше которого перестаёт действовать ограничение по времени на отправку сообщений на стену
$config['acl']['create']['wall']['limit_time_rating'] =  0;   // рейтинг, выше которого перестаёт действовать ограничение по времени на отправку сообщений на стену
$config['acl']['vote']['comment']['rating']               = -3;  // порог рейтинга при котором юзер может голосовать за комментарии
$config['acl']['vote']['blog']['rating']                  = -5;  // порог рейтинга при котором юзер может голосовать за блог
$config['acl']['vote']['topic']['rating']                 = -7;  // порог рейтинга при котором юзер может голосовать за топик
$config['acl']['vote']['user']['rating']                  = -1;  // порог рейтинга при котором юзер может голосовать за пользователя
$config['acl']['vote']['topic']['limit_time']             = 60*60*24*20; // ограничение времени голосования за топик
$config['acl']['vote']['comment']['limit_time']           = 60*60*24*5;  // ограничение времени голосования за комментарий
/**
 * Настройки модулей
 */
// Модуль Blog
$config['module']['blog']['per_page']        = 20;   // Число блогов на страницу
$config['module']['blog']['users_per_page']  = 20;   // Число пользователей блога на страницу
$config['module']['blog']['personal_good']   = -5;   // Рейтинг топика в персональном блоге ниже которого он считается плохим
$config['module']['blog']['collective_good'] = -3;   // рейтинг топика в коллективных блогах ниже которого он считается плохим
$config['module']['blog']['index_good']      =  8;   // Рейтинг топика выше которого(включительно) он попадает на главную
$config['module']['blog']['encrypt']         = 'livestreet'; // Ключ XXTEA шифрования идентификаторов в ссылках приглашения в блоги
$config['module']['blog']['avatar_size'] = array(100,64,48,24,0); // Список размеров аватаров у блога. 0 - исходный размер
// Модуль Topic
$config['module']['topic']['new_time']   = 60*60*24*1;  // Время в секундах в течении которого топик считается новым
$config['module']['topic']['per_page']   = 10;          // Число топиков на одну страницу
$config['module']['topic']['max_length'] = 15000;       // Максимальное количество символов в одном топике
$config['module']['topic']['allow_empty_tags'] = false; // Разрешать или нет не заполнять теги
// Модуль User
$config['module']['user']['per_page']    = 15;          // Число юзеров на страницу на странице статистики и в профиле пользователя
$config['module']['user']['friend_on_profile']    = 15;          // Ограничение на вывод числа друзей пользователя на странице его профиля
$config['module']['user']['friend_notice']['delete'] = false; // Отправить talk-сообщение в случае удаления пользователя из друзей
$config['module']['user']['friend_notice']['accept'] = false; // Отправить talk-сообщение в случае одобрения заявки на добавление в друзья
$config['module']['user']['friend_notice']['reject'] = false; // Отправить talk-сообщение в случае отклонения заявки на добавление в друзья
$config['module']['user']['avatar_size'] = array(100,64,48,24,0); // Список размеров аватаров у пользователя. 0 - исходный размер
$config['module']['user']['login']['min_size'] = 3; // Минимальное количество символов в логине
$config['module']['user']['login']['max_size'] = 30; // Максимальное количество символов в логине
$config['module']['user']['time_active'] = 60*60*24*7; 	// Число секунд с момента последнего посещения пользователем сайта, в течение которых он считается активным
$config['module']['user']['usernote_text_max'] = 250; 	    // Максимальный размер заметки о пользователе
$config['module']['user']['usernote_per_page'] = 20; 	      // Число заметок на одну страницу
$config['module']['user']['userfield_max_identical'] = 2; 	// Максимальное число контактов одного типа
$config['module']['user']['profile_photo_width'] = 250; 	  // ширина квадрата фотографии в профиле, px
$config['module']['user']['name_max'] = 30; 			  // максимальная длинна имени в профиле пользователя
// Модуль Comment
$config['module']['comment']['per_page'] = 20;          // Число комментариев на одну страницу(это касается только полного списка комментариев прямого эфира)
$config['module']['comment']['bad']      = -5;          // Рейтинг комментария, начиная с которого он будет скрыт
$config['module']['comment']['max_tree'] = 7;           // Максимальная вложенность комментов при отображении
$config['module']['comment']['use_nested'] = false; 	// Использовать или нет nested set при выборке комментов, увеличивает производительность при большом числе комментариев + позволяет делать постраничное разбиение комментов
$config['module']['comment']['nested_per_page'] = 0; 	// Число комментов на одну страницу в топике, актуально только при use_nested = true
$config['module']['comment']['nested_page_reverse'] = true; 	// Определяет порядок вывода страниц. true - последние комментарии на первой странице, false - последние комментарии на последней странице
$config['module']['comment']['favourite_target_allow'] = array('topic'); 	// Список типов комментов, которые разрешено добавлять в избранное
// Модуль Talk
$config['module']['talk']['per_page']   = 30;           // Число приватных сообщений на одну страницу
$config['module']['talk']['reload']     = false;
$config['module']['talk']['request']    = 60;
$config['module']['talk']['period']     = 20000;
$config['module']['talk']['max_errors'] = 4;
$config['module']['talk']['encrypt']    = 'livestreet'; // Ключ XXTEA шифрования идентификаторов в ссылках
$config['module']['talk']['max_users']	= 15; // Максимальное число адресатов в одном личном сообщении
// Модуль Lang
$config['module']['lang']['delete_undefined'] = true;   // Если установлена true, то модуль будет автоматически удалять из языковых конструкций переменные вида %%var%%, по которым не была произведена замена
// Модуль Notify
$config['module']['notify']['delayed']        = false;  // Указывает на необходимость использовать режим отложенной рассылки сообщений на email
$config['module']['notify']['insert_single']  = false;  // Если опция установлена в true, систему будет собирать записи заданий удаленной публикации, для вставки их в базу единым INSERT
$config['module']['notify']['per_process']    = 10;     // Количество отложенных заданий, обрабатываемых одним крон-процессом
// Модуль Image
$config['module']['image']['default']['watermark_use']        = false;
$config['module']['image']['default']['watermark_type']       = 'text';
$config['module']['image']['default']['watermark_position']   = '0,24';
$config['module']['image']['default']['watermark_text']       = '(c) LiveStreet';
$config['module']['image']['default']['watermark_font']       = 'arial';
$config['module']['image']['default']['watermark_font_color'] = '255,255,255';
$config['module']['image']['default']['watermark_font_size']  = '10';
$config['module']['image']['default']['watermark_font_alfa']  = '0';
$config['module']['image']['default']['watermark_back_color'] = '0,0,0';
$config['module']['image']['default']['watermark_back_alfa']  = '40';
$config['module']['image']['default']['watermark_image']      = false;
$config['module']['image']['default']['watermark_min_width']  = 200;
$config['module']['image']['default']['watermark_min_height'] = 130;
$config['module']['image']['default']['round_corner']         = false;
$config['module']['image']['default']['round_corner_radius']  = '18';
$config['module']['image']['default']['round_corner_rate']    = '40';
$config['module']['image']['default']['path']['watermarks']   = '___path.root.server___/engine/lib/external/LiveImage/watermarks/';
$config['module']['image']['default']['path']['fonts']        = '___path.root.server___/engine/lib/external/LiveImage/fonts/';
$config['module']['image']['default']['jpg_quality']          = 95;  // Число от 0 до 100

$config['module']['image']['foto']['watermark_use']  = false;
$config['module']['image']['foto']['round_corner']   = false;

$config['module']['image']['topic']['watermark_use']  = false;
$config['module']['image']['topic']['round_corner']   = false;
// Модуль Security
$config['module']['security']['key']   = "livestreet_security_key"; // ключ сессии для хранения security-кода
$config['module']['security']['hash']  = "livestreet_security_key"; // "примесь" к строке, хешируемой в качестве security-кода

$config['module']['userfeed']['count_default'] = 10; // Число топиков в ленте по умолчанию

$config['module']['stream']['count_default'] = 20; // Число топиков в ленте по умолчанию
$config['module']['stream']['disable_vote_events'] = false;
// Модуль Ls
$config['module']['ls']['send_general'] = true;	// Отправка на сервер LS общей информации о сайте (домен, версия LS и плагинов)
$config['module']['ls']['use_counter'] = true;	// Использование счетчика GA
// Модуль Wall - стена
$config['module']['wall']['count_last_reply'] = 3;	// Число последних ответов на сообщени на стене для отображения в ленте
$config['module']['wall']['per_page'] = 10;			    // Число сообщений на стене на одну страницу
$config['module']['wall']['text_max'] = 250;		    // Ограничение на максимальное количество символов в одном сообщении на стене
$config['module']['wall']['text_min'] = 1;		      // Ограничение на минимальное количество символов в одном сообщении на стене


/**
 * Настройка топика-фотосета
 */
$config['module']['image']['photoset']['jpg_quality'] = 100;        // настройка модуля Image, качество обработки фото
$config['module']['topic']['photoset']['photo_max_size'] = 6*1024;  // максимально допустимый размер фото, Kb
$config['module']['topic']['photoset']['count_photos_min'] = 2;     // минимальное количество фоток
$config['module']['topic']['photoset']['count_photos_max'] = 30;    // максимальное количество фоток
$config['module']['topic']['photoset']['per_page'] = 20;            // число фоток для одновременной загрузки
$config['module']['topic']['photoset']['size'] = array(             // список размеров превью, которые необходимо делать при загрузке фото
	array(
		'w' => 1000,
		'h' => null,
		'crop' => false,
	),
	array(
		'w' => 500,
		'h' => null,
		'crop' => false,
	),
	array(
		'w' => 100,
		'h' => 65,
		'crop' => true,
	),
	array(
		'w' => 50,
		'h' => 50,
		'crop' => true,
	)
);

// Какие модули должны быть загружены на старте
$config['module']['autoLoad'] = array('Hook','Cache','Security','Session','Lang','Message','User');
/**
 * Настройка базы данных
 */
$config['db']['params']['host']   = 'localhost';
$config['db']['params']['port']   = '3306';
$config['db']['params']['user']   = 'root';
$config['db']['params']['pass']   = '';
$config['db']['params']['type']   = 'mysql';
$config['db']['params']['dbname'] = 'social';
/**
 * Настройка таблиц базы данных
 */
$config['db']['table']['prefix'] = 'prefix_';

$config['db']['table']['user']                = '___db.table.prefix___user';
$config['db']['table']['blog']                = '___db.table.prefix___blog';
$config['db']['table']['topic']               = '___db.table.prefix___topic';
$config['db']['table']['topic_tag']           = '___db.table.prefix___topic_tag';
$config['db']['table']['comment']             = '___db.table.prefix___comment';
$config['db']['table']['vote']                = '___db.table.prefix___vote';
$config['db']['table']['topic_read']          = '___db.table.prefix___topic_read';
$config['db']['table']['blog_user']           = '___db.table.prefix___blog_user';
$config['db']['table']['favourite']           = '___db.table.prefix___favourite';
$config['db']['table']['favourite_tag']           = '___db.table.prefix___favourite_tag';
$config['db']['table']['talk']                = '___db.table.prefix___talk';
$config['db']['table']['talk_user']           = '___db.table.prefix___talk_user';
$config['db']['table']['talk_blacklist']      = '___db.table.prefix___talk_blacklist';
$config['db']['table']['friend']              = '___db.table.prefix___friend';
$config['db']['table']['topic_content']       = '___db.table.prefix___topic_content';
$config['db']['table']['topic_question_vote'] = '___db.table.prefix___topic_question_vote';
$config['db']['table']['user_administrator']  = '___db.table.prefix___user_administrator';
$config['db']['table']['comment_online']      = '___db.table.prefix___comment_online';
$config['db']['table']['invite']              = '___db.table.prefix___invite';
$config['db']['table']['page']                = '___db.table.prefix___page';
$config['db']['table']['city']                = '___db.table.prefix___city';
$config['db']['table']['city_user']           = '___db.table.prefix___city_user';
$config['db']['table']['country']             = '___db.table.prefix___country';
$config['db']['table']['country_user']        = '___db.table.prefix___country_user';
$config['db']['table']['reminder']            = '___db.table.prefix___reminder';
$config['db']['table']['session']             = '___db.table.prefix___session';
$config['db']['table']['notify_task']         = '___db.table.prefix___notify_task';
$config['db']['table']['userfeed_subscribe']  = '___db.table.prefix___userfeed_subscribe';
$config['db']['table']['stream_subscribe']    = '___db.table.prefix___stream_subscribe';
$config['db']['table']['stream_event']        = '___db.table.prefix___stream_event';
$config['db']['table']['stream_user_type']    = '___db.table.prefix___stream_user_type';
$config['db']['table']['user_field']          = '___db.table.prefix___user_field';
$config['db']['table']['user_field_value']    = '___db.table.prefix___user_field_value';
$config['db']['table']['topic_photo']         = '___db.table.prefix___topic_photo';
$config['db']['table']['subscribe']           = '___db.table.prefix___subscribe';
$config['db']['table']['wall']                = '___db.table.prefix___wall';
$config['db']['table']['user_note']           = '___db.table.prefix___user_note';
$config['db']['table']['geo_country']         = '___db.table.prefix___geo_country';
$config['db']['table']['geo_region']          = '___db.table.prefix___geo_region';
$config['db']['table']['geo_city']            = '___db.table.prefix___geo_city';
$config['db']['table']['geo_target']          = '___db.table.prefix___geo_target';

$config['db']['tables']['engine'] = 'InnoDB';  // InnoDB или MyISAM
/**
 * Настройка memcache
 */
$config['memcache']['servers'][0]['host'] = 'localhost';
$config['memcache']['servers'][0]['port'] = '11211';
$config['memcache']['servers'][0]['persistent'] = true;
$config['memcache']['compression'] = true;
/**
 * Настройки роутинга
 */
$config['router']['rewrite'] = array();
// Правила реврайта для REQUEST_URI
$config['router']['uri'] = array(
	// короткий вызов топиков из личных блогов
	'~^(\d+)\.html~i' => "blog/\\1.html",
);
// Распределение action
$config['router']['page']['error']         = 'ActionError';
$config['router']['page']['registration']  = 'ActionRegistration';
$config['router']['page']['profile']       = 'ActionProfile';
$config['router']['page']['my']            = 'ActionMy';
$config['router']['page']['blog']          = 'ActionBlog';
$config['router']['page']['personal_blog'] = 'ActionPersonalBlog';
$config['router']['page']['index']         = 'ActionIndex';
$config['router']['page']['topic']         = 'ActionTopic';
$config['router']['page']['login']         = 'ActionLogin';
$config['router']['page']['people']        = 'ActionPeople';
$config['router']['page']['settings']      = 'ActionSettings';
$config['router']['page']['tag']           = 'ActionTag';
$config['router']['page']['talk']          = 'ActionTalk';
$config['router']['page']['comments']      = 'ActionComments';
$config['router']['page']['rss']           = 'ActionRss';
$config['router']['page']['link']          = 'ActionLink';
$config['router']['page']['question']      = 'ActionQuestion';
$config['router']['page']['blogs']         = 'ActionBlogs';
$config['router']['page']['search']        = 'ActionSearch';
$config['router']['page']['admin']         = 'ActionAdmin';
$config['router']['page']['ajax']          = 'ActionAjax';
$config['router']['page']['feed']          = 'ActionUserfeed';
$config['router']['page']['stream']        = 'ActionStream';
$config['router']['page']['photoset']      = 'ActionPhotoset';
$config['router']['page']['subscribe']     = 'ActionSubscribe';
// Глобальные настройки роутинга
$config['router']['config']['action_default']   = 'index';
$config['router']['config']['action_not_found'] = 'error';

/**
 * Настройки вывода блоков
 */
$config['block']['rule_index_blog'] = array(
	'action'  => array(
			'index', 'blog' => array('{topics}','{topic}','{blog}')
		),
	'blocks'  => array(
			'right' => array('stream'=>array('priority'=>100),'tags'=>array('priority'=>50),'blogs'=>array('params'=>array(),'priority'=>1))
		),
	'clear' => false,
);

$config['block']['rule_topic_type'] = array(
	'action'  => array(
		'link'     => array('add','edit'),
		'question' => array('add','edit'),
		'topic'    => array('add','edit'),
		'photoset'    => array('add','edit')
	),
	'blocks'  => array( 'right' => array('blocks/block.blogInfo.tpl') ),
);
$config['block']['rule_people'] = array(
	'action'  => array( 'people' ),
	'blocks'  => array( 'right' => array('actions/ActionPeople/sidebar.tpl') ),
);
$config['block']['rule_personal_blog'] = array(
	'action'  => array( 'personal_blog' ),
	'blocks'  => array( 'right' => array('stream','tags') ),
);
$config['block']['rule_profile'] = array(
	'action'  => array( 'profile', 'talk', 'settings' ),
	'blocks'  => array( 'right' => array('actions/ActionProfile/sidebar.tpl') ),
);
$config['block']['rule_tag'] = array(
	'action'  => array( 'tag' ),
	'blocks'  => array( 'right' => array('tags','stream') ),
);
$config['block']['rule_blogs'] = array(
	'action'  => array( 'blogs' ),
	'blocks'  => array( 'right' => array('stream') ),
);

$config['block']['userfeedBlogs'] = array(
	'action'  => array('feed'),
	'blocks'  => array(
                    'right' => array(
                        'userfeedBlogs'=> array()
                    )
                )
);
$config['block']['userfeedUsers'] = array(
	'action'  => array('feed'),
	'blocks'  => array(
                    'right' => array(
                        'userfeedUsers'=> array()
                    )
                )
);
$config['block']['rule_blog_info'] = array(
	'action'  => array(
			'blog' => array('{topic}')
		),
	'blocks'  => array(
			'right' => array('blocks/block.blog.tpl'=>array('priority'=>300))
		),
	'clear' => false,
);


/**
 * Настройки вывода js и css файлов
 */
$config['head']['rules']['page'] =array(
	'path'=>$config['path']['root']['web'].'/page/',
	'js' => array(
		'exclude' => array(
			"___path.static.skin___/js/vote.js",
			"___path.static.skin___/js/favourites.js",
			"___path.static.skin___/js/questions.js",
		)
	),
);

$config['head']['default']['js']  = array(
	"___path.root.engine_lib___/external/html5shiv.js" => array('browser'=>'lt IE 9'),
	"___path.root.engine_lib___/external/jquery/jquery.js",
	"___path.root.engine_lib___/external/jquery/jquery-ui.js",
	"___path.root.engine_lib___/external/jquery/jquery.notifier.js",
	"___path.root.engine_lib___/external/jquery/jquery.jqmodal.js",
	"___path.root.engine_lib___/external/jquery/jquery.scrollto.js",
	"___path.root.engine_lib___/external/jquery/jquery.rich-array.min.js",
	"___path.root.engine_lib___/external/jquery/markitup/jquery.markitup.js",
	"___path.root.engine_lib___/external/jquery/jquery.form.js",
	"___path.root.engine_lib___/external/jquery/jquery.jqplugin.js",
	"___path.root.engine_lib___/external/jquery/jquery.cookie.js",
	"___path.root.engine_lib___/external/jquery/jquery.serializejson.js",
	"___path.root.engine_lib___/external/jquery/jquery.file.js",
	"___path.root.engine_lib___/external/jquery/jcrop/jquery.Jcrop.js",
	"___path.root.engine_lib___/external/jquery/poshytip/jquery.poshytip.js",
	"___path.root.engine_lib___/external/jquery/jquery.placeholder.min.js",
	"___path.root.engine_lib___/external/jquery/jquery.charcount.js",
	"___path.root.engine_lib___/external/prettify/prettify.js",
	"___path.root.engine_lib___/internal/template/js/main.js",
	"___path.root.engine_lib___/internal/template/js/favourite.js",
	"___path.root.engine_lib___/internal/template/js/blocks.js",
	"___path.root.engine_lib___/internal/template/js/talk.js",
	"___path.root.engine_lib___/internal/template/js/vote.js",
	"___path.root.engine_lib___/internal/template/js/poll.js",
	"___path.root.engine_lib___/internal/template/js/subscribe.js",
	"___path.root.engine_lib___/internal/template/js/infobox.js",
	"___path.root.engine_lib___/internal/template/js/geo.js",
	"___path.root.engine_lib___/internal/template/js/wall.js",
	"___path.root.engine_lib___/internal/template/js/usernote.js",
	"___path.root.engine_lib___/internal/template/js/comments.js",
	"___path.root.engine_lib___/internal/template/js/blog.js",
	"___path.root.engine_lib___/internal/template/js/user.js",
	"___path.root.engine_lib___/internal/template/js/userfeed.js",
	"___path.root.engine_lib___/internal/template/js/userfield.js",
	"___path.root.engine_lib___/internal/template/js/stream.js",
	"___path.root.engine_lib___/internal/template/js/photoset.js",
	"___path.root.engine_lib___/internal/template/js/toolbar.js",
	"___path.root.engine_lib___/internal/template/js/settings.js",
	"___path.root.engine_lib___/internal/template/js/topic.js",
	"___path.root.engine_lib___/internal/template/js/hook.js",
	"http://yandex.st/share/share.js" => array('merge'=>false),
);
$config['head']['default']['css'] = array(
	"___path.static.skin___/css/reset.css",
	"___path.root.engine_lib___/external/jquery/markitup/skins/simple/style.css",
	"___path.root.engine_lib___/external/jquery/markitup/sets/default/style.css",	
	"___path.root.engine_lib___/external/jquery/jcrop/jquery.Jcrop.css",
	"___path.root.engine_lib___/external/prettify/prettify.css",
	"___path.static.skin___/css/main.css",
	"___path.static.skin___/css/grid.css",
	"___path.static.skin___/css/common.css",
	"___path.static.skin___/css/forms.css",
	"___path.static.skin___/css/popups.css",
	"___path.static.skin___/css/topic.css",
	"___path.static.skin___/css/comments.css",
	"___path.static.skin___/css/blocks.css",
	"___path.static.skin___/css/infobox.css",
	"___path.static.skin___/css/jquery.jqmodal.css",
	"___path.static.skin___/css/jquery.notifier.css",
	"___path.static.skin___/css/smoothness/jquery-ui.css",
);

/**
 * Параметры компрессии css-файлов
 */
$config['compress']['css']['merge'] = true;       // указывает на необходимость слияния файлов по указанным блокам.
$config['compress']['css']['use']   = false;       // указывает на необходимость компрессии файлов. Компрессия используется только в активированном режиме слияния файлов.
$config['compress']['css']['case_properties']     = 1;
$config['compress']['css']['merge_selectors']     = 0;
$config['compress']['css']['optimise_shorthands'] = 1;
$config['compress']['css']['remove_last_;']       = true;
$config['compress']['css']['css_level']           = 'CSS2.1';
$config['compress']['css']['template']            = "highest_compression";
/**
 * Параметры компрессии js-файлов
 */
$config['compress']['js']['merge']  = true;    // указывает на необходимость слияния файлов по указанным блокам.
$config['compress']['js']['use']    = true;    // указывает на необходимость компрессии файлов. Компрессия используется только в активированном режиме слияния файлов.

/**
 * Установка локали
 */
setlocale(LC_ALL, "ru_RU.UTF-8");
date_default_timezone_set('Europe/Moscow'); // See http://php.net/manual/en/timezones.php

/**
 * Настройки типографа текста Jevix
 */
$config['jevix']=require(dirname(__FILE__).'/jevix.php');


return $config;
?>