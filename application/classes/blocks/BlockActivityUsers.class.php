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
 * Блок выбора пользователей для чтения в ленте активности
 *
 * @package blocks
 * @since 1.0
 */
class BlockActivityUsers extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		/**
		 * пользователь авторизован?
		 */
		if ($oUserCurrent = $this->User_getUserCurrent()) {
			$this->Viewer_Assign('users', $this->Stream_getUserSubscribes($oUserCurrent->getId()));
		}
	}
}