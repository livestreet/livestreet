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

class BlogEntity_Blog extends Entity 
{    
    public function getId() {
        return $this->_aData['blog_id'];
    }  
    public function getOwnerId() {
        return $this->_aData['user_owner_id'];
    }
    public function getTitle() {
        return $this->_aData['blog_title'];
    }
    public function getDescription() {
        return $this->_aData['blog_description'];
    }
    public function getType() {
        return $this->_aData['blog_type'];
    }
    public function getDateAdd() {
        return $this->_aData['blog_data_add'];
    }
    public function getDateEdit() {
        return $this->_aData['blog_data_edit'];
    }
    public function getRating() {        
        return number_format(round($this->_aData['blog_rating'],2), 2, '.', '');
    }
    public function getCountVote() {
        return $this->_aData['blog_count_vote'];
    }
    public function getCountUser() {
        return $this->_aData['blog_count_user'];
    }
    public function getLimitRatingTopic() {
        return $this->_aData['blog_limit_rating_topic'];
    }
	public function getUrl() {
        return $this->_aData['blog_url'];
    }
    public function getAvatar() {
        return $this->_aData['blog_avatar'];
    }
    public function getAvatarType() {
        return $this->_aData['blog_avatar_type'];
    }
    
    public function getUserLogin() {
        return $this->_aData['user_login'];
    }    
	public function getUserProfileAvatar() {
        return $this->_aData['user_profile_avatar'];
    }
    public function getUserProfileAvatarType() {
        return $this->_aData['user_profile_avatar_type'];
    }
    public function getUserProfileAvatarPath($iSize=100) {   
    	if ($this->getUserProfileAvatar()) { 	
        	return DIR_WEB_ROOT.DIR_UPLOADS_IMAGES.'/'.$this->getOwnerId().'/avatar_'.$iSize.'x'.$iSize.'.'.$this->getUserProfileAvatarType();
    	} else {
    		return DIR_STATIC_SKIN.'/images/avatar_'.$iSize.'x'.$iSize.'.jpg';
    	}
    }
    public function getUserIsVote() {
        return $this->_aData['user_is_vote'];
    }
    public function getUserVoteDelta() {
        return $this->_aData['user_vote_delta'];
    }
    public function getAvatarPath($iSize=48) {   
    	if ($this->getAvatar()) { 	
        	return DIR_WEB_ROOT.DIR_UPLOADS_IMAGES.'/'.$this->getOwnerId()."/avatar_blog_{$this->getUrl()}_".$iSize.'x'.$iSize.'.'.$this->getAvatarType();
    	} else {
    		return DIR_STATIC_SKIN.'/images/avatar_blog_'.$iSize.'x'.$iSize.'.gif';
    	}
    }
    public function getCurrentUserIsJoin() {
        return $this->_aData['current_user_is_join'];
    }
    public function getUrlFull() {
        if ($this->getType()=='personal') {
    		return DIR_WEB_ROOT.'/'.ROUTE_PAGE_MY.'/'.$this->getUserLogin().'/';
    	} else {
    		return DIR_WEB_ROOT.'/'.ROUTE_PAGE_BLOG.'/'.$this->getUrl().'/';
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
        $this->_aData['blog_data_add']=$data;
    }   
    public function setDateEdit($data) {
        $this->_aData['blog_data_edit']=$data;
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
    public function setLimitRatingTopic($data) {
        $this->_aData['blog_limit_rating_topic']=$data;
    }
    public function setUrl($data) {
        $this->_aData['blog_url']=$data;
    }
    public function setAvatar($data) {
        $this->_aData['blog_avatar']=$data;
    }
    public function setAvatarType($data) {
        $this->_aData['blog_avatar_type']=$data;
    }
}
?>