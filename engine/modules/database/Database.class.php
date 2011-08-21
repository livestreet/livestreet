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

require_once(Config::Get('path.root.engine').'/lib/external/DbSimple/Generic.php');
/**
 * Модуль для работы с базой данных
 * Создаёт объект БД библиотеки DbSimple Дмитрия Котерова
 *
 */
class ModuleDatabase extends Module {
	/**
	 * Массив инстанцируемых объектов БД, или проще говоря уникальных коннектов к БД
	 *
	 * @var array
	 */
	protected $aInstance=array();
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {		
		
	}
	/**
	 * Получает объект БД
	 *
	 * @param array $aConfig - конфиг подключения к БД(хост, логин, пароль, тип бд, имя бд)
	 * @return DbSimple
	 */
	public function GetConnect($aConfig=null) {
		/**
		 * Если конфиг не передан то используем главный конфиг БД из config.php
		 */
		if (is_null($aConfig)) {
			$aConfig = Config::Get('db.params');
		}
		$sDSN=$aConfig['type'].'wrapper://'.$aConfig['user'].':'.$aConfig['pass'].'@'.$aConfig['host'].':'.$aConfig['port'].'/'.$aConfig['dbname'];		
		/**
		 * Создаём хеш подключения, уникальный для каждого конфига
		 */
		$sDSNKey=md5($sDSN);
		/**
		 * Проверяем создавали ли уже коннект с такими параметрами подключения(DSN)
		 */
		if (isset($this->aInstance[$sDSNKey])) {
			return $this->aInstance[$sDSNKey];
		} else {
			/**
			 * Если такого коннекта еще не было то создаём его
			 */
			$oDbSimple=DbSimple_Generic::connect($sDSN);	
			/**
			 * Устанавливаем хук на перехват ошибок при работе с БД
			 */
			$oDbSimple->setErrorHandler('databaseErrorHandler');
			/**
			 * Если нужно логировать все SQL запросы то подключаем логгер
			 */
			if (Config::Get('sys.logs.sql_query')) {
				$oDbSimple->setLogger('databaseLogger');
			}
	     	/**
	     	 * Устанавливаем настройки соединения, по хорошему этого здесь не должно быть :)
	     	 * считайте это костылём
	     	 */
        	$oDbSimple->query("set character_set_client='utf8', character_set_results='utf8', collation_connection='utf8_bin' ");        	
        	/**
        	 * Сохраняем коннект
        	 */
			$this->aInstance[$sDSNKey]=$oDbSimple;	
			/**
			 * Возвращаем коннект
			 */
			return $oDbSimple;
		}		
	}
	
	
	public function GetStats() {
		$aQueryStats=array('time'=>0,'count'=>-1); // не считаем тот самый костыльный запрос, который устанавливает настройки DB соединения
		foreach ($this->aInstance as $oDb) {
			$aStats=$oDb->_statistics;
			$aQueryStats['time']+=$aStats['time'];
			$aQueryStats['count']+=$aStats['count'];
		}
		$aQueryStats['time']=round($aQueryStats['time'],3);
		return $aQueryStats;
	}
	
	/**
	 * Экспорт SQL дампа
	 *
	 * @param unknown_type $sFilePath
	 * @param unknown_type $aConfig
	 * @return unknown
	 */
	public function ExportSQL($sFilePath,$aConfig=null) {
		if(!is_file($sFilePath)){
			return array('result'=>false,'errors'=>array("cant find file '$sFilePath'"));
		}elseif(!is_readable($sFilePath)){
			return array('result'=>false,'errors'=>array("cant read file '$sFilePath'"));
		}
		$sFileQuery = file_get_contents($sFilePath);
		return $this->ExportSQLQuery($sFileQuery,$aConfig);
	}
	
	/**
	 * Экспорт SQL
	 *
	 * @param unknown_type $sFileQuery
	 * @param unknown_type $aConfig
	 * @return unknown
	 */
	public function ExportSQLQuery($sFileQuery,$aConfig=null) {
		/**
		 * Замена префикса таблиц
		 */
		$sFileQuery = str_replace('prefix_', Config::Get('db.table.prefix'), $sFileQuery);

		/**
		 * Массивы запросов и пустой контейнер для сбора ошибок
		 */
		$aErrors = array();
		$aQuery=explode(';',$sFileQuery);
		/**
		 * Выполняем запросы по очереди
		 */
		foreach($aQuery as $sQuery){
			$sQuery = trim($sQuery);
			/**
			 * Заменяем движек, если таковой указан в запросе
			 */
			if(Config::Get('db.tables.engine')!='InnoDB') $sQuery=str_ireplace('ENGINE=InnoDB', "ENGINE=".Config::Get('db.tables.engine'),$sQuery);
			
			if($sQuery!='') {
				$bResult=$this->GetConnect($aConfig)->query($sQuery);
				if($bResult===false) $aErrors[] = mysql_error();
			}
		}

		/**
		 * Возвращаем результат выполнения, взависимости от количества ошибок 
		 */
		if(count($aErrors)==0) {
			return array('result'=>true,'errors'=>null);
		}
		return array('result'=>false,'errors'=>$aErrors);
	}
	
