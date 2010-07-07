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
require_once(Config::Get('path.root.engine').'/lib/internal/ProfilerSimple/Profiler.class.php');

require_once("Object.class.php");
require_once("Plugin.class.php");
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
	protected $aPlugins=array();
	protected $aConfigModule;
	public $iTimeLoadModule=0;
	protected $iTimeInit=null;
	
		
	/**
	 * При создании объекта делаем инициализацию
	 *
	 */
	protected function __construct() {
		$this->iTimeInit=microtime(true);
		if (get_magic_quotes_gpc()) {
			func_stripslashes($_REQUEST);
			func_stripslashes($_GET);
			func_stripslashes($_POST);
			func_stripslashes($_COOKIE);
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
		/**
		 * Загружаем плагины
		 */
		$this->LoadPlugins();
		/**
		 * Инициализируем хуки
		 */
		$this->InitHooks();
		/**
		 * Загружаем модули автозагрузки
		 */
		$this->LoadModules();
		/**
		 * Инициализируем загруженные модули
		 */
		$this->InitModules();
		/**
		 * Инициализируем загруженные плагины
		 */
		$this->InitPlugins();
		/**
		 * Запускаем хуки для события завершения инициализации Engine
		 */
		$this->Hook_Run('engine_init_complete');
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
			if(!$oModule->isInit()) {
				/**
			 	* Замеряем время инициализации модуля
			 	*/
				$oProfiler=ProfilerSimple::getInstance();
				$iTimeId=$oProfiler->Start('InitModule',get_class($oModule));

				$oModule->Init();
				$oModule->SetInit();

				$oProfiler->Stop($iTimeId);
			}
		}
	}
	
	/**
	 * Завершаем работу всех модулей
	 *
	 */
	protected function ShutdownModules() {
		foreach ($this->aModules as $sKey => $oModule) {
			/**
			 * Замеряем время shutdown`a модуля
			 */
			$oProfiler=ProfilerSimple::getInstance();
			$iTimeId=$oProfiler->Start('ShutdownModule',get_class($oModule));

			$oModule->Shutdown();

			$oProfiler->Stop($iTimeId);
		}
	}
	/**
	 * Выполняет загрузку модуля по его названию
	 *
	 * @param  string $sModuleName
	 * @param  bool $bInit - инициализировать модуль или нет
	 * @return Module
	 */
	public function LoadModule($sModuleName,$bInit=false) {
		$tm1=microtime(true);
		
		/**		 
		 * Создаем объект модуля
		 */		
		$oModule=new $sModuleName($this);
		if ($bInit or $sModuleName=='Cache') {
			$oModule->Init();
			$oModule->SetInit();
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
			$sModuleClass='Module'.$sModuleName;
			if(!in_array($sModuleName,array('Plugin','Hook'))) $sModuleClass=$this->Plugin_GetDelegate('module',$sModuleClass);
			
			if (!isset($this->aModules[$sModuleClass])) {
				$this->LoadModule($sModuleClass);
			}
		}
	}
	/**
	 * Выполняет загрузку конфигов
	 *
	 */
	protected function LoadConfig() {
		$this->aConfigModule = Config::Get('module');
	}
	/**
	 * Регистрирует хуки из /classes/hooks/
	 *
	 */
	protected function InitHooks() {
		$sDirHooks=Config::Get('path.root.server').'/classes/hooks/';
		$aFiles=glob($sDirHooks.'Hook*.class.php');
		
		if($aFiles and count($aFiles)) {
			foreach ($aFiles as $sFile) {
				if (preg_match("/Hook([\w]+)\.class\.php$/i",basename($sFile),$aMatch)) {
					require_once($sFile);
					$sClassName='Hook'.$aMatch[1];
					$oHook=new $sClassName;
					$oHook->RegisterHook();
				}
			}
		}
		
		/**
		 * Подгружаем хуки активных плагинов
		 */
		$this->InitPluginHooks();
	}
	
	/**
	 * Инициализация хуков активированных плагинов
	 *
	 */
	protected function InitPluginHooks() {
		if($aPluginList = @file(Config::Get('path.root.server').'/plugins/plugins.dat')) {
			$aPluginList=array_map('trim',$aPluginList);
			
			$aFiles=array();
			$sDirHooks=Config::Get('path.root.server').'/plugins/';
			
			foreach ($aPluginList as $sPluginName) {
				$aFiles=glob($sDirHooks.$sPluginName.'/classes/hooks/Hook*.class.php');
				if($aFiles and count($aFiles)) {
					foreach ($aFiles as $sFile) {
						if (preg_match("/Hook([\w]+)\.class\.php$/i",basename($sFile),$aMatch)) {
							require_once($sFile);
							$sPluginName = ucfirst($sPluginName);
							$sClassName="Plugin{$sPluginName}_Hook{$aMatch[1]}";
							$oHook=new $sClassName;
							$oHook->RegisterHook();
						}
					}
				}
			}
		}
	}

	/**
	 * Загрузка плагинов и делегирование
	 *
	 */
	protected function LoadPlugins() {
		if($aPluginList = @file(Config::Get('path.root.server').'/plugins/plugins.dat')) {				
			$aPluginList=array_map('trim',$aPluginList);

			foreach ($aPluginList as $sPluginName) {
				$sDirPlugins=Config::Get('path.root.server').'/plugins/';
				$sPluginNameClass='Plugin'.ucfirst($sPluginName);
				$sFile="{$sDirPlugins}{$sPluginName}/{$sPluginNameClass}.class.php";
				if(is_file($sFile)) {
					require_once($sFile);					
					$sClassName="{$sPluginNameClass}";
					$oPlugin=new $sClassName;
					$oPlugin->Delegate();
					$this->aPlugins[$sPluginName]=$oPlugin;
				}
			}
		}
	}
	
	/**
	 * Инициализация активированных плагинов
	 *
	 */
	protected function InitPlugins() {
		foreach ($this->aPlugins as $oPlugin) {			
			$oPlugin->Init();
		}
	}
	
	/**
	 * Возвращает список активных плагинов
	 *
	 * @return unknown
	 */
	public function GetPlugins() {
		return $this->aPlugins;
	}
	
	/**
	 * Проверяет файл на существование, если используется кеширование memcache то кеширует результат работы
	 *
	 * @param  string $sFile
	 * @return mixed
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
		
		list($oModule,$sModuleName,$sMethod)=$this->GetModule($sName);
		
		if (!method_exists($oModule,$sMethod)) {
			throw new Exception("The module has no required method: ".$sModuleName.'->'.$sMethod.'()');
		}
				
		$oProfiler=ProfilerSimple::getInstance();
		$iTimeId=$oProfiler->Start('callModule',$sModuleName.'->'.$sMethod.'()');
		
		$sModuleName=strtolower($sModuleName);
		$aResultHook=array();
		if (!in_array($sModuleName,array('plugin','hook'))) {
			$aResultHook=$this->_CallModule('Hook_Run',array('module_'.$sModuleName.'_'.strtolower($sMethod).'_before',&$aArgs));
		}		
		
		if (array_key_exists('delegate_result',$aResultHook)) {			
			$result=$aResultHook['delegate_result'];
		} else {
			$aArgsRef=array();
			foreach ($aArgs as $key=>$v) {
				$aArgsRef[]=&$aArgs[$key];
			}
			$result=call_user_func_array(array($oModule,$sMethod),$aArgsRef);
		}
				
		if (!in_array($sModuleName,array('plugin','hook'))) {
			$this->Hook_Run('module_'.$sModuleName.'_'.strtolower($sMethod).'_after',array('result'=>&$result,'params'=>$aArgs));
		}
				
		$oProfiler->Stop($iTimeId);
		return $result;
	}

	/**
	 * Возвращает объект модуля, имя модуля и имя вызванного метода
	 *
	 * @param  string $sName
	 * @return array
	 */
	public function GetModule($sName) {
		/**
		 * Поддержка полного синтаксиса при вызове метода модуля
		 */
		if (preg_match("/^Plugin(\w+)\_Module(\w+)\_(\w+)$/i",$sName,$aMatch)) {
			$sName="Plugin{$aMatch[1]}_{$aMatch[2]}_{$aMatch[3]}";
		}
		if (preg_match("/^Module(\w+)\_(\w+)$/i",$sName,$aMatch)) {
			$sName="{$aMatch[1]}_{$aMatch[2]}";
		}
		$aName=explode("_",$sName);
		
		if(count($aName)==2) {
			$sModuleName=$aName[0];
			$sModuleClass='Module'.$aName[0];
			$sMethod=$aName[1];
		} else {
			$sModuleName=$aName[0].'_'.$aName[1];
			$sModuleClass=$aName[0].'_Module'.$aName[1];
			$sMethod=$aName[2];
		}
		/**
		 * Подхватыем делегат модуля (в случае наличия такового)
		 */
		if(!in_array($sModuleName,array('Plugin','Hook'))) $sModuleClass=$this->Plugin_GetDelegate('module',$sModuleClass);

		if (isset($this->aModules[$sModuleClass])) {
			$oModule=$this->aModules[$sModuleClass];
		} else {
			$oModule=$this->LoadModule($sModuleClass,true);
		}
		
		return array($oModule,$sModuleName,$sMethod);
	}
	
	public function getStats() {
		return array('sql'=>$this->Database_GetStats(),'cache'=>$this->Cache_GetStats(),'engine'=>array('time_load_module'=>round($this->iTimeLoadModule,3)));
	}
	
	public function GetTimeInit() {
		return $this->iTimeInit;
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
	
	/**
	 * Получает объект маппера
	 *
	 * @param string $sClassName
	 * @param string $sName
	 * @return mixed
	 */
	public static function GetMapper($sClassName,$sName=null,$oConnect=null) {		
		if (preg_match("/^(?:Plugin\w+_)?Module(\w+)$/i",$sClassName,$aMatch)) {
			if (!$sName) {
				$sName=$aMatch[1];
			}
			$sClass=$sClassName.'_Mapper'.$sName;
			if (!$oConnect) {			
				$oConnect=Engine::getInstance()->Database_GetConnect();
			}
			$sClass=self::getInstance()->Plugin_GetDelegate('mapper',$sClass);
			return new $sClass($oConnect);
		}		
		return null;
	}
	
	/**
	 * Создает объект сущности, контролируя варианты кастомизации
	 *
	 * @param  string $sName
	 * @param  mixed  $aParams
	 * @return mixed
	 */
	public static function GetEntity($sName,$aParams=array()) {
		/**
		 * Сущности, имеющие такое же название как модуль, 
		 * можно вызывать сокращенно. Например, вместо User_User -> User
		 */
		switch (substr_count($sName,'_')) {
			case 0:
				$sEntity = $sModule = $sName;
				break;
			
			case 1:		
				/**
				 * Поддержка полного синтаксиса при вызове сущности
				 */
				if (preg_match("/^Module(\w+)\_Entity(\w+)$/i",$sName,$aMatch)) {
					$sName="{$aMatch[1]}_{$aMatch[2]}";
				}
				
				list($sModule,$sEntity) = explode('_',$sName,2);
				/**
				 * Обслуживание короткой записи сущностей плагинов 
				 * PluginTest_Test -> PluginTest_ModuleTest_EntityTest
				 */
				if(substr($sModule,0,6)=='Plugin' and strlen($sModule)>6) {
					$sPlugin = substr($sModule,6);
					$sModule = $sEntity;
				}
				break;
				
			case 2:
				/**
				 * Поддержка полного синтаксиса при вызове сущности плагина
				 */
				if (preg_match("/^Plugin(\w+)\_Module(\w+)\_Entity(\w+)$/i",$sName,$aMatch)) {
					$sName="Plugin{$aMatch[1]}_{$aMatch[2]}_{$aMatch[3]}";
				}
				/**
				 * Entity плагина
				 */
				if(substr($sName,0,6)=='Plugin') { 
					list($sPlugin,$sModule,$sEntity)=explode('_',$sName);
					$sPlugin = substr($sPlugin,6);
				} else {
					throw new Exception("Unknown entity '{$sName}' given.");
				}
				break;
				
			default:
				throw new Exception("Unknown entity '{$sName}' given.");
		}
						
		$sClass=isset($sPlugin)
			? 'Plugin'.$sPlugin.'_Module'.$sModule.'_Entity'.$sEntity
			: 'Module'.$sModule.'_Entity'.$sEntity;
		/**
		 * Определяем наличие делегата сущности
		 * Делегирование указывается только в полной форме!
		 */
		$sClass=self::getInstance()->Plugin_GetDelegate('entity',$sClass);		
		
		$oEntity=new $sClass($aParams);
		return $oEntity;
	}
}

