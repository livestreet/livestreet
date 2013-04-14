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

require_once("Event.class.php");
/**
 * Абстрактный класс экшена.
 *
 * От этого класса наследуются все экшены в движке.
 * Предоставляет базовые метода для работы с параметрами и шаблоном при запросе страницы в браузере.
 *
 * @package engine
 * @since 1.0
 */
abstract class Action extends LsObject {
	/**
	 * Список зарегистрированных евентов
	 *
	 * @var array
	 */
	protected $aRegisterEvent=array();
	/**
	 * Список евентов, которые нужно обрабатывать внешним обработчиком
	 *
	 * @var array
	 */
	protected $aRegisterEventExternal=array();
	/**
	 * Список параметров из URL
	 * <pre>/action/event/param0/param1/../paramN/</pre>
	 *
	 * @var array
	 */
	protected $aParams=array();
	/**
	 * Список совпадений по регулярному выражению для евента
	 *
	 * @var array
	 */
	protected $aParamsEventMatch=array('event'=>array(),'params'=>array());
	/**
	 * Объект ядра
	 *
	 * @var Engine|null
	 */
	protected $oEngine=null;
	/**
	 * Шаблон экшена
	 * @see SetTemplate
	 * @see SetTemplateAction
	 *
	 * @var string|null
	 */
	protected $sActionTemplate=null;
	/**
	 * Дефолтный евент
	 * @see SetDefaultEvent
	 *
	 * @var string|null
	 */
	protected $sDefaultEvent=null;
	/**
	 * Текущий евент
	 *
	 * @var string|null
	 */
	protected $sCurrentEvent=null;
	/**
	 * Имя текущий евента
	 * Позволяет именовать экшены на основе регулярных выражений
	 *
	 * @var string|null
	 */
	protected $sCurrentEventName=null;
	/**
	 * Текущий экшен
	 *
	 * @var null|string
	 */
	protected $sCurrentAction=null;

	/**
	 * Конструктор
	 *
	 * @param Engine $oEngine Объект ядра
	 * @param string $sAction Название экшена
	 */
	public function __construct(Engine $oEngine, $sAction) {
		$this->oEngine=$oEngine;
		$this->RegisterEvent();
		$this->sCurrentAction=$sAction;
		$this->aParams=Router::GetParams();
	}

	/**
	 * Позволяет запускать не публичные методы экшена через объект
	 *
	 * @param string $sCall
	 *
	 * @return mixed
	 */
	public function ActionCall($sCall) {
		$aArgs = func_get_args();
		unset($aArgs[0]);
		return call_user_func_array(array($this,$sCall),$aArgs);
	}
	/**
	 * Проверяет метод экшена на существование
	 *
	 * @param string $sCall
	 *
	 * @return bool
	 */
	public function ActionCallExists($sCall) {
		return method_exists($this,$sCall);
	}

	/**
	 * Возвращает свойство объекта экшена
	 *
	 * @param string $sVar
	 *
	 * @return mixed
	 */
	public function ActionGet($sVar) {
		return $this->$sVar;
	}

	/**
	 * Устанавливает свойство объекта экшена
	 *
	 * @param string $sVar
	 * @param null|mixed $mValue
	 */
	public function ActionSet($sVar,$mValue=null) {
		$this->$sVar=$mValue;
	}

	/**
	 * Добавляет евент в экшен
	 * По сути является оберткой для AddEventPreg(), оставлен для простоты и совместимости с прошлыми версиями ядра
	 * @see AddEventPreg
	 *
	 * @param string $sEventName Название евента
	 * @param string $sEventFunction Какой метод ему соответствует
	 */
	protected function AddEvent($sEventName,$sEventFunction) {
		$this->AddEventPreg("/^{$sEventName}$/i",$sEventFunction);
	}

