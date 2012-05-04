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
 * Класс инициализации экшенов.
 * Он вызывается всегда перед запуском любого экшена. Может выполнять какие то инициализирующие действия, а так же может помочь при введении инвайтов,
 * т.е. перенапрявлять всех неавторизованных юзеров на страницу регистрации по приглашению
 *
 * @package actions
 * @since 1.0
 */
class Init {
	/**
	 * Объект ядра
	 *
	 * @var Engine|null
	 */
	protected $oEngine=null;
	/**
	 * Конструктор
	 *
	 */
	public function __construct($oEngine) {
		$this->oEngine=$oEngine;
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
	 * Логика инициализации
	 *
	 */
	public function InitAction() {
		$this->Hook_Run('init_action');
	}
}
?>