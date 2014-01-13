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

class ModuleProperty_EntityProperty extends EntityORM {

	protected $aValidateRules=array(
		array('type','check_type','on'=>array('create')),
		array('code','regexp','allowEmpty'=>false,'pattern'=>'#^[a-z0-9\_]+$#i','on'=>array('create','update')),
		array('title','string','allowEmpty'=>false,'min'=>1,'max'=>250,'on'=>array('create','update')),
		array('description','string','allowEmpty'=>true,'max'=>500,'on'=>array('update')),
		array('sort','number','allowEmpty'=>false,'integerOnly'=>true,'min'=>0,'on'=>array('update')),
		array('validate_rules_raw','check_validate_rules_raw','on'=>array('create','update')),
		array('params_raw','check_params_raw','on'=>array('update')),
		array('title','check_title','on'=>array('create','update')),
		array('description','check_description','on'=>array('update')),
	);

	protected $aRelations=array(
		'selects' => array(self::RELATION_TYPE_HAS_MANY,'ModuleProperty_EntitySelect','property_id',array('#order'=>array('sort'=>'desc'))),
	);

	public function ValidateCheckType() {
		if ($this->Property_IsAllowPropertyType($this->getType())) {
			return true;
		}
		return 'Неверный тип поля';
	}

	public function ValidateCheckTitle() {
		$this->setTitle(htmlspecialchars($this->getTitle()));
		return true;
	}

	public function ValidateCheckDescription() {
		$this->setDescription(htmlspecialchars($this->getDescription()));
		return true;
	}

	public function ValidateCheckValidateRulesRaw() {
		$aRulesRaw=$this->getValidateRulesRaw();
		/**
		 * Валидация зависит от типа
		 */
		$oValue=Engine::GetEntity('ModuleProperty_EntityValue',array('property_type'=>$this->getType(),'property_id'=>$this->getId(),'target_type'=>$this->getTargetType(),'target_id'=>$this->getId()));
		$oValueType=$oValue->getValueTypeObject();
		$aRules=$oValueType->prepareValidateRulesRaw($aRulesRaw);
		$this->setValidateRules($aRules);
		return true;
	}

	public function ValidateCheckParamsRaw() {
		$aParamsRaw=$this->getParamsRaw();
		/**
		 * Валидация зависит от типа
		 */
		$oValue=Engine::GetEntity('ModuleProperty_EntityValue',array('property_type'=>$this->getType(),'property_id'=>$this->getId(),'target_type'=>$this->getTargetType(),'target_id'=>$this->getId()));
		$oValueType=$oValue->getValueTypeObject();
		$aParams=$oValueType->prepareParamsRaw($aParamsRaw);
		$this->setParams($aParams);
		return true;
	}

	protected function beforeSave() {
		if ($this->_isNew()) {
			$this->setDateCreate(date("Y-m-d H:i:s"));
		}
		return true;
	}
	/**
	 * Возвращает правила валидации поля
	 *
	 * @return array
	 */
	public function getValidateRules() {
		$aData=@unserialize($this->_getDataOne('validate_rules'));
		if (!$aData) {
			$aData=array();
		}
		return $aData;
	}
	/**
	 * Возвращает конкретное правило валидации
	 *
	 * @param string $sRule
	 *
	 * @return null|mixed
	 */
	public function getValidateRuleOne($sRule) {
		$aData=$this->getValidateRules();
		if (isset($aData[$sRule])) {
			return $aData[$sRule];
		}
		return null;
	}
	/**
	 * Устанавливает правила валидации поля
	 *
	 * @param array $aRules
	 */
	public function setValidateRules($aRules) {
		$this->_aData['validate_rules']=@serialize($aRules);
	}
	/**
	 * Возвращает список дополнительных параметров поля
	 *
	 * @return array|mixed
	 */
	public function getParams() {
		$aData=@unserialize($this->_getDataOne('params'));
		if (!$aData) {
			$aData=array();
		}
		return $aData;
	}
	/**
	 * Устанавливает список дополнительных параметров поля
	 *
	 * @param $aParams
	 */
	public function setParams($aParams) {
		$this->_aData['params']=@serialize($aParams);
	}
	/**
	 * Возвращает конкретный параметр поля
	 *
	 * @param $sName
	 *
	 * @return null
	 */
	public function getParam($sName) {
		$aParams=$this->getParams();
		return isset($aParams[$sName]) ? $aParams[$sName] : null;
	}
	/**
	 * Возвращает URL админки для редактирования поля
	 *
	 * @return string
	 */
	public function getUrlAdminUpdate() {
		return Router::GetPath('admin/properties/'.$this->getTargetType().'/update/'.$this->getId());
	}
	/**
	 * Возвращает URL админки для редактирования поля
	 *
	 * @return string
	 */
	public function getUrlAdminRemove() {
		return Router::GetPath('admin/properties/'.$this->getTargetType().'/remove/'.$this->getId());
	}
	/**
	 * Возвращает описание типа поля
	 *
	 * @return mixed
	 */
	public function getTypeTitle() {
		/**
		 * TODO: использовать текстовку из языкового
		 */
		return $this->getType();
	}
}