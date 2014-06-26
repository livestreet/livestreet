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
 * Все изменения нужно вносить в файл config/config.local.php
 */
define('LS_VERSION','1.1.0.dev');

/**
 * Основные настройки путей
 * Если необходимо установить движек в директорию(не корень сайта) то следует сделать так:
 * $config['path']['root']['web']    = 'http://'.$_SERVER['HTTP_HOST'].'/subdir';
 * и увеличить значение $config['path']['offset_request_url'] на число вложенных директорий,
 * например, для директории первой вложенности www.site.ru/livestreet/ поставить значение равное 1
 */
$config['path']['root']['server']		= dirname(dirname(dirname(__FILE__)));
$config['path']['root']['web']			= isset($_SERVER['HTTP_HOST']) ? 'http://'.$_SERVER['HTTP_HOST'] : null;
$config['path']['offset_request_url']	= 0;


/**
 * Настройки HTML вида
 */
$config['view']['skin']        = 'developer';		// Название текущего шаблона
$config['view']['theme'] = 'default';			// тема оформления шаблона (шаблон должен поддерживать темы)
$config['view']['name']        = 'Мой сайт';                   // название сайта
$config['view']['description'] = 'Описание сайта'; // seo description
$config['view']['keywords']    = 'site, google, internet';      // seo keywords
$config['view']['wysiwyg']         = false;  // использовать или нет визуальный редактор TinyMCE
$config['view']['noindex']          = true;   // "прятать" или нет ссылки от поисковиков, оборачивая их в тег <noindex> и добавляя rel="nofollow"
$config['view']['img_resize_width'] = 570;    // до какого размера в пикселях ужимать картинку по щирине при загрузки её в топики и комменты
$config['view']['img_max_width'] = 5000;    // максимальная ширина загружаемых изображений в пикселях
$config['view']['img_max_height'] = 5000;    // максимальная высота загружаемых изображений в пикселях
$config['view']['img_max_size_url'] = 500;    // максимальный размер картинки в kB для загрузки по URL

/**
 * Настройки СЕО для вывода топиков
 */
$config['seo']['description_words_count'] = 20;               // количество слов из топика для вывода в метатег description

/**
 * Настройка основных блоков
 */
$config['block']['stream']['row'] = 20;                       // сколько записей выводить в блоке "Прямой эфир"
$config['block']['stream']['show_tip'] = true;                // выводить или нет всплывающие сообщения в блоке "Прямой эфир"
$config['block']['blogs']['row']  = 10;                       // сколько записей выводить в блоке "Блоги"
$config['block']['tags']['tags_count'] = 70;                  // сколько тегов выводить в блоке "теги"
$config['block']['tags']['personal_tags_count'] = 70;         // сколько тегов пользователя выводить в блоке "теги"

/**
 * Общие настройки
 */
$config['general']['close']             = false; // использовать закрытый режим работы сайта, сайт будет доступен только авторизованным пользователям
$config['general']['rss_editor_mail']   = '___sys.mail.from_email___'; // мыло редактора РСС
$config['general']['reg']['invite']     = false; // использовать режим регистрации по приглашению или нет. Если использовать, то регистрация будет доступна ТОЛЬКО по приглашениям!
$config['general']['reg']['activation'] = false; // использовать активацию при регистрации или нет
$config['general']['login']['captcha']  = false; // использовать каптчу при входе или нет
$config['general']['admin_mail']  = 'admin@admin.adm'; // email администратора
/**
 * Настройки кеширования
 */