	/**
	 * Проверяет существование таблицы
	 *
	 * @param string $sTableName
	 * @param array $aConfig
	 * @return bool
	 */
	public function isTableExists($sTableName,$aConfig=null) {
		$sTableName = str_replace('prefix_', Config::Get('db.table.prefix'), $sTableName);
		$sQuery="SHOW TABLES LIKE '{$sTableName}'";
		if ($aRows=$this->GetConnect($aConfig)->select($sQuery)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Проверяет существование поля в таблице
	 *
	 * @param string $sTableName
	 * @param string $sFieldName
	 * @param array $aConfig
	 * @return bool
	 */
	public function isFieldExists($sTableName,$sFieldName,$aConfig=null) {
		$sTableName = str_replace('prefix_', Config::Get('db.table.prefix'), $sTableName);
		$sQuery="SHOW FIELDS FROM `{$sTableName}`";
		if ($aRows=$this->GetConnect($aConfig)->select($sQuery)) {
			foreach ($aRows as $aRow){
				if ($aRow['Field'] == $sFieldName){
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * Доавляет новый тип в поле таблицы с типом enum
	 *
	 * @param string $sTableName
	 * @param string $sFieldName
	 * @param string $sType
	 * @param array $aConfig
	 */
	public function addEnumType($sTableName,$sFieldName,$sType,$aConfig=null) {
		$sTableName = str_replace('prefix_', Config::Get('db.table.prefix'), $sTableName);		
		$sQuery="SHOW COLUMNS FROM  `{$sTableName}`";
		
		if ($aRows=$this->GetConnect($aConfig)->select($sQuery)) {
			foreach ($aRows as $aRow){
				if ($aRow['Field'] == $sFieldName) break;
			}
			if (strpos($aRow['Type'], "'{$sType}'") === FALSE) {
				$aRow['Type'] =str_ireplace('enum(', "enum('{$sType}',", $aRow['Type']);
				$sQuery="ALTER TABLE `{$sTableName}` MODIFY `{$sFieldName}` ".$aRow['Type'];
				$sQuery.= ($aRow['Null']=='NO') ? ' NOT NULL ' : ' NULL ';
				$sQuery.= is_null($aRow['Default']) ? ' DEFAULT NULL ' : " DEFAULT '{$aRow['Default']}' ";
				$this->GetConnect($aConfig)->select($sQuery);
			}
		}
	}
		
}

/**
 * Функция хука для перехвата SQL ошибок
 *
 * @param string $message
 * @param unknown_type $info
 */
function databaseErrorHandler($message, $info) {	
	/**
	 * Записываем информацию об ошибке в переменную $msg
	 */	
	$msg="SQL Error: $message<br>\n";	
	$msg.=print_r($info,true);
	/**
	 * Если нужно логировать SQL ошибке то пишем их в лог
	 */
	if (Config::Get('sys.logs.sql_error')) {
		/**
		 * Получаем ядро
		 */
		$oEngine=Engine::getInstance();
		/**
		 * Меняем имя файла лога на нужное, записываем в него ошибку и меняем имя обратно :)
		 */
		$sOldName=$oEngine->Logger_GetFileName();
		$oEngine->Logger_SetFileName(Config::Get('sys.logs.sql_error_file'));
		$oEngine->Logger_Error($msg);
		$oEngine->Logger_SetFileName($sOldName);
	}
	/**
	 * Если стоит вывод ошибок то выводим ошибку на экран(браузер)
	 */
	if (error_reporting()) {
		exit($msg);
	}
}

/**
 * Функция логгирования SQL запросов
 *
 * @param object $db
 * @param unknown_type $sql
 */
function databaseLogger($db, $sql) {
	/**
	 * Получаем информацию о запросе и сохраняем её в переменной $msg
	 */
	$caller = $db->findLibraryCaller();	
	$msg=print_r($sql,true);	
	/**
	 * Получаем ядро и сохраняем в логе SQL запрос
	 */
	$oEngine=Engine::getInstance();
	$sOldName=$oEngine->Logger_GetFileName();
	$oEngine->Logger_SetFileName(Config::Get('sys.logs.sql_query_file'));
	$oEngine->Logger_Debug($msg);
	$oEngine->Logger_SetFileName($sOldName);
}
?>