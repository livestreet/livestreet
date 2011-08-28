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
 * Блок настройки списка пользователей в ленте
 *
 */
class BlockUserfeedUsers extends Block {
	public function Exec() {
		if ($oUserCurrent = $this->User_getUserCurrent()) {
			$aUserSubscribes = $this->Userfeed_getUserSubscribes($oUserCurrent->getId());
			$aFriends = $this->User_getUsersFriend($oUserCurrent->getId());
			$this->Viewer_Assign('aUserfeedSubscribedUsers', $aUserSubscribes['users']);
			$this->Viewer_Assign('aUserfeedFriends', $aFriends);
		}
	}
}