$config['sys']['cache']['use']    = false;               // использовать кеширование или нет
$config['sys']['cache']['type']   = 'file';             // тип кеширования: file, xcache и memory. memory использует мемкеш, xcache - использует XCache

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
$config['acl']['update']['comment']['rating'] =  -5;   // порог рейтинга при котором юзер может редактировать комментарии
$config['acl']['update']['comment']['limit_time'] =  60*3;   // время в секундах после создания комментария, когда можно его отредактировать, если 0 то ограничение по времени не будет работать
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
$config['module']['blog']['avatar_size'] = array('500crop','100crop','64crop','48crop','24crop'); // Список размеров аватаров у блога
$config['module']['blog']['category_allow'] = true;  		// Разрешить использование категорий бля блогов
$config['module']['blog']['category_only_admin'] = true;  	// Задавать и менять категории для блога может только админ
$config['module']['blog']['category_only_children'] = true;	// Для блога можно выбрать только конечную категорию, у которой нет других вложенных
$config['module']['blog']['category_allow_empty'] = true;	// Разрешить блоги без категории
// Модуль Topic
$config['module']['topic']['new_time']   = 60*60*24*1;  // Время в секундах в течении которого топик считается новым
$config['module']['topic']['per_page']   = 10;          // Число топиков на одну страницу
$config['module']['topic']['max_length'] = 15000;       // Максимальное количество символов в одном топике
$config['module']['topic']['min_length'] = 2;       // Минимальное количество символов в одном топике
$config['module']['topic']['allow_empty'] = false;       // Разрешать или нет не заполнять текст топика
$config['module']['topic']['title_max_length'] = 200;       // Максимальное количество символов в заголовке топика
$config['module']['topic']['title_min_length'] = 2;       // Минимальное количество символов в заголовке топика
$config['module']['topic']['title_allow_empty'] = false;       // Разрешать или нет не заполнять заголовок топика
$config['module']['topic']['allow_empty_tags'] = false; // Разрешать или нет не заполнять теги
$config['module']['topic']['default_period_top'] = 1; // Дефолтный период (количество дней) для отображения ТОП топиков. Значения: 1,7,30,'all'
$config['module']['topic']['default_period_discussed'] = 1; // Дефолтный период (количество дней) для отображения обсуждаемых топиков. Значения: 1,7,30,'all'
// Модуль User
$config['module']['user']['per_page']    = 15;          // Число юзеров на страницу на странице статистики и в профиле пользователя
$config['module']['user']['friend_on_profile']    = 15;          // Ограничение на вывод числа друзей пользователя на странице его профиля
$config['module']['user']['friend_notice']['delete'] = false; // Отправить talk-сообщение в случае удаления пользователя из друзей
$config['module']['user']['friend_notice']['accept'] = false; // Отправить talk-сообщение в случае одобрения заявки на добавление в друзья
$config['module']['user']['friend_notice']['reject'] = false; // Отправить talk-сообщение в случае отклонения заявки на добавление в друзья
$config['module']['user']['avatar_size'] = array('100crop','64crop','48crop','24crop'); // Список размеров аватаров у пользователя
$config['module']['user']['login']['min_size'] = 3; // Минимальное количество символов в логине
$config['module']['user']['login']['max_size'] = 30; // Максимальное количество символов в логине
$config['module']['user']['login']['charset'] = '0-9a-z_\-'; // Допустимые в имени пользователя символы
$config['module']['user']['time_active'] = 60*60*24*7; 	// Число секунд с момента последнего посещения пользователем сайта, в течение которых он считается активным
$config['module']['user']['time_onlive'] = 60*10; 	// Число секунд с момента последнего посещения пользователем сайта, в течение которых он считается "онлайн"
$config['module']['user']['usernote_text_max'] = 250; 	    // Максимальный размер заметки о пользователе
$config['module']['user']['usernote_per_page'] = 20; 	      // Число заметок на одну страницу
$config['module']['user']['userfield_max_identical'] = 2; 	// Максимальное число контактов одного типа
$config['module']['user']['profile_photo_size'] = '370x'; 	  // размер фото в профиле пользователя, формат вида: WxH[crop]
$config['module']['user']['name_max'] = 30; 			  // максимальная длинна имени в профиле пользователя
$config['module']['user']['captcha_use_registration'] = true;  // проверять поле капчи при регистрации пользователя
$config['module']['user']['complaint_captcha'] = true;  // Использовать или нет каптчу при написании жалобы
$config['module']['user']['complaint_notify_by_mail'] = true;  // Уведомлять администратора на емайл о поступлении новой жалобы
$config['module']['user']['complaint_text_required'] = true;  // Обязательно указывать текст при жалобе
$config['module']['user']['complaint_text_max'] = 2000;  // Максимальный размер текста жалобы
$config['module']['user']['complaint_type'] = array(	// Список типов жалоб на пользователя
	'spam','obscene','other'
);

