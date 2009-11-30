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
 * Плагин для смарти
 * Позволяет получать данные о роутах
 *
 * @param   array $aParams
 * @param   Smarty $oSmarty
 * @return  string
 */
function smarty_function_router($aParams,&$oSmarty) {	
	if(empty($aParams['page'])) {
		$oSmarty->trigger_error("Router: missing 'page' parametr");
		return ;
	}
	require_once(Config::Get('path.root.engine').'/classes/Router.class.php');
	
	if(!$sPath = Router::GetPath($aParams['page'])) {
		$oSmarty->trigger_error("Router: unknown 'page' given");
		return ;
	}
	/**
	 * Возвращаем полный адрес к указаному Action
	 */
	return (isset($aParams['extend'])) 
		? $sPath . $aParams['extend'] ."/"
		: $sPath;
}
?>