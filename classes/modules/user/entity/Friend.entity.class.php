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

class UserEntity_Friend extends Entity 
{    
    public function getUserId() {
        return $this->_aData['user_id'];
    }  
    public function getFriendId() {
        return $this->_aData['user_friend_id'];
    }
    
    
	public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setFriendId($data) {
        $this->_aData['user_friend_id']=$data;
    }
}
?>