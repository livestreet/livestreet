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
 * Абстрактный класс сущности
 *
 */
abstract class Entity extends Object {	
	protected $_aData=array();

	/**
	 * Если передать в конструктор ассоциативный массив свойств и их значений, то они автоматом загрузятся в сущность
	 *
	 * @param unknown_type $aParam
	 */
	public function __construct($aParam = false) {		
		if(is_array($aParam)) {
			foreach ($aParam as $sKey => $val)	{
				$this->_aData[$sKey] = $val;
			}
		}
	}
	public function _getData() {
		return $this->_aData;
	}
	/**
	 * При попытке вызвать неопределенный метод сущности возвращаем null
	 * В принципе можно это закомментить чтоб отлавливать ошибки при обращении к несуществующим методам :)
	 *
	 * @param string $sName
	 * @param array $aArgs
	 * @return unknown
	 */
	/*
	public function __call($sName,$aArgs) {
		return null;
	}
	*/
	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 *
	 * @param string $sName
	 * @param array $aArgs
	 * @return unknown
	 */
	public function __call($sName,$aArgs) {
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
}
?>