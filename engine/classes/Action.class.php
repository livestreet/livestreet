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
 * Абстрактный класс экшена
 *
 */
abstract class Action extends Object {
	
	protected $aRegisterEvent=array();
	protected $aParams=array();
	protected $aParamsEventMatch=array('event'=>array(),'params'=>array());
	protected $oEngine=null;
	protected $sActionTemplate=null;
	protected $sDefaultEvent=null;
	protected $sCurrentEvent=null;
	protected $sCurrentAction=null;
	
	/**
	 * Конструктор
	 *
	 * @param Engine $oEngine
	 * @param string $sAction
	 */
	public function __construct(Engine $oEngine, $sAction) {
		$this->RegisterEvent();
		$this->oEngine=$oEngine;
		$this->sCurrentAction=$sAction;
		$this->aParams=Router::GetParams();	
	}

	/**
	 * Добавляет евент в экшен
	 * По сути является оберткой для AddEventPreg(), оставлен для простоты и совместимости с прошлыми версиями ядра
	 *
	 * @param string $sEventName Название евента
	 * @param string $sEventFunction Какой метод ему соответствует
	 */	
	protected function AddEvent($sEventName,$sEventFunction) {
		$this->AddEventPreg("/^{$sEventName}$/i",$sEventFunction);
	}
	
	/**
	 * Добавляет евент в экшен, используя регулярное вырожение для евента и параметров
	 *
	 */
	protected function AddEventPreg() {		
		$iCountArgs=func_num_args();
		if ($iCountArgs<2) {
			throw new Exception("Incorrect number of arguments when adding events");
		}
		$aEvent=array();
		$aEvent['method']=func_get_arg($iCountArgs-1);
		if (!method_exists($this,$aEvent['method'])) {			
			throw new Exception("Method of the event not found: ".$aEvent['method']);
		}
		$aEvent['preg']=func_get_arg(0);		
		$aEvent['params_preg']=array();
		for ($i=1;$i<$iCountArgs-1;$i++) {
			$aEvent['params_preg'][]=func_get_arg($i);
		}
		$this->aRegisterEvent[]=$aEvent;		
	}
	
	/**
	 * Запускает евент на выполнение
	 * Если текущий евент не определен то  запускается тот которые определен по умолчанию(default event)
	 *
	 * @return unknown
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
				$sCmd='$result=$this->'.$aEvent['method'].'();';
				$this->Hook_Run("action_event_".strtolower($this->sCurrentAction)."_before",array('event'=>$this->sCurrentEvent,'params'=>$this->GetParams()));
				eval($sCmd);
				$this->Hook_Run("action_event_".strtolower($this->sCurrentAction)."_after",array('event'=>$this->sCurrentEvent,'params'=>$this->GetParams()));
				return $result;
			}
		}
		return $this->EventNotFound();
	}	
	
	/**
	 * Устанавливает евент по умолчанию
	 *
	 * @param string $sEvent
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
	 * @param unknown_type $iItem
	 * @return unknown
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
	 * @param unknown_type $iParamNum
	 * @param unknown_type $iItem
	 * @return unknown
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
	 * @param unknown_type $iOffset
	 * @return unknown
	 */
	public function GetParam($iOffset,$default=null) {
		$iOffset=(int)$iOffset;
		return isset($this->aParams[$iOffset]) ? $this->aParams[$iOffset] : $default;
	}
	
	/**
	 * Получает список параметров из УРЛ
	 *
	 * @return unknown
	 */
	public function GetParams() {		
		return $this->aParams;
	}
	
	
	/**
	 * Установить значение параметра(эмуляция параметра в URL). 
	 * После установки занова считывает параметры из роутера - для корректной работы
	 *
	 * @param int $iOffset - по идеи может быть не только числом
	 * @param unknown_type $value	 
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
	 * @return unknown
	 */
	public function GetTemplate() {
		if (is_null($this->sActionTemplate)) {
		    $this->SetTemplateAction($this->sCurrentEvent);
		}
		return $this->sActionTemplate;
	}
	
	/**
	 * Получить каталог с шаблонами экшена(совпадает с именем класса)
	 *
	 * @return unknown
	 */
	public function GetActionClass() {
		return Router::GetActionClass();
	}
	
	/**
	 * Вызывается в том случаи если не найден евент который запросили через URL
	 * По дефолту происходит перекидывание на страницу ошибки, это можно переопределить в наследнике, а в ряде случаев и необходимо :) Для примера смотри экшен Profile
	 *
	 * @return unknown
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
	 *
	 * @param string $sName
	 * @param array $aArgs
	 * @return unknown
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