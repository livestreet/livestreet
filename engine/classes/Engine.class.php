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

require_once("LsObject.class.php");
require_once("Plugin.class.php");
require_once("Block.class.php");
require_once("Hook.class.php");
require_once("Module.class.php");
require_once("Router.class.php");

require_once("Entity.class.php");
require_once("Mapper.class.php");

require_once("ModuleORM.class.php");
require_once("EntityORM.class.php");
require_once("MapperORM.class.php");

require_once("ManyToManyRelation.class.php");


/**
 * Основной класс движка. Ядро.
 *
 * Производит инициализацию плагинов, модулей, хуков.
 * Через этот класс происходит выполнение методов всех модулей, которые вызываются как <pre>$this->Module_Method();</pre>
 * Также отвечает за автозагрузку остальных классов движка.
 *
 * В произвольном месте (не в классах движка у которых нет обработки метода __call() на выполнение модулей) метод модуля можно вызвать так:
 * <pre>
 * Engine::getInstance()->Module_Method();
 * </pre>
 *
 * @package engine
 * @since 1.0
 */
class Engine extends LsObject {

	/**
	 * Имя плагина
	 * @var int
	 */
	const CI_PLUGIN = 1;

	/**
	 * Имя экшна
	 * @var int
	 */
	const CI_ACTION = 2;

	/**
	 * Имя модуля
	 * @var int
	 */
	const CI_MODULE = 4;

	/**
	 * Имя сущности
	 * @var int
	 */
	const CI_ENTITY = 8;

	/**
	 * Имя маппера
	 * @var int
	 */
	const CI_MAPPER = 16;

	/**
	 * Имя метода
	 * @var int
	 */
	const CI_METHOD = 32;

	/**
	 * Имя хука
	 * @var int
	 */
	const CI_HOOK = 64;

	/**
	 * Имя класс наследования
	 * @var int
	 */
	const CI_INHERIT = 128;

	/**
	 * Имя блока
	 * @var int
	 */
	const CI_BLOCK = 256;

	/**
	 * Имя обработчика евента
	 * @var int
	 */
	const CI_EVENT = 512;

	/**
	 * Префикс плагина
	 * @var int
	 */
	const CI_PPREFIX = 8192;

	/**
	 * Разобранный класс наследования
	 * @var int
	 */
	const CI_INHERITS = 16384;

	/**
	 * Путь к файлу класса
	 * @var int
	 */
	const CI_CLASSPATH = 32768;

	/**
	 * Все свойства класса
	 * @var int
	 */
	const CI_ALL = 65535;

	/**
	 * Свойства по-умолчанию
	 * CI_ALL ^ (CI_CLASSPATH | CI_INHERITS | CI_PPREFIX)
	 * @var int
	 */
	const CI_DEFAULT = 8191;

	/**
	 * Объекты
	 * CI_ACTION | CI_MAPPER | CI_HOOK | CI_PLUGIN | CI_EVENT | CI_MODULE | CI_ENTITY | CI_BLOCK
	 * @var int
	 */
	const CI_OBJECT = 863 ;

	/**
	 * Текущий экземпляр движка, используется для синглтона.
	 * @see getInstance использование синглтона
	 *
	 * @var Engine
	 */
	static protected $oInstance=null;
	/**
	 * Список загруженных модулей
	 *
	 * @var array
	 */
	protected $aModules=array();
	/**
	 * Список загруженных плагинов
	 *
	 * @var array
	 */
	protected $aPlugins=array();
	/**
	 * Содержит конфиг модулей.
	 * Используется для получания списка модулей для авто-загрузки. Остальные модули загружаются при первом обращении.
	 * В конфиге определен так:
	 * <pre>
	 * $config['module']['autoLoad'] = array('Hook','Cache','Security','Session','Lang','Message','User');
	 * </pre>
	 *
	 * @var array
	 */
	protected $aConfigModule;
	/**
	 * Время загрузки модулей в микросекундах
	 *
	 * @var int
	 */
	public $iTimeLoadModule=0;
	/**
	 * Текущее время в микросекундах на момент инициализации ядра(движка).
	 * Определается так:
	 * <pre>
	 * $this->iTimeInit=microtime(true);
	 * </pre>
	 *
	 * @var int|null
	 */
	protected $iTimeInit=null;


