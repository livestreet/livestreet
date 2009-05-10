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

class TalkEntity_TalkComment extends Entity 
{    
    public function getId() {
        return $this->_aData['talk_comment_id'];
    } 
    public function getPid() {
        return $this->_aData['talk_comment_pid'];
    } 
    public function getTalkId() {
        return $this->_aData['talk_id'];
    }
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getText() {
        return $this->_aData['talk_comment_text'];
    }
    public function getDate() {
        return $this->_aData['talk_comment_date'];
    }
    public function getUserIp() {
        return $this->_aData['talk_comment_user_ip'];
    }    
    
    
    public function getUserLogin() {
        return $this->_aData['user_login'];
    }
    public function getLevel() {
        return $this->_aData['level'];
    }
	public function getUserProfileAvatar() {
        return $this->_aData['user_profile_avatar'];
    }
    public function getUserProfileAvatarType() {
        return $this->_aData['user_profile_avatar_type'];
    }
    public function getUserProfileAvatarPath($iSize=100) {     	  
    	if ($this->getUserProfileAvatar()) { 	
        	return DIR_WEB_ROOT.DIR_UPLOADS_IMAGES.'/'.$this->getUserId().'/avatar_'.$iSize.'x'.$iSize.'.'.$this->getUserProfileAvatarType();
    	} else {
    		return DIR_STATIC_SKIN.'/images/avatar_'.$iSize.'x'.$iSize.'.jpg';
    	}
    }
	public function getTalkTitle() {
        return $this->_aData['talk_title'];
    }
        
    
    
	public function setId($data) {
        $this->_aData['talk_comment_id']=$data;
    }
    public function setPid($data) {
        $this->_aData['talk_comment_pid']=$data;
    }
    public function setTalkId($data) {
        $this->_aData['talk_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setText($data) {
        $this->_aData['talk_comment_text']=$data;
    }
    public function setDate($data) {
        $this->_aData['talk_comment_date']=$data;
    }
    public function setUserIp($data) {
        $this->_aData['talk_comment_user_ip']=$data;
    }  
}
?>