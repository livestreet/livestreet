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
 * Шаблон(скин)
 */
//define('SITE_SKIN','new');

/**
 * Настройка путей
 * Если необходимо установить движек в директорию(не корень сайта) то следует сделать так:
 * define('DIR_WEB_ROOT','http://'.$_SERVER['HTTP_HOST'].'/subdir');
 * define('DIR_SERVER_ROOT',$_SERVER['DOCUMENT_ROOT'].'/subdir');
 * и возможно придёться увеличить значение SYS_OFFSET_REQUEST_URL на число вложенных директорий, например, для директории первой вложенности www.site.ru/livestreet/ поставить значение равное 1 
 */
//define('DIR_WEB_ROOT','http://'.$_SERVER['HTTP_HOST']); // полный WEB адрес сайта
//define('DIR_STATIC_ROOT',DIR_WEB_ROOT); // чтоб можно было статику засунуть на отдельный сервер
//define('DIR_SERVER_ROOT',$_SERVER['DOCUMENT_ROOT']); // полный путь до сайта в файловой системе
//define('DIR_SERVER_ENGINE',DIR_SERVER_ROOT.'/engine'); // полный путь до сайта в файловой системе
//define('DIR_WEB_ENGINE_LIB',DIR_WEB_ROOT.'/engine/lib'); // полный путь до сайта в файловой системе
//define('DIR_STATIC_SKIN',DIR_STATIC_ROOT.'/templates/skin/'.SITE_SKIN);
//define('DIR_UPLOADS','/uploads');
//define('DIR_UPLOADS_IMAGES',DIR_UPLOADS.'/images');

/**
 * Настройки шаблонизатора Smarty
 *
 */
//define('DIR_SMARTY_TEMPLATE',DIR_SERVER_ROOT.'/templates/skin/'.SITE_SKIN);
//define('DIR_SMARTY_COMPILED',DIR_SERVER_ROOT.'/templates/compiled');
//define('DIR_SMARTY_CACHE',DIR_SERVER_ROOT.'/templates/cache');
//define('DIR_SMARTY_PLUG',DIR_SERVER_ENGINE.'/modules/viewer/plugs');

/**
 * Системные настройки
 */
//define('SYS_OFFSET_REQUEST_URL',0); // иногда помогает если сервер использует внутренние реврайты

/**
 * Настройки логирования
 */
//define('SYS_LOGS_FILE','log.log'); // файл общего лога
//define('SYS_LOGS_SQL_QUERY',false); // логировать или нет SQL запросы
//define('SYS_LOGS_SQL_QUERY_FILE','sql_query.log'); // файл лога SQL запросов
//define('SYS_LOGS_SQL_ERROR',true); // логировать или нет ошибки SQl
//define('SYS_LOGS_SQL_ERROR_FILE','sql_error.log'); // файл лога ошибок SQL

/**
 * Настройки кеширования
 */
//define('SYS_CACHE_USE',false); // использовать кеширование или нет
//define('SYS_CACHE_TYPE','file'); // тип кеширования: file и memory. memory использует мемкеш
//$aTmpDir=explode(';',session_save_path());
//$sTmpDir = count($aTmpDir)>1 ? $aTmpDir[1] : $aTmpDir[0];
//define('SYS_CACHE_DIR',$sTmpDir.'/'); // каталог для файлового кеша, также используется для временных картинок. По умолчанию подставляем каталог для хранения сессий
//define('SYS_CACHE_PREFIX','livestreet_cache'); // префикс кеширования, чтоб можно было на одной машине держать несколько сайтов с общим кешевым хранилищем

/**
 * Настройки куков
 */
//define('SYS_COOKIE_HOST',null); // хост для установки куков
//define('SYS_COOKIE_PATH','/'); // путь для установки куков

/**
 * Настройки сессий
 */
//define('SYS_SESSION_STANDART',true); // Использовать или нет стандартный механизм сессий
//define('SYS_SESSION_NAME','PHPSESSID'); // название сессии
//define('SYS_SESSION_TIMEOUT',null); // Тайм-аут сессии в секундах
//define('SYS_SESSION_HOST',SYS_COOKIE_HOST); // хост сессии в куках
//define('SYS_SESSION_PATH',SYS_COOKIE_PATH); // путь сессии в куках

