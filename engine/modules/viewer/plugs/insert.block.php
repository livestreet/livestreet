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
 * Подключает обработчик блоков шаблона
 *
 * @param array $aParams
 * @param Smarty $oSmarty
 * @return string
 */
function smarty_insert_block($aParams,&$oSmarty) {	
	/**
	 * Устанавливаем шаблон
	 */
	$sBlock=ucfirst(basename($aParams['block']));	
	/**
	 * Проверяем наличие шаблона. Определяем значения параметров работы в зависимости от того, 
	 * принадлежит ли блок одному из плагинов, или является пользовательским классом движка
	 */
	if(isset($aParams['params']) and isset($aParams['params']['plugin'])) {
		require_once(Config::Get('path.root.server').'/engine/classes/ActionPlugin.class.php');
		
		$sBlockTemplate = Plugin::GetTemplatePath($aParams['params']['plugin']).'/block.'.$aParams['block'].'.tpl';	
		$sBlockClass = Config::Get('path.root.server').'/plugins/'.$aParams['params']['plugin'].'/classes/blocks/Block'.$sBlock.'.class.php';
		$sCmd='$oBlock=new Plugin'.ucfirst($aParams['params']['plugin']).'_Block'.$sBlock.'($aParamsBlock);';
	} else {		
		$sBlockTemplate = Engine::getInstance()->Plugin_GetDelegate('template','block.'.$aParams['block'].'.tpl');
		$sBlockClass = Config::Get('path.root.server').'/classes/blocks/Block'.$sBlock.'.class.php';
		$sCmd='$oBlock=new Block'.$sBlock.'($aParamsBlock);';
	}
	
	if (!isset($aParams['block']) or !$oSmarty->template_exists($sBlockTemplate)) {
		$oSmarty->trigger_error("Not found template for block: ".$sBlockTemplate);
		return ;
	}
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
	require_once($sBlockClass);	
	eval($sCmd);
	/**
	 * Запускаем обработчик
	 */
	$oBlock->Exec();
	/**
	 * Возвращаем результат в виде обработанного шаблона блока
	 */
	return $oSmarty->fetch($sBlockTemplate);
}
?>