// Модуль Comment
$config['module']['comment']['per_page'] = 20;          // Число комментариев на одну страницу(это касается только полного списка комментариев прямого эфира)
$config['module']['comment']['bad']      = -5;          // Рейтинг комментария, начиная с которого он будет скрыт
$config['module']['comment']['max_tree'] = 7;           // Максимальная вложенность комментов при отображении
$config['module']['comment']['use_nested'] = false; 	// Использовать или нет nested set при выборке комментов, увеличивает производительность при большом числе комментариев + позволяет делать постраничное разбиение комментов
$config['module']['comment']['nested_per_page'] = 0; 	// Число комментов на одну страницу в топике, актуально только при use_nested = true
$config['module']['comment']['nested_page_reverse'] = true; 	// Определяет порядок вывода страниц. true - последние комментарии на первой странице, false - последние комментарии на последней странице
$config['module']['comment']['favourite_target_allow'] = array('topic'); 	// Список типов комментов, которые разрешено добавлять в избранное
$config['module']['comment']['edit_target_allow'] = array('topic','talk'); 	// Список типов комментов, которые разрешено редактировать
$config['module']['comment']['vote_target_allow'] = array('topic'); 	// Список типов комментов, за которые разрешено голосовать
// Модуль Talk
$config['module']['talk']['per_page']   = 30;           // Число приватных сообщений на одну страницу
$config['module']['talk']['encrypt']    = 'livestreet'; // Ключ XXTEA шифрования идентификаторов в ссылках
$config['module']['talk']['max_users']	= 15; // Максимальное число адресатов в одном личном сообщении
// Модуль Lang
$config['module']['lang']['delete_undefined'] = true;   // Если установлена true, то модуль будет автоматически удалять из языковых конструкций переменные вида %%var%%, по которым не была произведена замена
// Модуль Notify
$config['module']['notify']['delayed']       = false;    // Указывает на необходимость использовать режим отложенной рассылки сообщений на email
$config['module']['notify']['insert_single'] = false;    // Если опция установлена в true, систему будет собирать записи заданий удаленной публикации, для вставки их в базу единым INSERT
$config['module']['notify']['per_process']   = 10;       // Количество отложенных заданий, обрабатываемых одним крон-процессом
$config['module']['notify']['dir']           = 'emails'; // Путь до папки с емэйлами относительно шаблона
$config['module']['notify']['prefix']        = 'email';  // Префикс шаблонов емэйлов

// Модуль Security
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
 * Модуль опросов (Poll)
 */
$config['module']['poll']['time_limit_update'] = 60*60*30; // Время в секундах, в течении которого можно изменять опрос
/**
 * Модуль Image
 */
$config['module']['image']['params']['blog_avatar']['size_max_width']=500;
$config['module']['image']['params']['blog_avatar']['size_max_height']=500;
/**
 * Модуль Media
 */
$config['module']['media']['image']['autoresize'] = true; // Разрешает автоматическое создание изображений нужного размера при их запросе
$config['module']['media']['image']['max_size_url'] = 1024; // Максимальный размер изображения в kB для загрузки по URL
$config['module']['media']['image']['sizes'] = array(  // список размеров, которые необходимо делать при загрузке изображения
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
		'h' => 100,
		'crop' => true,
	),
	array(
		'w' => 50,
		'h' => 50,
		'crop' => true,
	)
);
$config['module']['media']['image']['preview']['sizes'] = array(  // список размеров, которые необходимо делать при создании превью
	array(
		'w' => 500,
		'h' => 300,
		'crop' => true,
	),
	array(
		'w' => 250,
		'h' => 150,
		'crop' => true,
	),
);

