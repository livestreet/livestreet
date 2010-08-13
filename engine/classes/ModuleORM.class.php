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
			$oEntity->_setData(array($oEntity->_GetPrimatyKey() => $res));
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
	
	protected function _DeleteEntity($oEntity) {
		$res=$this->oMapperORM->DeleteEntity($oEntity);
		if ($res) {			
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
				// ItemsByArrayId - id пока идет костылем, т.к. у стандартных сущностей нет метода _GetPrimatyKey()
				$aRelData=Engine::GetInstance()->_CallModule("{$sRelPluginPrefix}{$sRelModuleName}_get{$sRelEntityName}ItemsByArrayId",array($aEntityKeys[$sRelKey]));
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
		
		return $aEntities;
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
			$aEntities[$oEntity->_getDataOne($oEntity->_GetPrimatyKey())]=$oEntity;
		}
		return $aEntities;
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
		
		$sNameUnderscore=func_underscore($sName);
		
		/**
		 * getUserItemsByArrayId() get_user_items_by_array_id
		 */
		if (preg_match("@^get_([a-z]+)_items_by_array_([_a-z]+)$@i",$sNameUnderscore,$aMatch)) {
			return $this->GetItemsByArray(array($aMatch[2]=>$aArgs[0]),$aMatch[1]);
		}
		/**
		 * getUserByLogin() get_user_by_login
		 * getUserByLoginAndMail() get_user_by_login_and_mail
		 * getUserItemsByName() get_user_items_by_name
		 * getUserItemsByNameAndActive() get_user_items_by_name_and_active		 
		 * 
		 */		
		if (preg_match("@^get_([a-z]+)((_items)|())_by_([_a-z]+)$@i",$sNameUnderscore,$aMatch)) {
			$aFilter=array_combine(explode('_and_',$aMatch[5]),$aArgs);
			if ($aMatch[2]=='_items') {
				return $this->GetItemsByFilter($aFilter,$aMatch[1]);
			} else {				
				return $this->GetByFilter($aFilter,$aMatch[1]);
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
			return $this->GetItemsByFilter($aFilter,$aMatch[1]);
		}
		
		return $this->oEngine->_CallModule($sName,$aArgs);
	}
	
}
?>