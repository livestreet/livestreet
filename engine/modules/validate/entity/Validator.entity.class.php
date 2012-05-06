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
 * Базовый класс валидатора
 * От этого класса наследуются все валидаторы
 * Public свойства используются в качестве параметров валидатора, котрый можно задавать в правилах
 * @see Entity::aValidateRules
 *
 * @package engine.modules.validate
 * @since 1.0
 */
abstract class ModuleValidate_EntityValidator extends Entity {
	/**
	 * Пропускать или нет ошибку
	 *
	 * @var bool
	 */
	public $bSkipOnError=false;
	/**
	 * Список полей сущности для валидации
	 *
	 * @var array
	 */
	public $fields=array();
	/**
	 * Название поля сущности для отображения в тексте ошибки
	 *
	 * @var null|string
	 */
	public $label=null;
	/**
	 * Текст ошибки валидации, переопределяет текст валидатора
	 *
	 * @var null|string
	 */
	public $msg=null;
	/**
	 * Список сценариев в которых участвует валидатор
	 *
	 * @var null|array
	 */
	public $on=null;
	/**
	 * Объект текущей сущности, которая проходит валидацию
	 *
	 * @var null|Entity
	 */
	protected $oEntityCurrent=null;
	/**
	 * Объект текущей сущности, которая проходит валидацию
	 *
	 * @var null|string
	 */
	protected $sFieldCurrent=null;

	/**
	 * Основной метод валидации
	 *
	 * @abstract
	 * @param $sValue
	 */
	abstract public function validate($sValue);
	/**
	 * Проверяет данные на пустое значение
	 *
	 * @param mixed $mValue Данные
	 * @param bool $bTrim Не учитывать пробелы
	 *
	 * @return bool
	 */
	protected function isEmpty($mValue,$bTrim=false) {
		return $mValue===null || $mValue===array() || $mValue==='' || $bTrim && is_scalar($mValue) && trim($mValue)==='';
	}
	/**
	 * Применять или нет сценарий к текущему валидатору
	 * Для сценария учитываются только те правила, где явно прописан необходимый сценарий
	 * Если в правиле не прописан сценарий, то он принимает значение '' (пустая строка)
	 *
	 * @param string $sScenario Сценарий валидации
	 *
	 * @return bool
	 */
	public function applyTo($sScenario) {
		return (empty($this->on) && !$sScenario) || isset($this->on[$sScenario]);
	}
	/**
	 * Возвращает сообщение, используется для получения сообщения об ошибке валидатора
	 *
	 * @param string $sMsgDefault	Дефолтное сообщение
	 * @param null|string $sMsgFieldCustom	Поле/параметр в котором может храниться кастомное сообщение. В поле $sMsgFieldCustom."Id" можно хранить ключ текстовки из языкового файла
	 * @param array $aReplace	Список параметров для замены в сообщении (плейсхолдеры)
	 *
	 * @return string
	 */
	protected function getMessage($sMsgDefault,$sMsgFieldCustom=null,$aReplace=array()) {
		if (!is_null($sMsgFieldCustom)) {
			if (!is_null($this->$sMsgFieldCustom)) {
				$sMsgDefault=$this->$sMsgFieldCustom;
			} else {
				$sMsgFieldCustomId=$sMsgFieldCustom.'Id';
				if (property_exists($this,$sMsgFieldCustomId) and !is_null($this->$sMsgFieldCustomId)) {
					$sMsgDefault=$this->Lang_Get($this->$sMsgFieldCustomId,array(),false);
				}
			}
		}
		if ($aReplace) {
			foreach ($aReplace as $sFrom => $sTo) {
				$aReplacePairs["%%{$sFrom}%%"]=$sTo;
			}
			$sMsgDefault=strtr($sMsgDefault,$aReplacePairs);
		}
		return $sMsgDefault;
	}
	/**
	 * Запускает валидацию полей сущности
	 *
	 * @param Entity $oEntity	Объект сущности
	 * @param null $aFields	Список полей для валидации, если пуст то валидируются все поля указанные в правиле
	 */
	public function validateEntity($oEntity,$aFields=null) {
		if(is_array($aFields)) {
			$aFields=array_intersect($this->fields,$aFields);
		} else {
			$aFields=$this->fields;
		}
		$this->oEntityCurrent=$oEntity;
		/**
		 * Запускаем валидацию для каждого поля
		 */
		foreach($aFields as $sField) {
			if(!$this->bSkipOnError || !$oEntity->_hasValidateErrors($sField)) {
				$this->validateEntityField($oEntity,$sField);
			}
		}
	}
	/**
	 * Запускает валидацию конкретного поля сущности
	 *
	 * @param Entity $oEntity	Объект сущности
	 * @param string $sField	Поле сущности
	 *
	 * @return bool
	 */
	public function validateEntityField($oEntity,$sField) {
		$this->sFieldCurrent=$sField;
		/**
		 * Получаем значение поля у сущности через геттер
		 */
		$sValue=call_user_func_array(array($oEntity,'get'.func_camelize($sField)),array());

		if (($sMsg=$this->validate($sValue))!==true) {
			/**
			 * Подставляем имя поля в сообщение об ошибке валидации
			 */
			$sMsg=str_replace('%%field%%',is_null($this->label) ? $sField : $this->label,$sMsg);
			$oEntity->_addValidateError($sField,$sMsg);
			return false;
		} else {
			return true;
		}
	}
	/**
	 * Возвращает значение поля текущей сущности
	 *
	 * @param string $sField
	 * @return mixed|null
	 */
	protected function getValueOfCurrentEntity($sField) {
		if ($this->oEntityCurrent) {
			return call_user_func_array(array($this->oEntityCurrent,'get'.func_camelize($sField)),array());
		}
		return null;
	}
	/**
	 * Устанавливает значение поля текущей сущности
	 *
	 * @param string $sField
	 * @param string|mixed $sValue
	 */
	protected function setValueOfCurrentEntity($sField,$sValue) {
		if ($this->oEntityCurrent) {
			call_user_func_array(array($this->oEntityCurrent,'set'.func_camelize($sField)),array($sValue));
		}
	}
}
?>