// Какие модули должны быть загружены на старте
$config['module']['autoLoad'] = array('Hook','Cache','Logger','Security','Session','Lang','Message','User');
/**
 * Настройка базы данных
 */
$config['db']['params']['host']   = 'localhost';
$config['db']['params']['port']   = '3306';
$config['db']['params']['user']   = 'root';
$config['db']['params']['pass']   = '';
$config['db']['params']['type']   = 'mysqli';
$config['db']['params']['dbname'] = 'social';
/**
 * Настройка таблиц базы данных
 */
$config['db']['table']['prefix'] = 'prefix_';

$config['db']['table']['user']                = '___db.table.prefix___user';
$config['db']['table']['blog']                = '___db.table.prefix___blog';
$config['db']['table']['blog_category']       = '___db.table.prefix___blog_category';
$config['db']['table']['topic']               = '___db.table.prefix___topic';
$config['db']['table']['topic_tag']           = '___db.table.prefix___topic_tag';
$config['db']['table']['topic_type']          = '___db.table.prefix___topic_type';
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
$config['db']['table']['user_administrator']  = '___db.table.prefix___user_administrator';
$config['db']['table']['comment_online']      = '___db.table.prefix___comment_online';
$config['db']['table']['invite']              = '___db.table.prefix___invite';
$config['db']['table']['page']                = '___db.table.prefix___page';
$config['db']['table']['reminder']            = '___db.table.prefix___reminder';
$config['db']['table']['session']             = '___db.table.prefix___session';
$config['db']['table']['notify_task']         = '___db.table.prefix___notify_task';
$config['db']['table']['userfeed_subscribe']  = '___db.table.prefix___userfeed_subscribe';
$config['db']['table']['stream_subscribe']    = '___db.table.prefix___stream_subscribe';
$config['db']['table']['stream_event']        = '___db.table.prefix___stream_event';
$config['db']['table']['stream_user_type']    = '___db.table.prefix___stream_user_type';
$config['db']['table']['user_field']          = '___db.table.prefix___user_field';
$config['db']['table']['user_field_value']    = '___db.table.prefix___user_field_value';
$config['db']['table']['subscribe']           = '___db.table.prefix___subscribe';
$config['db']['table']['wall']                = '___db.table.prefix___wall';
$config['db']['table']['user_note']           = '___db.table.prefix___user_note';
$config['db']['table']['user_complaint']           = '___db.table.prefix___user_complaint';
$config['db']['table']['geo_country']         = '___db.table.prefix___geo_country';
$config['db']['table']['geo_region']          = '___db.table.prefix___geo_region';
$config['db']['table']['geo_city']            = '___db.table.prefix___geo_city';
$config['db']['table']['geo_target']          = '___db.table.prefix___geo_target';
$config['db']['table']['user_changemail']     = '___db.table.prefix___user_changemail';
$config['db']['table']['property'] 			  = '___db.table.prefix___property';
$config['db']['table']['property_target'] 	  = '___db.table.prefix___property_target';
$config['db']['table']['property_select'] 	  = '___db.table.prefix___property_select';
$config['db']['table']['property_value'] 	  = '___db.table.prefix___property_value';
$config['db']['table']['property_value_tag']  = '___db.table.prefix___property_value_tag';
$config['db']['table']['property_value_select']  = '___db.table.prefix___property_value_select';
$config['db']['table']['media']  			  = '___db.table.prefix___media';
$config['db']['table']['media_target']  	  = '___db.table.prefix___media_target';
$config['db']['table']['rbac_role'] 		  = '___db.table.prefix___rbac_role';
$config['db']['table']['rbac_permission'] 	  = '___db.table.prefix___rbac_permission';
$config['db']['table']['rbac_role_permission']= '___db.table.prefix___rbac_role_permission';
$config['db']['table']['rbac_user_role'] 	  = '___db.table.prefix___rbac_user_role';
$config['db']['table']['storage']			  = '___db.table.prefix___storage';
$config['db']['table']['poll']			  = '___db.table.prefix___poll';
$config['db']['table']['poll_answer']			  = '___db.table.prefix___poll_answer';
$config['db']['table']['poll_vote']			  = '___db.table.prefix___poll_vote';
$config['db']['table']['category']			  = '___db.table.prefix___category';
$config['db']['table']['category_type']		  = '___db.table.prefix___category_type';
$config['db']['table']['category_target']	  = '___db.table.prefix___category_target';

