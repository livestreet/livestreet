<?php

class BlockUserfeedUsers extends Block
{
	public function Exec() {
		if ($oUserCurrent = $this->User_getUserCurrent()) {
			$aUserSubscribes = $this->Userfeed_getUserSubscribes($oUserCurrent->getId());
			$aFriends = $this->User_getUsersFriend($oUserCurrent->getId());
			$this->Viewer_Assign('aUserfeedSubscribedUsers', $aUserSubscribes['users']);
			$this->Viewer_Assign('aUserfeedFriends', $aFriends);
		}
	}
}