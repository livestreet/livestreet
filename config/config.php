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
$config['view']['skin']        = 'new';                                                              // шаблон(скин)
$config['view']['name']        = 'LiveStreet - бесплатный движок социальной сети';                   // название сайта
$config['view']['description'] = 'LiveStreet - официальный сайт бесплатного движка социальной сети'; // seo description
$config['view']['keywords']    = 'движок, livestreet, блоги, социальная сеть, бесплатный, php';      // seo keywords
$config['view']['tinymce']          = false;  // использовать или нет визуальный редактор TinyMCE
$config['view']['noindex']          = true;   // "прятать" или нет ссылки от поисковиков, оборачивая их в тег <noindex> и добавляя rel="nofollow"
$config['view']['img_resize_width'] = 500;    // до какого размера в пикселях ужимать картинку по щирине при загрузки её в топики и комменты
$config['view']['no_assign']   = array('db'); // список групп конфигурации, которые необходимо исключить из передачи во Viewer. Только для системного пользования.

/**
 * Настройка основных блоков 
 */
$config['block']['stream']['row'] = 20;  // сколько записей выводить в блоке "Прямой эфир"
$config['block']['blogs']['row']  = 10;  // сколько записей выводить в блоке "Блоги"
/**
 * Настройка путей
 * Если необходимо установить движек в директорию(не корень сайта) то следует сделать так:
 * $config['path']['root']['web']    = 'http://'.$_SERVER['HTTP_HOST'].'/subdir';
 * $config['path']['root']['server'] = $_SERVER['DOCUMENT_ROOT'].'/subdir';
 * и возможно придёться увеличить значение SYS_OFFSET_REQUEST_URL на число вложенных директорий, 
 * например, для директории первой вложенности www.site.ru/livestreet/ поставить значение равное 1 
 */
$config['path']['root']['web']        = 'http://'.$_SERVER['HTTP_HOST'];     // полный WEB адрес сайта
$config['path']['root']['server']     = $_SERVER['DOCUMENT_ROOT'];           // полный путь до сайта в файловой системе
/**
 * Для CLI режима использовать
 * $config['path']['root']['server']     = dirname(dirname(__FILE__));           // полный путь до сайта в файловой системе 
 */
$config['path']['root']['engine']     = $config['path']['root']['server'].'/engine';  // полный путь до сайта в файловой системе;
$config['path']['root']['engine_lib'] = $config['path']['root']['web'].'/engine/lib'; // полный путь до сайта в файловой системе
$config['path']['static']['root']     = $config['path']['root']['web'];      // чтоб можно было статику засунуть на отдельный сервер
$config['path']['static']['skin']     = $config['path']['static']['root'].'/templates/skin/'.$config['view']['skin'];
$config['path']['uploads']['root']    = '/uploads';                          // директория для загрузки файлов
$config['path']['uploads']['images']  = $config['path']['uploads']['root'].'/images';
$config['path']['offset_request_url'] = 0;                                   // иногда помогает если сервер использует внутренние реврайты
/**
 * Настройки шаблонизатора Smarty
 */
$config['path']['smarty']['template'] = $config['path']['root']['server'].'/templates/skin/'.$config['view']['skin'];
$config['path']['smarty']['compiled'] = $config['path']['root']['server'].'/templates/compiled';
$config['path']['smarty']['cache']    = $config['path']['root']['server'].'/templates/cache';
$config['path']['smarty']['plug']     = $config['path']['root']['engine'].'/modules/viewer/plugs';
/**
 * Настройки куков
 */
$config['sys']['cookie']['host'] = null; // хост для установки куков
$config['sys']['cookie']['path'] = '/';  // путь для установки куков
/**
 * Настройки сессий
 */
$config['sys']['session']['standart'] = true;                             // Использовать или нет стандартный механизм сессий
$config['sys']['session']['name']     = 'PHPSESSID';                      // название сессии
$config['sys']['session']['timeout']  = null;                             // Тайм-аут сессии в секундах
$config['sys']['session']['host']     = $config['sys']['cookie']['host']; // хост сессии в куках
$config['sys']['session']['path']     = $config['sys']['cookie']['path']; // путь сессии в куках
/**
 * Настройки почтовых уведомлений
 */
