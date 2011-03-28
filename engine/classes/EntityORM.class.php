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
 * Абстрактный класс сущности ORM
 *
 */
abstract class EntityORM extends Entity {	
	
	const RELATION_TYPE_BELONGS_TO='belongs_to';
	const RELATION_TYPE_HAS_MANY='has_many';
	const RELATION_TYPE_HAS_ONE='has_one';
	const RELATION_TYPE_MANY_TO_MANY='many_to_many';
	const RELATION_TYPE_TREE='tree';
	
	protected $_aOriginalData=array(); 
	
	protected $aFields=array();
	
	protected $aRelations=array();
	protected $aRelationsData=array();
	
	protected $sPrimaryKey='id';
	protected $bIsNew=true;
	
	
	public function __construct($aParam=false) {
		parent::__construct($aParam);
		$this->aRelations=$this->_getRelations();
	}
	
	public function _getPrimaryKey() {
		if(!$this->_getDataOne($this->sPrimaryKey)) {
			if($this->_getFields()) {
				if(array_key_exists('#primary_key',$this->aFields)) {
					$this->sPrimaryKey = $this->aFields['#primary_key'];
				} else {
					$this->sPrimaryKey = $this->_getField($this->sPrimaryKey,2);
				}
			}
		}
		return $this->sPrimaryKey;
	}
	
	public function _isNew() {
		return $this->bIsNew;
	}
	
	public function _SetIsNew($bIsNew) {
		$this->bIsNew=$bIsNew;
	}
	
	public function Add() {
		if ($this->beforeSave())
			if ($res=$this->_Method(__FUNCTION__)) {
				$this->afterSave();
				return $res;
			}
		return false;
	}
	
	public function Update() {
		if ($this->beforeSave())
			if ($res=$this->_Method(__FUNCTION__)) {
				$this->afterSave();
				return $res;
			}
		return false;
	}
	
	public function Save() {
		if ($this->beforeSave())
			if ($res=$this->_Method(__FUNCTION__)) {
				$this->afterSave();
				return $res;
			}
		return false;
	}
	
	public function Delete() {
		if ($this->beforeDelete())
			if ($res=$this->_Method(__FUNCTION__)) {
				$this->afterDelete();
				return $res;
			}
		return false;
	}	
	
	public function Reload() {
		return $this->_Method(__FUNCTION__);
	}	
	
	public function ShowColumns() {
		return $this->_Method(__FUNCTION__ .'From');
	}

	
	protected function beforeSave() {
		return true;
	}
	
	protected function afterSave() {
		
	}
	
	protected function beforeDelete() {
		return true;
	}
	
	protected function afterDelete() {
		
	}
	

