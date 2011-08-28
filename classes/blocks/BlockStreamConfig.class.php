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
 */
class BlockStreamConfig extends Block {
	public function Exec() {
		if ($oUserCurrent = $this->User_getUserCurrent()) {
			$aTypesList = $this->Stream_getTypesList($oUserCurrent->getId());
			$this->Viewer_Assign('aStreamTypesList', $aTypesList);
			$aUserSubscribes = $this->Stream_getUserSubscribes($oUserCurrent->getId());
			$aFriends = $this->User_getUsersFriend($oUserCurrent->getId());
			$this->Viewer_Assign('aStreamSubscribedUsers', $aUserSubscribes);
			$this->Viewer_Assign('aStreamFriends', $aFriends);
		}
	}
}