<?php

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