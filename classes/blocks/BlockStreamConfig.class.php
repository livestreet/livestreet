<?php

class BlockStreamConfig extends Block
{
	public function Exec() {
        $aTypesList = $this->Stream_getTypesList($this->User_getUserCurrent()->getId());
        $this->Viewer_Assign('aStreamTypesList', $aTypesList);
        $aUserSubscribes = $this->Stream_getUserSubscribes($this->User_getUserCurrent()->getId());
        $aFriends = $this->User_getUsersFriend($this->User_getUserCurrent()->getId());
        $this->Viewer_Assign('aStreamSubscribedUsers', $aUserSubscribes);
        $this->Viewer_Assign('aStreamFriends', $aFriends);
    }
}
