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

class ModuleBlog_EntityBlog extends Entity 
{    
    public function getId() {
        return $this->_getDataOne('blog_id');
    }  
    public function getOwnerId() {
        return $this->_getDataOne('user_owner_id');
    }
    public function getTitle() {
        return $this->_getDataOne('blog_title');
    }
    public function getDescription() {
        return $this->_getDataOne('blog_description');
    }
    public function getType() {
        return $this->_getDataOne('blog_type');
    }
    public function getDateAdd() {
        return $this->_getDataOne('blog_date_add');
    }
    public function getDateEdit() {
        return $this->_getDataOne('blog_date_edit');
    }
    public function getRating() {        
        return number_format(round($this->_getDataOne('blog_rating'),2), 2, '.', '');
    }
    public function getCountVote() {
        return $this->_getDataOne('blog_count_vote');
    }
    public function getCountUser() {
        return $this->_getDataOne('blog_count_user');
    }
	public function getCountTopic() {
		return $this->_getDataOne('blog_count_topic');
	}
    public function getLimitRatingTopic() {
        return $this->_getDataOne('blog_limit_rating_topic');
    }
	public function getUrl() {
        return $this->_getDataOne('blog_url');
    }
    public function getAvatar() {
        return $this->_getDataOne('blog_avatar');
    }
    public function getAvatarType() {
          return ($sPath=$this->getAvatarPath()) ? pathinfo($sPath,PATHINFO_EXTENSION) : null;
    }
    
    
    
    public function getOwner() {
        return $this->_getDataOne('owner');
    }    
    public function getVote() {
        return $this->_getDataOne('vote');
    }
    public function getAvatarPath($iSize=48) {   
    	if ($sPath=$this->getAvatar()) {
        	return preg_replace("#_\d{1,3}x\d{1,3}(\.\w{3,4})$#", ((($iSize==0)?"":"_{$iSize}x{$iSize}") . "\\1"),$sPath);
    	} else {
    		return Config::Get('path.static.skin').'/images/avatar_blog_'.$iSize.'x'.$iSize.'.gif';
    	}
    }
    public function getUserIsJoin() {
        return $this->_getDataOne('user_is_join');
    }
    public function getUserIsAdministrator() {
        return $this->_getDataOne('user_is_administrator');
    }
    public function getUserIsModerator() {
        return $this->_getDataOne('user_is_moderator');
    }
    public function getUrlFull() {
        if ($this->getType()=='personal') {
    		return $this->getOwner()->getUserWebPath().'created/topics/';
    	} else {
    		return Router::GetPath('blog').$this->getUrl().'/';
    	}
    }
           
    
	public function setId($data) {
        $this->_aData['blog_id']=$data;
    }
    public function setOwnerId($data) {
        $this->_aData['user_owner_id']=$data;
    }
    public function setTitle($data) {
        $this->_aData['blog_title']=$data;
    }
    public function setDescription($data) {
        $this->_aData['blog_description']=$data;
    }
    public function setType($data) {
        $this->_aData['blog_type']=$data;
    }
    public function setDateAdd($data) {
        $this->_aData['blog_date_add']=$data;
    }   
    public function setDateEdit($data) {
        $this->_aData['blog_date_edit']=$data;
    } 
    public function setRating($data) {
        $this->_aData['blog_rating']=$data;
    }
    public function setCountVote($data) {
        $this->_aData['blog_count_vote']=$data;
    }
    public function setCountUser($data) {
        $this->_aData['blog_count_user']=$data;
    }
	public function setCountTopic($data) {
		$this->_aData['blog_count_topic']=$data;
	}
    public function setLimitRatingTopic($data) {
        $this->_aData['blog_limit_rating_topic']=$data;
    }
    public function setUrl($data) {
        $this->_aData['blog_url']=$data;
    }
    public function setAvatar($data) {
        $this->_aData['blog_avatar']=$data;
    }
    
    public function setOwner($data) {
        $this->_aData['owner']=$data;
    }
    public function setUserIsAdministrator($data) {
        $this->_aData['user_is_administrator']=$data;
    }
    public function setUserIsModerator($data) {
        $this->_aData['user_is_moderator']=$data;
    }
    public function setUserIsJoin($data) {
        $this->_aData['user_is_join']=$data;
    }
    public function setVote($data) {
        $this->_aData['vote']=$data;
    }
}
?>