<?php

class BlockUserfeedBlogs extends Block
{
	public function Exec() {
		if ($oUserCurrent = $this->User_getUserCurrent()) {
			$aUserSubscribes = $this->Userfeed_getUserSubscribes($oUserCurrent->getId());
			
			$aBlogsId = $this->Blog_getBlogUsersByUserId($oUserCurrent->getId(), array(ModuleBlog::BLOG_USER_ROLE_USER,ModuleBlog::BLOG_USER_ROLE_MODERATOR,ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR),true);
			$aBlogsOwnerId=$this->Blog_GetBlogsByOwnerId($oUserCurrent->getId(),true);
			$aBlogsId=array_merge($aBlogsId,$aBlogsOwnerId);
						
			$aBlogs=$this->Blog_GetBlogsAdditionalData($aBlogsId,array('owner'=>array()),array('blog_title'=>'asc'));
									
			$this->Viewer_Assign('aUserfeedSubscribedBlogs', $aUserSubscribes['blogs']);
			$this->Viewer_Assign('aUserfeedBlogs', $aBlogs);
		}
	}
}