<?
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
	 *
	 * @param string $sEventName Название евента
	 * @param string $sEventFunction Какой метод ему соответствует
	 */
	protected function AddEvent($sEventName,$sEventFunction) {
		$sEventName=strtolower($sEventName);
		if (!isset($this->aRegisterEvent[$sEventName])) {
			if (method_exists($this,$sEventFunction)) {
				$this->aRegisterEvent[$sEventName]=$sEventFunction;
			} else {
				throw new Exception("Добавляемый метод евента не найден: ".$sEventFunction);
			}			
		}
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
		if (isset($this->aRegisterEvent[$this->sCurrentEvent])) {						
			$sCmd='$result=$this->'.$this->aRegisterEvent[$this->sCurrentEvent].'();';
			eval($sCmd);			
			return $result;
		} else {
			return $this->EventNotFound();
		}		
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
	 * Получает параметр из URL по его номеру, если его нет то null
	 *
	 * @param unknown_type $iOffset
	 * @return unknown
	 */
	public function GetParam($iOffset) {
		$iOffset=(int)$iOffset;
		return isset($this->aParams[$iOffset]) ? $this->aParams[$iOffset] : null;
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
		$this->sActionTemplate='actions/'.$this->GetActionClass().'/'.$sTemplate.'.tpl';
	}
	
	/**
	 * Получить шаблон
	 * Если шаблон не определен то возвращаем дефолтный шаблон евента: action/{Action}.{event}.tpl
	 *
	 * @return unknown
	 */
	public function GetTemplate() {
		if (is_null($this->sActionTemplate)) {
			$this->sActionTemplate='actions/'.$this->GetActionClass().'/'.$this->sCurrentEvent.'.tpl';
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
		$this->Message_AddErrorSingle('К сожалению, такой страницы не существует. Вероятно, она была удалена с сервера, либо ее здесь никогда не было.','404');
		return Router::Action('error');
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