/**
 * Настройки почтовых уведомлений
 */
//define('SYS_MAIL_TYPE','mail'); // Какой тип отправки использовать
//define('SYS_MAIL_FROM_EMAIL','rus.engine@gmail.com'); // Мыло с которого отправляются все уведомления
//define('SYS_MAIL_FROM_NAME','Почтовик LiveStreet'); // Имя с которого отправляются все уведомления
//define('SYS_MAIL_CHARSET','UTF-8'); // Какую кодировку использовать в письмах
//define('SYS_MAIL_SMTP_HOST','localhost'); // Настройки SMTP - хост
//define('SYS_MAIL_SMTP_PORT',25); // Настройки SMTP - порт
//define('SYS_MAIL_SMTP_USER',''); // Настройки SMTP - пользователь
//define('SYS_MAIL_SMTP_PASSWORD',''); // Настройки SMTP - пароль
//define('SYS_MAIL_SMTP_AUTH',true); // Использовать авторизацию при отправке
//define('SYS_MAIL_INCLUDE_COMMENT_TEXT',true); // Включает в уведомление о новых комментах текст коммента
//define('SYS_MAIL_INCLUDE_TALK_TEXT',true); // Включает в уведомление о новых личных сообщениях текст сообщения


/**
 * Настройки ACL(Access Control List — список контроля доступа)
 */
//define('ACL_CAN_CREATE_BLOG',1); // порог рейтинга при котором юзер может создать коллективный блог
//define('ACL_CAN_POST_COMMENT',-10); // порог рейтинга при котором юзер может добавлять комментарии
//define('ACL_CAN_POST_COMMENT_TIME',10); // время в секундах между постингом комментариев, если 0 то ограничение по времени не будет работать 
//define('ACL_CAN_POST_COMMENT_TIME_RATING',1); // рейтинг, выше которого перестаёт действовать ограничение по времени на постинг комментов. Не имеет смысла при ACL_CAN_POST_COMMENT_TIME=0 
//define('ACL_CAN_VOTE_COMMENT',-3); // порог рейтинга при котором юзер может голосовать за комментарии
//define('ACL_CAN_VOTE_BLOG',-5); // порог рейтинга при котором юзер может голосовать за блог
//define('ACL_CAN_VOTE_TOPIC',-7); // порог рейтинга при котором юзер может голосовать за топик
//define('ACL_CAN_VOTE_USER',-1); // порог рейтинга при котором юзер может голосовать за пользователя

/**
 * Ограничение по времени на голосования
 */
//define('VOTE_LIMIT_TIME_TOPIC',60*60*24*20);
//define('VOTE_LIMIT_TIME_COMMENT',60*60*24*5);

/**
 * Языковые настройки
 */
//define('LANG_CURRENT','russian'); // текущий язык текстовок
//define('LANG_PATH',DIR_SERVER_ROOT.'/templates/language'); // полный путь до языковых файлов

/**
 * Прочие настройки
 */