	/**
	 * Добавляет евент в экшен, используя регулярное выражение для евента и параметров
	 *
	 */
	protected function AddEventPreg() {
		$iCountArgs=func_num_args();
		if ($iCountArgs<2) {
			throw new Exception("Incorrect number of arguments when adding events");
		}
		$aEvent=array();
		/**
		 * Последний параметр может быть массивом - содержать имя метода и имя евента(именованный евент)
		 * Если указан только метод, то имя будет равным названию метода
		 */
		$aNames=(array)func_get_arg($iCountArgs-1);
		$aEvent['method']=$aNames[0];
		/**
		 * Определяем наличие внешнего обработчика евента
		 */
		$aEvent['external']=null;
		$aMethod=explode('::',$aEvent['method']);
		if (count($aMethod)>1) {
			$aEvent['method']=$aMethod[1];
			$aEvent['external']=$aMethod[0];
		}

		if (isset($aNames[1])) {
			$aEvent['name']=$aNames[1];
		} else {
			$aEvent['name']=$aEvent['method'];
		}
		if (!$aEvent['external']) {
			if (!method_exists($this,$aEvent['method'])) {
				throw new Exception("Method of the event not found: ".$aEvent['method']);
			}
		}
		$aEvent['preg']=func_get_arg(0);
		$aEvent['params_preg']=array();
		for ($i=1;$i<$iCountArgs-1;$i++) {
			$aEvent['params_preg'][]=func_get_arg($i);
		}
		$this->aRegisterEvent[]=$aEvent;
	}

	/**
	 * Регистрируем внешние обработчики для евентов
	 *
	 * @param string $sEventName
	 * @param string|array $sExternalClass
	 */
	protected function RegisterEventExternal($sEventName,$sExternalClass) {
		$this->aRegisterEventExternal[$sEventName]=$sExternalClass;
	}

	/**
	 * Запускает евент на выполнение
	 * Если текущий евент не определен то  запускается тот которые определен по умолчанию(default event)
	 *
	 * @return mixed
	 */
	public function ExecEvent() {
		$this->sCurrentEvent=Router::GetActionEvent();
		if ($this->sCurrentEvent==null) {
			$this->sCurrentEvent=$this->GetDefaultEvent();
			Router::SetActionEvent($this->sCurrentEvent);
		}
		foreach ($this->aRegisterEvent as $aEvent) {
			if (preg_match($aEvent['preg'],$this->sCurrentEvent,$aMatch)) {
				$this->aParamsEventMatch['event']=$aMatch;
				$this->aParamsEventMatch['params']=array();
				foreach ($aEvent['params_preg'] as $iKey => $sParamPreg) {
					if (preg_match($sParamPreg,$this->GetParam($iKey,''),$aMatch)) {
						$this->aParamsEventMatch['params'][$iKey]=$aMatch;
					} else {
						continue 2;
					}
				}
				$this->sCurrentEventName=$aEvent['name'];
				if ($aEvent['external']) {
					if (!isset($this->aRegisterEventExternal[$aEvent['external']])) {
						throw new Exception("External processing for event not found: ".$aEvent['external']);
					}
				}
				$this->Hook_Run("action_event_".strtolower($this->sCurrentAction)."_before",array('event'=>$this->sCurrentEvent,'params'=>$this->GetParams()));
				/**
				 * Проверяем на наличие внешнего обработчика евента
				 */
				if ($aEvent['external']) {
					$sEventClass=$this->Plugin_GetDelegate('event',$this->aRegisterEventExternal[$aEvent['external']]);
					$oEvent=new $sEventClass;
					$oEvent->SetActionObject($this);
					$oEvent->Init();
					if (!$aEvent['method']) {
						$result=$oEvent->Exec();
					} else {
						$result=call_user_func_array(array($oEvent,$aEvent['method']),array());
					}
				} else {
					$result=call_user_func_array(array($this,$aEvent['method']),array());
				}
				$this->Hook_Run("action_event_".strtolower($this->sCurrentAction)."_after",array('event'=>$this->sCurrentEvent,'params'=>$this->GetParams()));
				return $result;
			}
		}
		return $this->EventNotFound();
	}

	/**
	 * Устанавливает евент по умолчанию
	 *
	 * @param string $sEvent Имя евента
	 */
	public function SetDefaultEvent($sEvent) {
		$this->sDefaultEvent=$sEvent;
	}

	/**
	 * Получает евент по умолчанию
	 *
	 * @return string
	 */
	public function GetDefaultEvent() {
		return $this->sDefaultEvent;
	}

	/**
	 * Возвращает элементы совпадения по регулярному выражению для евента
	 *
	 * @param int|null $iItem	Номер совпадения
	 * @return string|null
	 */
	protected function GetEventMatch($iItem=null) {
		if ($iItem) {
			if (isset($this->aParamsEventMatch['event'][$iItem])) {
				return $this->aParamsEventMatch['event'][$iItem];
			} else {
				return null;
			}
		} else {
			return $this->aParamsEventMatch['event'];
		}
	}
	/**
	 * Возвращает элементы совпадения по регулярному выражению для параметров евента
	 *
	 * @param int $iParamNum	Номер параметра, начинается с нуля
	 * @param int|null $iItem	Номер совпадения, начинается с нуля
	 * @return string|null
	 */
	protected function GetParamEventMatch($iParamNum,$iItem=null) {
		if (!is_null($iItem)) {
			if (isset($this->aParamsEventMatch['params'][$iParamNum][$iItem])) {
				return $this->aParamsEventMatch['params'][$iParamNum][$iItem];
			} else {
				return null;
			}
		} else {
			if (isset($this->aParamsEventMatch['event'][$iParamNum])) {
				return $this->aParamsEventMatch['event'][$iParamNum];
			} else {
				return null;
			}
		}
	}

