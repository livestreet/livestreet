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
          return ($sPath=$this->getAvatarPath()) ? pathinfo($sPath,PATHINFO_EXTENSION) : null;
    }
    
    
    
    public function getOwner() {
        return $this->_aData['owner'];
    }    
    public function getVote() {
        return $this->_aData['vote'];
    }
    public function getAvatarPath($iSize=48) {   
    	if ($sPath=$this->getAvatar()) { 	
        	return str_replace('_48x48',(($iSize==0)?"":"_{$iSize}x{$iSize}"),$sPath);;
    	} else {
    		return Config::Get('path.static.skin').'/images/avatar_blog_'.$iSize.'x'.$iSize.'.gif';
    	}
    }
    public function getUserIsJoin() {
        return $this->_aData['user_is_join'];
    }
    public function getUserIsAdministrator() {
        return $this->_aData['user_is_administrator'];
    }
    public function getUserIsModerator() {
        return $this->_aData['user_is_moderator'];
    }
    public function getUrlFull() {
        if ($this->getType()=='personal') {
    		return Router::GetPath('my').$this->getOwner()->getLogin().'/';
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