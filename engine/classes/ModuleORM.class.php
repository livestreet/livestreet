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
 * Абстракция модуля ORM
 *
 */
abstract class ModuleORM extends Module {
	
	protected $oMapperORM=null;
	
	public function Init() {
		$this->_LoadMapperORM();
	}
	
	protected function _LoadMapperORM() {
		$this->oMapperORM=new MapperORM($this->oEngine->Database_GetConnect());
	}
	
	
	protected function _AddEntity($oEntity) {
		$res=$this->oMapperORM->AddEntity($oEntity);
		if ($res===0) {
			// у таблицы нет автоинремента
			return $oEntity;
		} elseif ($res) {
			// есть автоинкремент, устанавливаем его
			$oEntity->_setData(array($oEntity->_GetPrimaryKey() => $res));
			return $oEntity;
		}
		return false;
	}
	
	protected function _UpdateEntity($oEntity) {
		$res=$this->oMapperORM->UpdateEntity($oEntity);
		if ($res===0) {
			// апдейт прошел нормально, но запись не изменилась
			return $oEntity;
		} elseif ($res) {
			// запись изменилась
			return $oEntity;
		}
		return false;
	}

	protected function _SaveEntity($oEntity) {
		if ($oEntity->_isNew()) {
			return $this->_AddEntity($oEntity);
		} else {
			return $this->_UpdateEntity($oEntity);
		}
	}	
	
	protected function _DeleteEntity($oEntity) {
		$res=$this->oMapperORM->DeleteEntity($oEntity);
		if ($res) {			
			return $oEntity;
		}
		return false;
	}	
	
	protected function _ReloadEntity($oEntity) {
		if($sPrimaryKey=$oEntity->_getPrimaryKey()) {
			if($sPrimaryKeyValue=$oEntity->_getDataOne($sPrimaryKey)) {
				if($oEntityNew=$this->GetByFilter(array($sPrimaryKey=>$sPrimaryKeyValue),Engine::GetEntityName($oEntity))) {
					$oEntity->_setData($oEntityNew->_getData());
					$oEntity->_setRelationsData(array());
					return $oEntity;
				}
			}
		}
		return false;
	}
	
	
	protected function _ShowColumnsFrom($oEntity) {
		$res=$this->oMapperORM->ShowColumnsFrom($oEntity);
		return $res;
	}	
	
	
	protected function _GetChildrenOfEntity($oEntity) {
		if(in_array(EntityORM::RELATION_TYPE_TREE,$oEntity->_getRelations())) {
			$aRelationsData=$oEntity->_getRelationsData();
			if(array_key_exists('children',$aRelationsData)) {
				$aChildren=$aRelationsData['children'];
			} else {
				$aChildren=array();
				if($sPrimaryKey=$oEntity->_getPrimaryKey()) {
					if($sPrimaryKeyValue=$oEntity->_getDataOne($sPrimaryKey)) {
						$aChildren=$this->GetItemsByFilter(array('parent_id'=>$sPrimaryKeyValue),Engine::GetEntityName($oEntity));
					}
				}
			}
			if(is_array($aChildren)) {
				$oEntity->setChildren($aChildren);
				return $aChildren;
			}
		}
		return false;
	}

	
	protected function _GetParentOfEntity($oEntity) {
		if(in_array(EntityORM::RELATION_TYPE_TREE,$oEntity->_getRelations())) {
			$aRelationsData=$oEntity->_getRelationsData();
			if(array_key_exists('parent',$aRelationsData)) {
				$oParent=$aRelationsData['parent'];
			} else {
				$oParent='%%NULL_PARENT%%';
				if($sPrimaryKey=$oEntity->_getPrimaryKey()) {
					if($sParentId=$oEntity->getParentId()) {
						$oParent=$this->GetByFilter(array($sPrimaryKey=>$sParentId),Engine::GetEntityName($oEntity));
					}
				}
			}
			if(!is_null($oParent)) {
				$oEntity->setParent($oParent);
				return $oParent;
			}
		}
		return false;
	}
	
	
	protected function _GetAncestorsOfEntity($oEntity) {
		if(in_array(EntityORM::RELATION_TYPE_TREE,$oEntity->_getRelations())) {
			$aRelationsData=$oEntity->_getRelationsData();
			if(array_key_exists('ancestors',$aRelationsData)) {
				$aAncestors=$aRelationsData['ancestors'];
			} else {
				$aAncestors=array();
				$oEntityParent=$oEntity->getParent();
				while(is_object($oEntityParent)) {
					$aAncestors[]=$oEntityParent;
					$oEntityParent=$oEntityParent->getParent();
				}
			}
			if(is_array($aAncestors)) {
				$oEntity->setAncestors($aAncestors);
				return $aAncestors;
			}
		}
		return false;
	}	
	
	
	protected function _GetDescendantsOfEntity($oEntity) {
		if(in_array(EntityORM::RELATION_TYPE_TREE,$oEntity->_getRelations())) {
			$aRelationsData=$oEntity->_getRelationsData();
			if(array_key_exists('descendants',$aRelationsData)) {
				$aDescendants=$aRelationsData['descendants'];
			} else {
				$aDescendants=array();
				if($aChildren=$oEntity->getChildren()) {
					$aTree=self::buildTree($aChildren);
					foreach($aTree as $aItem) {
						$aDescendants[] = $aItem['entity'];
					}
				}
			}
			if(is_array($aDescendants)) {
				$oEntity->setDescendants($aDescendants);
				return $aDescendants;
			}
		}
		return false;
	}
	