$config['db']['tables']['engine'] = 'InnoDB';  // InnoDB или MyISAM

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
$config['router']['page']['login']         = 'ActionLogin';
$config['router']['page']['people']        = 'ActionPeople';
$config['router']['page']['settings']      = 'ActionSettings';
$config['router']['page']['tag']           = 'ActionTag';
$config['router']['page']['talk']          = 'ActionTalk';
$config['router']['page']['comments']      = 'ActionComments';
$config['router']['page']['rss']           = 'ActionRss';
$config['router']['page']['blogs']         = 'ActionBlogs';
$config['router']['page']['search']        = 'ActionSearch';
$config['router']['page']['admin']         = 'ActionAdmin';
$config['router']['page']['ajax']          = 'ActionAjax';
$config['router']['page']['feed']          = 'ActionUserfeed';
$config['router']['page']['stream']        = 'ActionStream';
$config['router']['page']['subscribe']     = 'ActionSubscribe';
$config['router']['page']['content']     = 'ActionContent';
$config['router']['page']['property']     = 'ActionProperty';
// Глобальные настройки роутинга
$config['router']['config']['default']['action']	= 'index';
$config['router']['config']['default']['event']		= null;
$config['router']['config']['default']['params']	= null;
$config['router']['config']['default']['request']	= null;
$config['router']['config']['action_not_found']		= 'error';

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
$config['block']['rule_index'] = array(
	'action'  => array( 'index' ),
	'blocks'  => array( 'right' => array('blogNav'=>array('priority'=>500)) ),
);
$config['block']['rule_topic_type'] = array(
	'action'  => array(
		'content'     => array('add','edit'),
	),
	'blocks'  => array( 'right' => array('blocks/block.blogInfo.tpl', 'blocks/block.blogInfoNote.tpl') ),
);
$config['block']['rule_personal_blog'] = array(
	'action'  => array( 'personal_blog' ),
	'blocks'  => array( 'right' => array('stream','tags') ),
);
$config['block']['rule_tag'] = array(
	'action'  => array( 'tag' ),
	'blocks'  => array( 'right' => array('tags','stream') ),
);
$config['block']['rule_blogs'] = array(
	'action'  => array( 'blogs' ),
	'blocks'  => array(
		'right' => array(
			'blocks/block.blogAdd.tpl' => array('priority' => 100),
			'blogsSearch' => array('priority' => 50)
		)
	),
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
$config['block']['rule_users'] = array(
	'action' => array('people'),
	'blocks' => array(
		'right' => array(
			'blocks/block.usersStatistics.tpl',
			'blocks/block.usersSearch.tpl',
			'tagsCountry',
			'tagsCity',
		)
	)
);
$config['block']['rule_profile'] = array(
	'action' => array( 'profile', 'talk', 'settings' ),
	'blocks' => array(
		'right' => array(
			'blocks/block.userPhoto.tpl'   =>array('priority' => 100),
			'blocks/block.userActions.tpl' =>array('priority' => 50),
			'blocks/block.userNote.tpl'    =>array('priority' => 25),
			'blocks/block.userNav.tpl'     =>array('priority' => 1),
		)
	)
);
$config['block']['rule_blog'] = array(
	'action' => array('blog' => array('{blog}')),
	'blocks' => array(
		'right' => array(
			'blocks/block.blogActions.tpl' => array('priority'=>300),
			'blocks/block.blogUsers.tpl'   => array('priority'=>300),
			'blocks/block.blogAdmins.tpl'  => array('priority'=>300)
		)
	),
	'clear' => true
);


$config['head']['default']['js'] = array(
	/* Vendor libs */
	"___path.framework.frontend.web___/js/vendor/html5shiv.js" => array('browser'=>'lt IE 9'),
	"___path.framework.frontend.web___/js/vendor/jquery-1.9.1.min.js",
	"___path.framework.frontend.web___/js/vendor/jquery-ui/js/jquery-ui-1.10.2.custom.min.js",
	"___path.framework.frontend.web___/js/vendor/jquery-ui/js/localization/jquery-ui-datepicker-ru.js",
	"___path.framework.frontend.web___/js/vendor/jquery.browser.js",
	"___path.framework.frontend.web___/js/vendor/jquery.scrollto.js",
	"___path.framework.frontend.web___/js/vendor/jquery.rich-array.min.js",
	"___path.framework.frontend.web___/js/vendor/jquery.form.js",
	"___path.framework.frontend.web___/js/vendor/jquery.jqplugin.js",
	"___path.framework.frontend.web___/js/vendor/jquery.cookie.js",
	"___path.framework.frontend.web___/js/vendor/jquery.serializejson.js",
	"___path.framework.frontend.web___/js/vendor/jquery.file.js",
	"___path.framework.frontend.web___/js/vendor/jquery.hotkeys.js",
	"___path.framework.frontend.web___/js/vendor/jcrop/jquery.Jcrop.js",
	"___path.framework.frontend.web___/js/vendor/jquery.placeholder.min.js",
	"___path.framework.frontend.web___/js/vendor/jquery.charcount.js",
	"___path.framework.frontend.web___/js/vendor/jquery.imagesloaded.js",
	"___path.framework.frontend.web___/js/vendor/jquery.fileupload.js",
	"___path.framework.frontend.web___/js/vendor/jquery.timers.js",
	"___path.framework.frontend.web___/js/vendor/notifier/jquery.notifier.js",
	"___path.framework.frontend.web___/js/vendor/prettify/prettify.js",
	"___path.framework.frontend.web___/js/vendor/parsley/parsley.js",
	"___path.framework.frontend.web___/js/vendor/parsley/i18n/messages.ru.js",
	"___path.framework.frontend.web___/js/vendor/jquery.livequery.js",
	"___path.framework.frontend.web___/js/vendor/fotorama/fotorama.js",
	"___path.framework.frontend.web___/js/vendor/nprogress/nprogress.js",
	"___path.framework.frontend.web___/js/vendor/colorbox/jquery.colorbox.js",

	/* Core */
	"___path.framework.frontend.web___/js/core/polyfills.js",
	"___path.framework.frontend.web___/js/core/main.js",
	"___path.framework.frontend.web___/js/core/dev.js",
	"___path.framework.frontend.web___/js/core/hook.js",
	"___path.framework.frontend.web___/js/core/i18n.js",
	"___path.framework.frontend.web___/js/core/ie.js",
	"___path.framework.frontend.web___/js/core/ajax.js",
	"___path.framework.frontend.web___/js/core/registry.js",
	"___path.framework.frontend.web___/js/core/utils.js",
	"___path.framework.frontend.web___/js/core/timer.js",

	/* User Interface */
	"___path.framework.frontend.web___/js/ui/dropdown.js",
	"___path.framework.frontend.web___/js/ui/tab.js",
	"___path.framework.frontend.web___/js/ui/modal.js",
	"___path.framework.frontend.web___/js/ui/toolbar.js",
	"___path.framework.frontend.web___/js/ui/tooltip.js",
	"___path.framework.frontend.web___/js/ui/autocomplete.js",
	"___path.framework.frontend.web___/js/ui/notification.js",
	"___path.framework.frontend.web___/js/ui/alert.js",
	"___path.framework.frontend.web___/js/ui/captcha.js",

	/* LiveStreet */
	"___path.application.web___/frontend/common/js/favourite.js",
	"___path.application.web___/frontend/common/js/favourite_topic.js",
	"___path.application.web___/frontend/common/js/blocks.js",
	"___path.application.web___/frontend/common/js/pagination.js",
	"___path.application.web___/frontend/common/js/editor.js",
	"___path.application.web___/frontend/common/js/talk.js",
	"___path.application.web___/frontend/common/js/vote.js",
	"___path.application.web___/frontend/common/js/poll.js",
	"___path.application.web___/frontend/common/js/poll-manage.js",
	"___path.application.web___/frontend/common/js/subscribe.js",
	"___path.application.web___/frontend/common/js/geo.js",
	"___path.application.web___/frontend/common/js/wall.js",
	"___path.application.web___/frontend/common/js/usernote.js",
	"___path.application.web___/frontend/common/js/comments.js",
	"___path.application.web___/frontend/common/js/blog.js",
	"___path.application.web___/frontend/common/js/user.js",
	"___path.application.web___/frontend/common/js/userfeed.js",
	"___path.application.web___/frontend/common/js/activity.js",
	"___path.application.web___/frontend/common/js/toolbar.comments.js",
	"___path.application.web___/frontend/common/js/toolbar.topics.js",
	"___path.application.web___/frontend/common/js/toolbar.scrollup.js",
	"___path.application.web___/frontend/common/js/topic.js",
	"___path.application.web___/frontend/common/js/admin.js",
	"___path.application.web___/frontend/common/js/userfield.js",
	"___path.application.web___/frontend/common/js/media.js",
	"___path.application.web___/frontend/common/js/tags.js",
	"___path.application.web___/frontend/common/js/content.js",
	"___path.application.web___/frontend/common/js/user_list_add.js",
	"___path.application.web___/frontend/common/js/blog_invite_users.js",
	"___path.application.web___/frontend/common/js/message_users.js",
	"___path.application.web___/frontend/common/js/search.js",
	"___path.application.web___/frontend/common/js/more.js",
	"___path.application.web___/frontend/common/js/init.js",

	"//yandex.st/share/share.js" => array('merge'=>false),
);

$config['head']['default']['css'] = array(
	// Framework styles
	"___path.framework.frontend.web___/css/reset.css",
	"___path.framework.frontend.web___/css/helpers.css",
	"___path.framework.frontend.web___/css/text.css",
	"___path.framework.frontend.web___/css/dropdowns.css",
	"___path.framework.frontend.web___/css/buttons.css",
	"___path.framework.frontend.web___/css/forms.css",
	"___path.framework.frontend.web___/css/navs.css",
	"___path.framework.frontend.web___/css/modals.css",
	"___path.framework.frontend.web___/css/tooltip.css",
	"___path.framework.frontend.web___/css/alerts.css",
	"___path.framework.frontend.web___/css/toolbar.css",
	"___path.framework.frontend.web___/css/typography.css",
	"___path.framework.frontend.web___/css/grid.css"
);

/**
 * Установка локали
 */
setlocale(LC_ALL, "ru_RU.UTF-8");
date_default_timezone_set('Europe/Moscow'); // See http://php.net/manual/en/timezones.php

/**
 * Настройки типографа текста Jevix
 * Добавляем к настройках из /framework/config/jevix.php
 */
$config['jevix']=array_merge_recursive((array)Config::Get('jevix'),require(dirname(__FILE__).'/jevix.php'));


return $config;