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
 * Абстрактный класс внешнего обработчика евента.
 *
 * От этого класса наследуются внешние обработчики евентов.
 *
 * @package engine
 * @since 1.1
 */
abstract class Event extends LsObject {

	/**
	 * Объект текущего экшена
	 *
	 * @var null|Action
	 */
	protected $oAction=null;
	/**
	 * Список приватных методов экшена для проксирования из внешнего евента
	 *
	 * @var array
	 */
	protected $aMethodProxyAction=array(
		'GetDefaultEvent','GetEventMatch','GetParamEventMatch','GetParam','GetParams',
		'SetParam','SetTemplate','SetTemplateAction','EventNotFound',
	);

	public function __construct() {
		/**
		 * Переводим доступные методы к нижнему регистру
		 */
		$aMethods=array();
		foreach($this->aMethodProxyAction as $sMethod) {
			$aMethods[]=strtolower($sMethod);
		}
		$this->aMethodProxyAction=$aMethods;
	}

	/**
	 * Устанавливает объект экшена
	 *
	 * @param Action $oAction Объект текущего экшена
	 */
	public function SetActionObject($oAction) {
		$this->oAction=$oAction;
	}

	/**
	 * Запускается для обработки евента, если у него не указанно имя, например, "User::"
	 */
	public function Exec() {

	}

	/**
	 * Запускается всегда перед вызовом метода евента
	 */
	public function Init() {

	}

	public function __get($sName) {
		if (property_exists($this->oAction,$sName)) {
			return $this->oAction->$sName;
		}
	}

	public function __set($sName,$mValue) {
		if (property_exists($this->oAction,$sName)) {
			return $this->oAction->$sName=$mValue;
		}
	}

	public function __call($sName,$aArgs) {
		/**
		 * Обработка вызова приватных методов экшена
		 */
		if (in_array(strtolower($sName),$this->aMethodProxyAction)) {
			array_unshift($aArgs,$sName);
			return call_user_func_array(array($this->oAction,'ActionCall'),$aArgs);
		}
		/**
		 * Обработка вызова публичных методов экшена
		 */
		if (method_exists($this->oAction,$sName)) {
			return call_user_func_array(array($this->oAction,$sName),$aArgs);
		}
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
}