	public function LoadTree($sEntityFull=null) {
		if (is_null($sEntityFull)) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.Engine::GetModuleName(get_class($this));
		} elseif (!substr_count($sEntityFull,'_')) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.$sEntityFull;
		}
		if($oEntityDefault=Engine::GetEntity($sEntityFull)) {
			if(in_array(EntityORM::RELATION_TYPE_TREE,$oEntityDefault->_getRelations())) {
				if($sPrimaryKey=$oEntityDefault->_getPrimaryKey()) {
					if($aItems=$this->GetItemsByFilter(array(),$sEntityFull)) {
						$aItemsById = array();
						$aItemsByParentId = array();
						foreach($aItems as $oEntity) {
							$oEntity->setChildren(array());
							$aItemsById[$oEntity->_getDataOne($sPrimaryKey)] = $oEntity;
							if(empty($aItemsByParentId[$oEntity->getParentId()])) {
								$aItemsByParentId[$oEntity->getParentId()] = array();
							}
							$aItemsByParentId[$oEntity->getParentId()][] = $oEntity;
						}
						foreach($aItemsByParentId as $iParentId=>$aItems) {
							if($iParentId > 0) {
								$aItemsById[$iParentId]->setChildren($aItems);
								foreach($aItems as $oEntity) {
									$oEntity->setParent($aItemsById[$iParentId]);
								}
							}
						}
						return $aItemsByParentId[0];
					}
				}
			}
		}
		return false;
	}
	
	public function GetByFilter($aFilter=array(),$sEntityFull=null) {
		if (is_null($sEntityFull)) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.Engine::GetModuleName(get_class($this));
		} elseif (!substr_count($sEntityFull,'_')) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.$sEntityFull;
		}
		
		return $this->oMapperORM->GetByFilter($aFilter,$sEntityFull);
	}
	
	public function GetItemsByFilter($aFilter=array(),$sEntityFull=null) {
		if (is_null($sEntityFull)) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.Engine::GetModuleName(get_class($this));
		} elseif (!substr_count($sEntityFull,'_')) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.$sEntityFull;
		}
			
		$aEntities=$this->oMapperORM->GetItemsByFilter($aFilter,$sEntityFull);
		/**
		 * Если необходимо подцепить связанные данные
		 */
		if (isset($aFilter['#with'])) {
			if (!is_array($aFilter['#with'])) {
				$aFilter['#with']=array($aFilter['#with']);
			}
			$oEntityEmpty=Engine::GetEntity($sEntityFull);
			$aRelations=$oEntityEmpty->_getRelations();
			$aEntityKeys=array();
			foreach ($aFilter['#with'] as $sRelationName) {
				$sRelType=$aRelations[$sRelationName][0];
				$sRelEntity=$aRelations[$sRelationName][1];
				$sRelKey=$aRelations[$sRelationName][2];
				
				if (!array_key_exists($sRelationName,$aRelations) or !in_array($sRelType,array(EntityORM::RELATION_TYPE_BELONGS_TO,EntityORM::RELATION_TYPE_HAS_ONE))) {
					throw new Exception("The entity <{$sEntityFull}> not have relation <{$sRelationName}>");
				}

				/**
				 * Формируем список ключей
				 */
				foreach ($aEntities as $oEntity) {
					$aEntityKeys[$sRelKey][]=$oEntity->_getDataOne($sRelKey);
				}
				$aEntityKeys[$sRelKey]=array_unique($aEntityKeys[$sRelKey]);
				
				/**
				 * Делаем общий запрос по всем ключам
				 */
				$oRelEntityEmpty=Engine::GetEntity($sRelEntity);
				
				$sRelModuleName=Engine::GetModuleName($oRelEntityEmpty);
				$sRelEntityName=Engine::GetEntityName($oRelEntityEmpty);
				$sRelPluginPrefix=Engine::GetPluginPrefix($oRelEntityEmpty);
				$sPrimaryKey="Id";
				if ($sPrimaryKey=$oRelEntityEmpty->_GetPrimaryKey()) {
					$sPrimaryKey=ucfirst($sPrimaryKey);					
				}				
				$aRelData=Engine::GetInstance()->_CallModule("{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}ItemsByArray{$sPrimaryKey}",array($aEntityKeys[$sRelKey]));
				/**
			 	 * Собираем набор
				 */
				foreach ($aEntities as $oEntity) {
					if (isset($aRelData[$oEntity->_getDataOne($sRelKey)])) {
						$oEntity->_setData(array($sRelationName => $aRelData[$oEntity->_getDataOne($sRelKey)]));
					}
				}
			}
			
		}
		
		/**
		 * Если запрашиваем постраничный список, то возвращаем сам список и общее количество записей
		 */
		if (isset($aFilter['#page'])) {
			return array('collection'=>$aEntities,'count'=>$this->GetCountItemsByFilter($aFilter,$sEntityFull));
		}		
		return $aEntities;
	}
	
	public function GetCountItemsByFilter($aFilter=array(),$sEntityFull=null) {
		if (is_null($sEntityFull)) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.Engine::GetModuleName(get_class($this));
		} elseif (!substr_count($sEntityFull,'_')) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.$sEntityFull;
		}
			
		$iCount=$this->oMapperORM->GetCountItemsByFilter($aFilter,$sEntityFull);
		
		return $iCount;
	}
	
	public function GetItemsByArray($aFilter,$sEntityFull=null) {
		if (is_null($sEntityFull)) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.Engine::GetModuleName(get_class($this));
		} elseif (!substr_count($sEntityFull,'_')) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.$sEntityFull;
		}
		
		$aEntities=array();
		$aData=$this->oMapperORM->GetItemsByArray($aFilter,$sEntityFull);
		foreach ($aData as $oEntity) {
			// здесь под вопросом какое поле использовать в качестве ключа, всегда примари или тот, который передан?
			$aEntities[$oEntity->_getDataOne($oEntity->_GetPrimaryKey())]=$oEntity;
		}
		return $aEntities;
	}
	
	public function GetCountItemsByArray($aFilter=array(),$sEntityFull=null) {
		if (is_null($sEntityFull)) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.Engine::GetModuleName(get_class($this));
		} elseif (!substr_count($sEntityFull,'_')) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.$sEntityFull;
		}
			
		$iCount=$this->oMapperORM->GetCountItemsByArray($aFilter,$sEntityFull);
		
		return $iCount;
	}	
	public function GetItemsByJoinTable($aJoinData=array(),$sEntityFull=null) {
		if (is_null($sEntityFull)) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.Engine::GetModuleName(get_class($this));
		} elseif (!substr_count($sEntityFull,'_')) {
			$sEntityFull=Engine::GetPluginPrefix($this).'Module'.Engine::GetModuleName($this).'_Entity'.$sEntityFull;
		}
		return $this->oMapperORM->GetItemsByJoinTable($aJoinData,$sEntityFull);
	}
	
	public function __call($sName,$aArgs) {
		if (preg_match("@^add([\w]+)$@i",$sName,$aMatch)) {
			return $this->_AddEntity($aArgs[0]);
		}
		
		if (preg_match("@^update([\w]+)$@i",$sName,$aMatch)) {
			return $this->_UpdateEntity($aArgs[0]);
		}
		
		if (preg_match("@^save([\w]+)$@i",$sName,$aMatch)) {
			return $this->_SaveEntity($aArgs[0]);
		}
		
		if (preg_match("@^delete([\w]+)$@i",$sName,$aMatch)) {
			return $this->_DeleteEntity($aArgs[0]);
		}
		
		if (preg_match("@^reload([\w]+)$@i",$sName,$aMatch)) {
			return $this->_ReloadEntity($aArgs[0]);
		}

		if (preg_match("@^showcolumnsfrom([\w]+)$@i",$sName,$aMatch)) {
			return $this->_ShowColumnsFrom($aArgs[0]);
		}

		if (preg_match("@^getchildrenof([\w]+)$@i",$sName,$aMatch)) {
			return $this->_GetChildrenOfEntity($aArgs[0]);
		}
		
		if (preg_match("@^getparentof([\w]+)$@i",$sName,$aMatch)) {
			return $this->_GetParentOfEntity($aArgs[0]);
		}
		
		if (preg_match("@^getdescendantsof([\w]+)$@i",$sName,$aMatch)) {
			return $this->_GetDescendantsOfEntity($aArgs[0]);
		}
		
		if (preg_match("@^getancestorsof([\w]+)$@i",$sName,$aMatch)) {
			return $this->_GetAncestorsOfEntity($aArgs[0]);
		}
		
		if (preg_match("@^loadtreeof([\w]+)$@i",$sName,$aMatch)) {
			$sEntityFull = array_key_exists(1,$aMatch) ? $aMatch[1] : null;
			return $this->LoadTree($sEntityFull);
		}
		
		$sNameUnderscore=func_underscore($sName);
		$iEntityPosEnd=0; 
		if(strpos($sNameUnderscore,'_items')>4) {
			$iEntityPosEnd=strpos($sNameUnderscore,'_items');
		} else if(strpos($sNameUnderscore,'_by')>4) {
			$iEntityPosEnd=strpos($sNameUnderscore,'_by');
		} else if(strpos($sNameUnderscore,'_all')>4) {
			$iEntityPosEnd=strpos($sNameUnderscore,'_all');
		}
		if($iEntityPosEnd) {
			$sEntityName=substr($sNameUnderscore,4,$iEntityPosEnd-4);
		} else {
			$sEntityName=func_underscore(Engine::GetModuleName($this)).'_';
			$sNameUnderscore=substr_replace($sNameUnderscore,$sEntityName,4,0);
			$iEntityPosEnd=strlen($sEntityName)-1+4;
		}
		/**
		 * getUserRoleJoinByUserIdAndRoleId() get_user-role-join_by_user_id_and_role_id
		 */
		$sNameUnderscore=substr_replace($sNameUnderscore,str_replace('_','',$sEntityName),4,$iEntityPosEnd-4);

		$sEntityName=func_camelize($sEntityName);
		/**
		 * getUserItemsByArrayId() get_user_items_by_array_id
		 */
		if (preg_match("@^get_([a-z]+)_items_by_array_([_a-z]+)$@i",$sNameUnderscore,$aMatch)) {
			return $this->GetItemsByArray(array($aMatch[2]=>$aArgs[0]),$sEntityName);
		}
		/**
		 * getUserItemsByJoinTable() get_user_items_by_join_table
		 */
		if (preg_match("@^get_([a-z]+)_items_by_join_table$@i",$sNameUnderscore,$aMatch)) {
			return $this->GetItemsByJoinTable($aArgs[0],func_camelize($sEntityName));
		}
		/**
		 * getUserByLogin() get_user_by_login
		 * getUserByLoginAndMail() get_user_by_login_and_mail
		 * getUserItemsByName() get_user_items_by_name
		 * getUserItemsByNameAndActive() get_user_items_by_name_and_active		 
		 * 
		 */		
		if (preg_match("@^get_([a-z]+)((_items)|())_by_([_a-z]+)$@i",$sNameUnderscore,$aMatch)) {
			$aSearchParams=explode('_and_',$aMatch[5]);
			$aSplit=array_chunk($aArgs,count($aSearchParams));			
			$aFilter=array_combine($aSearchParams,$aSplit[0]);
			if (isset($aSplit[1][0])) {
				$aFilter=array_merge($aFilter,$aSplit[1][0]);
			}
			if ($aMatch[2]=='_items') {
				return $this->GetItemsByFilter($aFilter,$sEntityName);
			} else {				
				return $this->GetByFilter($aFilter,$sEntityName);
			}
		}
		/**
		 * getUserAll() get_user_all
		 */
		if (preg_match("@^get_([a-z]+)_items_all$@i",$sNameUnderscore,$aMatch)) {
			$aFilter=array();
			if (isset($aArgs[0]) and is_array($aArgs[0])) {
				$aFilter=$aArgs[0];
			}
			return $this->GetItemsByFilter($aFilter,$sEntityName);
		}
		
		return $this->oEngine->_CallModule($sName,$aArgs);
	}
	
	static function buildTree($aItems,$aList=array(),$iLevel=0) {
		foreach($aItems as $oEntity) {
			$aChildren=$oEntity->getChildren();
			$bHasChildren = !empty($aChildren); 
			$sEntityId = $oEntity->_getDataOne($oEntity->_getPrimaryKey());
			$aList[$sEntityId] = array(
				'entity'		 => $oEntity,
				'parent_id'		 => $oEntity->getParentId(),
				'children_count' => $bHasChildren ? count($aChildren) : 0,
				'level'			 => $iLevel,
			);
			if($bHasChildren) {
				$aList=self::buildTree($aChildren,$aList,$iLevel+1);
			}
		}
		return $aList;
	}
}
?>