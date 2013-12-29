<?php

/**
 * Обертка для объекта коннекта к БД
 * Позволяет определить тип запроса и выбрать нужный инстанс реплики.
 * Изначально объект $this->oDbSimple всегда мастер, в момент запроса к slave мы его заменяем, а после запроса возвращаем обратно.
 *
 * Общая логика:
 * 	1. определяем тип запроса - чтение или запись
 * 	2. чтение таблиц - если еще не выбран инстанс slave, то делаем его выборку. При условии, что еще не выбран инстанс master(не было запроса на запись в эти таблицы)
 * 	3. запись в таблицу - если еще не выбран инстанс master, то делаем его выборку, далее запрещаем его менять на slave(последующие запросы к этим таблицам в рамках сеанса отправляем только на него)
 */
class ModuleDatabase_DbSimpleWrapper {

	const REPLICA_TYPE_MASTER=1;
	const REPLICA_TYPE_SLAVE=2;

	protected $oDbSimple=null;

	public function __construct($oDbSimple) {
		$this->oDbSimple=$oDbSimple;
	}

	public function __getDbSimple() {
		return $this->oDbSimple;
	}

	public function __getTables($sSql) {
		if (preg_match_all('#((prefix_)|(\?_))([a-z0-9_]+)#i',$sSql,$aMatch)) {
			return $aMatch[4];
		}
		return array('_unknown_');
	}

	/**
	 * Проверяет необходимость запроса на slave и возврашает его
	 *
	 * @param $aTables
	 * @param $aDsn
	 *
	 * @return null
	 */
	public function __getInstanceSlave($aTables,$aDsn) {
		if (!isset($aDsn['replication']['slave']) or !count($aDsn['replication']['slave'])) {
			return null;
		}
		$sHash=md5(serialize($aDsn));
		/**
		 * Смотрим на необходимость мастера для таблицы
		 */
		$aReplicaMasterByTable=Engine::getInstance()->Database_GetReplicaMasterByTable();
		foreach($aTables as $sTable) {
			if (isset($aReplicaMasterByTable[$sHash][$sTable])) {
				return null;
			}
		}

		if ($oSlave=Engine::getInstance()->Database_GetReplicaInstanceSlaveByHash($sHash)) {
			return $oSlave;
		}
		$iKey=array_rand($aDsn['replication']['slave']);
		$oSlave=Engine::getInstance()->Database_GetConnect($aDsn['replication']['slave'][$iKey]);
		$oSlave=$oSlave->__getDbSimple();
		Engine::getInstance()->Database_SetReplicaInstanceSlave($sHash,$oSlave);
		return $oSlave;
	}

	public function __select($args,$sMethod) {
		$aTables=$this->__getTables(isset($args[0]) ? $args[0] : '');
		if ($oDbSlave=$this->__getInstanceSlave($aTables,$this->oDbSimple->getDsnParsed())) {
			$oDbOld=$this->oDbSimple;
			$this->oDbSimple=$oDbSlave;
		}
		$mResult=call_user_func_array(array($this->oDbSimple,$sMethod),$args);
		if (isset($oDbOld)) {
			$this->oDbSimple=$oDbOld;
		}
		return $mResult;
	}

	public function query() {
		$args = func_get_args();

		$aDsn=$this->oDbSimple->getDsnParsed();
		if (isset($aDsn['replication']['slave']) and count($aDsn['replication']['slave'])) {
			$aTables=$this->__getTables(isset($args[0]) ? $args[0] : '');
			$sHash=md5(serialize($aDsn));
			$aReplicaMasterByTable=Engine::getInstance()->Database_GetReplicaMasterByTable();
			foreach($aTables as $sTable) {
				if (!isset($aReplicaMasterByTable[$sHash][$sTable])) {
					$aReplicaMasterByTable[$sHash][$sTable]=true;
				}
			}
			Engine::getInstance()->Database_SetReplicaMasterByTable($aReplicaMasterByTable);
		}

		return call_user_func_array(array($this->oDbSimple,'query'),$args);
	}

	public function select() {
		$args = func_get_args();
		return $this->__select($args,__FUNCTION__);
	}



	public function selectRow() {
		$args = func_get_args();
		return $this->__select($args,__FUNCTION__);
	}

	public function selectCol() {
		$args = func_get_args();
		return $this->__select($args,__FUNCTION__);
	}

	public function selectCell() {
		$args = func_get_args();
		return $this->__select($args,__FUNCTION__);
	}

	public function selectPage(&$total, $query) {
		$args = func_get_args();
		array_shift($args);
		$total = true;

		$aTables=$this->__getTables(isset($args[0]) ? $args[0] : '');
		if ($oDbSlave=$this->__getInstanceSlave($aTables,$this->oDbSimple->getDsnParsed())) {
			$oDbOld=$this->oDbSimple;
			$this->oDbSimple=$oDbSlave;
		}
		$mResult=$this->oDbSimple->_query($args, $total);
		if (isset($oDbOld)) {
			$this->oDbSimple=$oDbOld;
		}
		return $mResult;
	}

	public function __call($sName,$aArgs) {
		return call_user_func_array(array($this->oDbSimple,$sName),$aArgs);
	}
}