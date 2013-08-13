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
 * Модуль Tools - различные вспомогательные методы
 *
 * @package modules.tools
 * @since 1.0
 */
class ModuleTools extends Module {
	/**
	 * Инициализация
	 *
	 */
	public function Init() {

	}

	/**
	 * Строит логарифмическое облако - расчитывает значение size в зависимости от count
	 * У объектов в коллекции обязательно должны быть методы getCount() и setSize()
	 *
	 * @param aray $aCollection	Список тегов
	 * @param int $iMinSize	Минимальный размер
	 * @param int $iMaxSize	Максимальный размер
	 * @return array
	 */
	public function MakeCloud($aCollection,$iMinSize=1,$iMaxSize=10) {
		if (count($aCollection)) {
			$iSizeRange=$iMaxSize-$iMinSize;

			$iMin=10000;
			$iMax=0;
			foreach($aCollection as $oObject) {
				if ($iMax<$oObject->getCount()) {
					$iMax=$oObject->getCount();
				}
				if ($iMin>$oObject->getCount()) {
					$iMin=$oObject->getCount();
				}
			}
			$iMinCount=log($iMin+1);
			$iMaxCount=log($iMax+1);
			$iCountRange=$iMaxCount-$iMinCount;
			if ($iCountRange==0) {
				$iCountRange=1;
			}
			foreach($aCollection as $oObject) {
				$iTagSize=$iMinSize+(log($oObject->getCount()+1)-$iMinCount)*($iSizeRange/$iCountRange);
				$oObject->setSize(round($iTagSize));
			}
		}
		return $aCollection;
	}

	/**
	 * Преобразует спец символы в html последовательнось, поведение аналогично htmlspecialchars, кроме преобразования амперсанта "&"
	 *
	 * @param string $sText
	 *
	 * @return string
	 */
	public function Urlspecialchars($sText) {
		$aTable=get_html_translation_table();
		unset($aTable['&']);
		return strtr($sText,$aTable);
	}
	/**
	 * Обработка тега ls в тексте
	 * <pre>
	 * <ls user="admin" />
	 * </pre>
	 *
	 * @param string $sTag	Тег на ктором сработал колбэк
	 * @param array $aParams Список параметров тега
	 * @return string
	 */
	public function CallbackParserTagLs($sTag,$aParams) {
		$sText='';
		if (isset($aParams['user'])) {
			if ($oUser=$this->User_getUserByLogin($aParams['user'])) {
				$sText.="<a href=\"{$oUser->getUserWebPath()}\" class=\"ls-user\">{$oUser->getLogin()}</a> ";
			}
		}
		return $sText;
	}
}
?>