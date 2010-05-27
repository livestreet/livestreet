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

class ModuleUser_EntityInvite extends Entity 
{    
    public function getId() {
        return $this->_aData['invite_id'];
    }  
    public function getCode() {
        return $this->_aData['invite_code'];
    }
    public function getUserFromId() {
        return $this->_aData['user_from_id'];
    }
    public function getUserToId() {
        return $this->_aData['user_to_id'];
    }
    public function getDateAdd() {
        return $this->_aData['invite_date_add'];
    }
    public function getDateUsed() {
        return $this->_aData['invite_date_used'];
    }
    public function getUsed() {
        return $this->_aData['invite_used'];
    }
    
    
    
	public function setId($data) {
        $this->_aData['invite_id']=$data;
    }
    public function setCode($data) {
        $this->_aData['invite_code']=$data;
    }
    public function setUserFromId($data) {
        $this->_aData['user_from_id']=$data;
    }
    public function setUserToId($data) {
        $this->_aData['user_to_id']=$data;
    }
    public function setDateAdd($data) {
        $this->_aData['invite_date_add']=$data;
    }
    public function setDateUsed($data) {
        $this->_aData['invite_date_used']=$data;
    }
    public function setUsed($data) {
        $this->_aData['invite_used']=$data;
    }
    
}
?>