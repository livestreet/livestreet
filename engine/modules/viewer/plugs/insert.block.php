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
		$sBlockTemplate = Plugin::GetTemplatePath($aParams['params']['plugin']).'/blocks/block.'.$aParams['block'].'.tpl';
		$sBlock ='Plugin'.ucfirst($aParams['params']['plugin']).'_Block'.$sBlock;
	} else {
		$sBlockTemplate = Engine::getInstance()->Plugin_GetDelegate('template','blocks/block.'.$aParams['block'].'.tpl');
		$sBlock ='Block'.$sBlock;
	}

	$sBlock=Engine::getInstance()->Plugin_GetDelegate('block',$sBlock);

	if (!isset($aParams['block']) or !$oSmarty->templateExists($sBlockTemplate)) {
		trigger_error("Not found template for block: ".$sBlockTemplate,E_USER_WARNING);
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
	$oBlock = new $sBlock($aParamsBlock);
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