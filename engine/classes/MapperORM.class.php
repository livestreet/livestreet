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
 * Класс мапера ORM
 *
 */
class MapperORM extends Mapper {
	
	public function AddEntity($oEntity) {
		$sTableName = self::GetTableName($oEntity);
		
		$sql = "INSERT INTO ".$sTableName." SET ?a ";			
		return $this->oDb->query($sql,$oEntity->_getData());
	}
	
	public function UpdateEntity($oEntity) {
		$sTableName = self::GetTableName($oEntity);
		$iPrimaryKeyValue=$oEntity->_getDataOne($oEntity->_GetPrimaryKey());
				
		$sql = "UPDATE ".$sTableName." SET ?a WHERE ".$oEntity->_GetPrimaryKey()." = ? "; 
		
		return $this->oDb->query($sql,$oEntity->_getData(),$iPrimaryKeyValue);
	}
	
	public function DeleteEntity($oEntity) {		
		$sTableName = self::GetTableName($oEntity);
		$iPrimaryKeyValue=$oEntity->_getDataOne($oEntity->_GetPrimaryKey());
		
		$sql = "DELETE FROM ".$sTableName." WHERE ".$oEntity->_GetPrimaryKey()." = ? "; 
		return $this->oDb->query($sql,$iPrimaryKeyValue);
	}
	
	public function GetByFilter($aFilter,$sEntityFull) {
		$sTableName = self::GetTableName($sEntityFull);
		
		$aFilterFields=array();
		foreach ($aFilter as $k=>$v) {
			if (substr($k,0,1)=='#') {
				
			} else {
				$aFilterFields[$k]=$v;
			}
		}
		
		
		$sFilterFields='';		
		if (count($aFilterFields)) {
			$sFilterFields=' and '.implode(' = ? and ',array_keys($aFilterFields)).' = ? ';
		}		
		
		$sql = "SELECT * FROM ".$sTableName." WHERE 1=1 {$sFilterFields}  LIMIT 0,1";		
		$aQueryParams=array_merge(array($sql),array_values($aFilterFields));
				
		if($aRow=call_user_func_array(array($this->oDb,'selectRow'),$aQueryParams)) {
			$oEntity=Engine::GetEntity($sEntityFull,$aRow);
			$oEntity->_SetIsNew(false);
			return $oEntity;
		}
	}

	public function GetItemsByFilter($aFilter,$sEntityFull) {
		$sTableName = self::GetTableName($sEntityFull);

		$aFilterFields=array();
		foreach ($aFilter as $k=>$v) {
			if (substr($k,0,1)=='#') {
				
			} else {
				$aFilterFields[$k]=$v;
			}
		}
		
			
		$sFilterFields='';		
		if (count($aFilterFields)) {
			$sFilterFields=' and '.implode(' = ? and ',array_keys($aFilterFields)).' = ? ';
		}		
		
		$sql = "SELECT * FROM ".$sTableName." WHERE 1=1 {$sFilterFields} ";		
		$aQueryParams=array_merge(array($sql),array_values($aFilterFields));
		
		$aItems=array();
		if($aRows=call_user_func_array(array($this->oDb,'select'),$aQueryParams)) {
			foreach($aRows as $aRow) {
				$oEntity=Engine::GetEntity($sEntityFull,$aRow);
				$oEntity->_SetIsNew(false);
				$aItems[] = $oEntity;
			}			
		}
		return $aItems;
	}
	
	public function GetItemsByArray($aFilter,$sEntityFull) {
		$sTableName = self::GetTableName($sEntityFull);

		$aFilterFields=$aFilter;
			
		$sFilterFields='';		
		if (count($aFilterFields)) {
			$sFilterFields=' and '.implode(' IN ( ?a ) and ',array_keys($aFilterFields)).' IN ( ?a ) ';
		}		
		
		$sql = "SELECT * FROM ".$sTableName." WHERE 1=1 {$sFilterFields} ";		
		$aQueryParams=array_merge(array($sql),array_values($aFilterFields));
		
		$aItems=array();
		if($aRows=call_user_func_array(array($this->oDb,'select'),$aQueryParams)) {
			foreach($aRows as $aRow) {
				$oEntity=Engine::GetEntity($sEntityFull,$aRow);
				$oEntity->_SetIsNew(false);
				$aItems[] = $oEntity;
			}			
		}
		return $aItems;
	}
	
	public function GetItemsByJoinTable($aData,$sEntityFull) {
		if(empty($aData)) {
			return null;
		}
		$sTableName = self::GetTableName($sEntityFull);
		$sql = "SELECT a.*, b.* FROM ?# a LEFT JOIN ".$sTableName." b USING(?#) WHERE a.?#=?";
		$aItems = array();
		if($aRows=$this->oDb->select($sql, $aData['join_table'], $aData['relation_key'],  $aData['by_key'], $aData['by_value'])) {
			foreach($aRows as $aRow) {
				$oEntity=Engine::GetEntity($sEntityFull,$aRow);
				$oEntity->_SetIsNew(false);
				$aItems[] = $oEntity;
			}			
		}
		return $aItems;
	}
	
	public static function GetTableName($oEntity) {
		/**
		 * Варианты таблиц:
		 * 	prefix_user -> если модуль совпадает с сущностью
		 * 	prefix_user_invite -> если модуль не сопадает с сущностью
		 */
		$sModuleName = func_underscore(Engine::GetModuleName($oEntity));
		$sEntityName = func_underscore(Engine::GetEntityName($oEntity));
		if (strpos($sEntityName,$sModuleName)===0) {
			$sTable=func_underscore($sEntityName);
		} else {
			$sTable=func_underscore($sModuleName).'_'.func_underscore($sEntityName);
		}
		if(Config::Get('db.table.'.$sTable)) {
			return Config::Get('db.table.'.$sTable);
		} else {
			return Config::Get('db.table.prefix').$sTable;
		}
	}
}
?>