$config['sys']['mail']['type']             = 'mail';                 // Какой тип отправки использовать
$config['sys']['mail']['from_email']       = 'rus.engine@gmail.com'; // Мыло с которого отправляются все уведомления
$config['sys']['mail']['from_name']        = 'Почтовик LiveStreet';  // Имя с которого отправляются все уведомления
$config['sys']['mail']['charset']          = 'UTF-8';                // Какую кодировку использовать в письмах
$config['sys']['mail']['smtp']['host']     = 'localhost';            // Настройки SMTP - хост
$config['sys']['mail']['smtp']['port']     = 25;                     // Настройки SMTP - порт
$config['sys']['mail']['smtp']['user']     = '';                     // Настройки SMTP - пользователь
$config['sys']['mail']['smtp']['password'] = '';                     // Настройки SMTP - пароль
$config['sys']['mail']['smtp']['auth']     = true;                   // Использовать авторизацию при отправке
$config['sys']['mail']['include_comment']  = true;                   // Включает в уведомление о новых комментах текст коммента
$config['sys']['mail']['include_talk']     = true;                   // Включает в уведомление о новых личных сообщениях текст сообщения
/**
 * Настройки кеширования
 */
// Определяем каталог для сохранения сессий
$aTmpDir=explode(';',session_save_path());
$sTmpDir = count($aTmpDir)>1 ? $aTmpDir[1] : $aTmpDir[0];
// Устанавливаем настройки кеширования
$config['sys']['cache']['use']    = true;               // использовать кеширование или нет
$config['sys']['cache']['type']   = 'file';             // тип кеширования: file и memory. memory использует мемкеш
$config['sys']['cache']['dir']    = $sTmpDir.'/';       // каталог для файлового кеша, также используется для временных картинок. По умолчанию подставляем каталог для хранения сессий
$config['sys']['cache']['prefix'] = 'livestreet_cache'; // префикс кеширования, чтоб можно было на одной машине держать несколько сайтов с общим кешевым хранилищем
$config['sys']['cache']['solid']  = true; // Настройка использования раздельного и монолитного кеша для отдельных операций

/**
 * Настройки логирования
 */
$config['sys']['logs']['file']           = 'log.log';       // файл общего лога
$config['sys']['logs']['sql_query']      = false;           // логировать или нет SQL запросы
$config['sys']['logs']['sql_query_file'] = 'sql_query.log'; // файл лога SQL запросов
$config['sys']['logs']['sql_error']      = true;            // логировать или нет ошибки SQl
$config['sys']['logs']['sql_error_file'] = 'sql_error.log'; // файл лога ошибок SQL
$config['sys']['logs']['cron_file']      = 'cron.log';      // файл лога запуска крон-процессов
/**
 * Общие настройки
 */
$config['general']['close']             = false; // использовать закрытый режим работы сайта, сайт будет доступен только авторизованным пользователям
$config['general']['rss_editor_mail']   = $config['sys']['mail']['from_email']; // мыло редактора РСС
$config['general']['reg']['invite']     = false; // использовать активацию при регистрации или нет
$config['general']['reg']['activation'] = false; // использовать режим регистрации по приглашению или нет. Если использовать, то регистрация будет доступна ТОЛЬКО по приглашениям!
/**
 * Языковые настройки
 */
$config['lang']['current'] ='russian';                                                // текущий язык текстовок
$config['lang']['path']    = $config['path']['root']['server'].'/templates/language'; // полный путь до языковых файлов
/**
 * Настройки ACL(Access Control List — список контроля доступа)
 */
