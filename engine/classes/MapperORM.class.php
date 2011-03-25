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
				
		if(!empty($iPrimaryKeyValue)) {
			$sql = "UPDATE ".$sTableName." SET ?a WHERE ".$oEntity->_GetPrimaryKey()." = ? "; 
			return $this->oDb->query($sql,$oEntity->_getData(),$iPrimaryKeyValue);
		} else {
			$aOriginalData = $oEntity->_getOriginalData();
			$sWhere = implode(' AND ',array_map(create_function(
				'$k,$v',
				'return "{$k} = \"{$v}\"";'
			),array_keys($aOriginalData),array_values($aOriginalData)));
			$sql = "UPDATE ".$sTableName." SET ?a WHERE 1=1 AND ". $sWhere; 
			return $this->oDb->query($sql,$oEntity->_getData());
		}
	}
	
	public function DeleteEntity($oEntity) {		
		$sTableName = self::GetTableName($oEntity);
		$iPrimaryKeyValue=$oEntity->_getDataOne($oEntity->_GetPrimaryKey());
		
		if(!empty($iPrimaryKeyValue)) {
			$sql = "DELETE FROM ".$sTableName." WHERE ".$oEntity->_GetPrimaryKey()." = ? "; 
			return $this->oDb->query($sql,$iPrimaryKeyValue);
		} else {
			$aOriginalData = $oEntity->_getOriginalData();
			$sWhere = implode(' AND ',array_map(create_function(
				'$k,$v',
				'return "{$k} = \"{$v}\"";'
			),array_keys($aOriginalData),array_values($aOriginalData)));
			$sql = "DELETE FROM ".$sTableName." WHERE 1=1 AND ". $sWhere; 
			return $this->oDb->query($sql);
		}
	}
	
	public function GetByFilter($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);
		
		list($aFilterFields,$sFilterFields)=$this->BuildFilter($aFilter,$oEntitySample);
		
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
						
		list($aFilterFields,$sFilterFields)=$this->BuildFilter($aFilter,$oEntitySample);
		list($sOrder,$sLimit)=$this->BuildFilterMore($aFilter,$oEntitySample);
		
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
						
		list($aFilterFields,$sFilterFields)=$this->BuildFilter($aFilter,$oEntitySample);
		list($sOrder,$sLimit)=$this->BuildFilterMore($aFilter,$oEntitySample);
				
		$sql = "SELECT count(*) as c FROM ".$sTableName." WHERE 1=1 {$sFilterFields} {$sOrder} {$sLimit} ";		
		$aQueryParams=array_merge(array($sql),array_values($aFilterFields));		
		if($aRow=call_user_func_array(array($this->oDb,'selectRow'),$aQueryParams)) {
			return $aRow['c'];
		}
		return 0;
	}
	
	public function GetItemsByJoinTable($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);
    $sPrimaryKey = $oEntitySample->_GetPrimaryKey();
    
		list($aFilterFields,$sFilterFields)=$this->BuildFilter($aFilter,$oEntitySample);
		list($sOrder,$sLimit)=$this->BuildFilterMore($aFilter,$oEntitySample);

		$sql = "SELECT a.*, b.* FROM ?# a LEFT JOIN ".$sTableName." b ON b.?# = a.?# WHERE a.?#=? {$sFilterFields} {$sOrder} {$sLimit}";
		$aQueryParams=array_merge(array($sql,$aFilter['#join_table'],$sPrimaryKey,$aFilter['#relation_key'],$aFilter['#by_key'],$aFilter['#by_value']),array_values($aFilterFields));
		
		$aItems = array();
		if($aRows=call_user_func_array(array($this->oDb,'select'),$aQueryParams)) {
			foreach($aRows as $aRow) {
				$oEntity=Engine::GetEntity($sEntityFull,$aRow);
				$oEntity->_SetIsNew(false);
				$aItems[] = $oEntity;
			}			
		}
		return $aItems;
	}
	
	
	public function BuildFilter($aFilter,$oEntitySample) {
		if (isset($aFilter['#where']) and is_array($aFilter['#where'])) {
			// '#where' => array('id = ?d OR name = ?' => array(1,'admin'));
			foreach ($aFilter['#where'] as $sFilterFields => $aValues) {
				return array($aValues,' and '. trim($sFilterFields) .' '); // возвращает первый элемент
			}			
		}

		$aFilterFields=array();
		foreach ($aFilter as $k=>$v) {
			if (substr($k,0,1)=='#') {
				
			} else {
				$aFilterFields[$oEntitySample->_getField($k)]=$v;
			}
		}		
			
		$sFilterFields='';		
		foreach ($aFilterFields as $k => $v) {			
			$aK=explode(' ',trim($k));
			$sFieldCurrent=$aK[0];
			$sConditionCurrent=' = ';
			if (count($aK)>1) {
				$sConditionCurrent=strtolower($aK[1]);
			}
			if ($sConditionCurrent=='in') {
				$sFilterFields.=" and {$sFieldCurrent} {$sConditionCurrent} ( ?a ) ";
			} else {
				$sFilterFields.=" and {$sFieldCurrent} {$sConditionCurrent} ? ";
			}
		}
		return array($aFilterFields,$sFilterFields);
	}
	
	public function BuildFilterMore($aFilter,$oEntitySample) {
		// Сортировка
		$sOrder='';
		if (isset($aFilter['#order'])) {
			if(!is_array($aFilter['#order'])) {
				$aFilter['#order'] = array($aFilter['#order']);
			}
			foreach ($aFilter['#order'] as $key=>$value) {
				if (is_numeric($key)) {
					$key=$value;
					$value='asc';
				} elseif (!in_array($value,array('asc','desc'))) {
					$value='asc';
				}
				$key = $oEntitySample->_getField($key);
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
		return array($sOrder,$sLimit);
	}
		
	public function ShowColumnsFrom($oEntity) {
		$sTableName = self::GetTableName($oEntity);
		return $this->ShowColumnsFromTable($sTableName);
	}
	
	public function ShowColumnsFromTable($sTableName) {
		if (false === ($aItems = Engine::getInstance()->Cache_GetLife("columns_table_{$sTableName}"))) {
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
			Engine::getInstance()->Cache_SetLife($aItems, "columns_table_{$sTableName}");
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