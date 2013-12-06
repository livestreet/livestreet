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

class ModuleProperty_EntityValue extends EntityORM {

	protected $aRelations=array(

	);

	protected function beforeSave() {
		if ($bResult=parent::beforeSave()) {
			$oValueType=$this->getValueTypeObject();
			$oValueType->beforeSaveValue();
		}
		return $bResult;
	}

	public function getValueForDisplay() {
		$oValueType=$this->getValueTypeObject();
		return $oValueType->getValueForDisplay();
	}

	public function getValueForForm() {
		$oValueType=$this->getValueTypeObject();
		return $oValueType->getValueForForm();
	}

	public function getValueTypeObject() {
		if (!$this->_getDataOne('value_type_object')) {
			$oObject=Engine::GetEntity('ModuleProperty_EntityValueType'.func_camelize($this->getPropertyType()));
			$oObject->setValueObject($this);
			$this->setValueTypeObject($oObject);
		}
		return $this->_getDataOne('value_type_object');
	}

	public function getData() {
		$aData=@unserialize($this->_getDataOne('data'));
		if (!$aData) {
			$aData=array();
		}
		return $aData;
	}

	public function setData($aRules) {
		$this->_aData['data']=@serialize($aRules);
	}

	public function getDataOne($sKey) {
		$aData=$this->getData();
		if (isset($aData[$sKey])) {
			return $aData[$sKey];
		}
		return null;
	}

	public function setDataOne($sKey,$mValue) {
		$aData=$this->getData();
		$aData[$sKey]=$mValue;
		$this->setData($aData);
	}
}