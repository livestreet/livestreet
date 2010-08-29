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
	
	protected $aRelations=array();
	protected $aRelationsData=array();
	
	protected $sPrimaryKey='id';
	protected $bIsNew=true;
	
	
	public function __construct($aParam = false) {
		parent::__construct($aParam);
	}
	
	public function _GetPrimaryKey() {
		if(!$this->_getDataOne($this->sPrimaryKey)) {
			$sModulePrefix=null;
			if (preg_match('/Entity([^_]+)/',get_class($this),$sModulePrefix)) {						
				$sModulePrefix=func_underscore($sModulePrefix[1]).'_';
				if($this->_getDataOne($sModulePrefix.$this->sPrimaryKey)) {
					$this->sPrimaryKey=$sModulePrefix.$this->sPrimaryKey;
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
		return $this->_Method(__FUNCTION__);
	}
	
	public function Update() {
		return $this->_Method(__FUNCTION__);
	}
	
	public function Save() {
		return $this->_Method(__FUNCTION__);
	}
	
	public function Delete() {
		return $this->_Method(__FUNCTION__);
	}	
	
	public function Reload() {
		return $this->_Method(__FUNCTION__);
	}
	
	protected function _Method($sName) {		
		$sModuleName=Engine::GetModuleName($this);
		$sEntityName=Engine::GetEntityName($this);
		$sPluginPrefix=Engine::GetPluginPrefix($this);
		
		return Engine::GetInstance()->_CallModule("{$sPluginPrefix}{$sModuleName}_{$sName}{$sEntityName}",array($this));
	}
	
	public function _getRelations() {
		return $this->aRelations;
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
		}
	}
	
	public function __call($sName,$aArgs) {
		$sType=substr($sName,0,strpos(func_underscore($sName),'_'));	
		if (!strpos($sName,'_') and in_array($sType,array('get','set','reload'))) {	
			$sKey=func_underscore(str_replace($sType,'',$sName));
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
				/**
				 * Проверяем на связи
				 */
				if (array_key_exists($sKey,$this->aRelations)) {
					$sEntityRel=$this->aRelations[$sKey][1];
					$sRelationType=$this->aRelations[$sKey][0];
					$sRelationKey=$this->aRelations[$sKey][2];
					$sRelationJoinTable=null;
					if($sRelationType == self::RELATION_TYPE_MANY_TO_MANY && array_key_exists(3, $this->aRelations[$sKey])) {
						$sRelationJoinTable=$this->aRelations[$sKey][3];
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
					if($oRelEntity=Engine::GetEntity($sEntityRel)) {
						$sRelPrimaryKey=$oRelEntity->_GetPrimaryKey();
					}
					
					$iPrimaryKeyValue=$this->_getDataOne($this->_GetPrimaryKey());
					
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
							$sCmd="{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}ItemsBy".func_camelize($sRelationKey);
							$aCmdArgs[0]=$iPrimaryKeyValue;
							break;
						case self::RELATION_TYPE_MANY_TO_MANY :
						  $sCmd="{$sRelPluginPrefix}Module{$sRelModuleName}_get{$sRelEntityName}ItemsByJoinTable";
							$sByKey = strpos($this->_GetPrimaryKey(), $sModulePrefix) === 0 ? $this->_GetPrimaryKey() : $sModulePrefix.$this->_GetPrimaryKey();
							$aCmdArgs[0] = array(
								'join_table'			=> $sRelationJoinTable,
								'relation_key'		=> $sRelationKey,
								'by_key'					=> $sByKey,
								'by_value'				=> $iPrimaryKeyValue,
							);
							break;
						default:
							break;
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
					$this->_aData[$sKey]=$aArgs[0];
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