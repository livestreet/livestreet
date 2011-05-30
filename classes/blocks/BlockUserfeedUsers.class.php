<?php

class BlockUserfeedUsers extends Block
{
	public function Exec() {
        $aUserSubscribes = $this->Userfeed_getUserSubscribes($this->User_getUserCurrent()->getId());
        $aFriends = $this->User_getUsersFriend($this->User_getUserCurrent()->getId());
        $this->Viewer_Assign('aUserfeedSubscribedUsers', $aUserSubscribes['users']);
        $this->Viewer_Assign('aUserfeedFriends', $aFriends);
    }
}