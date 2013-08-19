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
 * Абстрактный класс сущности.
 * При запросе к базе данных удобно возвращать не просто массив данных, а данные в виде специального объекта - Entity.
 * Основные методы такого объекта делятся на два вида: get-методы и set-методы.
 * Первые получают свойство объекта по его имени, а вторые устанавливают.
 * Сущности поддерживает "магические" методы set* и get* , например
 * <pre>
 * $oEntity->getMyProperty()
 * </pre> вернет данные по ключу/полю my_property
 *
 * @package engine
 * @since 1.0
 */
abstract class Entity extends LsObject {
	/**
	 * Данные сущности, на этот массив мапятся методы set* и get*
	 *
	 * @var array
	 */
	protected $_aData=array();
	/**
	 * Имя поля с первичным ключом в БД
	 *
	 * @var null|string
	 */
	protected $sPrimaryKey = null;
	/**
	 * Список правил валидации полей
	 * @see ModuleValidate
	 *
	 * @var array
	 */
	protected $aValidateRules=array();
	/**
	 * Список ошибок валидации в разрезе полей, например
	 * <pre>
	 * array(
	 * 	'title' => array('error one','error two'),
	 * 	'name' => array('error one','error two'),
	 * )
	 * </pre>
	 *
	 * @var array
	 */
	protected $aValidateErrors=array();
	/**
	 * Сценарий валиадции полей
	 * @see _setValidateScenario
	 *
	 * @var string
	 */
	protected $sValidateScenario='';


	/**
	 * Если передать в конструктор ассоциативный массив свойств и их значений, то они автоматом загрузятся в сущность
	 *
	 * @param array|false $aParam	Ассоциативный массив данных сущности
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
	/**
	 * Устанавливает данные сущности
	 *
	 * @param array $aData	Ассоциативный массив данных сущности
	 */
	public function _setData($aData) {
		if(is_array($aData)) {
			foreach ($aData as $sKey => $val)    {
				$this->_aData[$sKey] = $val;
			}
		}
	}
	/**
	 * Устанавливает данные, но только те, которые есть в $this->aValidateRules
	 *
	 * @param array $aData Ассоциативный массив данных сущности
	 */
	public function _setDataSafe($aData) {
		/**
		 * Составляем список доступных полей
		 */
		if(is_array($aData)) {
			$aFields=array();
			foreach($this->aValidateRules as $aRule) {
				$aFields=array_merge($aFields,preg_split('/[\s,]+/',$aRule[0],-1,PREG_SPLIT_NO_EMPTY));
			}
			$aFields=array_unique($aFields);
			foreach ($aData as $sKey => $val)    {
				if (in_array($sKey,$aFields)) {
					$this->_aData[$sKey] = $val;
				}
			}
		}
	}
	/**
	 * Получает массив данных сущности
	 *
	 * @param array|null $aKeys	Список полей, данные по которым необходимо вернуть, если не передан, то возвращаются все данные
	 * @return array
	 */
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
	/**
	 * Возвращает данные по конкретному полю
	 *
	 * @param string $sKey	Название поля, например <pre>'my_property'</pre>
	 * @return null|mixed
	 */
	public function _getDataOne($sKey) {
		if(array_key_exists($sKey,$this->_aData)) {
			return $this->_aData[$sKey];
		}
		return null;
	}
	/**
	 * Рекурсивное преобразование объекта и вложенных объектов в массив
	 *
	 * @return array
	 */
	public function _getDataArray() {
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
	 * Также производит обработку методов set* и get*
	 * @see Engine::_CallModule
	 *
	 * @param string $sName Имя метода
	 * @param array $aArgs Аргументы
	 * @return mixed
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
	 * @see _getPrimaryKeyValue
	 *
	 * @return null|string
	 */
	public function _getPrimaryKey() {
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
	/**
	 * Возвращает значение первичного ключа/поля
	 *
	 * @return mixed|null
	 */
	public function _getPrimaryKeyValue() {
		return $this->_getDataOne($this->_getPrimaryKey());
	}
	/**
	 * Возвращает список правил для валидации
	 *
	 * @return array
	 */
	public function _getValidateRules() {
		return $this->aValidateRules;
	}
	/**
	 * Выполняет валидацию данных сущности
	 * Если $aFields=null, то выполняется валидация по всем полям из $this->aValidateRules, иначе по пересечению
	 *
	 * @param null|array $aFields	Список полей для валидации, если null то по всем полям
	 * @param bool $bClearErrors	Очищать или нет стек ошибок перед валидацией
	 *
	 * @return bool
	 */
	public function _Validate($aFields=null, $bClearErrors=true) {
		if($bClearErrors) {
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
	 * @see ModuleValidate::CreateValidator
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
	 * @param string $sError	Сообщение об ошибке
	 */
	public function _addValidateError($sField,$sError) {
		$this->aValidateErrors[$sField][]=$sError;
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
	 * Если использовать валидацию без сценария, то будут использоваться только те правила, где нет никаких сценариев, либо указан пустой сценарий ''
	 * Если указать сценарий, то проверка будет только по правилом, где в списке сценарией есть указанный
	 *
	 * @param string $sValue
	 */
	public function _setValidateScenario($sValue) {
		$this->sValidateScenario=$sValue;
	}
}
?>