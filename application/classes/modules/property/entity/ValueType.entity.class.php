<?php
/**
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

class ModuleProperty_EntityValueType extends Entity {

	public function getValueForDisplay() {
		return $this->getValueObject()->getValue();
	}

	public function getValueForForm() {
		return htmlspecialchars($this->getValueObject()->getValue());
	}

	public function validate() {
		return 'Неверное значение';
	}

	protected function validateStandart($sTypeValidator,$aParamsAdditional=array()) {
		$oProperty=$this->getValueObject()->getProperty();
		/**
		 * Получаем параметры валидации
		 */
		$aParams=$oProperty->getValidateRules();
		if (!isset($aParams['label'])) {
			$aParams['label']=$oProperty->getTitle();
		}
		$aParams=array_merge($aParams,$aParamsAdditional);

		$oValidator=$this->Validate_CreateValidator($sTypeValidator,$this,null,$aParams);
		$oValidator->fields=array('value_for_validate');
		$oValidator->validateEntity($this);
		if ($this->_hasValidateErrors()) {
			return $this->_getValidateError();
		} else {
			return true;
		}
	}

	public function setValue($mValue) {
		$this->resetAllValue();
	}

	public function resetAllValue() {
		$oValue=$this->getValueObject();
		$oValue->setValueInt(null);
		$oValue->setValueFloat(null);
		$oValue->setValueVarchar(null);
		$oValue->setValueText(null);
		$oValue->setData(null);
		/**
		 * Удаляем из таблицы тегов
		 */
		$this->Property_RemoveValueTagsByTarget($oValue->getTargetType(),$oValue->getTargetId(),$oValue->getPropertyId());
		/**
		 * Удаляем из таблицы селектов
		 */
		$this->Property_RemoveValueSelectsByTarget($oValue->getTargetType(),$oValue->getTargetId(),$oValue->getPropertyId());
	}

	public function prepareValidateRulesRaw($aRulesRaw) {
		return array();
	}

	public function prepareParamsRaw($aParamsRaw) {
		return array();
	}

	public function beforeSaveValue() {

	}

	public function removeValue() {

	}
}