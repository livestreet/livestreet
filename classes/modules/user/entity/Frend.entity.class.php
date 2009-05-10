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

class UserEntity_Frend extends Entity 
{    
    public function getUserId() {
        return $this->_aData['user_id'];
    }  
    public function getFrendId() {
        return $this->_aData['user_frend_id'];
    }
    
    
	public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setFrendId($data) {
        $this->_aData['user_frend_id']=$data;
    }
}
?>