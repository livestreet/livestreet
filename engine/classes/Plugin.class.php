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
 * Абстракция плагина, от которой наследуются все плагины
 *
 */
abstract class Plugin extends Object {
		
	public function __construct() {

	}

	/**
	 * Функция инициализации плагина
	 *
	 */
	public function Init() {

	}

	/**
	 * Функция активации плагина
	 *
	 */
	public function Activate() {

	}
	
	/**
	 * Функция деактивации плагина
	 *
	 */
	public function Deativate() {

	}
	
	public function __call($sName,$aArgs) {
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
}
?>