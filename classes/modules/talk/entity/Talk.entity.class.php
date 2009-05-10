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

class TalkEntity_Talk extends Entity 
{    
    public function getId() {
        return $this->_aData['talk_id'];
    }      
    public function getUserId() {
        return $this->_aData['user_id'];
    }    
    public function getTitle() {
        return $this->_aData['talk_title'];
    }
    public function getText() {
        return $this->_aData['talk_text'];
    }    
    public function getDate() {
        return $this->_aData['talk_date'];
    }    
    public function getDateLast() {
        return $this->_aData['talk_date_last'];
    }
    public function getUserIp() {
        return $this->_aData['talk_user_ip'];
    }
      
    
    public function getUserLogin() {
        return $this->_aData['user_login'];
    }
    public function getUsers() {
    	return $this->_aData['users'];
    }
    public function getCountUsers() {
    	return count($this->_aData['users']);
    }
	public function getCountComment() {
    	return $this->_aData['count_comment'];
    }
    public function getCountCommentNew() {
    	return $this->_aData['count_comment_new'];
    }
    public function getDateLastRead() {
    	return $this->_aData['date_last_read'];
    }
    
    
    
	public function setId($data) {
        $this->_aData['talk_id']=$data;
    }   
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }    
    public function setTitle($data) {
        $this->_aData['talk_title']=$data;
    }
    public function setText($data) {
        $this->_aData['talk_text']=$data;
    }    
    public function setDate($data) {
        $this->_aData['talk_date']=$data;
    }    
    public function setDateLast($data) {
        $this->_aData['talk_date_last']=$data;
    } 
    public function setUserIp($data) {
        $this->_aData['talk_user_ip']=$data;
    }  
    
    public function setUsers($data) {
        $this->_aData['users']=$data;
    }  
}
?>