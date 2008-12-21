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
return array(
	'page' => array(		
		'error' => 'ActionError',
		'registration' => 'ActionRegistration',
		'profile' => 'ActionProfile',
		'my' => 'ActionMy',
		'blog' => 'ActionBlog',
		'log' => 'ActionPersonalBlog',
		'top' => 'ActionTop',
		'index' => 'ActionIndex',
		'new' => 'ActionNew',
		'topic' => 'ActionTopic',
		'page' => 'ActionPage',
		'login' => 'ActionLogin',
		'people' => 'ActionPeople',
		'settings' => 'ActionSettings',
		'tag' => 'ActionTag',
		'comments' => 'ActionComments',
		'talk' => 'ActionTalk',
		'rss' => 'ActionRss',
		'link' => 'ActionLink',
		'question' => 'ActionQuestion',
		'blogs' => 'ActionBlogs',
		'search' => 'ActionSearch',
	),
	'config' => array(
		'action_default' => 'index',
		'action_not_found' => 'error',
	),	
);
?>