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

require_once("Action.class.php");
require_once("ActionPlugin.class.php");

/**
 * Класс роутинга(контроллера)
 * Инициализирует ядро, определяет какой экшен запустить согласно URL'у и запускает его.
 */
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
	
	
	protected function __construct() {
		$this->LoadConfig();
	}
	
	/**
	 * Запускает весь процесс :)
	 *
	 */
	public function Exec() {
		$this->ParseUrl();
		$this->DefineActionClass(); // Для возможности ДО инициализации модулей определить какой action/event запрошен
		$this->oEngine=Engine::getInstance();
		$this->oEngine->Init();		
		$this->ExecAction();
		$this->AssignVars();
		$this->oEngine->Shutdown();
		$this->Viewer_Display($this->oAction->GetTemplate());
	}
	/**
	 * Парсим URL 
	 * Пример: http://site.ru/action/event/param1/param2/  на выходе получим:
	 *  self::$sAction='action';
	 *	self::$sActionEvent='event';
	 *	self::$aParams=array('param1','param2');
	 *
	 */
	protected function ParseUrl() {
		$sReq = $this->GetRequestUri();
		$aRequestUrl=$this->GetRequestArray($sReq);
		
		self::$sAction=array_shift($aRequestUrl);
		self::$sActionEvent=array_shift($aRequestUrl);
		self::$aParams=$aRequestUrl;
	}
	
	/**
	 * Функция выполняет первичную обработку $_SERVER['REQUEST_URI']
	 *
	 * @return string
	 */
	protected function GetRequestUri() {
		$sReq=preg_replace("/\/+/",'/',$_SERVER['REQUEST_URI']);		
		$sReq=preg_replace("/^\/(.*)\/?$/U",'\\1',$sReq);		
		$sReq=preg_replace("/^(.*)\?.*$/U",'\\1',$sReq);

		/**
		 * Формируем $sPathWebCurrent ДО применения реврайтов
		 */		
		self::$sPathWebCurrent=Config::Get('path.root.web')."/".join('/',$this->GetRequestArray($sReq));
		
		/**
		 * Правила Rewrite для REQUEST_URI
		 */
		if($aRewrite=Config::Get('router.uri')) {
			$sReq = preg_replace(array_keys($aRewrite), array_values($aRewrite), $sReq);
		}
		
		return $sReq;
	}	
	/**
	 * Возвращает массив реквеста
	 *
	 * @param unknown_type $sReq
	 * @return unknown
	 */
	protected function GetRequestArray($sReq) {
		$aRequestUrl = ($sReq=='') ? array() : explode('/',$sReq);		
		for ($i=0;$i<Config::Get('path.offset_request_url');$i++) {
			array_shift($aRequestUrl);
		}	
		if (isset($aRequestUrl[0]) and @substr($aRequestUrl[0],0,1)=='?') {
			$aRequestUrl=array();
		}
		$aRequestUrl = array_map('urldecode',$aRequestUrl);
		return $aRequestUrl;
	}
	/**
	 * Выполняет загрузку конфигов роутинга
	 *
	 */
	protected function LoadConfig() {
		//Конфиг роутинга, содержит соответствия URL и классов экшенов
		$this->aConfigRoute = Config::Get('router');
		// Переписываем конфиг согласно правилу rewrite
		foreach ((array)$this->aConfigRoute['rewrite'] as $sPage=>$sRewrite) {
			if(isset($this->aConfigRoute['page'][$sPage])) {
				$this->aConfigRoute['page'][$sRewrite] = $this->aConfigRoute['page'][$sPage];
				unset($this->aConfigRoute['page'][$sPage]);
			}
		}
	}
			
	/**
	 * Загружает в шаблонизатор Smarty необходимые переменные
	 *
	 */
	protected function AssignVars() {
		$this->Viewer_Assign('sAction',$this->Standart(self::$sAction));
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
		require_once(Config::Get('path.root.server').'/classes/actions/Init.class.php');		
		$oActionInit=new Init($this->oEngine);		
		$oActionInit->InitAction();
				
		$sActionClass=$this->DefineActionClass();
		/**
		 * Определяем наличие делегата экшена
		 */
		$sActionClass=$this->Plugin_GetDelegate('action',$sActionClass);
		self::$sActionClass = $sActionClass;
		/**
		 * Если класс экешна начинается с Plugin*_, значит необходимо загрузить объект из указанного плагина
		 */
		if(!preg_match('/^Plugin([\w]+)_Action([\w]+)$/i',$sActionClass,$aMatches)) {
			require_once(Config::Get('path.root.server').'/classes/actions/'.$sActionClass.'.class.php');
		} else {
			require_once(Config::Get('path.root.server').'/plugins/'.strtolower($aMatches[1]).'/classes/actions/Action'.ucfirst($aMatches[2]).'.class.php');
		}
		
		$sClassName=$sActionClass;
		$this->oAction=new $sClassName($this->oEngine,self::$sAction);
		
		/**
		 * Инициализируем экшен
		 */
		$this->Hook_Run("action_init_".strtolower($sActionClass)."_before");
		$sInitResult = $this->oAction->Init();
		$this->Hook_Run("action_init_".strtolower($sActionClass)."_after");
		
		if ($sInitResult==='next') {
			$this->ExecAction();
		} else {
			/**
			 * Замеряем время работы action`а
			 */
			$oProfiler=ProfilerSimple::getInstance();
			$iTimeId=$oProfiler->Start('ExecAction',self::$sAction);				
			
			$res=$this->oAction->ExecEvent();
			
			$this->Hook_Run("action_shutdown_".strtolower($sActionClass)."_before");		
			$this->oAction->EventShutdown();
			$this->Hook_Run("action_shutdown_".strtolower($sActionClass)."_after");
						
			$oProfiler->Stop($iTimeId);

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
			self::$sAction=$this->aConfigRoute['config']['action_not_found'];
			self::$sActionEvent='404';
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
		return self::getInstance()->Standart(self::$sAction);		
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
	static public function GetParam($iOffset,$def=null) {
		$iOffset=(int)$iOffset;
		return isset(self::$aParams[$iOffset]) ? self::$aParams[$iOffset] : $def;
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

	/**
	 * Функция, возвращающая правильную адресацию по переданому названию страницы
	 *
	 * @param  string $action
	 * @return string
	 */
	static public function GetPath($action) {
		// Если пользователь запросил action по умолчанию
		$sPage = ($action == 'default') 
			? self::getInstance()->aConfigRoute['config']['action_default']
			: $action;
		
		// Смотрим, есть ли правило rewrite
		$sPage = self::getInstance()->Rewrite($sPage);
		return rtrim(Config::Get('path.root.web'),'/')."/$sPage/";
	}
	
	/**
	 * Try to find rewrite rule for given page.
	 * On success return rigth page, else return given param.
	 * 
	 * @param  string $sPage
	 * @return string
	 */
	protected function Rewrite($sPage) {
		return (isset($this->aConfigRoute['rewrite'][$sPage]))
			? $this->aConfigRoute['rewrite'][$sPage]
			: $sPage;
	}
	
	/**
	 * Стандартизирует определение внутренних ресурсов.
	 * 
	 * Пытается по переданому экшену найти rewrite rule и 
	 * вернуть стандартное название ресусрса.
	 *
	 * @see    $this->Rewrite()
	 * @param  string $sPage
	 * @return string
	 */
	protected function Standart($sPage) {
		$aRewrite=array_flip($this->aConfigRoute['rewrite']);
		return (isset($aRewrite[$sPage]))
			? $aRewrite[$sPage]
			: $sPage;		
	}
	
	/**
	 * Выполняет редирект, предварительно завершая работу Engine
	 *
	 * @param string $sLocation
	 */
	static public function Location($sLocation) {
		self::getInstance()->oEngine->Shutdown();
		func_header_location($sLocation);
	}
}
?>