//define('SITE_NAME','LiveStreet - бесплатный движок социальной сети'); // название сайта
//define('SITE_KEYWORDS','движок, livestreet, блоги, социальная сеть, бесплатный, php'); // seo keywords
//define('SITE_DESCRIPTION','LiveStreet - официальный сайт бесплатного движка социальной сети'); // seo description
//define('SITE_CLOSE_MODE',false); // использовать закрытый режим работы сайта, сайт будет доступен только авторизованным пользователям
//define('USER_USE_ACTIVATION',false); // использовать активацию при регистрации или нет
//define('USER_USE_INVITE',false); // использовать режим регистрации по приглашению или нет. Если использовать, то регистрация будет доступна ТОЛЬКО по приглашениям!
//define('BLOG_PERSONAL_LIMIT_GOOD',-5); // Рейтинг топика в персональном блоге ниже которого он считается плохим
//define('BLOG_COLLECTIVE_LIMIT_GOOD',-3); // рейтинг топика в коллективных блогах ниже которого он считается плохим
//define('BLOG_INDEX_LIMIT_GOOD',8); // рейтинг топика выше которого(включительно) он попадает на главную
//define('BLOG_TOPIC_NEW_TIME',60*60*24*1); // Время в секундах в течении которого топик считается новым
//define('BLOG_TOPIC_PER_PAGE',10); // число топиков на одну страницу
//define('BLOG_COMMENT_PER_PAGE',20); // число комментариев на одну страницу(это касается только полного списка комментариев прямого эфира)
//define('BLOG_COMMENT_BAD',-5); // рейтинг комментария, начиная с которого он будет скрыт
//define('BLOG_COMMENT_MAX_TREE_LEVEL',7); // максимальная вложенность комментов при отображении
//define('BLOG_BLOGS_PER_PAGE',20); // число блогов на страницу
//define('BLOG_IMG_RESIZE_WIDTH',500); // до какого размера в пикселях ужимать картинку по щирине при загрузки её в топики и комменты
//define('BLOG_USE_TINYMCE',false); // использовать или нет визуальный редактор TinyMCE
//define('USER_PER_PAGE',15); // число юзеров на страницу на странице статистики
//define('RSS_EDITOR_MAIL',$config['sys']['mail']['from_email'] ); // мыло редактора РСС
//define('BLOG_URL_NO_INDEX',true); // "прятать" или нет ссылки от поисковиков, оборачивая их в тег <noindex> и добавляя rel="nofollow"

/**
 * Настройки блоков
 */
//define('BLOCK_STREAM_COUNT_ROW',20); // сколько записей выводить в блоке "Прямой эфир"
//define('BLOCK_BLOGS_COUNT_ROW',10); // сколько записей выводить в блоке "Блоги"

//require_once($config['path']['root']['server'] ."/config/config.table.php");
//require_once($config['path']['root']['server'] ."/config/loader.php");

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
/**
 * Настройки логирования
 */
$config['sys']['logs']['file']           = 'log.log';       // файл общего лога
$config['sys']['logs']['sql_query']      = false;           // логировать или нет SQL запросы
$config['sys']['logs']['sql_query_file'] = 'sql_query.log'; // файл лога SQL запросов
$config['sys']['logs']['sql_error']      = true;            // логировать или нет ошибки SQl
$config['sys']['logs']['sql_error_file'] = 'sql_error.log'; // файл лога ошибок SQL
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
// Модуль Topic
$config['module']['topic']['new_time']   = 60*60*24*1;  // Время в секундах в течении которого топик считается новым
$config['module']['topic']['per_page']   = 10;          // Число топиков на одну страницу
// Модуль User
$config['module']['user']['per_page']    = 15;          // Число юзеров на страницу на странице статистики
// Модуль Comment
$config['module']['comment']['per_page'] = 20;          // Число комментариев на одну страницу(это касается только полного списка комментариев прямого эфира)
$config['module']['comment']['bad']      = -5;          // Рейтинг комментария, начиная с которого он будет скрыт
$config['module']['comment']['max_tree'] = 7;           // Максимальная вложенность комментов при отображении
// Модуль Talk
$config['module']['talk']['per_page']   = 15;           // Число приватных сообщений на одну страницу
$config['module']['talk']['reload']     = true;
$config['module']['talk']['request']    = 60;
$config['module']['talk']['period']     = 20000;
$config['module']['talk']['max_errors'] = 4;
// Модуль Lang
$config['module']['lang']['delete_undefined'] = true; // Если установлена true, то модуль будет автоматически удалять из языковых конструкций переменные вида %%var%%, по которым не была произведена замена

// Какие модули должны быть загружены на старте
$config['module']['autoLoad'] = array('Cache','Session','User', 'Lang', 'Message');
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
$config['router']['page']['tools']         = 'ActionTools';
// Глобальные настройки роутинга
$config['router']['config']['action_default']   = 'index';
$config['router']['config']['action_not_found'] = 'error';
/**
 * Установка локали
 */
setlocale(LC_ALL, "ru_RU.UTF-8");

return $config;
?>