	/**
	 * Вызывается при создании объекта ядра.
	 * Устанавливает время старта инициализации и обрабатывает входные параметры PHP
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
	 * Ограничиваем объект только одним экземпляром.
	 * Функционал синглтона.
	 *
	 * Используется так:
	 * <pre>
	 * Engine::getInstance()->Module_Method();
	 * </pre>
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
	 * Инициализация ядра движка
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
	 * Завершение работы движка
	 * Завершает все модули.
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

				$this->InitModule($oModule);

				$oProfiler->Stop($iTimeId);
			}
		}
	}

	/**
	 * Инициализирует модуль
	 *
	 * @param Module $oModule	Объект модуля
	 * @param bool $bHookParent	Вызывает хук на родительском модуле, от которого наследуется текущий
	 */
	protected function InitModule($oModule, $bHookParent = true){
		$sOrigClassName = $sClassName = get_class($oModule);
		$bRunHooks = false;

		if($this->isInitModule('ModuleHook')){
			$bRunHooks = true;
			if($bHookParent){
				while(self::GetPluginName($sClassName)){
					$sParentClassName = get_parent_class($sClassName);
					if(!self::GetClassInfo($sParentClassName, self::CI_MODULE, true)){
						break;
					}
					$sClassName = $sParentClassName;
				}
			}
		}
		if($bRunHooks || $sClassName == 'ModuleHook'){
			$sHookPrefix = 'module_';
			if($sPluginName = self::GetPluginName($sClassName)){
				$sHookPrefix .= "plugin{$sPluginName}_";
			}
			$sHookPrefix .= self::GetModuleName($sClassName).'_init_';
		}
		if($bRunHooks){
			$this->Hook_Run($sHookPrefix.'before');
		}
		$oModule->Init();
		$oModule->SetInit();
		if($bRunHooks || $sClassName == 'ModuleHook'){
			$this->Hook_Run($sHookPrefix.'after');
		}
	}

