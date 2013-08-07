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
 * !!!!! ВНИМАНИЕ !!!!!
 *
 * Ничего не изменяйте в этом файле!
 * Все изменения нужно вносить в файл /config/config.local.php
 */

/**
 * Настройки HTML вида
 */
$config['view']['skin']        = 'synio';                                                              // шаблон(скин)
$config['view']['name']        = 'Your Site';                   // название сайта
$config['view']['description'] = 'Description your site'; // seo description
$config['view']['keywords']    = 'site, google, internet';      // seo keywords
$config['view']['wysiwyg']         = false;  // использовать или нет визуальный редактор TinyMCE
$config['view']['noindex']          = true;   // "прятать" или нет ссылки от поисковиков, оборачивая их в тег <noindex> и добавляя rel="nofollow"

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
if (isset($_SERVER['HTTP_HOST'])) {
    $config['path']['root']['web']        = 'http://'.$_SERVER['HTTP_HOST'];     // полный WEB адрес сайта
} else {
    // for CLI scripts. or you can append "HTTP_HOST=http://yoursite.url" before script run command
    $config['path']['root']['web']        = null;
}
$config['path']['root']['server']     		= dirname(dirname(dirname(__FILE__)));           // полный путь до сайта в файловой системе
$config['path']['root']['application']     	= '___path.root.server___/application';           // полный путь до сайта в файловой системе
$config['path']['root']['engine']           = LS_FRAMEWORK_PATH;                         // полный путь до сайта в файловой системе;
$config['path']['root']['engine_lib']       = '___path.root.engine___/libs';                        // полный путь до сайта в файловой системе
$config['path']['root']['framework']		= '___path.root.engine___';
$config['path']['static']['root']           = '___path.root.web___';                                   // чтоб можно было статику засунуть на отдельный сервер
$config['path']['static']['skin']           = '___path.static.root___/application/frontend/skin/___view.skin___';
$config['path']['static']['assets']         = '___path.static.skin___/assets';                         // Папка с ассетами (js, css, images)
$config['path']['static']['framework']      = "___path.static.root___/framework/frontend/framework";   // Front-end framework todo: need fix path
$config['path']['uploads']['root']          = '/uploads';                                              // директория для загрузки файлов
$config['path']['uploads']['images']        ='___path.uploads.root___/images';
$config['path']['offset_request_url']       = 0;                                                       // иногда помогает если сервер использует внутренние реврайты
/**
 * Настройки шаблонизатора Smarty
 */
$config['path']['smarty']['template'] = '___path.root.application___/frontend/skin/___view.skin___';
$config['path']['smarty']['compiled'] = '___path.root.application___/tmp/templates/compiled';
$config['path']['smarty']['cache']    = '___path.root.application___/tmp/templates/cache';
$config['path']['smarty']['plug']     = '___path.root.framework___/classes/modules/viewer/plugs';
$config['smarty']['compile_check']    = true; // Проверять или нет файлы шаблона на изменения перед компиляцией, false может значительно увеличить быстродействие, но потребует ручного удаления кеша при изменения шаблона
$config['smarty']['force_compile']    = false; // Принудительно компилировать шаблоны при каждом запросе, true - существенно снижает производительность
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
$config['sys']['cache']['type']   = 'file';             // тип кеширования: file, xcache и memory. memory использует мемкеш, xcache - использует XCache
$config['sys']['cache']['dir']    = '___path.root.application___/tmp/';       // каталог для файлового кеша, также используется для временных картинок. По умолчанию подставляем каталог для хранения сессий
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
 * Языковые настройки
 */
$config['lang']['current']     = 'ru';                                                // текущий язык текстовок
$config['lang']['default']     = 'ru';                                                // язык, который будет использовать на сайте по умолчанию
$config['lang']['dir']         = 'i18n';                                              // название директории с языковыми файлами
$config['lang']['path']        = '___path.root.application___/frontend/___lang.dir___';   // полный путь до языковых файлов
$config['lang']['load_to_js']  = array();                                             // Массив текстовок, которые необходимо прогружать на страницу в виде JS хеша, позволяет использовать текстовки внутри js
/**
 * Настройки модулей
 */
// Модуль Lang
$config['module']['lang']['delete_undefined'] = true;   // Если установлена true, то модуль будет автоматически удалять из языковых конструкций переменные вида %%var%%, по которым не была произведена замена
// Модуль Notify
$config['module']['notify']['delayed']       = false;    // Указывает на необходимость использовать режим отложенной рассылки сообщений на email
$config['module']['notify']['insert_single'] = false;    // Если опция установлена в true, систему будет собирать записи заданий удаленной публикации, для вставки их в базу единым INSERT
$config['module']['notify']['per_process']   = 10;       // Количество отложенных заданий, обрабатываемых одним крон-процессом
$config['module']['notify']['dir']           = 'emails'; // Путь до папки с емэйлами относительно шаблона
$config['module']['notify']['prefix']        = 'email';  // Префикс шаблонов емэйлов
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
$config['module']['image']['default']['path']['watermarks']   = '___path.root.framework___/libs/vendor/LiveImage/watermarks/';
$config['module']['image']['default']['path']['fonts']        = '___path.root.framework___/libs/vendor/LiveImage/fonts/';
$config['module']['image']['default']['jpg_quality']          = 95;  // Число от 0 до 100

