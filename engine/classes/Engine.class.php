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

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
require_once(DIR_SERVER_ENGINE.'/lib/internal/ProfilerSimple/Profiler.class.php');
require_once("Object.class.php");
require_once("Block.class.php");
require_once("Hook.class.php");
require_once("Module.class.php");
require_once("Router.class.php");

require_once("Entity.class.php");
require_once("Mapper.class.php");

/**
 * Основной класс движка, который позволяет напрямую обращаться к любому модулю
 *
 */
class Engine extends Object {
	
	static protected $oInstance=null;
	
	protected $aModules=array();
	protected $aConfigModule;
	public $iTimeLoadModule=0;
	
	
	/**
	 * При создании объекта делаем инициализацию
	 *
	 */
	protected function __construct() {
		if (get_magic_quotes_gpc()) {
			func_stripslashes($_REQUEST);
		}
	}
	
	
	/**
	 * Ограничиваем объект только одним экземпляром
	 *
	 * @return Engine
	 */
	static public function getInstance() {
		if (isset(self::$oInstance) and (self::$oInstance instanceof self)) {
			return self::$oInstance;
		} else {
			self::$oInstance= new self();
			return self::$oInstance;
		}
	}
	
	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->LoadModules();
		$this->InitModules();
		$this->InitHooks();
	}
	/**
	 * Завершение работы модуля
	 *
	 */
	public function Shutdown() {
		$this->ShutdownModules();
	}
	/**
	 * Производит инициализацию всех модулей
	 *
	 */
	protected function InitModules() {
		foreach ($this->aModules as $oModule) {
			$oModule->Init();
		}
	}
	
	/**
	 * Завершаем работу всех модулей
	 *
	 */
	protected function ShutdownModules() {
		foreach ($this->aModules as $sKey => $oModule) {			
			$oModule->Shutdown();
		}
	}
	/**
	 * Выполняет загрузку модуля по его названию
	 *
	 * @param string $sModuleName
	 * @param bool $bInit - инициализировать модуль или нет
	 * @return unknown
	 */
	protected function LoadModule($sModuleName,$bInit=false) {
		$tm1=microtime(true);		
		if ($this->isFileExists(DIR_SERVER_ENGINE."/modules/".strtolower($sModuleName)."/".$sModuleName.".class.php")) {
			require_once(DIR_SERVER_ENGINE."/modules/".strtolower($sModuleName)."/".$sModuleName.".class.php");			
		} elseif ($this->isFileExists(DIR_SERVER_ROOT."/classes/modules/".strtolower($sModuleName)."/".$sModuleName.".class.php")) {
			require_once(DIR_SERVER_ROOT."/classes/modules/".strtolower($sModuleName)."/".$sModuleName.".class.php");
		} else {
			throw new Exception($this->Lang_Get('system_error_module')." - ".$sModuleName);
		}		
		/**
		 * Проверяем наличие кастомного класса. Также можно переопределить системный модуль
		 */
		$sPrefixCustom='';
		if ($this->isFileExists(DIR_SERVER_ROOT."/classes/modules/".strtolower($sModuleName)."/".$sModuleName.".class.custom.php")) {
			require_once(DIR_SERVER_ROOT."/classes/modules/".strtolower($sModuleName)."/".$sModuleName.".class.custom.php");
			$sPrefixCustom='_custom';
		}
		/**
		 * Создаем объект модуля
		 */
		$sModuleNameClass='Ls'.$sModuleName.$sPrefixCustom;
		$oModule=new $sModuleNameClass($this);
		if ($bInit or $sModuleName=='Cache') {
			$oModule->Init();
		}
		$this->aModules[$sModuleName]=$oModule;
		$tm2=microtime(true);
		$this->iTimeLoadModule+=$tm2-$tm1;
		dump("load $sModuleName - \t\t".($tm2-$tm1)."");
		return $oModule;
	}
	
	/**
	 * Загружает все используемые модули и передает им в конструктор ядро
	 *
	 */
	protected function LoadModules() {
		$this->LoadConfig();
		foreach ($this->aConfigModule['autoLoad'] as $sModuleName) {
			$this->LoadModule($sModuleName);
		}
	}
	/**
	 * Выполняет загрузку конфигов
	 *
	 */
	protected function LoadConfig() {
		$this->aConfigModule=include(DIR_SERVER_ROOT."/config/config.module.php");
		/**
		 * Ищет конфиги модулей и объединяет их с текущим
		 */
		$sDirConfig=DIR_SERVER_ROOT.'/config/modules/';
		if ($hDirConfig = opendir($sDirConfig)) {
			while (false !== ($sDirModule = readdir($hDirConfig))) {
				if ($sDirModule !='.' and $sDirModule !='..' and is_dir($sDirConfig.$sDirModule)) {
					$sFileConfig=$sDirConfig.$sDirModule.'/config.module.php';
					if (file_exists($sFileConfig)) {
						$aConfigModule=include($sFileConfig);
						$this->aConfigModule=array_merge_recursive($this->aConfigModule,$aConfigModule);
					}					
				}
			}
			closedir($hDirConfig);
		}
	}
	/**
	 * Регистрирует хуки из /classes/hooks/
	 *
	 */
	protected function InitHooks() {
		$sDirHooks=DIR_SERVER_ROOT.'/classes/hooks/';
		if ($hDir = opendir($sDirHooks)) {
			while (false !== ($sFile = readdir($hDir))) {
				if ($sFile !='.' and $sFile !='..' and is_file($sDirHooks.$sFile)) {
					if (preg_match("/^Hook([\w]+)\.class\.php$/i",$sFile,$aMatch)) {
						require_once($sDirHooks.$sFile);
						$sClassName='Hook'.$aMatch[1];
						$oHook=new $sClassName;
						$oHook->RegisterHook();
					}										
				}
			}
			closedir($hDir);
		}
	}
	/**
	 * Проверяет файл на существование, если используется кеширование memcache то кеширует результат работы
	 *
	 * @param unknown_type $sFile
	 * @return unknown
	 */
	public function isFileExists($sFile,$iTime=3600) {	return file_exists($sFile);	
		if (strpos($sFile,'/Cache.class.')!==false) {
			return file_exists($sFile);
		}
		if (SYS_CACHE_USE and SYS_CACHE_TYPE==SYS_CACHE_TYPE_MEMORY) {
			if (false === ($data = $this->Cache_Get("file_exists_{$sFile}"))) {
				$data=file_exists($sFile);
				$this->Cache_Set((int)$data, "file_exists_{$sFile}", array(), $iTime);
			}
			return $data;
		} else {
			return file_exists($sFile);
		}
	}
	/**
	 * Вызывает метод нужного модуля
	 *
	 * @param string $sName
	 * @param array $aArgs
	 * @return unknown
	 */
	public function _CallModule($sName,$aArgs) {				
		$sArgs='';
		$aStrArgs=array();
		foreach ($aArgs as $sKey => $arg) {
			$aStrArgs[]='$aArgs['.$sKey.']';
		}
		$sArgs=join(',',$aStrArgs);
		
		$aName=explode("_",$sName);
		
		$sModuleName=$aName[0];
		if (isset($this->aModules[$sModuleName])) {
			$oModule=$this->aModules[$sModuleName];
		} else {
			$oModule=$this->LoadModule($sModuleName,true);			
		}		
		if (!method_exists($oModule,$aName[1])) {
			throw new Exception($this->Lang_Get('system_error_module_no_method').": ".$sModuleName.'->'.$aName[1].'()');
		}		
		$sCmd='$result=$oModule->'.$aName[1].'('.$sArgs.');';
		$oProfiler=ProfilerSimple::getInstance();
		$iTimeId=$oProfiler->Start('callModule',$sModuleName.'->'.$aName[1].'()');					
		eval($sCmd);
		$oProfiler->Stop($iTimeId);
		return $result;		
	}

	public function getStats() {
		return array('sql'=>$this->Database_GetStats(),'cache'=>$this->Cache_GetStats(),'engine'=>array('time_load_module'=>round($this->iTimeLoadModule,3)));
	}

	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 *
	 * @param string $sName
	 * @param array $aArgs
	 * @return unknown
	 */
	public function __call($sName,$aArgs) {
		return $this->_CallModule($sName,$aArgs);
	}
	
	/**
	 * Блокируем копирование/клонирование объекта роутинга
	 *
	 */
	protected function __clone() {
		
	}
}

/**
 * Автозагрузка кслассов
 *
 * @param unknown_type $sClassName
 */
function __autoload($sClassName) {
	/**
	 * Если класс подходит под шблон класса сущности то згружаем его
	 */
	if (preg_match("/^(\w+)Entity\_(\w+)$/i",$sClassName,$aMatch)) {	
		$tm1=microtime(true);	
		$sFileClass=DIR_SERVER_ROOT.'/classes/modules/'.strtolower($aMatch[1]).'/entity/'.$aMatch[2].'.entity.class.php';
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
			$tm2=microtime(true);			
			dump($sClassName." - \t\t".($tm2-$tm1));
		}
	}
    
}
?>