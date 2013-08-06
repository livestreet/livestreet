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
 * Добавляет блок(в сайдбар, тулбар и т.п.)
 *
 * @param unknown_type $params
 * @param unknown_type $smarty
 * @return unknown
 */
function smarty_function_add_block($params, &$smarty)
{
	if (!array_key_exists('group', $params)) {
		trigger_error("add_block: missing 'group' parameter",E_USER_WARNING);
        return;
    }

	if (!array_key_exists('name', $params)) {
		trigger_error("add_block: missing 'name' parameter",E_USER_WARNING);
		return;
	}

	$aBlockParams=(isset($params['params']) && is_array($params['params'])) ? $params['params'] : array();
	$iPriority=isset($params['priority']) ? $params['priority'] : 5;

	foreach($params as $k=>$v) {
		if (!in_array($k,array('group','name','params','priority'))) {
			$aBlockParams[$k]=$v;
		}
	}

	Engine::getInstance()->Viewer_AddBlock($params['group'],$params['name'],$aBlockParams,$iPriority);
	return '';
}

?>