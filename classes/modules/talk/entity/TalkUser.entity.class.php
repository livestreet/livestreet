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

class ModuleTalk_EntityTalkUser extends Entity 
{    
    public function getTalkId() {
        return $this->_aData['talk_id'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getDateLast() {
        return $this->_aData['date_last'];
    }
    public function getCommentIdLast() {
        return $this->_aData['comment_id_last'];
    }
    public function getCommentCountNew() {
        return $this->_aData['comment_count_new'];
    }
    
    /**
     * Возвращает статус активности пользователя 
     *
     * @return int
     */
    public function getUserActive(){
    	return (array_key_exists('talk_user_active',$this->_aData)) 
    		? $this->_aData['talk_user_active']
    		: ModuleTalk::TALK_USER_ACTIVE;
    }
    /**
     * Возвращает соответствующий пользователю объект UserEntity
     *
     * @return UserEntity | null
     */
    public function getUser() {    
   		return $this->_aData['user']; 	
    }
   
    
	public function setTalkId($data) {
        $this->_aData['talk_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setDateLast($data) {
        $this->_aData['date_last']=$data;
    }  
    public function setCommentIdLast($data) {
        $this->_aData['comment_id_last']=$data;
    }
    public function setCommentCountNew($data) {
        $this->_aData['comment_count_new']=$data;
    }
    
    public function setUserActive($data) {
    	$this->_aData['talk_user_active']=$data;
    }
    public function setUser($data) {
    	$this->_aData['user']=$data;
    }
}
?>