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
 *
 * @package engine
 * @since 1.0
 */
class Router extends LsObject {
	/**
	 * Конфигурация роутинга, получается из конфига
	 *
	 * @var array
	 */
	protected $aConfigRoute=array();
	/**
	 * Текущий экшен
	 *
	 * @var string|null
	 */
	static protected $sAction=null;
	/**
	 * Текущий евент
	 *
	 * @var string|null
	 */
	static protected $sActionEvent=null;
	/**
	 * Имя текущего евента
	 *
	 * @var string|null
	 */
	static protected $sActionEventName=null;
	/**
	 * Класс текущего экшена
	 *
	 * @var string|null
	 */
	static protected $sActionClass=null;
	/**
	 * Текущий полный ЧПУ url
	 *
	 * @var string|null
	 */
	static protected $sPathWebCurrent = null;
	/**
	 * Список параметров ЧПУ url
	 * <pre>/action/event/param0/param1/../paramN/</pre>
	 *
	 * @var array
	 */
	static protected $aParams=array();
	/**
	 * Объект текущего экшена
	 *
	 * @var Action|null
	 */
	protected $oAction=null;
	/**
	 * Объект ядра
	 *
	 * @var Engine|null
	 */
	protected $oEngine=null;
	/**
	 * Покаывать или нет статистику выполнения
	 *
	 * @var bool
	 */
	static protected $bShowStats=true;
	/**
	 * Объект роутинга
	 * @see getInstance
	 *
	 * @var Router|null
	 */
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
	 * Загрузка конфига роутинга при создании объекта
	 */
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
		$this->Shutdown(false);
	}
	/**
	 * Завершение работы роутинга
	 *
	 * @param bool $bExit	Принудительно завершить выполнение скрипта
	 */
	public function Shutdown($bExit=true) {
		$this->AssignVars();
		$this->oEngine->Shutdown();
		$this->Viewer_Display($this->oAction->GetTemplate());
		if ($bExit) {
			exit();
		}
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
		$aRequestUrl=$this->RewriteRequest($aRequestUrl);

		self::$sAction=array_shift($aRequestUrl);
		self::$sActionEvent=array_shift($aRequestUrl);
		self::$aParams=$aRequestUrl;
	}
	/**
	 * Метод выполняет первичную обработку $_SERVER['REQUEST_URI']
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
		return $sReq;
	}
	/**
	 * Возвращает массив реквеста
	 *
	 * @param string $sReq	Строка реквеста
	 * @return array
	 */
	protected function GetRequestArray($sReq) {
		$aRequestUrl = ($sReq=='') ? array() : explode('/',trim($sReq,'/'));
		for ($i=0;$i<Config::Get('path.offset_request_url');$i++) {
			array_shift($aRequestUrl);
		}
		$aRequestUrl = array_map('urldecode',$aRequestUrl);
		return $aRequestUrl;
	}
	/**
	 * Применяет к реквесту правила реврайта из конфига Config::Get('router.uri')
	 *
	 * @param $aRequestUrl	Массив реквеста
	 * @return array
	 */
	protected function RewriteRequest($aRequestUrl) {
		/**
		 * Правила Rewrite для REQUEST_URI
		 */
		$sReq=implode('/',$aRequestUrl);
		if($aRewrite=Config::Get('router.uri')) {
			$sReq = preg_replace(array_keys($aRewrite), array_values($aRewrite), $sReq);
		}
		return ($sReq=='') ? array() : explode('/',$sReq);
	}
	/**
	 * Выполняет загрузку конфигов роутинга
	 *
	 */
	public function LoadConfig() {
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
		$this->Viewer_Assign('PATH_WEB_CURRENT',$this->Tools_Urlspecialchars(self::$sPathWebCurrent));
	}
	/**
	 * Запускает на выполнение экшен
	 * Может запускаться рекурсивно если в одном экшене стоит переадресация на другой
	 *
	 */
	public function ExecAction() {
		$this->DefineActionClass();
		/**
		 * Сначала запускаем инициализирующий евент
		 */
		$this->Hook_Run('init_action');

		$sActionClass=$this->DefineActionClass();
		/**
		 * Определяем наличие делегата экшена
		 */
		if($aChain=$this->Plugin_GetDelegationChain('action',$sActionClass)) {
			if(!empty($aChain)) {
				$sActionClass=$aChain[0];
			}
		}
		self::$sActionClass = $sActionClass;
		/**
		 * Если класс экешна начинается с Plugin*_, значит необходимо загрузить объект из указанного плагина
		 */
		if(!preg_match('/^Plugin([\w]+)_Action([\w]+)$/i',$sActionClass,$aMatches)) {
			require_once(Config::Get('path.root.server').'/classes/actions/'.$sActionClass.'.class.php');
		} else {
			require_once(Config::Get('path.root.server').'/plugins/'.func_underscore($aMatches[1]).'/classes/actions/Action'.ucfirst($aMatches[2]).'.class.php');
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
			self::$sActionEventName=$this->oAction->GetCurrentEventName();

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
	 * Пример: <pre>return Router::Action('error');</pre>
	 *
	 * @param string $sAction	Экшен
	 * @param string $sEvent	Евент
	 * @param array $aParams	Список параметров
	 * @return 'next'
	 */
	static public function Action($sAction,$sEvent=null,$aParams=null) {
		self::$sAction=self::getInstance()->Rewrite($sAction);
		self::$sActionEvent=$sEvent;
		if (is_array($aParams)) {
			self::$aParams=$aParams;
		}
		return 'next';
	}
	/**
	 * Возвращает текущий ЧПУ url
	 *
	 * @return string
	 */
	static public function GetPathWebCurrent() {
		return self::$sPathWebCurrent;
	}
	/**
	 * Возвращает текущий экшен
	 *
	 * @return string
	 */
	static public function GetAction() {
		return self::getInstance()->Standart(self::$sAction);
	}
	/**
	 * Возвращает текущий евент
	 *
	 * @return string
	 */
	static public function GetActionEvent() {
		return self::$sActionEvent;
	}
	/**
	 * Возвращает имя текущего евента
	 *
	 * @return string
	 */
	static public function GetActionEventName() {
		return self::$sActionEventName;
	}
	/**
	 * Возвращает класс текущего экшена
	 *
	 * @return string
	 */
	static public function GetActionClass() {
		return self::$sActionClass;
	}
	/**
	 * Устанавливает новый текущий евент
	 *
	 * @param string $sEvent	Евент
	 */
	static public function SetActionEvent($sEvent) {
		self::$sActionEvent=$sEvent;
	}
	/**
	 * Возвращает параметры(те которые передаются в URL)
	 *
	 * @return array
	 */
	static public function GetParams() {
		return self::$aParams;
	}
	/**
	 * Возвращает параметр по номеру, если его нет то возвращается null
	 * Нумерация параметров начинается нуля
	 *
	 * @param int $iOffset
	 * @param mixed|null $def
	 * @return string
	 */
	static public function GetParam($iOffset,$def=null) {
		$iOffset=(int)$iOffset;
		return isset(self::$aParams[$iOffset]) ? self::$aParams[$iOffset] : $def;
	}
	/**
	 * Устанавливает значение параметра
	 *
	 * @param int $iOffset Номер параметра, по идеи может быть не только числом
	 * @param mixed $value
	 */
	static public function SetParam($iOffset,$value) {
		self::$aParams[$iOffset]=$value;
	}
	/**
	 * Показывать или нет статистику выполение скрипта
	 * Иногда бывает необходимо отключить показ, например, при выводе RSS ленты
	 *
	 * @param bool $bState
	 */
	static public function SetIsShowStats($bState) {
		self::$bShowStats=$bState;
	}
	/**
	 * Возвращает статус показывать или нет статистику
	 *
	 * @return bool
	 */
	static public function GetIsShowStats() {
		return self::$bShowStats;
	}
	/**
	 * Проверяет запрос послан как ajax или нет
	 *
	 * @return bool
	 */
	static public function GetIsAjaxRequest() {
		return isAjaxRequest();
	}
	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 * @see Engine::_CallModule
	 *
	 * @param string $sName Имя метода
	 * @param array $aArgs Аргументы
	 * @return mixed
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
	 * Возвращает правильную адресацию по переданому названию страницы(экшену)
	 *
	 * @param  string $sAction	Экшен или путь, например, "people/top" или "/"
	 * @return string
	 */
	static public function GetPath($sAction) {
		if (!$sAction or $sAction=='/') {
			return rtrim(Config::Get('path.root.web'),'/').'/';
		}
		// Если пользователь запросил action по умолчанию
		$sPage = ($sAction == 'default')
			? self::getInstance()->aConfigRoute['config']['action_default']
			: $sAction;
		$aUrl=explode('/',$sPage);
		$sPage=array_shift($aUrl);
		$sAdditional=join('/',$aUrl);
		// Смотрим, есть ли правило rewrite
		$sPage = self::getInstance()->Rewrite($sPage);
		return rtrim(Config::Get('path.root.web'),'/')."/$sPage/".($sAdditional ? "{$sAdditional}/" : '');
	}
	/**
	 * Try to find rewrite rule for given page.
	 * On success return rigth page, else return given param.
	 *
	 * @param  string $sPage
	 * @return string
	 */
	public function Rewrite($sPage) {
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
	 * @see    Rewrite
	 * @param  string $sPage
	 * @return string
	 */
	public function Standart($sPage) {
		$aRewrite=array_flip($this->aConfigRoute['rewrite']);
		return (isset($aRewrite[$sPage]))
			? $aRewrite[$sPage]
			: $sPage;
	}
	/**
	 * Выполняет редирект, предварительно завершая работу Engine
	 *
	 * @param string $sLocation	URL для редиректа
	 */
	static public function Location($sLocation) {
		self::getInstance()->oEngine->Shutdown();
		func_header_location($sLocation);
	}
}
?>