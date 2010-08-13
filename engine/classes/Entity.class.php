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
	 * @param array|null $aParam
	 */
	public function __construct($aParam = false) {
		$this->_setData($aParam);
	}

	public function _setData($aData) {
		if(is_array($aData)) {
			foreach ($aData as $sKey => $val)    {
				$this->_aData[$sKey] = $val;
			}
		}
	}

	public function _getData($aKeys=array()) {
		if(!is_array($aKeys) or !count($aKeys)) return $this->_aData;
		
		$aReturn=array();
		foreach ($aKeys as $key) {
			if(array_key_exists($key,$this->_aData)) {
				$aReturn[$key] = $this->_aData[$key];
			}
		}
		return $aReturn;
	}

	public function _getDataOne($sKey) {
		if(array_key_exists($sKey,$this->_aData)) {
			return $this->_aData[$sKey];
		}
		return null;
	}
	
	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 *
	 * @param string $sName
	 * @param array $aArgs
	 * @return unknown
	 */
	public function __call($sName,$aArgs) {
		$sType=strtolower(substr($sName,0,3));
		if (!strpos($sName,'_') and in_array($sType,array('get','set'))) {
			$sKey=func_underscore(substr($sName,3));
			if ($sType=='get') {
				if (isset($this->_aData[$sKey])) {
					return $this->_aData[$sKey];
				} else {
					if (preg_match('/Entity([^_]+)/',get_class($this),$sModulePrefix)) {
						$sModulePrefix=func_underscore($sModulePrefix[1]).'_';
						if (isset($this->_aData[$sModulePrefix.$sKey])) {
							return $this->_aData[$sModulePrefix.$sKey];
						}
					}
				}
				return null;
			} elseif ($sType=='set' and array_key_exists(0,$aArgs)) {
				$this->_aData[$sKey]=$aArgs[0];
			}
		} else {
			return Engine::getInstance()->_CallModule($sName,$aArgs);
		}
	}
}
?>