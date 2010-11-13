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
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);
		
		$aFilterFields=array();
		foreach ($aFilter as $k=>$v) {
			if (substr($k,0,1)=='#') {
				
			} else {
				$aFilterFields[$oEntitySample->_getField($k)]=$v;
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
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);
		
		// Сортировка
		$sOrder='';
		if (isset($aFilter['#order'])) {
			foreach ($aFilter['#order'] as $key=>$value) {
				if (is_numeric($key)) {
					$key=$value;
					$value='asc';
				} elseif (!in_array($value,array('asc','desc'))) {
					$value='asc';
				}
				$sOrder.=" {$key} {$value},";
			}
			$sOrder=trim($sOrder,',');
			if ($sOrder!='') {
				$sOrder="ORDER BY {$sOrder}";
			}
		}
		
		// Постраничность		
		if (isset($aFilter['#page']) and is_array($aFilter['#page']) and count($aFilter['#page'])==2) { // array(2,15) - 2 - page, 15 - count			
			$aFilter['#limit']=array(($aFilter['#page'][0]-1)*$aFilter['#page'][1],$aFilter['#page'][1]);
		}
		
		// Лимит
		$sLimit='';
		if (isset($aFilter['#limit'])) { // допустимы варианты: limit=10 , limit=array(10) , limit=array(10,15)
			$aLimit=$aFilter['#limit'];
			if (is_numeric($aLimit)) {
				$iBegin=0;
				$iEnd=$aLimit;
			} elseif (is_array($aLimit)) {
				if (count($aLimit)>1) {
					$iBegin=$aLimit[0];
					$iEnd=$aLimit[1];
				} else {
					$iBegin=0;
					$iEnd=$aLimit[0];
				}
			}
			$sLimit="LIMIT {$iBegin}, {$iEnd}";
		}
		
						
		$aFilterFields=array();
		foreach ($aFilter as $k=>$v) {
			if (substr($k,0,1)=='#') {
				
			} else {
				$aFilterFields[$oEntitySample->_getField($k)]=$v;
			}
		}
		
			
		$sFilterFields='';
		if (count($aFilterFields)) {
			$sFilterFields=' and '.implode(' = ? and ',array_keys($aFilterFields)).' = ? ';
		}		
		$sql = "SELECT * FROM ".$sTableName." WHERE 1=1 {$sFilterFields} {$sOrder} {$sLimit} ";		
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
	
	
	public function GetCountItemsByFilter($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);
						
		$aFilterFields=array();
		foreach ($aFilter as $k=>$v) {
			if (substr($k,0,1)=='#') {
				
			} else {
				$aFilterFields[$oEntitySample->_getField($k)]=$v;
			}
		}
					
		$sFilterFields='';
		if (count($aFilterFields)) {
			$sFilterFields=' and '.implode(' = ? and ',array_keys($aFilterFields)).' = ? ';
		}		
		$sql = "SELECT count(*) as c FROM ".$sTableName." WHERE 1=1 {$sFilterFields} ";		
		$aQueryParams=array_merge(array($sql),array_values($aFilterFields));		
		if($aRow=call_user_func_array(array($this->oDb,'selectRow'),$aQueryParams)) {
			return $aRow['c'];
		}
		return 0;
	}
	
	
	public function GetItemsByArray($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);

		$aFilterFields=array();
		foreach($aFilter as $k=>$v) {
			$aFilterFields[$oEntitySample->_getField($k)]=$v;
		}
			
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
	
	public function GetCountItemsByArray($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);

		$aFilterFields=array();
		foreach($aFilter as $k=>$v) {
			$aFilterFields[$oEntitySample->_getField($k)]=$v;
		}
			
		$sFilterFields='';		
		if (count($aFilterFields)) {
			$sFilterFields=' and '.implode(' IN ( ?a ) and ',array_keys($aFilterFields)).' IN ( ?a ) ';
		}		
		
		$sql = "SELECT count(*) as c FROM ".$sTableName." WHERE 1=1 {$sFilterFields} ";		
		$aQueryParams=array_merge(array($sql),array_values($aFilterFields));
		
		$aItems=array();
		if($aRow=call_user_func_array(array($this->oDb,'selectRow'),$aQueryParams)) {
			return $aRow['c'];		
		}
		return 0;
	}
	
	
	public function GetItemsByJoinTable($aData,$sEntityFull) {
		if(empty($aData)) {
			return null;
		}
		$sTableName = self::GetTableName($sEntityFull);
		$sql = "SELECT a.*, b.* FROM ?# a LEFT JOIN ".$sTableName." b USING(?#) WHERE a.?#=?";
		$aItems = array();
		if($aRows=$this->oDb->select($sql, $aData['join_table'],$aData['relation_key'],$aData['by_key'],$aData['by_value'])) {
			foreach($aRows as $aRow) {
				$oEntity=Engine::GetEntity($sEntityFull,$aRow);
				$oEntity->_SetIsNew(false);
				$aItems[] = $oEntity;
			}			
		}
		return $aItems;
	}
	
	public function ShowColumnsFrom($oEntity) {
		$sTableName = self::GetTableName($oEntity);
		$sql = "SHOW COLUMNS FROM ".$sTableName;
		$aItems = array();
		if($aRows=$this->oDb->select($sql)) {
			foreach($aRows as $aRow) {
				$aItems[] = $aRow['Field'];
				if($aRow['Key']=='PRI') {
					$aItems['#primary_key'] = $aRow['Field'];
				}
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