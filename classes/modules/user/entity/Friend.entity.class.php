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

class ModuleUser_EntityFriend extends Entity 
{    
    /**
     * При переданном параметре $sUserId возвращает тот идентификатор,
     * который не равен переданному
     *
     * @param  ( string|null )
     * @return string
     */
    public function getFriendId($sUserId=null) {
    	if(!$sUserId) {
        	$sUserId=$this->getUserId();
    	}
    	if($this->_aData['user_from']==$sUserId) {
    		return $this->_aData['user_to'];
    	}
    	if($this->_aData['user_to']==$sUserId) {
    		return $this->_aData['user_from'];
    	}
    	return false;
    }
    /**
     * Получает идентификатор пользователя, 
     * относительно которого был сделан запрос
     *
     * @return int
     */
    public function getUserId() {
    	return array_key_exists('user',$this->_aData) 
    			? $this->_aData['user']
    			: null;
    }

    public function getUserFrom() {
        return $this->_aData['user_from'];
    }  
    public function getUserTo() {
        return $this->_aData['user_to'];
    }  
    public function getStatusFrom() {
        return $this->_aData['status_from'];
    }  
    public function getStatusTo() {
        return (empty($this->_aData['status_to']))
        	? ModuleUser::USER_FRIEND_NULL
        	: $this->_aData['status_to'];
    }  
    public function getFriendStatus() {
    	return $this->getStatusFrom()+$this->getStatusTo();
    }
   	public function getStatusByUserId($sUserId) {
     	if($sUserId==$this->getUserFrom()) {
    		return $this->getStatusFrom();
    	}
    	if($sUserId==$this->getUserTo()) {
    		return $this->getStatusTo();
    	}
    	return false;  		
   	}
       
    public function setUserFrom($data) {
    	$this->_aData['user_from']=$data;
    }
    public function setUserTo($data) {
    	$this->_aData['user_to']=$data;
    }
    public function setStatusFrom($data) {
    	$this->_aData['status_from']=$data;
    }
    public function setStatusTo($data) {
    	$this->_aData['status_to']=$data;
    }
    public function setUserId($data) {
    	$this->_aData['user']=$data;
    }
    public function setStatusByUserId($data,$sUserId) {
    	if($sUserId==$this->getUserFrom()) {
    		$this->setStatusFrom($data);
    		return true;
    	}
    	if($sUserId==$this->getUserTo()) {
    		$this->setStatusTo($data);
    		return true;
    	}
    	return false;
    }
}
?>