// Модуль Security
$config['module']['security']['hash']  = "livestreet_security_key"; // "примесь" к строке, хешируемой в качестве security-кода
// Модуль Ls
$config['module']['ls']['send_general'] = true;	// Отправка на сервер LS общей информации о сайте (домен, версия LS и плагинов)
$config['module']['ls']['use_counter'] = true;	// Использование счетчика GA


// Какие модули должны быть загружены на старте
$config['module']['autoLoad'] = array('Hook','Cache','Security','Session','Lang','Message');
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
$config['db']['table']['notify_task']         = '___db.table.prefix___notify_task';
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
$config['router']['uri'] = array();
// Распределение action
$config['router']['page']['error']         = 'ActionError';
$config['router']['page']['index']         = 'ActionIndex';
// Глобальные настройки роутинга
$config['router']['config']['action_default']   = 'index';
$config['router']['config']['action_not_found'] = 'error';

$config['head']['default']['js'] = array(
	/* Vendor libs */
	"___path.static.framework___/js/vendor/html5shiv.js" => array('browser'=>'lt IE 9'),
	"___path.static.framework___/js/vendor/jquery-1.9.1.min.js",
	"___path.static.framework___/js/vendor/jquery-ui/js/jquery-ui-1.10.2.custom.min.js",
	"___path.static.framework___/js/vendor/jquery-ui/js/localization/jquery-ui-datepicker-ru.js",
	"___path.static.framework___/js/vendor/jquery.browser.js",
	"___path.static.framework___/js/vendor/jquery.scrollto.js",
	"___path.static.framework___/js/vendor/jquery.rich-array.min.js",
	"___path.static.framework___/js/vendor/jquery.form.js",
	"___path.static.framework___/js/vendor/jquery.jqplugin.js",
	"___path.static.framework___/js/vendor/jquery.cookie.js",
	"___path.static.framework___/js/vendor/jquery.serializejson.js",
	"___path.static.framework___/js/vendor/jquery.file.js",
	"___path.static.framework___/js/vendor/jcrop/jquery.Jcrop.js",
	"___path.static.framework___/js/vendor/jquery.placeholder.min.js",
	"___path.static.framework___/js/vendor/jquery.charcount.js",
	"___path.static.framework___/js/vendor/jquery.imagesloaded.js",
	"___path.static.framework___/js/vendor/notifier/jquery.notifier.js",
	"___path.static.framework___/js/vendor/prettify/prettify.js",
	"___path.static.framework___/js/vendor/prettyphoto/js/jquery.prettyphoto.js",
	"___path.static.framework___/js/vendor/parsley/parsley.js",
	"___path.static.framework___/js/vendor/parsley/i18n/messages.ru.js",

	/* Core */
	"___path.static.framework___/js/core/main.js",
	"___path.static.framework___/js/core/hook.js",

	/* User Interface */
	"___path.static.framework___/js/ui/popup.js",
	"___path.static.framework___/js/ui/dropdown.js",
	"___path.static.framework___/js/ui/tooltip.js",
	"___path.static.framework___/js/ui/popover.js",
	"___path.static.framework___/js/ui/tab.js",
	"___path.static.framework___/js/ui/modal.js",
	"___path.static.framework___/js/ui/toolbar.js",

	/* LiveStreet */
	"___path.static.framework___/js/livestreet/favourite.js",
	"___path.static.framework___/js/livestreet/blocks.js",
	"___path.static.framework___/js/livestreet/pagination.js",
	"___path.static.framework___/js/livestreet/editor.js",
	"___path.static.framework___/js/livestreet/talk.js",
	"___path.static.framework___/js/livestreet/vote.js",
	"___path.static.framework___/js/livestreet/poll.js",
	"___path.static.framework___/js/livestreet/subscribe.js",
	"___path.static.framework___/js/livestreet/geo.js",
	"___path.static.framework___/js/livestreet/wall.js",
	"___path.static.framework___/js/livestreet/usernote.js",
	"___path.static.framework___/js/livestreet/comments.js",
	"___path.static.framework___/js/livestreet/blog.js",
	"___path.static.framework___/js/livestreet/user.js",
	"___path.static.framework___/js/livestreet/userfeed.js",
	"___path.static.framework___/js/livestreet/stream.js",
	"___path.static.framework___/js/livestreet/photoset.js",
	"___path.static.framework___/js/livestreet/toolbar.js",
	"___path.static.framework___/js/livestreet/settings.js",
	"___path.static.framework___/js/livestreet/topic.js",
	"___path.static.framework___/js/livestreet/admin.js",
	"___path.static.framework___/js/livestreet/admin.userfield.js",
	"___path.static.framework___/js/livestreet/captcha.js",
	"___path.static.framework___/js/livestreet/init.js",
);

$config['head']['default']['css'] = array(
	// Framework styles
	"___path.static.framework___/css/reset.css",
	"___path.static.framework___/css/helpers.css",
	"___path.static.framework___/css/text.css",
	"___path.static.framework___/css/dropdowns.css",
	"___path.static.framework___/css/buttons.css",
	"___path.static.framework___/css/forms.css",
	"___path.static.framework___/css/navs.css",
	"___path.static.framework___/css/modals.css",
	"___path.static.framework___/css/tooltip.css",
	"___path.static.framework___/css/popover.css",
	"___path.static.framework___/css/alerts.css",
	"___path.static.framework___/css/toolbar.css"
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