/**
 * Автозагрузка кслассов
 *
 * @param unknown_type $sClassName
 */
function __autoload($sClassName) {
	/**
	 * Если класс подходит под шаблон класса сущности то загружаем его
	 */
	if (preg_match("/^Module(\w+)\_Entity(\w+)$/i",$sClassName,$aMatch)) {			
		$tm1=microtime(true);	
		
		$sFileClass=Config::get('path.root.server').'/classes/modules/'.strtolower($aMatch[1]).'/entity/'.$aMatch[2].'.entity.class.php';
			
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
			$tm2=microtime(true);			
			dump($sClassName." - \t\t".($tm2-$tm1));
		}
	}
	
	/**
	 * Если класс подходит под шаблон класса сущности плагина
	 */
	if (preg_match("/^Plugin(\w+)\_Module(\w+)\_Entity(\w+)$/i",$sClassName,$aMatch)) {			
		$tm1=microtime(true);
		
		$sFileClass= Config::get('path.root.server').'/plugins/'.strtolower($aMatch[1]).'/classes/modules/'.strtolower($aMatch[2]).'/entity/'.$aMatch[3].'.entity.class.php';
		
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
			$tm2=microtime(true);			
			dump($sClassName." - \t\t".($tm2-$tm1));
		}
	}

	/**
	 * Если класс подходит под шаблон модуля, то загружаем его
	 */
	if(preg_match("/^Module(\w+)$/i",$sClassName,$aMatch)) {
		$sName = ucfirst($aMatch[1]);
		$sFileClass= Config::get('path.root.server').'/classes/modules/'.strtolower($sName).'/'.$sName.'.class.php';	
			
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
		} else {
			$sFileClass = str_replace('/classes/modules/','/engine/modules/',$sFileClass);
			if(file_exists($sFileClass)) require_once($sFileClass);
		}
	}
	
	/**
	 * Если класс подходит под шаблон класса маппера, то загружаем его
	 */
	if (preg_match("/^Module(\w+)\_Mapper(\w+)$/i",$sClassName,$aMatch)) {
		$sFileClass=Config::get('path.root.server').'/classes/modules/'.strtolower($aMatch[1]).'/mapper/'.$aMatch[2].'.mapper.class.php';		
		if (file_exists($sFileClass)) {
			require_once($sFileClass);			
		}
	}
	
	/**
	 * Если класс подходит под шаблон класса маппера плагина, то загружаем его
	 */
	if (preg_match("/^Plugin(\w+)\_Module(\w+)\_Mapper(\w+)$/i",$sClassName,$aMatch)) {
		$sFileClass=Config::get('path.root.server').'/plugins/'.strtolower($aMatch[1]).'/classes/modules/'.strtolower($aMatch[2]).'/mapper/'.$aMatch[3].'.mapper.class.php';		
		if (file_exists($sFileClass)) {
			require_once($sFileClass);			
		}
	}
	
	/**
	 * Если класс подходит под шаблон модуля плагина
	 */
	if (preg_match("/^Plugin(\w+)\_Module(\w+)$/i",$sClassName,$aMatch)) {
		$sFileClass=Config::get('path.root.server').'/plugins/'.strtolower($aMatch[1]).'/classes/modules/'.strtolower($aMatch[2]).'/'.$aMatch[2].'.class.php';		
		if (file_exists($sFileClass)) {
			require_once($sFileClass);			
		}
	}
	
	
	/**
	 * Загрузка цепочки наследуемых классов
	 */
	if (preg_match("/^Plugin(\w+)\_Inherit\_([\w\_]+)$/i",$sClassName,$aMatch)) {
		$sPlugin=$aMatch[1];
		$sInheritClass=$aMatch[2];
		$sParentClass=Engine::getInstance()->Plugin_GetParentInherit($sInheritClass);
		class_alias($sParentClass,$sClassName);
	}
	
	/**
	 * Загрузка класса экшена
	 */
	if (preg_match("/^Action(\w+)$/i",$sClassName,$aMatch)) {
		$sFileClass=Config::get('path.root.server').'/classes/actions/'.$sClassName.'.class.php';		
		if (file_exists($sFileClass)) {
			require_once($sFileClass);			
		}
	}
	
	/**
	 * Загрузка класса экшена плагина
	 */
	if (preg_match("/^Plugin(\w+)\_Action(\w+)$/i",$sClassName,$aMatch)) {
		$sFileClass=Config::get('path.root.server').'/plugins/'.strtolower($aMatch[1]).'/classes/actions/Action'.$aMatch[2].'.class.php';		
		if (file_exists($sFileClass)) {
			require_once($sFileClass);			
		}
	}
}
?>