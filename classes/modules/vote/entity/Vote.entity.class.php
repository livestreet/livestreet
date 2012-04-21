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

class ModuleVote_EntityVote extends Entity 
{    
    public function getTargetId() {
        return $this->_getDataOne('target_id');
    }
    public function getTargetType() {
        return $this->_getDataOne('target_type');
    }  
    public function getVoterId() {
        return $this->_getDataOne('user_voter_id');
    }
    public function getDirection() {
        return $this->_getDataOne('vote_direction');
    }
    public function getValue() {
        return $this->_getDataOne('vote_value');
    }
    public function getDate() {
		return $this->_getDataOne('vote_date');
    }
	public function getIp() {
		return $this->_getDataOne('vote_ip');
	}

    
    
	public function setTargetId($data) {
        $this->_aData['target_id']=$data;
    }
    public function setTargetType($data) {
        $this->_aData['target_type']=$data;
    }
    public function setVoterId($data) {
        $this->_aData['user_voter_id']=$data;
    }
    public function setDirection($data) {
        $this->_aData['vote_direction']=$data;
    }
    public function setValue($data) {
        $this->_aData['vote_value']=$data;
    }
    public function setDate($data) {
        $this->_aData['vote_date']=$data;
    }
	public function setIp($data) {
		$this->_aData['vote_ip']=$data;
	}
}
?>