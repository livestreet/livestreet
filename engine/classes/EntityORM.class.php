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
	
	protected $aRelations=array();
	protected $aRelationsData=array();
	
	protected $sPrimaryKey='id';
	protected $bIsNew=true;
	
	
	public function __construct($aParam = false) {
		parent::__construct($aParam);
	}
	
	public function _GetPrimatyKey() {
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
	
	protected function _Method($sName) {		
		$sModuleName=Engine::GetModuleName($this);
		$sEntityName=Engine::GetEntityName($this);
		$sPluginPrefix=Engine::GetPluginPrefix($this);
		
		return Engine::GetInstance()->_CallModule("{$sPluginPrefix}{$sModuleName}_{$sName}{$sEntityName}",array($this));
	}
	
	public function _getRelations() {
		return $this->aRelations;
	}
	
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
				/**
				 * Проверяем на связи
				 */
				if (array_key_exists($sKey,$this->aRelations)) {
					$sEntityRel=$this->aRelations[$sKey][1];
					$sRelationType=$this->aRelations[$sKey][0];
					$sRelationKey=$this->aRelations[$sKey][2];
					
					/**
					 * Если связь уже загруженна, то возвращаем сразу результат
					 */
					if (array_key_exists($sKey,$this->aRelationsData)) {
						return $this->aRelationsData[$sKey];
					}
					
					$sModuleName=Engine::GetModuleName($sEntityRel);
					$sEntityName=Engine::GetEntityName($sEntityRel);
					$sPluginPrefix=Engine::GetPluginPrefix($sEntityRel);
					
					$iPrimaryKeyValue=$this->_getDataOne($this->_GetPrimatyKey());
					
					$sCmd='';
					$aCmdArgs=array();
					switch ($sRelationType) {
						case self::RELATION_TYPE_HAS_MANY :
							$sCmd="{$sPluginPrefix}{$sModuleName}_get{$sEntityName}ItemsBy".func_camelize($sRelationKey);
							$aCmdArgs[0]=$iPrimaryKeyValue;
							break;
						case self::RELATION_TYPE_BELONGS_TO :
							$sCmd="{$sPluginPrefix}{$sModuleName}_get{$sEntityName}By".func_camelize($this->_GetPrimatyKey());
							$aCmdArgs[0]=$this->_getDataOne($sRelationKey);							
						case self::RELATION_TYPE_HAS_ONE :
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
			}
		} else {
			return Engine::getInstance()->_CallModule($sName,$aArgs);
		}
	}
}
?>