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

class ModuleUser_EntityReminder extends Entity 
{    
    public function getCode() {
        return $this->_aData['reminder_code'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getDateAdd() {
        return $this->_aData['reminder_date_add'];
    }
    public function getDateUsed() {
        return $this->_aData['reminder_date_used'];
    }
    public function getDateExpire() {
        return $this->_aData['reminder_date_expire'];
    }
    public function getIsUsed() {
        return $this->_aData['reminde_is_used'];
    }
        
    
	public function setCode($data) {
        $this->_aData['reminder_code']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }   
    public function setDateAdd($data) {
        $this->_aData['reminder_date_add']=$data;
    } 
    public function setDateUsed($data) {
        $this->_aData['reminder_date_used']=$data;
    } 
    public function setDateExpire($data) {
        $this->_aData['reminder_date_expire']=$data;
    } 
    public function setIsUsed($data) {
        $this->_aData['reminde_is_used']=$data;
    }  
}
?>