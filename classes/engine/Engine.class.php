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
		$this->Init();
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
	protected function Init() {		
		$this->LoadModules();				
	}
	
	/**
	 * Производит инициализацию всех модулей
	 *
	 */
	public function InitModules() {
		foreach ($this->aModules as $oModule) {
			$oModule->Init();
		}
	}
	
	/**
	 * Завершаем работу всех модулей
	 *
	 */
	public function ShutdownModules() {
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
		if (file_exists(DIR_SERVER_ROOT."/classes/modules/".strtolower($sModuleName)."/".$sModuleName.".class.php")) {
			require_once(DIR_SERVER_ROOT."/classes/modules/".strtolower($sModuleName)."/".$sModuleName.".class.php");
		} elseif (file_exists(DIR_SERVER_ROOT."/classes/modules/sys_".strtolower($sModuleName)."/".$sModuleName.".class.php")) {
			require_once(DIR_SERVER_ROOT."/classes/modules/sys_".strtolower($sModuleName)."/".$sModuleName.".class.php");
		} else {
			throw new Exception($this->Lang_Get('system_error_module')." - ".$sModuleName);
		}
		$sModuleNameClass='Ls'.$sModuleName;
		$oModule=new $sModuleNameClass($this);
		if ($bInit) {
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
	public function InitHooks() {
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
		
		$sCmd='$result=$oModule->'.$aName[1].'('.$sArgs.');';						
		eval($sCmd);									
		return $result;
		
		/*
		$sCmd='$bExists=isset($this->MOD_'.$sModuleName.');';
		eval($sCmd);
		if ($bExists) {			
			$sCmd='$bExists=method_exists($this->MOD_'.$sModuleName.',"'.$aName[1].'");';
			eval($sCmd);			
			if ($bExists) {
				$sCmd='$result=$this->MOD_'.$sModuleName.'->'.$aName[1].'('.$sArgs.');';				
				eval($sCmd);								
				return $result;
			}
			throw new Exception('В модуле '.$sModuleName.' нет метода '.$aName[1].'()');			
		} else {			
			throw new Exception("Не найден модуль: ".$sModuleName);
		}
		*/
		
		return false;
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