	/**
	 * Получает параметр из URL по его номеру, если его нет то null
	 *
	 * @param int $iOffset	Номер параметра, начинается с нуля
	 * @return mixed
	 */
	public function GetParam($iOffset,$default=null) {
		$iOffset=(int)$iOffset;
		return isset($this->aParams[$iOffset]) ? $this->aParams[$iOffset] : $default;
	}

	/**
	 * Получает список параметров из УРЛ
	 *
	 * @return array
	 */
	public function GetParams() {
		return $this->aParams;
	}


	/**
	 * Установить значение параметра(эмуляция параметра в URL).
	 * После установки занова считывает параметры из роутера - для корректной работы
	 *
	 * @param int $iOffset Номер параметра, но по идеи может быть не только числом
	 * @param string $value
	 */
	public function SetParam($iOffset,$value) {
		Router::SetParam($iOffset,$value);
		$this->aParams=Router::GetParams();
	}

	/**
	 * Устанавливает какой шаблон выводить
	 *
	 * @param string $sTemplate Путь до шаблона относительно общего каталога шаблонов
	 */
	protected function SetTemplate($sTemplate) {
		$this->sActionTemplate=$sTemplate;
	}

	/**
	 * Устанавливает какой шаблон выводить
	 *
	 * @param string $sTemplate Путь до шаблона относительно каталога шаблонов экшена
	 */
	protected function SetTemplateAction($sTemplate) {
		$aDelegates = $this->Plugin_GetDelegationChain('action',$this->GetActionClass());
		$sActionTemplatePath = $sTemplate.'.tpl';
		foreach($aDelegates as $sAction) {
			if(preg_match('/^(Plugin([\w]+)_)?Action([\w]+)$/i',$sAction,$aMatches)) {
				$sTemplatePath = $this->Plugin_GetDelegate('template','actions/Action'.ucfirst($aMatches[3]).'/'.$sTemplate.'.tpl');
				if(empty($aMatches[1])) {
					$sActionTemplatePath = $sTemplatePath;
				} else {
					$sTemplatePath = Plugin::GetTemplatePath($sAction).$sTemplatePath;
					if(is_file($sTemplatePath)) {
						$sActionTemplatePath = $sTemplatePath;
						break;
					}
				}
			}
		}
		$this->sActionTemplate = $sActionTemplatePath;
	}

	/**
	 * Получить шаблон
	 * Если шаблон не определен то возвращаем дефолтный шаблон евента: action/{Action}.{event}.tpl
	 *
	 * @return string
	 */
	public function GetTemplate() {
		if (is_null($this->sActionTemplate)) {
			$this->SetTemplateAction($this->sCurrentEvent);
		}
		return $this->sActionTemplate;
	}

	/**
	 * Получить каталог с шаблонами экшена(совпадает с именем класса)
	 * @see Router::GetActionClass
	 *
	 * @return string
	 */
	public function GetActionClass() {
		return Router::GetActionClass();
	}

	/**
	 * Возвращает имя евента
	 *
	 * @return null|string
	 */
	public function GetCurrentEventName() {
		return $this->sCurrentEventName;
	}

	/**
	 * Вызывается в том случаи если не найден евент который запросили через URL
	 * По дефолту происходит перекидывание на страницу ошибки, это можно переопределить в наследнике
	 * @see Router::Action
	 *
	 * @return string
	 */
	protected function EventNotFound() {
		return Router::Action('error','404');
	}

	/**
	 * Выполняется при завершение экшена, после вызова основного евента
	 *
	 */
	public function EventShutdown() {

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
	 * Абстрактный метод инициализации экшена
	 *
	 */
	abstract public function Init();

	/**
	 * Абстрактный метод регистрации евентов.
	 * В нём необходимо вызывать метод AddEvent($sEventName,$sEventFunction)
	 *
	 */
	abstract protected function RegisterEvent();

}
?>