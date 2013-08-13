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
 * Системный класс мапера ORM для работы с БД
 *
 * @package engine.orm
 * @since 1.0
 */
class MapperORM extends Mapper {
	/**
	 * Добавление сущности в БД
	 *
	 * @param EntityORM $oEntity	Объект сущности
	 * @return int|bool	Если есть primary индекс с автоинкрементом, то возвращает его для новой записи
	 */
	public function AddEntity($oEntity) {
		$sTableName = self::GetTableName($oEntity);

		$sql = "INSERT INTO ".$sTableName." SET ?a ";
		return $this->oDb->query($sql,$oEntity->_getDataFields());
	}
	/**
	 * Обновление сущности
	 *
	 * @param EntityORM $oEntity	Объект сущности
	 * @return int|bool	Возвращает число измененых записей в БД
	 */
	public function UpdateEntity($oEntity) {
		$sTableName = self::GetTableName($oEntity);

		if($aPrimaryKey=$oEntity->_getPrimaryKey()) {
			// Возможен составной ключ
			if (!is_array($aPrimaryKey)) {
				$aPrimaryKey=array($aPrimaryKey);
			}
			$sWhere=' 1 = 1 ';
			foreach ($aPrimaryKey as $sField) {
				$sWhere.=' and '.$this->oDb->escape($sField,true)." = ".$this->oDb->escape($oEntity->_getDataOne($sField));
			}
			$sql = "UPDATE ".$sTableName." SET ?a WHERE {$sWhere}";
			return $this->oDb->query($sql,$oEntity->_getDataFields());
		} else {
			$aOriginalData = $oEntity->_getOriginalData();
			$sWhere = implode(' AND ',array_map(create_function(
													'$k,$v,$oDb',
													'return "{$oDb->escape($k,true)} = {$oDb->escape($v)}";'
												),array_keys($aOriginalData),array_values($aOriginalData),array_fill(0,count($aOriginalData),$this->oDb)));
			$sql = "UPDATE ".$sTableName." SET ?a WHERE 1=1 AND ". $sWhere;
			return $this->oDb->query($sql,$oEntity->_getDataFields());
		}
	}
	/**
	 * Удаление сущности
	 *
	 * @param EntityORM $oEntity	Объект сущности
	 * @return int|bool	Возвращает число удаленных записей в БД
	 */
	public function DeleteEntity($oEntity) {
		$sTableName = self::GetTableName($oEntity);

		if($aPrimaryKey=$oEntity->_getPrimaryKey()) {
			// Возможен составной ключ
			if (!is_array($aPrimaryKey)) {
				$aPrimaryKey=array($aPrimaryKey);
			}
			$sWhere=' 1 = 1 ';
			foreach ($aPrimaryKey as $sField) {
				$sWhere.=' and '.$this->oDb->escape($sField,true)." = ".$this->oDb->escape($oEntity->_getDataOne($sField));
			}
			$sql = "DELETE FROM ".$sTableName." WHERE {$sWhere}";
			return $this->oDb->query($sql);
		} else {
			$aOriginalData = $oEntity->_getOriginalData();
			$sWhere = implode(' AND ',array_map(create_function(
													'$k,$v,$oDb',
													'return "{$oDb->escape($k,true)} = {$oDb->escape($v)}";'
												),array_keys($aOriginalData),array_values($aOriginalData),array_fill(0,count($aOriginalData),$this->oDb)));
			$sql = "DELETE FROM ".$sTableName." WHERE 1=1 AND ". $sWhere;
			return $this->oDb->query($sql);
		}
	}
	/**
	 * Получение сущности по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @param string $sEntityFull	Название класса сущности
	 * @return EntityORM|null
	 */
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
		return null;
	}
	/**
	 * Получение списка сущностей по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @param string $sEntityFull	Название класса сущности
	 * @return array
	 */
	public function GetItemsByFilter($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);

		list($aFilterFields,$sFilterFields)=$this->BuildFilter($aFilter,$oEntitySample);
		list($sOrder,$sLimit,$sGroup)=$this->BuildFilterMore($aFilter,$oEntitySample);

		$sql = "SELECT * FROM ".$sTableName." WHERE 1=1 {$sFilterFields} {$sGroup} {$sOrder} {$sLimit} ";
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
	/**
	 * Получение числа сущностей по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @param string $sEntityFull	Название класса сущности
	 * @return int
	 */
	public function GetCountItemsByFilter($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);

		list($aFilterFields,$sFilterFields)=$this->BuildFilter($aFilter,$oEntitySample);
		list($sOrder,$sLimit,$sGroup)=$this->BuildFilterMore($aFilter,$oEntitySample);

		if ($sGroup) {
			/**
			 * Т.к. count меняет свою логику при наличии группировки
			 */
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `".$sTableName."` WHERE 1=1 {$sFilterFields} {$sGroup} ";
		} else {
			$sql = "SELECT count(*) as c FROM ".$sTableName." WHERE 1=1 {$sFilterFields} {$sGroup} ";
		}
		$aQueryParams=array_merge(array($sql),array_values($aFilterFields));
		if($aRow=call_user_func_array(array($this->oDb,'selectRow'),$aQueryParams)) {
			if ($sGroup) {
				$aRow=$this->oDb->selectRow('SELECT FOUND_ROWS() as c;');
			}
			return $aRow['c'];
		}
		return 0;
	}
	/**
	 * Получение сущностей по связанной таблице
	 *
	 * @param array $aFilter	Фильтр
	 * @param string $sEntityFull	Название класса сущности
	 * @return array
	 */
	public function GetItemsByJoinTable($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);
		$sTableName = self::GetTableName($sEntityFull);
		$sPrimaryKey = $oEntitySample->_getPrimaryKey();

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

	/**
	 * Получение числа сущностей по связанной таблице
	 *
	 * @param array $aFilter	Фильтр
	 * @param string $sEntityFull	Название класса сущности
	 * @return int
	 */
	public function GetCountItemsByJoinTable($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);
		list($aFilterFields,$sFilterFields)=$this->BuildFilter($aFilter,$oEntitySample);

		$sql = "SELECT count(*) as c FROM ?# a  WHERE a.?#=? {$sFilterFields}";
		$aQueryParams=array_merge(array($sql,$aFilter['#join_table'],$aFilter['#by_key'],$aFilter['#by_value']),array_values($aFilterFields));

		if($aRow=call_user_func_array(array($this->oDb,'selectRow'),$aQueryParams)) {
			return $aRow['c'];
		}
		return 0;
	}
	/**
	 * Построение фильтра
	 *
	 * @param array $aFilter	Фильтр
	 * @param EntityORM $oEntitySample	Объект сущности
	 * @return array
	 */
	public function BuildFilter($aFilter,$oEntitySample) {
		$aFilterFields=array();
		foreach ($aFilter as $k=>$v) {
			if (substr($k,0,1)=='#' || (is_string($v) && substr($v,0,1)=='#')) {

			} else {
				$aFilterFields[$oEntitySample->_getField($k)]=$v;
			}
		}

		$sFilterFields='';
		foreach ($aFilterFields as $k => $v) {
			$aK=explode(' ',trim($k));
			$sFieldCurrent=$this->oDb->escape($aK[0],true);
			$sConditionCurrent=' = ';
			if (count($aK)>1) {
				$sConditionCurrent=strtolower($aK[1]);
			}
			if (strtolower($sConditionCurrent)=='in') {
				$sFilterFields.=" and {$sFieldCurrent} {$sConditionCurrent} ( ?a ) ";
			} else {
				$sFilterFields.=" and {$sFieldCurrent} {$sConditionCurrent} ? ";
			}
		}
		if (isset($aFilter['#where']) and is_array($aFilter['#where'])) {
			// '#where' => array('id = ?d OR name = ?' => array(1,'admin'));
			foreach ($aFilter['#where'] as $sFilterKey => $aValues) {
				$aFilterFields = array_merge($aFilterFields, $aValues);
				$sFilterFields .= ' and '. trim($sFilterKey) .' ';
			}
		}
		return array($aFilterFields,$sFilterFields);
	}
	/**
	 * Построение дополнительного фильтра
	 * Здесь учитываются ключи фильтра вида #*
	 *
	 * @param array $aFilter	Фильтр
	 * @param EntityORM $oEntitySample	Объект сущности
	 * @return array
	 */
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
				$key = $this->oDb->escape($oEntitySample->_getField($key),true);
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

		// Группировка
		$sGroup='';
		if (isset($aFilter['#group'])) {
			if(!is_array($aFilter['#group'])) {
				$aFilter['#group'] = array($aFilter['#group']);
			}
			foreach ($aFilter['#group'] as $sField) {
				$sField = $this->oDb->escape($oEntitySample->_getField($sField),true);
				$sGroup.=" {$sField},";
			}
			$sGroup=trim($sGroup,',');
			if ($sGroup!='') {
				$sGroup="GROUP BY {$sGroup}";
			}
		}
		return array($sOrder,$sLimit,$sGroup);
	}
	/**
	 * Список колонок/полей сущности
	 *
	 * @param EntityORM $oEntity	Объект сущности
	 * @return array
	 */
	public function ShowColumnsFrom($oEntity) {
		$sTableName = self::GetTableName($oEntity);
		return $this->ShowColumnsFromTable($sTableName);
	}
	/**
	 * Список колонок/полей таблицы
	 *
	 * @param string $sTableName	Название таблицы
	 * @return array
	 */
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
	/**
	 * Primary индекс сущности
	 *
	 * @param EntityORM $oEntity	Объект сущности
	 * @return array
	 */
	public function ShowPrimaryIndexFrom($oEntity) {
		$sTableName = self::GetTableName($oEntity);
		return $this->ShowPrimaryIndexFromTable($sTableName);
	}
	/**
	 * Primary индекс таблицы
	 *
	 * @param string $sTableName	Название таблицы
	 * @return array
	 */
	public function ShowPrimaryIndexFromTable($sTableName) {
		if (false === ($aItems = Engine::getInstance()->Cache_GetLife("index_table_{$sTableName}"))) {
			$sql = "SHOW INDEX FROM ".$sTableName;
			$aItems = array();
			if($aRows=$this->oDb->select($sql)) {
				foreach($aRows as $aRow) {
					if ($aRow['Key_name']=='PRIMARY') {
						$aItems[$aRow['Seq_in_index']]=$aRow['Column_name'];
					}
				}
			}
			Engine::getInstance()->Cache_SetLife($aItems, "index_table_{$sTableName}");
		}
		return $aItems;
	}
	/**
	 * Возвращает имя таблицы для сущности
	 *
	 * @param EntityORM $oEntity	Объект сущности
	 * @return string
	 */
	public static function GetTableName($oEntity) {
		/**
		 * Варианты таблиц:
		 * 	prefix_user -> если модуль совпадает с сущностью
		 * 	prefix_user_invite -> если модуль не сопадает с сущностью
		 * Если сущность плагина:
		 * 	prefix_pluginname_user
		 * 	prefix_pluginname_user_invite
		 */
		$sClass = Engine::getInstance()->Plugin_GetDelegater('entity', is_object($oEntity)?get_class($oEntity):$oEntity);
		$sPluginName = func_underscore(Engine::GetPluginName($sClass));
		$sModuleName = func_underscore(Engine::GetModuleName($sClass));
		$sEntityName = func_underscore(Engine::GetEntityName($sClass));
		if (strpos($sEntityName,$sModuleName)===0) {
			$sTable=func_underscore($sEntityName);
		} else {
			$sTable=func_underscore($sModuleName).'_'.func_underscore($sEntityName);
		}
		if ($sPluginName) {
			$sTablePlugin=$sPluginName.'_'.$sTable;
			/**
			 * Для обратной совместимости с 1.0.1
			 * Если такая таблица определена в конфиге, то ок, если нет, то используем старый вариант без имени плагина
			 */
			if (Config::Get('db.table.'.$sTablePlugin)) {
				$sTable=$sTablePlugin;
			}
		}
		/**
		 * Если название таблиц переопределено в конфиге, то возвращаем его
		 */
		if(Config::Get('db.table.'.$sTable)) {
			return Config::Get('db.table.'.$sTable);
		} else {
			return Config::Get('db.table.prefix').$sTable;
		}
	}
	/**
	 * Загрузка данных из таблицы связи many_to_many
	 *
	 * @param string $sDbTableAlias Алиас имени таблицы связи, например, 'db.table.my_relation'
	 * @param string $sEntityKey Название поля в таблице связи с id сущности, для которой зегружаются объекты.
	 * @param int $iEntityId Id сущнсоти, для который загружаются объекты
	 * @param string $sRelationKey Название поля в таблице связи с id сущности, объекты которой загружаются по связи.
	 * @return array Список id из столбца $sRelationKey, у которых столбец $sEntityKey = $iEntityId
	 */
	public function getManyToManySet($sDbTableAlias, $sEntityKey, $iEntityId, $sRelationKey) {
		if (!Config::Get($sDbTableAlias)) return array();
		$sql = 'SELECT ?# FROM '.Config::Get($sDbTableAlias).' WHERE ?# = ?d';
		return $this->oDb->selectCol($sql, $sRelationKey, $sEntityKey, $iEntityId);
	}
	/**
	 * Обновление связи many_to_many
	 *
	 * @param string $sDbTableAlias Алиас имени таблицы связи
	 * @param string $sEntityKey Название поля в таблице связи с id сущности, для которой обновляются связи.
	 * @param int $iEntityId Id сущнсоти, для который обновляются связи
	 * @param string $sRelationKey Название поля в таблице связи с id сущности, с объектами которой назначаются связи.
	 * @param array $aInsertSet Массив id для $sRelationKey, которые нужно добавить
	 * @param array $aDeleteSet Массив id для $sRelationKey, которые нужно удалить
	 * @return bool
	 */
	public function updateManyToManySet($sDbTableAlias, $sEntityKey, $iEntityId, $sRelationKey, $aInsertSet, $aDeleteSet) {
		if (!Config::Get($sDbTableAlias)) return false;
		if (count($aDeleteSet)) {
			$sql = 'DELETE FROM '.Config::Get($sDbTableAlias).' WHERE ?# = ?d AND ?# IN (?a)';
			$this->oDb->query($sql,  $sEntityKey, $iEntityId, $sRelationKey, $aDeleteSet);
		}

		if (count($aInsertSet)) {
			$sql = 'INSERT INTO '.Config::Get($sDbTableAlias).' (?#,?#) VALUES ';
			$aParams = array();
			foreach ($aInsertSet as $iId) {
				$sql .= '(?d, ?d), ';
				$aParams[] = $iEntityId;
				$aParams[] = $iId;
			}
			$sql = substr($sql, 0, -2); // удаление последних ", "
			call_user_func_array(array($this->oDb, 'query'), array_merge(array($sql,$sEntityKey, $sRelationKey), $aParams));
		}
		return true;
	}
	/**
	 * Удаление связей many_to_many для объекта. Используется при удалении сущности,
	 * чтобы не удалять большие коллекции связанных объектов через updateManyToManySet(),
	 * где используется IN.
	 *
	 * @param string $sDbTableAlias Алиас имени таблицы связи
	 * @param string $sEntityKey Название поля в таблице связи с id сущности, для которой удаляются связи.
	 * @param int $iEntityId Id сущнсоти, для который удаляются связи
	 * @return bool
	 */
	public function deleteManyToManySet($sDbTableAlias, $sEntityKey, $iEntityId) {
		if (!Config::Get($sDbTableAlias)) return false;
		$sql = 'DELETE FROM '.Config::Get($sDbTableAlias).' WHERE ?# = ?d';
		$this->oDb->query($sql, $sEntityKey, $iEntityId);
		return true;
	}
}