	/**
	 * Проверяет модуль на инициализацию
	 *
	 * @param string $sModuleClass	Класс модуля
	 * @return bool
	 */
	public function isInitModule($sModuleClass) {
		if(!in_array($sModuleClass,array('ModulePlugin','ModuleHook'))) {
			$sModuleClass=$this->Plugin_GetDelegate('module',$sModuleClass);
		}
		if(isset($this->aModules[$sModuleClass]) and $this->aModules[$sModuleClass]->isInit()){
			return true;
		}
		return false;
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
	 * @param  string $sModuleClass	Класс модуля
	 * @param  bool $bInit Инициализировать модуль или нет
	 *
	 * @throws RuntimeException если класс $sModuleClass не существует
	 *
	 * @return Module
	 */
	public function LoadModule($sModuleClass,$bInit=false) {
		$tm1=microtime(true);

		if (!class_exists($sModuleClass))
		{
			throw new RuntimeException(sprintf('Class "%s" not found!', $sModuleClass));
		}
		/**
		 * Создаем объект модуля
		 */
		$oModule=new $sModuleClass($this);
		$this->aModules[$sModuleClass]=$oModule;
		if ($bInit or $sModuleClass=='ModuleCache') {
			$this->InitModule($oModule);
		}
		$tm2=microtime(true);
		$this->iTimeLoadModule+=$tm2-$tm1;
		dump("load $sModuleClass - \t\t".($tm2-$tm1)."");
		return $oModule;
	}

	/**
	 * Загружает модули из авто-загрузки и передает им в конструктор ядро
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
				if (preg_match("/Hook([^_]+)\.class\.php$/i",basename($sFile),$aMatch)) {
					//require_once($sFile);
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
		if($aPluginList = func_list_plugins()) {
			$sDirHooks=Config::Get('path.root.server').'/plugins/';

			foreach ($aPluginList as $sPluginName) {
				$aFiles=glob($sDirHooks.$sPluginName.'/classes/hooks/Hook*.class.php');
				if($aFiles and count($aFiles)) {
					foreach ($aFiles as $sFile) {
						if (preg_match("/Hook([^_]+)\.class\.php$/i",basename($sFile),$aMatch)) {
							//require_once($sFile);
							$sPluginName = func_camelize($sPluginName);
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
		if($aPluginList = func_list_plugins()) {
			foreach ($aPluginList as $sPluginName) {
				$sClassName='Plugin'.func_camelize($sPluginName);
				$oPlugin=new $sClassName;
				$oPlugin->Delegate();
				$this->aPlugins[$sPluginName]=$oPlugin;
			}
		}
	}

	/**
	 * Инициализация активированных(загруженных) плагинов
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
	 * @return array
	 */
	public function GetPlugins() {
		return $this->aPlugins;
	}

	/**
	 * Проверяет файл на существование, если используется кеширование memcache то кеширует результат работы
	 *
	 * @param  string $sFile	Полный путь до файла
	 * @param  int $iTime	Время жизни кеша
	 * @return bool
	 */
	public function isFileExists($sFile,$iTime=3600) {
		// пока так
		return file_exists($sFile);

		if(
			!$this->isInit('cache')
			|| !Config::Get('sys.cache.use')
			|| Config::Get('sys.cache.type') != 'memory'
		){
			return file_exists($sFile);
		}
		if (false === ($data = $this->Cache_Get("file_exists_{$sFile}"))) {
			$data=file_exists($sFile);
			$this->Cache_Set((int)$data, "file_exists_{$sFile}", array(), $iTime);
		}
		return $data;
	}
	/**
	 * Вызывает метод нужного модуля
	 *
	 * @param string $sName	Название метода в полном виде.
	 * Например <pre>Module_Method</pre>
	 * @param array $aArgs	Список аргументов
	 * @return mixed
	 */
	public function _CallModule($sName,$aArgs) {
		list($oModule,$sModuleName,$sMethod)=$this->GetModule($sName);

		if (!method_exists($oModule,$sMethod)) {
			// comment for ORM testing
			//throw new Exception("The module has no required method: ".$sModuleName.'->'.$sMethod.'()');
		}
		/**
		 * Замеряем время выполнения метода
		 */
		$oProfiler=ProfilerSimple::getInstance();
		$iTimeId=$oProfiler->Start('callModule',$sModuleName.'->'.$sMethod.'()');

		$sModuleName=strtolower($sModuleName);
		$aResultHook=array();
		if (!in_array($sModuleName,array('plugin','hook'))) {
			$aResultHook=$this->_CallModule('Hook_Run',array('module_'.$sModuleName.'_'.strtolower($sMethod).'_before',&$aArgs));
		}
		/**
		 * Хук может делегировать результат выполнения метода модуля, сам метод при этом не выполняется, происходит только подмена результата
		 */
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
	 * @param  string $sName	Имя метода модуля в полном виде
	 * Например <pre>Module_Method</pre>
	 * @return array
	 */
	public function GetModule($sName) {
		/**
		 * Поддержка полного синтаксиса при вызове метода модуля
		 */
		$aInfo = self::GetClassInfo(
			$sName,
			self::CI_MODULE
				|self::CI_PPREFIX
				|self::CI_METHOD
		);
		if($aInfo[self::CI_MODULE] && $aInfo[self::CI_METHOD]){
			$sName = $aInfo[self::CI_MODULE].'_'.$aInfo[self::CI_METHOD];
			if($aInfo[self::CI_PPREFIX]){
				$sName = $aInfo[self::CI_PPREFIX].$sName;
			}
		}

		$aName=explode("_",$sName);

		if(count($aName)==2) {
			$sModuleName=$aName[0];
			$sModuleClass='Module'.$aName[0];
			$sMethod=$aName[1];
		} elseif (count($aName)==3) {
			$sModuleName=$aName[0].'_'.$aName[1];
			$sModuleClass=$aName[0].'_Module'.$aName[1];
			$sMethod=$aName[2];
		} else {
			throw new Exception("Undefined method module: ".$sName);
		}
		/**
		 * Подхватыем делегат модуля (в случае наличия такового)
		 */
		if(!in_array($sModuleName,array('Plugin','Hook'))){
			$sModuleClass=$this->Plugin_GetDelegate('module',$sModuleClass);
		}

		if (isset($this->aModules[$sModuleClass])) {
			$oModule=$this->aModules[$sModuleClass];
		} else {
			$oModule=$this->LoadModule($sModuleClass,true);
		}

		return array($oModule,$sModuleName,$sMethod);
	}

	/**
	 * Возвращает объект модуля
	 *
	 * @param string $sName Имя модуля
	 */
	public function GetModuleObject($sName) {
		if(self::GetPluginPrefix($sName)){
			if(substr_count($sName,'_')<2) {
				$sName.='_x';
			}
		} else {
			if(substr_count($sName,'_')<1) {
				$sName.='_x';
			}
		}
		$aCallArray=$this->GetModule($sName);
		return $aCallArray[0];
	}

	/**
	 * Возвращает статистику выполнения
	 *
	 * @return array
	 */
	public function getStats() {
		return array('sql'=>$this->Database_GetStats(),'cache'=>$this->Cache_GetStats(),'engine'=>array('time_load_module'=>round($this->iTimeLoadModule,3)));
	}

	/**
	 * Возвращает время старта выполнения движка в микросекундах
	 *
	 * @return int
	 */
	public function GetTimeInit() {
		return $this->iTimeInit;
	}

	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 *
	 * @param string $sName	Имя метода
	 * @param array $aArgs	Аргументы
	 * @return mixed
	 */
	public function __call($sName,$aArgs) {
		return $this->_CallModule($sName,$aArgs);
	}

	/**
	 * Блокируем копирование/клонирование объекта ядра
	 *
	 */
	protected function __clone() {

	}

	/**
	 * Получает объект маппера
	 *
	 * @param string $sClassName Класс модуля маппера
	 * @param string|null $sName	Имя маппера
	 * @param DbSimple_Mysql|null $oConnect	Объект коннекта к БД
	 * Можно получить так:
	 * <pre>
	 * Engine::getInstance()->Database_GetConnect($aConfig);
	 * </pre>
	 * @return mixed
	 */
	public static function GetMapper($sClassName,$sName=null,$oConnect=null) {
		$sModuleName = self::GetClassInfo(
			$sClassName,
			self::CI_MODULE,
			true
		);
		if ($sModuleName) {
			if (!$sName) {
				$sName=$sModuleName;
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
	 * @param  string $sName	Имя сущности, возможны сокращенные варианты.
	 * Например <pre>ModuleUser_EntityUser</pre> эквивалентно <pre>User_User</pre> и эквивалентно <pre>User</pre> т.к. имя сущности совпадает с именем модуля
	 * @param  array  $aParams
	 * @return Entity
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
				$aInfo = self::GetClassInfo(
					$sName,
					self::CI_ENTITY
						|self::CI_MODULE
						|self::CI_PLUGIN
				);
				if ($aInfo[self::CI_MODULE]
					&& $aInfo[self::CI_ENTITY]) {
					$sName=$aInfo[self::CI_MODULE].'_'.$aInfo[self::CI_ENTITY];
				}

				list($sModule,$sEntity) = explode('_',$sName,2);
				/**
				 * Обслуживание короткой записи сущностей плагинов
				 * PluginTest_Test -> PluginTest_ModuleTest_EntityTest
				 */
				if($aInfo[self::CI_PLUGIN]) {
					$sPlugin = $aInfo[self::CI_PLUGIN];
					$sModule = $sEntity;
				}
				break;

			case 2:
				/**
				 * Поддержка полного синтаксиса при вызове сущности плагина
				 */
				$aInfo = self::GetClassInfo(
					$sName,
					self::CI_ENTITY
						|self::CI_MODULE
						|self::CI_PLUGIN
				);
				if ($aInfo[self::CI_PLUGIN]
					&& $aInfo[self::CI_MODULE]
					&& $aInfo[self::CI_ENTITY]) {
					$sName='Plugin'.$aInfo[self::CI_PLUGIN]
						.'_'.$aInfo[self::CI_MODULE]
						.'_'.$aInfo[self::CI_ENTITY]
					;
				}
				/**
				 * Entity плагина
				 */
				if($aInfo[self::CI_PLUGIN]) {
					list(,$sModule,$sEntity)=explode('_',$sName);
					$sPlugin = $aInfo[self::CI_PLUGIN];
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
		 * If Plugin Entity doesn't exist, search among it's Module delegates
		 */
		if(isset($sPlugin) && !self::GetClassPath($sClass)) {
			$aModulesChain = Engine::GetInstance()->Plugin_GetDelegationChain('module','Plugin'.$sPlugin.'_Module'.$sModule);
			foreach($aModulesChain as $sModuleName) {
				$sClassTest=$sModuleName.'_Entity'.$sEntity;
				if(self::GetClassPath($sClassTest)) {
					$sClass=$sClassTest;
					break;
				}
			}
			if(!self::GetClassPath($sClass)) {
				$sClass='Module'.$sModule.'_Entity'.$sEntity;
			}
		}

		/**
		 * Определяем наличие делегата сущности
		 * Делегирование указывается только в полной форме!
		 */
		$sClass=self::getInstance()->Plugin_GetDelegate('entity',$sClass);

		$oEntity=new $sClass($aParams);
		return $oEntity;
	}

	/**
	 * Возвращает имя плагина моудля если модул принадлежит плагину.
	 * Например <pre>Openid</pre>
	 *
	 * @static
	 * @param Module $oModule Объект модуля
	 * @return string|null
	 */
	public static function GetPluginName($oModule) {
		return self::GetClassInfo($oModule, self::CI_PLUGIN, true);
	}

	/**
	 * Возвращает префикс плагина
	 * Например <pre>PluginOpenid_</pre>
	 *
	 * @static
	 * @param Module $oModule Объект модуля
	 * @return string	Если плагина нет, возвращает пустую строку
	 */
	public static function GetPluginPrefix($oModule) {
		return self::GetClassInfo($oModule, self::CI_PPREFIX, true);
	}

	/**
	 * Возвращает имя модуля
	 *
	 * @static
	 * @param Module $oModule Объект модуля
	 * @return string|null
	 */
	public static function GetModuleName($oModule) {
		return self::GetClassInfo($oModule, self::CI_MODULE, true);
	}

	/**
	 * Возвращает имя сущности
	 *
	 * @static
	 * @param Entity $oEntity Объект сущности
	 * @return string|null
	 */
	public static function GetEntityName($oEntity) {
		return self::GetClassInfo($oEntity, self::CI_ENTITY, true);
	}

	/**
	 * Возвращает имя экшена
	 *
	 * @static
	 * @param $oAction	Объект экшена
	 * @return string|null
	 */
	public static function GetActionName($oAction) {
		return self::GetClassInfo($oAction, self::CI_ACTION, true);
	}

	/**
	 * Возвращает информацию об объекта или классе
	 *
	 * @static
	 * @param LsObject|string $oObject	Объект или имя класса
	 * @param int $iFlag	Маска по которой нужно вернуть рузультат. Доступные маски определены в константах CI_*
	 * Например, получить информацию о плагине и модуле:
	 * <pre>
	 * Engine::GetClassInfo($oObject,Engine::CI_PLUGIN | Engine::CI_MODULE);
	 * </pre>
	 * @param bool $bSingle	Возвращать полный результат или только первый элемент
	 * @return array|string|null
	 */
	public static function GetClassInfo($oObject,$iFlag=self::CI_DEFAULT,$bSingle=false){
		$sClassName = is_string($oObject) ? $oObject : get_class($oObject);
		$aResult = array();
		if($iFlag & self::CI_PLUGIN){
			$aResult[self::CI_PLUGIN] = preg_match('/^Plugin([^_]+)/',$sClassName,$aMatches)
				? $aMatches[1]
				: null
			;
		}
		if($iFlag & self::CI_ACTION){
			$aResult[self::CI_ACTION] = preg_match('/^(?:Plugin[^_]+_|)Action([^_]+)/',$sClassName,$aMatches)
				? $aMatches[1]
				: null
			;
		}
		if($iFlag & self::CI_MODULE){
			$aResult[self::CI_MODULE] = preg_match('/^(?:Plugin[^_]+_|)Module(?:ORM|)([^_]+)/',$sClassName,$aMatches)
				? $aMatches[1]
				: null
			;
		}
		if($iFlag & self::CI_ENTITY){
			$aResult[self::CI_ENTITY] = preg_match('/_Entity(?:ORM|)([^_]+)/',$sClassName,$aMatches)
				? $aMatches[1]
				: null
			;
		}
		if($iFlag & self::CI_MAPPER){
			$aResult[self::CI_MAPPER] = preg_match('/_Mapper(?:ORM|)([^_]+)/',$sClassName,$aMatches)
				? $aMatches[1]
				: null
			;
		}
		if($iFlag & self::CI_HOOK){
			$aResult[self::CI_HOOK] = preg_match('/^(?:Plugin[^_]+_|)Hook([^_]+)$/',$sClassName,$aMatches)
				? $aMatches[1]
				: null
			;
		}
		if($iFlag & self::CI_BLOCK){
			$aResult[self::CI_BLOCK] = preg_match('/^(?:Plugin[^_]+_|)Block([^_]+)$/',$sClassName,$aMatches)
				? $aMatches[1]
				: null
			;
		}
		if($iFlag & self::CI_EVENT){
			$aResult[self::CI_EVENT] = preg_match('/_Event([^_]+)/',$sClassName,$aMatches)
				? $aMatches[1]
				: null
			;
		}
		if($iFlag & self::CI_METHOD){
			$sModuleName = isset($aResult[self::CI_MODULE])
				? $aResult[self::CI_MODULE]
				: self::GetClassInfo($sClassName, self::CI_MODULE, true)
			;
			$aResult[self::CI_METHOD] = preg_match('/_([^_]+)$/',$sClassName,$aMatches)
				? ($sModuleName && strtolower($aMatches[1]) == strtolower('module'.$sModuleName)
					? null
					: $aMatches[1]
				)
				: null
			;
		}
		if($iFlag & self::CI_PPREFIX){
			$sPluginName = isset($aResult[self::CI_PLUGIN])
				? $aResult[self::CI_PLUGIN]
				: self::GetClassInfo($sClassName, self::CI_PLUGIN, true)
			;
			$aResult[self::CI_PPREFIX] = $sPluginName
				? "Plugin{$sPluginName}_"
				: ''
			;
		}
		if($iFlag & self::CI_INHERIT){
			$aResult[self::CI_INHERIT] = preg_match('/_Inherits?_(\w+)$/',$sClassName,$aMatches)
				? $aMatches[1]
				: null
			;
		}
		if($iFlag & self::CI_INHERITS){
			$sInherit = isset($aResult[self::CI_INHERIT])
				? $aResult[self::CI_INHERIT]
				: self::GetClassInfo($sClassName, self::CI_INHERIT, true)
			;
			$aResult[self::CI_INHERITS] = $sInherit
				? self::GetClassInfo(
					$sInherit,
					self::CI_OBJECT,
					false)
				: null
			;
		}
		if($iFlag & self::CI_CLASSPATH){
			$aResult[self::CI_CLASSPATH] = self::GetClassPath($sClassName);
		}

		return $bSingle ? array_pop($aResult) : $aResult;
	}

	/**
	 * Возвращает информацию о пути до файла класса.
	 * Используется в {@link autoload автозагрузке}
	 *
	 * @static
	 * @param LsObject $oObject Объект - модуль, экшен, плагин, хук, сущность
	 * @return null|string
	 */
	public static function GetClassPath($oObject){
		$aInfo = self::GetClassInfo(
			$oObject,
			self::CI_OBJECT
		);
		$sPath = Config::get('path.root.server').'/';
		if($aInfo[self::CI_ENTITY]){
			// Сущность
			if($aInfo[self::CI_PLUGIN]){
				// Сущность модуля плагина
				$sPath .= 'plugins/'.func_underscore($aInfo[self::CI_PLUGIN])
					.'/classes/modules/'.strtolower($aInfo[self::CI_MODULE])
					.'/entity/'.$aInfo[self::CI_ENTITY].'.entity.class.php'
				;
			}else{
				// Сущность модуля ядра
				$sPath .= 'classes/modules/'.strtolower($aInfo[self::CI_MODULE])
					.'/entity/'.$aInfo[self::CI_ENTITY].'.entity.class.php'
				;
				if(!is_file($sPath)){
					$sPath = str_replace('/classes/modules/','/engine/modules/',$sPath);
				}
			}
		}elseif($aInfo[self::CI_MAPPER]){
			// Маппер
			if($aInfo[self::CI_PLUGIN]){
				// Маппер модуля плагина
				$sPath .= 'plugins/'.func_underscore($aInfo[self::CI_PLUGIN])
					.'/classes/modules/'.strtolower($aInfo[self::CI_MODULE])
					.'/mapper/'.$aInfo[self::CI_MAPPER].'.mapper.class.php'
				;
			}else{
				// Маппер модуля ядра
				$sPath .= 'classes/modules/'.strtolower($aInfo[self::CI_MODULE])
					.'/mapper/'.$aInfo[self::CI_MAPPER].'.mapper.class.php'
				;
				if(!is_file($sPath)){
					$sPath = str_replace('/classes/modules/','/engine/modules/',$sPath);
				}
			}
		}elseif($aInfo[self::CI_EVENT]){
			// Евент
			if($aInfo[self::CI_PLUGIN]){
				// Евент плагина
				$sPath .= 'plugins/'.func_underscore($aInfo[self::CI_PLUGIN])
					.'/classes/actions/'.lcfirst($aInfo[self::CI_ACTION]).'/Event'.$aInfo[self::CI_EVENT].'.class.php'
				;
			}else{
				// Евент ядра
				$sPath .= 'classes/actions/'.lcfirst($aInfo[self::CI_ACTION]).'/Event'
					.$aInfo[self::CI_EVENT].'.class.php'
				;
			}
		}elseif($aInfo[self::CI_ACTION]){
			// Экшн
			if($aInfo[self::CI_PLUGIN]){
				// Экшн плагина
				$sPath .= 'plugins/'.func_underscore($aInfo[self::CI_PLUGIN])
					.'/classes/actions/Action'.$aInfo[self::CI_ACTION].'.class.php'
				;
			}else{
				// Экшн ядра
				$sPath .= 'classes/actions/Action'
					.$aInfo[self::CI_ACTION].'.class.php'
				;
			}
		}elseif($aInfo[self::CI_MODULE]){
			// Модуль
			if($aInfo[self::CI_PLUGIN]){
				// Модуль плагина
				$sPath .= 'plugins/'.func_underscore($aInfo[self::CI_PLUGIN])
					.'/classes/modules/'.strtolower($aInfo[self::CI_MODULE])
					.'/'.$aInfo[self::CI_MODULE].'.class.php';
				;
			}else{
				// Модуль ядра
				$sPath .= 'classes/modules/'.strtolower($aInfo[self::CI_MODULE])
					.'/'.$aInfo[self::CI_MODULE].'.class.php'
				;
				if(!is_file($sPath)){
					$sPath = str_replace('/classes/modules/','/engine/modules/',$sPath);
				}
			}
		}elseif($aInfo[self::CI_HOOK]){
			// Хук
			if($aInfo[self::CI_PLUGIN]){
				// Хук плагина
				$sPath .= 'plugins/'.func_underscore($aInfo[self::CI_PLUGIN])
					.'/classes/hooks/Hook'.$aInfo[self::CI_HOOK]
					.'.class.php';
				;
			}else{
				// Хук ядра
				$sPath .= 'classes/hooks/Hook'.$aInfo[self::CI_HOOK].'.class.php';
			}
		}elseif($aInfo[self::CI_BLOCK]){
			// Блок
			if($aInfo[self::CI_PLUGIN]){
				// Блок плагина
				$sPath .= 'plugins/'.func_underscore($aInfo[self::CI_PLUGIN])
					.'/classes/blocks/Block'.$aInfo[self::CI_BLOCK]
					.'.class.php';
				;
			}else{
				// Блок ядра
				$sPath .= 'classes/blocks/Block'.$aInfo[self::CI_BLOCK].'.class.php';
			}
		}elseif($aInfo[self::CI_PLUGIN]){
			// Плагин
			$sPath .= 'plugins/'.func_underscore($aInfo[self::CI_PLUGIN])
				.'/Plugin'.$aInfo[self::CI_PLUGIN]
				.'.class.php';
			;
		}else{
			$sClassName = is_string($oObject) ? $oObject : get_class($oObject);
			$sPath .= 'engine/classes/'.$sClassName.'.class.php';
		}
		return is_file($sPath) ? $sPath : null;
	}


	/**
	 * Автозагрузка классов
	 *
	 * @param string $sClassName	Название класса
	 * @return bool
	 */
	public static function autoload($sClassName) {
		$aInfo = Engine::GetClassInfo(
			$sClassName,
			Engine::CI_CLASSPATH|Engine::CI_INHERIT
		);
		if($aInfo[Engine::CI_INHERIT]){
			$sInheritClass = $aInfo[Engine::CI_INHERIT];
			$sParentClass = Engine::getInstance()->Plugin_GetParentInherit($sInheritClass);
			if(!class_alias($sParentClass,$sClassName)){
				dump("(autoload $sParentClass) Can not load CLASS-file");
			} else {
				return true;
			}
		}elseif($aInfo[Engine::CI_CLASSPATH]){
			require_once $aInfo[Engine::CI_CLASSPATH];
			return true;
		}elseif(!class_exists($sClassName)){
			dump("(autoload $sClassName) Can not load CLASS-file");
			dump($aInfo);
			//throw new Exception("(autoload '$sClassName') Can not load CLASS-file");
		}
		return false;
	}

}

/**
 * Регистрация автозагрузки классов
 */
spl_autoload_register(array('Engine','autoload'));

/**
 * Короткий алиас для вызова основных методов движка
 *
 * @package engine
 * @since 1.0
 */
class LS extends LsObject {

	static protected $oInstance=null;

	static public function getInstance() {
		if (isset(self::$oInstance) and (self::$oInstance instanceof self)) {
			return self::$oInstance;
		} else {
			self::$oInstance = new self();
			return self::$oInstance;
		}
	}
	/**
	 * Возвращает ядро
	 * @see Engine::GetInstance
	 *
	 * @return Engine
	 */
	static public function E() {
		return Engine::GetInstance();
	}
	/**
	 * Возвращает объект сущности
	 * @see Engine::GetEntity
	 *
	 * @param $sName	Название сущности
	 * @param array $aParams	Параметры для передачи в конструктор
	 * @return Entity
	 */
	static public function Ent($sName,$aParams=array()) {
		return Engine::GetEntity($sName,$aParams);
	}
	/**
	 * Возвращает объект маппера
	 * @see Engine::GetMapper
	 *
	 * @param $sClassName Класс модуля маппера
	 * @param string|null $sName	Имя маппера
	 * @param DbSimple_Mysql|null $oConnect	Объект коннекта к БД
	 * @return mixed
	 */
	static public function Mpr($sClassName,$sName=null,$oConnect=null) {
		return Engine::GetMapper($sClassName,$sName,$oConnect);
	}
	/**
	 * Возвращает текущего авторизованного пользователя
	 * @see ModuleUser::GetUserCurrent
	 *
	 * @return ModuleUser_EntityUser
	 */
	static public function CurUsr() {
		return self::E()->User_GetUserCurrent();
	}
	/**
	 * Возвращает true если текущий пользователь администратор
	 * @see ModuleUser::GetUserCurrent
	 * @see ModuleUser_EntityUser::isAdministrator
	 *
	 * @return bool
	 */
	static public function Adm() {
		return self::CurUsr() && self::CurUsr()->isAdministrator();
	}
	/**
	 * Вызов метода модуля
	 * Например <pre>$LS->Module_Method()</pre>
	 *
	 * @param $sName	Полное название метода, например <pre>Module_Method</pre>
	 * @param array $aArgs Список аргуметов метода
	 * @return mixed
	 */
	public function __call($sName,$aArgs=array()) {
		return call_user_func_array(array(self::E(),$sName),$aArgs);
	}
	/**
	 * Статический вызов метода модуля для PHP >= 5.3
	 * Например <pre>LS::Module_Method()</pre>
	 *
	 * @static
	 * @param $sName	Полное название метода, например <pre>Module_Method</pre>
	 * @param array $aArgs Список аргуметов метода
	 * @return mixed
	 */
	public static function __callStatic($sName,$aArgs=array()) {
		return call_user_func_array(array(self::E(),$sName),$aArgs);
	}
}

?>