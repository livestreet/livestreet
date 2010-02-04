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
 * Абстракция модуля, от которой наследуются все модули
 *
 */
abstract class Module extends Object {
	/**
	 * Engine object
	 *
	 * @var Engine
	 */
	protected $oEngine=null;
	
	/**
	 * Указывает на то, была ли проведенна инициализация модуля
	 *
	 * @var bool
	 */
	protected $bIsInit=false;
	
	final public function __construct(Engine $oEngine) {		
		$this->oEngine=$oEngine;		
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
	 * Абстрактный метод инициализации модуля, должен быть переопределен в модуле
	 *
	 */
	abstract public function Init();
	
	/**
	 * Метод срабатывает после отработки цепочки экшенов(action)
	 *
	 */
	public function Shutdown() {
		
	}
	
	/**
	 * Возвращает значение флага инициализации
	 *
	 * @return bool
	 */
	public function isInit() {
		return $this->bIsInit;
	}
	
	/**
	 * Помечает модуль как инициализированный
	 *
	 * @return null
	 */
	public function SetInit() {
		$this->bIsInit=true;
	}
}
?>