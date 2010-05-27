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

class ModuleFavourite_EntityFavourite extends Entity 
{   
    public function getTargetId() {
        return $this->_aData['target_id'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getTargetPublish() {
        return $this->_aData['target_publish'];
    }
    public function getTargetType() {
    	return $this->_aData['target_type'];
    }
    
	public function setTargetId($data) {
        $this->_aData['target_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setTargetPublish($data) {
        $this->_aData['target_publish']=$data;
    }
    public function setTargetType($data) {
    	$this->_aData['target_type']=$data;
    }
}
?>