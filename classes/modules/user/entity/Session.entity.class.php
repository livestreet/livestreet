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

class ModuleUser_EntitySession extends Entity 
{    
    public function getKey() {
        return $this->_aData['session_key'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getIpCreate() {
        return $this->_aData['session_ip_create'];
    }    
    public function getIpLast() {
        return $this->_aData['session_ip_last'];
    }
    public function getDateCreate() {
        return $this->_aData['session_date_create'];
    }
    public function getDateLast() {
        return $this->_aData['session_date_last'];
    }
        
    
	public function setKey($data) {
        $this->_aData['session_key']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setIpCreate($data) {
        $this->_aData['session_ip_create']=$data;
    }    
    public function setIpLast($data) {
        $this->_aData['session_ip_last']=$data;
    }
    public function setDateCreate($data) {
        $this->_aData['session_date_create']=$data;
    }
    public function setDateLast($data) {
        $this->_aData['session_date_last']=$data;
    }    
}
?>