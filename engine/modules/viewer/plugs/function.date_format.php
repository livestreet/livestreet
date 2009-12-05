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
 * Плагин для смарти.
 * Позволяет получать дату с возможностью склонения 
 * формы слова и поддержкой мультиязычноти.
 *
 * @param   array $aParams
 * @param   Smarty $oSmarty
 * @return  string
 */
function smarty_function_date_format($aParams,&$oSmarty) {
	$sFormatDefault = "d F Y, H:i";  //  формат даты по умолчанию
	$iDeclinationDefault  = 1;       //  индекс склонения по умолчанию
	
	/**
	 * Определяем дату
	 */
	$sDate = (empty($aParams['date'])) ? time() : $aParams['date'];
	$iDeclination = (!isset($aParams['declination'])) ? $iDeclinationDefault : $aParams['declination'];
	$sFormat = (empty($aParams['format'])) ? $sFormatDefault : $aParams['format'];
	
	require_once(Config::Get('path.root.engine').'/classes/Engine.class.php');
	$oEngine = Engine::getInstance();

	/**
	 * Если указан другой язык, подгружаем его
	 */
	if(isset($aParams['lang']) and $aParams['lang']!=$oEngine->Lang_GetLang()) {
		$oEngine->Lang_SetLang($aParams['lang']);
	}
	
	$aMonth = $oEngine->Lang_Get('month_array');
	$iDate= (preg_match("/^\d+$/",$sDate)) ?  $sDate : strtotime($sDate);
	
	/**
	 * Определяем нужное текстовое значение названия месяца
	 */
	$iMonth = date("m",$iDate);
	$sMonth = isset($aMonth['date_month_'.$iMonth]) 
		? $aMonth['date_month_'.$iMonth] 
		: "";

	/**
	 * Если не найден индекс склонения, берем склонене по умолчанию.
	 * Если индекс по умолчанию также не определен, берем первое значение в массиве.
	 */
	if(is_array($sMonth)) {
		$sMonth = isset($sMonth[$iDeclination]) 
			? $sMonth[$iDeclination] 
			: (isset($sMonth[$iDeclinationDefault])) 
				? $sMonth[$iDeclinationDefault]
				: array_shift($sMonth);
	}
		
	$sFormat=str_replace("F",preg_replace('~(\pL{1})~u','\\\${1}',$sMonth),$sFormat);

	return date($sFormat,$iDate);
}
?>