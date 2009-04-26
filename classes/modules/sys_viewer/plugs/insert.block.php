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
 * Плагин для смарти
 * Подключает обработчик блоков шаблона
 *
 * @param array $aParams
 * @param Smarty $oSmarty
 * @return string
 */
function smarty_insert_block($aParams,&$oSmarty) {	
	/**
	 * Проверяем наличие шаблона
	 */
	if (!isset($aParams['block']) or !$oSmarty->template_exists('block.'.$aParams['block'].'.tpl')) {
		$oSmarty->trigger_error("Not found template for block: ".$aParams['block']);
		return ;
	}	
	/**
	 * Устанавливаем шаблон
	 */
	$sTemplate=$aParams['block'];
	$aPath=pathinfo($sTemplate);	
	$sBlock=ucfirst($aPath['basename']);
	/**
	 * параметры
	 */
	$aParamsBlock=array();
	if (isset($aParams['params'])) {
		$aParamsBlock=$aParams['params'];
	}
	/**
	 * Подключаем необходимый обработчик
	 */
	$result=require_once(DIR_SERVER_ROOT.'/classes/blocks/Block'.$sBlock.'.class.php');	
	$sCmd='$oBlock=new Block'.$sBlock.'($aParamsBlock);';
	eval($sCmd);
	/**
	 * Запускаем обработчик
	 */
	$oBlock->Exec();	
	/**
	 * Возвращаем результат в виде обработанного шаблона блока
	 */
	return $oSmarty->fetch('block.'.$sTemplate.'.tpl');
}
?>