	public function getChildren() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__);
	}
	
	public function getDescendants() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__);
	}

	public function getParent() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__);
	}

	public function getAncestors() {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			return $this->_Method(__FUNCTION__ .'Of');
		}
		return $this->__call(__FUNCTION__);
	}
	
	public function setChildren($aChildren=array()) {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			$this->aRelationsData['children'] = $aChildren;
		} else {
			$aArgs = func_get_args();
			return $this->__call(__FUNCTION__,$aArgs);
		}
	}	
	
	public function setDescendants($aDescendants=array()) {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			$this->aRelationsData['descendants'] = $aDescendants;
		} else {
			$aArgs = func_get_args();
			return $this->__call(__FUNCTION__,$aArgs);
		}
	}

	public function setParent($oParent=null) {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			$this->aRelationsData['parent'] = $oParent;
		} else {
			$aArgs = func_get_args();
			return $this->__call(__FUNCTION__,$aArgs);
		}
	}
	
	public function setAncestors($oParent=null) {
		if(in_array(self::RELATION_TYPE_TREE,$this->aRelations)) {
			$this->aRelationsData['ancestors'] = $oParent;
		} else {
			$aArgs = func_get_args();
			return $this->__call(__FUNCTION__,$aArgs);
		}
	}
	
	protected function _Method($sName) {		
		$sModuleName=Engine::GetModuleName($this);
		$sEntityName=Engine::GetEntityName($this);
		$sPluginPrefix=Engine::GetPluginPrefix($this);	
		/**
		 * If Module not exists, try to find its root Delegater
		 */
		$aClassInfo = Engine::GetClassInfo($sPluginPrefix.'Module_'.$sModuleName,Engine::CI_MODULE);
		if(empty($aClassInfo[Engine::CI_MODULE]) && $sRootDelegater=$this->Plugin_GetRootDelegater('entity',get_class($this))) {
			$sModuleName=Engine::GetModuleName($sRootDelegater);
			$sPluginPrefix=Engine::GetPluginPrefix($sRootDelegater);	
		}
		return Engine::GetInstance()->_CallModule("{$sPluginPrefix}{$sModuleName}_{$sName}{$sEntityName}",array($this));
	}

	
	public function _setData($aData) {
		if(is_array($aData)) {
			foreach ($aData as $sKey => $val) {
				if (array_key_exists($sKey,$this->aRelations)) {
					$this->aRelationsData[$sKey]=$val;
				} else {
					$this->_aData[$sKey] = $val;
				}
			}
			$this->_aOriginalData = $this->_aData;
		}
	}
	
	public function _getOriginalData() {
		return $this->_aOriginalData;
	}
	
	public function _getFields() {
		if(empty($this->aFields)) {
			$this->aFields=$this->ShowColumns();
		}
		return $this->aFields;
	}
	
	public function _getField($sField,$iPersistence=3) {
		if($aFields=$this->_getFields()) {
			if(in_array($sField,$aFields)) {
				return $sField;
			}
			if($iPersistence==0) {
				return null;
			}
			$sFieldU = func_camelize($sField);
			$sEntityField = func_underscore(Engine::GetEntityName($this).$sFieldU);
			if(in_array($sEntityField,$aFields)) {
				return $sEntityField;
			}
			if($iPersistence==1) {
				return null;
			}
			$sModuleEntityField = func_underscore(Engine::GetModuleName($this).Engine::GetEntityName($this).$sFieldU);
			if(in_array($sModuleEntityField,$aFields)) {
				return $sModuleEntityField;
			}
			if($iPersistence==2) {
				return null;
			}
			$sModuleField = func_underscore(Engine::GetModuleName($this).$sFieldU);
			if(in_array($sModuleField,$aFields)) {
				return $sModuleField;
			}
		}
		return $sField;
	}
	
	public function _getRelations() {
		$sParent=get_parent_class($this);
		if(substr_count($sParent,'_Inherits_') || substr_count($sParent,'_Inherit_')) {
			$sParent = get_parent_class($sParent);
		}
		$aParentRelations=array();
		if(!in_array($sParent,array('Entity','EntityORM'))) {
			$oEntityParent=new $sParent();
			$aParentRelations=$oEntityParent->_getRelations();
		}
		return array_merge($aParentRelations,$this->aRelations);
	}	

	public function _getRelationsData() {
		return $this->aRelationsData;
	}

	public function _setRelationsData($aData) {
		$this->aRelationsData=$aData;
	}	
	
	public function __call($sName,$aArgs) {
		$sType=substr($sName,0,strpos(func_underscore($sName),'_'));	
		if (!strpos($sName,'_') and in_array($sType,array('get','set','reload'))) {	
			$sKey=func_underscore(str_replace($sType,'',$sName));
			if ($sType=='get') {
				if (isset($this->_aData[$sKey])) {
					return $this->_aData[$sKey];
				} else {
					$sField=$this->_getField($sKey);
					if($sField!=$sKey && isset($this->_aData[$sField])) {
						return $this->_aData[$sField];
					}
				}
				/**
				 * Проверяем на связи
				 */
				if (array_key_exists($sKey,$this->aRelations)) {
					$sEntityRel=$this->aRelations[$sKey][1];
					$sRelationType=$this->aRelations[$sKey][0];
					$sRelationKey=$this->aRelations[$sKey][2];
					$sRelationJoinTable=null;
					$sRelationJoinTableKey=0;	// foreign key в join-таблице для текущей сущности
					if($sRelationType == self::RELATION_TYPE_MANY_TO_MANY && array_key_exists(3, $this->aRelations[$sKey])) {
						$sRelationJoinTable=$this->aRelations[$sKey][3];
						$sRelationJoinTableKey=isset($this->aRelations[$sKey][4]) ? $this->aRelations[$sKey][4] : $this->_getPrimaryKey();
					}
					
					/**
					 * Если связь уже загруженна, то возвращаем сразу результат
					 */
					if (array_key_exists($sKey,$this->aRelationsData)) {
						return $this->aRelationsData[$sKey];
					}
					
					
					$sRelModuleName=Engine::GetModuleName($sEntityRel);
					$sRelEntityName=Engine::GetEntityName($sEntityRel);
					$sRelPluginPrefix=Engine::GetPluginPrefix($sEntityRel);
					$sRelPrimaryKey='id';
					if($oRelEntity=Engine::GetEntity($sEntityRel) and method_exists($oRelEntity,'_getPrimaryKey')) { // для совместимости с сущностями Entity
						$sRelPrimaryKey=$oRelEntity->_getPrimaryKey();
					}
					
					$iPrimaryKeyValue=$this->_getDataOne($this->_getPrimaryKey());
					
					$sCmd='';
					$aCmdArgs=array();
					switch ($sRelationType) {
						case self::RELATION_TYPE_BELONGS_TO :
							$sCmd="{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}By".func_camelize($sRelPrimaryKey);
							$aCmdArgs[0]=$this->_getDataOne($sRelationKey);
							break;
						case self::RELATION_TYPE_HAS_ONE :
							$sCmd="{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}By".func_camelize($sRelationKey);
							$aCmdArgs[0]=$iPrimaryKeyValue;
							break;
						case self::RELATION_TYPE_HAS_MANY :
							$sCmd="{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}ItemsByFilter";
							$aCmdArgs[0]=array($sRelationKey => $iPrimaryKeyValue);
							break;
						case self::RELATION_TYPE_MANY_TO_MANY :
						  $sCmd="{$sRelPluginPrefix}Module{$sRelModuleName}_get{$sRelEntityName}ItemsByJoinTable";
							$aCmdArgs[0]=array(
								'#join_table'		=> $sRelationJoinTable,
								'#relation_key'		=> $sRelationKey,
								'#by_key'			=> $sRelationJoinTableKey,
								'#by_value'			=> $iPrimaryKeyValue,
							);
							break;
						default:
							break;
					}
					if(is_array($aCmdArgs[0]) && array_key_exists(0,$aArgs) && is_array($aArgs[0])) {
						$aCmdArgs[0] = array_merge($aCmdArgs[0], $aArgs[0]);
					}
					$res=Engine::GetInstance()->_CallModule($sCmd,$aCmdArgs);

					$this->aRelationsData[$sKey]=$res;
					return $res;
				}
				
				return null;
			} elseif ($sType=='set' and array_key_exists(0,$aArgs)) {
				if (array_key_exists($sKey,$this->aRelations)) {
					$this->aRelationsData[$sKey]=$aArgs[0];
				} else {
					$this->_aData[$this->_getField($sKey)]=$aArgs[0];
				}
			} elseif ($sType=='reload') {
				if (array_key_exists($sKey,$this->aRelationsData)) {
					unset($this->aRelationsData[$sKey]);
					return $this->__call('get'.func_camelize($sKey),$aArgs);
				}
			}
		} else {
			return Engine::getInstance()->_CallModule($sName,$aArgs);
		}
	}
}
?>