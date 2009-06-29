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
 * Класс роутинга(контроллера)
 * Инициализирует ядро, определяет какой экшен запустить согласно URL'у и запускает его.
 */
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
require_once("Object.class.php");
require_once("Action.class.php");
require_once("Block.class.php");
require_once("Hook.class.php");
require_once("Module.class.php");
require_once("Engine.class.php");

require_once("Entity.class.php");
require_once("Mapper.class.php");


class Router extends Object {
	
	protected $aConfigRoute=array();
	static protected $sAction=null;
	static protected $sActionEvent=null;
	static protected $sActionClass=null;
	static protected $sPathWebCurrent = null;
	static protected $aParams=array();
	protected $oAction=null;
	protected $oEngine=null;
	static protected $bShowStats=true;	
	
	static protected $oInstance=null;
	
	/**
	 * Делает возможным только один экземпляр этого класса
	 *
	 * @return Router
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
	 * В конструкторе парсим URL 
	 * Пример: http://site.ru/action/event/param1/param2/  на выходе получим:
	 *  self::$sAction='action';
	 *	self::$sActionEvent='event';
	 *	self::$aParams=array('param1','param2');
	 *
	 */
	protected function __construct() {
		$this->LoadConfig();		
		
		if (get_magic_quotes_gpc()) {
			func_stripslashes($_REQUEST);
		}
		
		$sReq=preg_replace("/\/+/",'/',$_SERVER['REQUEST_URI']);		
		$sReq=preg_replace("/^\/(.*)\/?$/U",'\\1',$sReq);		
		$sReq=preg_replace("/^(.*)\/\?.*$/U",'\\1',$sReq);
		
		self::$sPathWebCurrent = DIR_WEB_ROOT."/".$sReq;
			
		$aRequestUrl = ($sReq=='') ? array() : explode('/',$sReq);		
		for ($i=0;$i<SYS_OFFSET_REQUEST_URL;$i++) {
			array_shift($aRequestUrl);
		}		
		self::$sAction=array_shift($aRequestUrl);
		self::$sActionEvent=array_shift($aRequestUrl);
		self::$aParams=$aRequestUrl;
	}
	
	/**
	 * Запускает весь процесс :)
	 *
	 */
	public function Exec() {
		$this->oEngine=Engine::getInstance();
		$this->oEngine->InitModules();		
		$this->oEngine->InitHooks();
		$this->ExecAction();		
		$this->AssignVars();
		$this->Viewer_VarAssign();	
		/**
		 * тут такое дело: модуль Viewer сначала шатдаунится а потом выполняет метод дисплей
		 */
		$this->oEngine->ShutdownModules();		
		$this->Viewer_Display($this->oAction->GetTemplate());		
	}
	
	/**
	 * Выполняет загрузку конфигов роутинга
	 *
	 */
	protected function LoadConfig() {
		//Конфиг роутинга, содержит соответствия URL и классов экшенов
		$this->aConfigRoute=include(DIR_SERVER_ROOT."/config/config.route.php");
		/**
		 * Ищет конфиги модулей и объединяет их с текущим
		 */
		$sDirConfig=DIR_SERVER_ROOT.'/config/modules/';
		if ($hDirConfig = opendir($sDirConfig)) {
			while (false !== ($sDirModule = readdir($hDirConfig))) {
				if ($sDirModule !='.' and $sDirModule !='..' and is_dir($sDirConfig.$sDirModule)) {
					$sFileConfig=$sDirConfig.$sDirModule.'/config.route.php';
					if (file_exists($sFileConfig)) {
						$aConfigModule=include($sFileConfig);
						$this->aConfigRoute=array_merge_recursive($this->aConfigRoute,$aConfigModule);
					}					
				}
			}
			closedir($hDirConfig);
		}
	}
	
	public function getStats() {
		return array('sql'=>$this->Database_GetStats(),'cache'=>$this->Cache_GetStats(),'engine'=>array('time_load_module'=>round($this->oEngine->iTimeLoadModule,3)));
	}
	
	/**
	 * Загружает в шаблонизатор Smarty необходимые переменные
	 *
	 */
	protected function AssignVars() {		
		$this->Viewer_Assign('sAction',self::$sAction);
		$this->Viewer_Assign('sEvent',self::$sActionEvent);
		$this->Viewer_Assign('aParams',self::$aParams);
		$this->Viewer_Assign('PATH_WEB_CURRENT',self::$sPathWebCurrent);
	}
	
