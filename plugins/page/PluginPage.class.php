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

class PluginPage extends Plugin {
	
	/**
	 * Активация плагина "Статические страницы".
	 * Создание таблицы в базе данных при ее отсутствии.
	 */
	public function Activate() {		
		return true;
	}
	
	/**
	 * Инициализация плагина
	 */
	public function Init() {
	
	}
}
?>