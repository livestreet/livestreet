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

require_once("./config/config.table.php");
/**
 * Настройки шаблонизатора Smarty
 *
 */
define('DIR_SMARTY_TEMPLATE','templates/skin/habra');
define('DIR_SMARTY_COMPILED','templates/compiled');
define('DIR_SMARTY_CACHE','templates/cache');
define('DIR_SMARTY_PLUG','classes/modules/sys_viewer/plugs');

/**
 * Настройка путей
 */
define('DIR_WEB_ROOT','http://'.$_SERVER['HTTP_HOST']);
define('DIR_STATIC_ROOT',DIR_WEB_ROOT); // чтоб можно было статику засунуть на отдельный сервер
define('DIR_SERVER_ROOT',$_SERVER['DOCUMENT_ROOT']);
define('DIR_STATIC_SKIN',DIR_STATIC_ROOT.'/'.DIR_SMARTY_TEMPLATE); 
define('DIR_UPLOADS','/uploads');
define('DIR_UPLOADS_IMAGES',DIR_UPLOADS.'/images');

/**
 * Системные настройки
 */
define('SYS_OFFSET_REQUEST_URL',0); // иногда помогает если сервер использует внутренние реврайты

/**
 * Настройки логирования
 */
define('SYS_LOGS_FILE','log.log'); // файл общего лога
define('SYS_LOGS_SQL_QUERY',false); // логировать или нет SQL запросы
define('SYS_LOGS_SQL_QUERY_FILE','sql_query.log'); // файл лога SQL запросов
define('SYS_LOGS_SQL_ERROR',true); // логировать или нет ошибки SQl
define('SYS_LOGS_SQL_ERROR_FILE','sql_error.log'); // файл лога ошибок SQL

/**
 * Настройки кеширования
 */
define('SYS_CACHE_USE',true); // использовать кеширование или нет
define('SYS_CACHE_TYPE','file'); // тип кеширования: file и memory(пока не работает). memory использует мемкеш
define('SYS_CACHE_DIR','/tmp/'); // каталог для файлового кеша, также используется для временных картинок
define('SYS_CACHE_PREFIX','livestreet_cache'); // префикс кеширования, чтоб можно было на одной машине держать несколько сайтов с общим кешевым хранилищем

/**
 * Настройки куков
 */
define('SYS_COOKIE_HOST',null); // хост для установки куков
define('SYS_COOKIE_PATH','/'); // путь для установки куков

/**
 * Настройки сессий
 */
define('SYS_SESSION_STANDART',true); // Использовать или нет стандартный механизм сессий
define('SYS_SESSION_NAME','PHPSESSID'); // название сессии
define('SYS_SESSION_TIMEOUT',null); // Тайм-аут сессии в секундах
define('SYS_SESSION_HOST',SYS_COOKIE_HOST); // хост сессии в куках
define('SYS_SESSION_PATH',SYS_COOKIE_PATH); // путь сессии в куках

/**
 * Настройки почтовых уведомлений
 */
define('SYS_MAIL_TYPE','mail'); // Какой тип отправки использовать
define('SYS_MAIL_FROM_EMAIL','rus.engine@gmail.com'); // Мыло с которого отправляются все уведомления
define('SYS_MAIL_FROM_NAME','Почтовик LiveStreet'); // Имя с которого отправляются все уведомления
define('SYS_MAIL_CHARSET','UTF-8'); // Какую кодировку использовать в письмах
define('SYS_MAIL_SMTP_HOST','localhost'); // Настройки SMTP - хост
define('SYS_MAIL_SMTP_PORT',25); // Настройки SMTP - порт
define('SYS_MAIL_SMTP_USER',''); // Настройки SMTP - пользователь
define('SYS_MAIL_SMTP_PASSWORD',''); // Настройки SMTP - пароль
define('SYS_MAIL_INCLUDE_COMMENT_TEXT',true); // Включает в уведомление о новых комментах текст коммента
define('SYS_MAIL_INCLUDE_TALK_TEXT',true); // Включает в уведомление о новых личных сообщениях текст сообщения


/**
 * Настройки ACL(Access Control List — список контроля доступа)
 */
define('ACL_CAN_CREATE_BLOG',1); // порог рейтинга при котором юзер может создать коллективный блог
define('ACL_CAN_POST_COMMENT',-10); // порог рейтинга при котором юзер может добавлять комментарии
define('ACL_CAN_VOTE_COMMENT',-3); // порог рейтинга при котором юзер может голосовать за комментарии
define('ACL_CAN_VOTE_BLOG',-5); // порог рейтинга при котором юзер может голосовать за блог
define('ACL_CAN_VOTE_TOPIC',-7); // порог рейтинга при котором юзер может голосовать за топик
define('ACL_CAN_VOTE_USER',-1); // порог рейтинга при котором юзер может голосовать за пользователя


/**
 * Прочие настройки
 */
define('SITE_NAME','LiveStreet - бесплатный движок социальной сети'); // название сайта
define('SITE_KYEWORDS','движок, livestreet, блоги, социальная сеть, бесплатный, php'); // seo keywords
define('SITE_DESCRIPTION','LiveStreet - официальный сайт бесплатного движка социальной сети'); // seo description
define('SITE_CLOSE_MODE',false); // использовать закрытый режим работы сайта, сайт будет доступен только авторизованным пользователям
define('USER_USE_ACTIVATION',false); // использовать активацию при регистрации или нет
define('USER_USE_INVITE',false); // использовать режим регистрации по приглашению или нет. Если использовать, то регистрация будет доступна ТОЛЬКО по приглашениям!
define('BLOG_PERSONAL_LIMIT_GOOD',-5); // Рейтинг топика в персональном блоге ниже которого он считается плохим
define('BLOG_COLLECTIVE_LIMIT_GOOD',-3); // рейтинг топика в коллективных блогах ниже которого он считается плохим
define('BLOG_INDEX_LIMIT_GOOD',8); // рейтинг топика выше которого(включительно) он попадает на главную
define('BLOG_TOPIC_NEW_TIME',60*60*24*1); // Время в секундах в течении которого топик считается новым
define('BLOG_TOPIC_PER_PAGE',10); // число топиков на одну страницу
define('BLOG_COMMENT_PER_PAGE',20); // число комментариев на одну страницу(это касается только полного списка комментариев прямого эфира)
define('BLOG_COMMENT_BAD',-5); // рейтинг комментария, начиная с которого он будет скрыт
define('USER_PER_PAGE',15); // число юзеров на страницу на странице статистики
define('RSS_EDITOR_MAIL',SYS_MAIL_FROM_EMAIL); // мыло редактора РСС


/**
 * Установка локали
 */
setlocale(LC_ALL, "ru_RU.UTF-8");
?>