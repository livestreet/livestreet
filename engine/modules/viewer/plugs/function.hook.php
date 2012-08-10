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
		trigger_error("Hook: missing 'run' parametr",E_USER_WARNING);
		return;
	}
	
	$sHookName='template_'.strtolower($aParams['run']);
	unset($aParams['run']);
	$aResultHook=Engine::getInstance()->Hook_Run($sHookName,$aParams);

	$sReturn='';
	if (array_key_exists('template_result',$aResultHook)) {
		$sReturn=join('',$aResultHook['template_result']);
	}

	if (!empty($aParams['assign'])) {
		$oSmarty->assign($aParams['assign'], $sReturn);
	} else {
		return $sReturn;
	}
}
?>