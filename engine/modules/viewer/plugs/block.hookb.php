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
 * Запускает блочные хуки из шаблона на выполнение
 *
 * @param array $aParams
 * @param string $sContent
 * @param Smarty $oSmarty
 * @param bool $bRepeat
 * @return string
 */
function smarty_block_hookb($aParams,$sContent,&$oSmarty,&$bRepeat) {
	if(empty($aParams['run'])) {
		trigger_error("Hook: missing 'run' parametr",E_USER_WARNING);
		return;
	}
	
	if ($sContent) {
		$sHookName='template_block_'.strtolower($aParams['run']);
		unset($aParams['run']);
		$aParams['content']=$sContent;
		$aResultHook=Engine::getInstance()->Hook_Run($sHookName,$aParams);
		if (array_key_exists('template_result',$aResultHook)) {
			echo join('',$aResultHook['template_result']);
			return ;
		}
		echo $sContent;
	}
}
?>