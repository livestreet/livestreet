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

/**
 * Настройки роутинга страниц
 * Определяет какой экшен должен запускаться при определенном УРЛе
 */

define("ROUTE_PAGE_ERROR",'error');
define("ROUTE_PAGE_REGISTRATION",'registration');
define("ROUTE_PAGE_PROFILE",'profile');
define("ROUTE_PAGE_MY",'my');
define("ROUTE_PAGE_BLOG",'blog');
define("ROUTE_PAGE_PERSONAL_BLOG",'log');
define("ROUTE_PAGE_TOP",'top');
define("ROUTE_PAGE_INDEX",'index');
define("ROUTE_PAGE_NEW",'new');
define("ROUTE_PAGE_TOPIC",'topic');
define("ROUTE_PAGE_LOGIN",'login');
define("ROUTE_PAGE_PEOPLE",'people');
define("ROUTE_PAGE_SETTINGS",'settings');
define("ROUTE_PAGE_TAG",'tag');
define("ROUTE_PAGE_COMMENTS",'comments');
define("ROUTE_PAGE_TALK",'talk');
define("ROUTE_PAGE_RSS",'rss');
define("ROUTE_PAGE_LINK",'link');
define("ROUTE_PAGE_QUESTION",'question');
define("ROUTE_PAGE_BLOGS",'blogs');
define("ROUTE_PAGE_SEARCH",'search');

return array(
	'page' => array(		
		ROUTE_PAGE_ERROR => 'ActionError',
		ROUTE_PAGE_REGISTRATION => 'ActionRegistration',
		ROUTE_PAGE_PROFILE => 'ActionProfile',
		ROUTE_PAGE_MY => 'ActionMy',
		ROUTE_PAGE_BLOG => 'ActionBlog',
		ROUTE_PAGE_PERSONAL_BLOG => 'ActionPersonalBlog',
		ROUTE_PAGE_TOP => 'ActionTop',
		ROUTE_PAGE_INDEX => 'ActionIndex',
		ROUTE_PAGE_NEW => 'ActionNew',
		ROUTE_PAGE_TOPIC => 'ActionTopic',		
		ROUTE_PAGE_LOGIN => 'ActionLogin',
		ROUTE_PAGE_PEOPLE => 'ActionPeople',
		ROUTE_PAGE_SETTINGS => 'ActionSettings',
		ROUTE_PAGE_TAG => 'ActionTag',
		ROUTE_PAGE_COMMENTS => 'ActionComments',
		ROUTE_PAGE_TALK => 'ActionTalk',
		ROUTE_PAGE_RSS => 'ActionRss',
		ROUTE_PAGE_LINK => 'ActionLink',
		ROUTE_PAGE_QUESTION => 'ActionQuestion',
		ROUTE_PAGE_BLOGS => 'ActionBlogs',
		ROUTE_PAGE_SEARCH => 'ActionSearch',
		'tools' => 'ActionTools',
	),
	'config' => array(
		'action_default' => ROUTE_PAGE_INDEX,
		'action_not_found' => ROUTE_PAGE_ERROR,
	),	
);
?>