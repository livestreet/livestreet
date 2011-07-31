<?php

class BlockUserfeedBlogs extends Block
{
	public function Exec() {
		if ($oUserCurrent = $this->User_getUserCurrent()) {
			$aUserSubscribes = $this->Userfeed_getUserSubscribes($oUserCurrent->getId());
			$aBlogsTmp = $this->Blog_getBlogUsersByUserId($oUserCurrent->getId(), ModuleBlog::BLOG_USER_ROLE_USER);
			$aBlogs = array();
			foreach ($aBlogsTmp as $oUserBlog) {
				$aBlogs[$oUserBlog->getBlogId()] = $oUserBlog->getBlog();
			}
			$this->Viewer_Assign('aUserfeedSubscribedBlogs', $aUserSubscribes['blogs']);
			$this->Viewer_Assign('aUserfeedBlogs', $aBlogs);
		}
	}
}