$config['acl']['create']['blog']['rating']                =  1;  // порог рейтинга при котором юзер может создать коллективный блог
$config['acl']['create']['comment']['rating']             = -10; // порог рейтинга при котором юзер может добавлять комментарии
$config['acl']['create']['comment']['limit_time']         =  10; // время в секундах между постингом комментариев, если 0 то ограничение по времени не будет работать 
$config['acl']['create']['comment']['limit_time_rating']  = -1;  // рейтинг, выше которого перестаёт действовать ограничение по времени на постинг комментов. Не имеет смысла при $config['acl']['create']['comment']['limit_time']=0 
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
$config['module']['blog']['personal_good']   = -5;   // Рейтинг топика в персональном блоге ниже которого он считается плохим
$config['module']['blog']['collective_good'] = -3;   // рейтинг топика в коллективных блогах ниже которого он считается плохим
$config['module']['blog']['index_good']      = -8;   // Рейтинг топика выше которого(включительно) он попадает на главную
$config['module']['blog']['encrypt']         = 'livestreet'; // Ключ XXTEA шифрования идентификаторов в ссылках приглашения в блоги
// Модуль Topic
$config['module']['topic']['new_time']   = 60*60*24*1;  // Время в секундах в течении которого топик считается новым
$config['module']['topic']['per_page']   = 10;          // Число топиков на одну страницу
// Модуль User
$config['module']['user']['per_page']    = 15;          // Число юзеров на страницу на странице статистики
$config['module']['user']['friend_notice']['delete'] = false; // Отправить talk-сообщение в случае удаления пользователя из друзей
$config['module']['user']['friend_notice']['accept'] = false; // Отправить talk-сообщение в случае одобрения заявки на добавление в друзья
$config['module']['user']['friend_notice']['reject'] = false; // Отправить talk-сообщение в случае отклонения заявки на добавление в друзья
// Модуль Comment
$config['module']['comment']['per_page'] = 20;          // Число комментариев на одну страницу(это касается только полного списка комментариев прямого эфира)
$config['module']['comment']['bad']      = -5;          // Рейтинг комментария, начиная с которого он будет скрыт
$config['module']['comment']['max_tree'] = 7;           // Максимальная вложенность комментов при отображении
// Модуль Talk
$config['module']['talk']['per_page']   = 15;           // Число приватных сообщений на одну страницу
$config['module']['talk']['reload']     = false;
$config['module']['talk']['request']    = 60;
$config['module']['talk']['period']     = 20000;
$config['module']['talk']['max_errors'] = 4;
$config['module']['talk']['encrypt']    = 'livestreet'; // Ключ XXTEA шифрования идентификаторов в ссылках
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
$config['module']['image']['default']['round_corner']         = false;
$config['module']['image']['default']['round_corner_radius']  = '18';
$config['module']['image']['default']['round_corner_rate']    = '40';
$config['module']['image']['default']['path']['watermarks']   = $config['path']['root']['server'].'/engine/lib/external/LiveImage/watermarks/';
$config['module']['image']['default']['path']['fonts']        = $config['path']['root']['server'].'/engine/lib/external/LiveImage/fonts/';

$config['module']['image']['foto']['watermark_use']  = false;
$config['module']['image']['foto']['round_corner']   = false;

$config['module']['image']['topic']['watermark_use']  = false;
$config['module']['image']['topic']['round_corner']   = false;
// Модуль Security
$config['module']['security']['code'] = "livestreet_security";     // текстовая строка для генерирования security-кода
$config['module']['security']['key']  = "livestreet_security_key"; // ключ сессии для хранения security-кода

// Какие модули должны быть загружены на старте
$config['module']['autoLoad'] = array('Cache', 'Security','Session','User', 'Lang', 'Message');
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

$config['db']['table']['user']                = $config['db']['table']['prefix'].'user';
$config['db']['table']['blog']                = $config['db']['table']['prefix'].'blog';
$config['db']['table']['topic']               = $config['db']['table']['prefix'].'topic';
$config['db']['table']['topic_tag']           = $config['db']['table']['prefix'].'topic_tag';
$config['db']['table']['comment']             = $config['db']['table']['prefix'].'comment';
$config['db']['table']['vote']                = $config['db']['table']['prefix'].'vote';
$config['db']['table']['topic_read']          = $config['db']['table']['prefix'].'topic_read';
$config['db']['table']['blog_user']           = $config['db']['table']['prefix'].'blog_user';
$config['db']['table']['blog_vote']           = $config['db']['table']['prefix'].'blog_vote';
$config['db']['table']['topic_comment_vote']  = $config['db']['table']['prefix'].'topic_comment_vote';
$config['db']['table']['user_vote']           = $config['db']['table']['prefix'].'user_vote';
$config['db']['table']['favourite']           = $config['db']['table']['prefix'].'favourite';
$config['db']['table']['talk']                = $config['db']['table']['prefix'].'talk';
$config['db']['table']['talk_user']           = $config['db']['table']['prefix'].'talk_user';
$config['db']['table']['talk_comment']        = $config['db']['table']['prefix'].'talk_comment';
$config['db']['table']['talk_blacklist']      = $config['db']['table']['prefix'].'talk_blacklist';
$config['db']['table']['friend']              = $config['db']['table']['prefix'].'friend';
$config['db']['table']['topic_content']       = $config['db']['table']['prefix'].'topic_content';
$config['db']['table']['topic_question_vote'] = $config['db']['table']['prefix'].'topic_question_vote';
$config['db']['table']['user_administrator']  = $config['db']['table']['prefix'].'user_administrator';
$config['db']['table']['comment_online']      = $config['db']['table']['prefix'].'comment_online';
$config['db']['table']['invite']              = $config['db']['table']['prefix'].'invite';
$config['db']['table']['page']                = $config['db']['table']['prefix'].'page';
$config['db']['table']['city']                = $config['db']['table']['prefix'].'city';
$config['db']['table']['city_user']           = $config['db']['table']['prefix'].'city_user';
$config['db']['table']['country']             = $config['db']['table']['prefix'].'country';
$config['db']['table']['country_user']        = $config['db']['table']['prefix'].'country_user';
$config['db']['table']['reminder']            = $config['db']['table']['prefix'].'reminder';
$config['db']['table']['session']             = $config['db']['table']['prefix'].'session';
$config['db']['table']['notify_task']         = $config['db']['table']['prefix'].'notify_task';

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
$config['router']['page']['top']           = 'ActionTop';
$config['router']['page']['index']         = 'ActionIndex';
$config['router']['page']['new']           = 'ActionNew';
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
// Глобальные настройки роутинга
$config['router']['config']['action_default']   = 'index';
$config['router']['config']['action_not_found'] = 'error';