	/**
	 * Запускает на выполнение экшен
	 * Может запускаться рекурсивно если в одном экшене стоит переадресация на другой
	 *
	 */
	public function ExecAction() {
		$this->DefineActionClass();		
		/**
		 * Сначала запускаем инициализирующий экшен
		 */
		require_once(DIR_SERVER_ROOT.'/classes/actions/Init.class.php');		
		$oActionInit=new Init($this->oEngine);		
		$oActionInit->InitAction();			
				
		$sActionClass=$this->DefineActionClass();		
		require_once(DIR_SERVER_ROOT.'/classes/actions/'.$sActionClass.'.class.php');
		$sPrefixCustom='';
		if (file_exists(DIR_SERVER_ROOT."/classes/actions/".$sActionClass.'.class.custom.php')) {
			require_once(DIR_SERVER_ROOT."/classes/actions/".$sActionClass.'.class.custom.php');
			$sPrefixCustom='_custom';
		}
		$sClassName=$sActionClass.$sPrefixCustom;
		$this->oAction=new $sClassName($this->oEngine,self::$sAction);
		if ($this->oAction->Init()==='next') {
			$this->ExecAction();
		} else {
			$res=$this->oAction->ExecEvent();
			$this->oAction->EventShutdown();			
			if ($res==='next') {
				$this->ExecAction();
			}
		}
	}

	/**
	 * Определяет какой класс соответствует текущему экшену
	 *
	 * @return string
	 */
	protected function DefineActionClass() {		
		if (isset($this->aConfigRoute['page'][self::$sAction])) {
			
		} elseif (self::$sAction===null) {
			self::$sAction=$this->aConfigRoute['config']['action_default'];			
		} else {
			//Если не находим нужного класса то отправляем на страницу ошибки	
			$this->Message_AddError($this->Lang_Get('system_error_404'),'404');		
			self::$sAction=$this->aConfigRoute['config']['action_not_found'];		
		}
		self::$sActionClass=$this->aConfigRoute['page'][self::$sAction];
		return self::$sActionClass;
	}
	
	/**
	 * Функция переадресации на другой экшен
	 * Если ею завершить евент в экшене то запуститься новый экшен
	 * Пример: return Router::Action('error');
	 *
	 * @param string $sAction
	 * @param string $sEvent
	 * @param array $aParams
	 * @return 'next'
	 */
	static public function Action($sAction,$sEvent=null,$aParams=null) {
		self::$sAction=$sAction;
		self::$sActionEvent=$sEvent;	
		if (is_array($aParams)) {
			self::$aParams=$aParams;
		}
		return 'next';
	}
	
	/**
	 * Получить текущий путь
	 *
	 * @return string
	 */
	static public function GetPathWebCurrent() {
		return self::$sPathWebCurrent;		
	}
	
	/**
	 * Получить текущий экшен
	 *
	 * @return string
	 */
	static public function GetAction() {
		return self::$sAction;		
	}
	
	/**
	 * Получить текущий евент
	 *
	 * @return string
	 */
	static public function GetActionEvent() {
		return self::$sActionEvent;		
	}
	
	/**
	 * Получить класс текущего экшена
	 *
	 * @return string
	 */
	static public function GetActionClass() {
		return self::$sActionClass;		
	}
	
	/**
	 * Установить новый текущий евент
	 *
	 * @param string $sEvent
	 */
	static public function SetActionEvent($sEvent) {
		self::$sActionEvent=$sEvent;		
	}	
	
	/**
	 * Получить параметры(те которые передаются в URL)
	 *
	 * @return array
	 */
	static public function GetParams() {
		return self::$aParams;
	}
	
	/**
	 * Получить параметр по номеру, если его нет то возвращается null
	 *
	 * @param int $iOffset
	 * @return string
	 */
	static public function GetParam($iOffset) {
		$iOffset=(int)$iOffset;
		return isset(self::$aParams[$iOffset]) ? self::$aParams[$iOffset] : null;
	}
	
	/**
	 * Установить значение параметра
	 *
	 * @param int $iOffset - по идеи может быть не только числом
	 * @param unknown_type $value	 
	 */
	static public function SetParam($iOffset,$value) {		
		self::$aParams[$iOffset]=$value;
	}
	
	/**
	 * Показывать или нет статистику выполение скрипта
	 * Иногда бывает отключить показ, например, при выводе RSS ленты
	 *
	 * @param unknown_type $bState
	 */
	static public function SetIsShowStats($bState) {
		self::$bShowStats=$bState;
	}
	
	/**
	 * Получить статус показывать или нет статистику
	 *
	 * @return unknown
	 */
	static public function GetIsShowStats() {
		return self::$bShowStats;
	}
	
	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 *
	 * @param string $sName
	 * @param array $aArgs
	 * @return unknown
	 */
	public function __call($sName,$aArgs) {
		return $this->oEngine->_CallModule($sName,$aArgs);
	}
	
	/**
	 * Блокируем копирование/клонирование объекта роутинга
	 *
	 */
	protected function __clone() {
		
	}
}
?>