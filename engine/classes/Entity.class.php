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
abstract class Entity extends LsObject {
	protected $_aData=array();
	protected $sPrimaryKey = null;
	protected $aValidateRules=array();
	protected $aValidateErrors=array();
	protected $sValidateScenario='';


	/**
	 * Если передать в конструктор ассоциативный массив свойств и их значений, то они автоматом загрузятся в сущность
	 *
	 * @param array|null $aParam
	 */
	public function __construct($aParam = false) {
		$this->_setData($aParam);
		$this->Init();
	}

	/**
	 * Метод инициализации сущности, вызывается при её создании
	 */
	public function Init() {

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
	 * Рекурсивное преобразование объекта и вложенных объектов в массив
	 */
	public function _getDataArray()
	{
		$aResult = array();
		foreach ($this->_aData as $sKey => $sValue) {
			if (is_object($sValue) && $sValue instanceOf Entity) {
				$aResult[$sKey] = $sValue->_getDataArray();
			} else {
				$aResult[$sKey] = $sValue;
			}
		}
		return $aResult;
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

	/**
	 * Получение первичного ключа сущности (ключ, а не значение!)
	 */
	public function _getPrimaryKey()
	{
		if (!$this->sPrimaryKey) {
			if (isset($this->_aData['id'])) {
				$this->sPrimaryKey = 'id';
			} else {
				// Получение primary_key из схемы бд (пока отсутствует)
				$this->sPrimaryKey = 'id';
			}
		}

		return $this->sPrimaryKey;
	}

	public function _getPrimaryKeyValue() {
		return $this->_getDataOne($this->_getPrimaryKey());
	}


	/**
	 * Выполняет валидацию данных сущности
	 * Если $aFields=null, то выполняется валидация по всем полям из $this->aValidateRules, иначе по пересечению
	 *
	 * @param null $attributes
	 * @param bool $clearErrors
	 *
	 * @return bool
	 */
	public function _Validate($aFields=null, $clearErrors=true) {
		if($clearErrors) {
			$this->_clearValidateErrors();
		}
		foreach($this->_getValidators() as $validator) {
			$validator->validateEntity($this,$aFields);
		}

		return !$this->_hasValidateErrors();
	}

	/**
	 * Возвращает список валидаторов с учетом текущего сценария
	 *
	 * @param null|string $sField	Поле сущности для которого необходимо вернуть валидаторы, если нет, то возвращается для всех полей
	 *
	 * @return array
	 */
	public function _getValidators($sField=null) {
		$aValidators=$this->_createValidators();

		$aValidatorsReturn=array();
		$sScenario=$this->_getValidateScenario();
		foreach($aValidators as $oValidator) {
			/**
			 * Проверка на текущий сценарий
			 */
			if($oValidator->applyTo($sScenario)) {
				if($sField===null || in_array($sField,$oValidator->fields,true)) {
					$aValidatorsReturn[]=$oValidator;
				}
			}
		}
		return $aValidatorsReturn;
	}

	/**
	 * Создает и возвращает список валидаторов для сущности
	 *
	 * @return array
	 * @throws Exception
	 */
	public function _createValidators() {
		$aValidators=array();
		foreach($this->aValidateRules as $aRule) {
			if(isset($aRule[0],$aRule[1])) {
				$aValidators[]=$this->Validate_CreateValidator($aRule[1],$this,$aRule[0],array_slice($aRule,2));
			} else {
				throw new Exception(get_class($this).' has an invalid validation rule');
			}
		}
		return $aValidators;
	}

	/**
	 * Проверяет есть ли ошибки валидации
	 *
	 * @param null|string $sField	Поле сущности, если нет, то проверяется для всех полей
	 *
	 * @return bool
	 */
	public function _hasValidateErrors($sField=null) {
		if($sField===null) {
			return $this->aValidateErrors!==array();
		} else {
			return isset($this->aValidateErrors[$sField]);
		}
	}

	/**
	 * Возвращает список ошибок для всех полей или одного поля
	 *
	 * @param null|string $sField	Поле сущности, если нет, то возвращается для всех полей
	 *
	 * @return array
	 */
	public function _getValidateErrors($sField=null) {
		if($sField===null) {
			return $this->aValidateErrors;
		} else {
			return isset($this->aValidateErrors[$sField]) ? $this->aValidateErrors[$sField] : array();
		}
	}

	/**
	 * Возвращает первую ошибку для поля или среди всех полей
	 *
	 * @param null|string $sField	Поле сущности
	 *
	 * @return string|null
	 */
	public function _getValidateError($sField=null) {
		if ($sField===null) {
			foreach($this->_getValidateErrors() as $sFieldKey=>$aErros) {
				return reset($aErros);
			}
		} else {
			return isset($this->aValidateErrors[$sField]) ? reset($this->aValidateErrors[$sField]) : null;
		}
	}

	/**
	 * Добавляет для поля ошибку в список ошибок
	 *
	 * @param string $sField	Поле сущности
	 * @param string $error		Сообщение об ошибке
	 */
	public function _addValidateError($sField,$error) {
		$this->aValidateErrors[$sField][]=$error;
	}

	/**
	 * Очищает список всех ошибок или для конкретного поля
	 *
	 * @param null|string $sField	Поле сущности
	 */
	public function _clearValidateErrors($sField=null) {
		if($sField===null) {
			$this->aValidateErrors=array();
		} else {
			unset($this->aValidateErrors[$sField]);
		}
	}

	/**
	 * Возвращает текущий сценарий валидации
	 *
	 * @return string
	 */
	public function _getValidateScenario() {
		return $this->sValidateScenario;
	}

	/**
	 * Устанавливает сценарий валидации
	 *
	 * @param string $sValue
	 */
	public function _setValidateScenario($sValue) {
		$this->sValidateScenario=$sValue;
	}
}
?>