/**
 * Настройки вывода блоков
 */
$config['block']['rule_index_blog'] = array(
	'path' => array( 
		$config['path']['root']['web'].'/blog/*/*\.html$',
		$config['path']['root']['web'].'/blog/*\.html$',
	),
	'action'  => array(
			'index' => array('index'),
		),
	'blocks'  => array(
			'right' => array('stream','tags','blogs'=>array('params'=>array(),'priority'=>1))
		)
);

/**
 * Настройки вывода js и css файлов
 */
$config['head']['rules']['page'] =array(
	'path'=>$config['path']['root']['web'].'/page/',
	'js' => array(
		'exclude' => array(
			$config['path']['static']['skin']."/js/vote.js",
			$config['path']['static']['skin']."/js/favourites.js",
			$config['path']['static']['skin']."/js/questions.js",		
		)
	),
);

$config['head']['default']['js']  = array(
	$config['path']['root']['engine_lib']."/external/JsHttpRequest/JsHttpRequest.js",
	$config['path']['root']['engine_lib']."/external/MooTools_1.2/mootools-1.2.js?v=1.2.2",
	$config['path']['root']['engine_lib']."/external/MooTools_1.2/plugs/Roal/Roar.js",
	$config['path']['root']['engine_lib']."/external/MooTools_1.2/plugs/Autocompleter/Observer.js",
	$config['path']['root']['engine_lib']."/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.js",
	$config['path']['root']['engine_lib']."/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.Request.js",
	$config['path']['root']['engine_lib']."/external/MooTools_1.2/plugs/Piechart/moocanvas.js",
	$config['path']['root']['engine_lib']."/external/MooTools_1.2/plugs/Piechart/piechart.js",
	$config['path']['root']['engine_lib']."/external/MooTools_1.2/plugs/vlaCal-v2.1/jslib/vlaCal-v2.1.js",
	$config['path']['root']['engine_lib']."/external/prettify/prettify.js",
	$config['path']['static']['skin']."/js/vote.js",
	$config['path']['static']['skin']."/js/favourites.js",
	$config['path']['static']['skin']."/js/questions.js",
	$config['path']['static']['skin']."/js/block_loader.js",
	$config['path']['static']['skin']."/js/friend.js",
	$config['path']['static']['skin']."/js/blog.js",	
	$config['path']['static']['skin']."/js/other.js",
	$config['path']['static']['skin']."/js/login.js",
	$config['path']['static']['skin']."/js/panel.js",
	$config['path']['root']['engine_lib']."/external/MooTools_1.2/plugs/Piechart/moocanvas.js"=>array('browser'=>'IE'),
);
$config['head']['default']['css'] = array(
	$config['path']['static']['skin']."/css/style.css?v=1",
	$config['path']['static']['skin']."/css/Roar.css",
	$config['path']['static']['skin']."/css/piechart.css",
	$config['path']['static']['skin']."/css/Autocompleter.css",
	$config['path']['static']['skin']."/css/prettify.css",	
	$config['path']['static']['skin']."/css/thickbox.css",
	$config['path']['static']['skin']."/css/vlaCal-v2.1.css",
	$config['path']['static']['skin']."/css/ie6.css?v=1"=>array('browser'=>'IE 6'),
	$config['path']['static']['skin']."/css/ie7.css?v=1"=>array('browser'=>'gte IE 7'),	
	$config['path']['static']['skin']."/css/simple_comments.css"=>array('browser'=>'gt IE 6'),	
);

/**
 * Параметры компрессии css-файлов
 */
$config['compress']['css']['use'] = true;
$config['compress']['css']['case_properties']     = 1;
$config['compress']['css']['merge_selectors']     = 0;
$config['compress']['css']['optimise_shorthands'] = 1;
$config['compress']['css']['remove_last_;']       = true;
$config['compress']['css']['css_level']           = 'CSS2.1';
$config['compress']['css']['template']            = "highest_compression";
/**
 * Параметры компрессии js-файлов
 */
$config['compress']['js']['use'] = true;

/**
 * Установка локали
 */
setlocale(LC_ALL, "ru_RU.UTF-8");

return $config;
?>