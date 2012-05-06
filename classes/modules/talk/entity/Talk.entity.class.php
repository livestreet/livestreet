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

class ModuleTalk_EntityTalk extends Entity 
{    
    public function getId() {
        return $this->_getDataOne('talk_id');
    }      
    public function getUserId() {
        return $this->_getDataOne('user_id');
    }    
    public function getTitle() {
        return $this->_getDataOne('talk_title');
    }
    public function getText() {
        return $this->_getDataOne('talk_text');
    }    
    public function getDate() {
        return $this->_getDataOne('talk_date');
    }    
    public function getDateLast() {
        return $this->_getDataOne('talk_date_last');
    }
	public function getUserIdLast() {
		return $this->_getDataOne('talk_user_id_last');
	}
    public function getUserIp() {
        return $this->_getDataOne('talk_user_ip');
    }
	public function getCommentIdLast() {
		return $this->_getDataOne('talk_comment_id_last');
	}
    public function getCountComment() {
        return $this->_getDataOne('talk_count_comment');
    }
      
    
	/**
	 * Возвращает последний текст(коммент) из письма, если комментов нет, то текст исходного сообщения
	 *
	 * @return string
	 */
	public function getTextLast() {
		if ($oComment=$this->getCommentLast()) {
			return $oComment->getText();
		}
		return $this->getText();
	}
    public function getUsers() {
    	return $this->_getDataOne('users');
    }
    public function getUser() {
    	return $this->_getDataOne('user');
    }
    public function getTalkUser() {
    	return $this->_getDataOne('talk_user');
    }
    /**
     * Возращает true, если разговор занесен в избранное
     *
     * @return bool
     */
    public function getIsFavourite() {
        return $this->_getDataOne('talk_is_favourite');
    }
	/**
	 * Получает пользователей разговора
	 *
	 * @return array
	 */
    public function getTalkUsers() {
    	return $this->_getDataOne('talk_users');
    }
    
    
	public function setId($data) {
        $this->_aData['talk_id']=$data;
    }   
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }    
    public function setTitle($data) {
        $this->_aData['talk_title']=$data;
    }
    public function setText($data) {
        $this->_aData['talk_text']=$data;
    }    
    public function setDate($data) {
        $this->_aData['talk_date']=$data;
    }    
    public function setDateLast($data) {
        $this->_aData['talk_date_last']=$data;
    }
	public function setUserIdLast($data) {
		$this->_aData['talk_user_id_last']=$data;
	}
    public function setUserIp($data) {
        $this->_aData['talk_user_ip']=$data;
    }
	public function setCommentIdLast($data) {
		$this->_aData['talk_comment_id_last']=$data;
	}
    public function setCountComment($data) {
        $this->_aData['talk_count_comment']=$data;
    }
    
    
    public function setUsers($data) {
        $this->_aData['users']=$data;
    } 
    public function setUser($data) {
        $this->_aData['user']=$data;
    }
    public function setTalkUser($data) {
        $this->_aData['talk_user']=$data;
    } 
    
    public function setIsFavourite($data) {
        $this->_aData['talk_is_favourite']=$data;
    }
    public function setTalkUsers($data) {
    	$this->_aData['talk_users']=$data;
    }
}
?>