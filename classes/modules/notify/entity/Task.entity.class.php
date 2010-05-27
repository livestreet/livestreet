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

class ModuleNotify_EntityTask extends Entity 
{   
    public function getTaskId() {
        return $this->_aData['notify_task_id'];
    }  
    public function getUserMail() {
        return $this->_aData['user_mail'];
    }
    public function getUserLogin() {
    	return $this->_aData['user_login'];
    }
    public function getNotifyText() {
        return $this->_aData['notify_text'];
    }
    public function getDateCreated() {
    	return $this->_aData['date_created'];
    }
    public function getTaskStatus() {
    	return $this->_aData['notify_task_status'];
    }
    public function getNotifySubject() {
    	return $this->_aData['notify_subject'];
    }
    
    
    public function setTaskId($data) {
    	$this->_aData['notify_task_id']=$data;
    }
    public function setUserMail($data) {
    	$this->_aData['user_mail']=$data;
    }
    public function setUserLogin($data) {
    	$this->_aData['user_login']=$data;
    }
    public function setNotifyText($data) {
    	$this->_aData['notify_text']=$data;
    }
    public function setDateCreated($data) {
    	$this->_aData['date_created']=$data;
    }
    public function setTaskStatus($data) {
    	$this->_aData['notify_task_status']=$data;
    }
    public function setNotifySubject($data) {
    	$this->_aData['notify_subject']=$data;
    }
}
?>