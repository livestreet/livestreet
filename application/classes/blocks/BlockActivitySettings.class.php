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
 * Блок настройки ленты активности
 *
 * @package blocks
 * @since 1.0
 */
class BlockActivitySettings extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		/**
		 * пользователь авторизован?
		 */
		if ($oUserCurrent = $this->User_getUserCurrent()) {
			/**
			 * Получаем и прогружаем необходимые переменные в шаблон
			 */
			$aTypesList = $this->Stream_getTypesList($oUserCurrent->getId());
			$this->Viewer_Assign('aStreamTypesList', $aTypesList);
		}
	}
}