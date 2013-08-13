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
	 * Объект для анализа структуры класса экшена
	 *
	 * @var null
	 */
	protected $oActionReflection=null;

	public function __construct() {

	}

	/**
	 * Устанавливает объект экшена
	 *
	 * @param Action $oAction Объект текущего экшена
	 */
	public function SetActionObject($oAction) {
		$this->oAction=$oAction;
		$this->oActionReflection=new ReflectionClass($this->oAction);
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
		if ($this->oActionReflection->hasProperty($sName)) {
			return call_user_func_array(array($this->oAction,'ActionGet'),array($sName));
		}
	}

	public function __set($sName,$mValue) {
		if ($this->oActionReflection->hasProperty($sName)) {
			return call_user_func_array(array($this->oAction,'ActionSet'),array($sName,$mValue));
		}
	}

	public function __call($sName,$aArgs) {
		/**
		 * Обработка вызова методов экшена
		 */
		if ($this->oAction->ActionCallExists($sName)) {
			array_unshift($aArgs,$sName);
			return call_user_func_array(array($this->oAction,'ActionCall'),$aArgs);
		}

		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
}