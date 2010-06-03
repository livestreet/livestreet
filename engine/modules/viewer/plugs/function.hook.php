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
 * Запускает хуки из шаблона на выполнение
 *
 * @param   array $aParams
 * @param   Smarty $oSmarty
 * @return  string
 */
function smarty_function_hook($aParams,&$oSmarty) {	
	if(empty($aParams['run'])) {
		$oSmarty->trigger_error("Hook: missing 'run' parametr");
		return;
	}
	
	$sHookName='template_'.strtolower($aParams['run']);
	unset($aParams['run']);
	$aResultHook=Engine::getInstance()->Hook_Run($sHookName,$aParams);
	if (array_key_exists('template_result',$aResultHook)) {
		return join('',$aResultHook['template_result']);
	}	
	return '';
}
?>