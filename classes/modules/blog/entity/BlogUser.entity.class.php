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

class BlogEntity_BlogUser extends Entity 
{    
    public function getBlogId() {
        return $this->_aData['blog_id'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getIsModerator() {
        return $this->_aData['is_moderator'];
    }
    public function getIsAdministrator() {
        return $this->_aData['is_administrator'];
    }

        

    public function getBlogUrl() {
        return $this->_aData['blog_url'];
    }
    public function getBlogTitle() {
        return $this->_aData['blog_title'];
    }
    public function getUserLogin() {
        return $this->_aData['user_login'];
    }   
    public function getUserMail() {
        return $this->_aData['user_mail'];
    }
	public function getUserProfileAvatar() {
        return $this->_aData['user_profile_avatar'];
    }
    public function getUserProfileAvatarType() {
        return $this->_aData['user_profile_avatar_type'];
    }
    public function getUserProfileAvatarPath($iSize=100) {   
    	if ($this->getUserProfileAvatar()) { 	
        	return DIR_WEB_ROOT.'/'.DIR_UPLOADS_IMAGES.'/'.$this->getUserId().'/avatar_'.$iSize.'x'.$iSize.'.'.$this->getUserProfileAvatarType();
    	} else {
    		return DIR_STATIC_SKIN.'/images/avatar_'.$iSize.'x'.$iSize.'.jpg';
    	}
    }
    public function getUserSettingsNoticeNewTopic() {
        return $this->_aData['user_settings_notice_new_topic'];
    }
    public function getUserSettingsNoticeNewComment() {
        return $this->_aData['user_settings_notice_new_comment'];
    }
    public function getUserSettingsNoticeNewTalk() {
        return $this->_aData['user_settings_notice_new_talk'];
    }
    public function getUserSettingsNoticeReplyComment() {
        return $this->_aData['user_settings_notice_reply_comment'];
    }
  
    
	public function setBlogId($data) {
        $this->_aData['blog_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setIsModerator($data) {
        $this->_aData['is_moderator']=$data;
    }
    public function setIsAdministrator($data) {
        $this->_aData['is_administrator']=$data;
    }


}
?>