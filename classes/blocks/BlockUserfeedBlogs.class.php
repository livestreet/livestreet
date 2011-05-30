<?php

class BlockUserfeedBlogs extends Block
{
	public function Exec() {
        $aUserSubscribes = $this->Userfeed_getUserSubscribes($this->User_getUserCurrent()->getId());
        $aBlogsTmp = $this->Blog_getBlogUsersByUserId($this->User_getUserCurrent()->getId(), ModuleBlog::BLOG_USER_ROLE_USER);
        $aBlogs = array();
        foreach ($aBlogsTmp as $oUserBlog) {
            $aBlogs[$oUserBlog->getBlogId()] = $oUserBlog->getBlog();
        }
        $this->Viewer_Assign('aUserfeedSubscribedBlogs', $aUserSubscribes['blogs']);
        $this->Viewer_Assign('aUserfeedBlogs', $aBlogs);
    }
}