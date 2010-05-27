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

class ModuleTopic_EntityTopicRead extends Entity 
{    
    public function getTopicId() {
        return $this->_aData['topic_id'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getDateRead() {
        return $this->_aData['date_read'];
    }
    public function getCommentCountLast() {
        return $this->_aData['comment_count_last'];
    }
    public function getCommentIdLast() {
        return $this->_aData['comment_id_last'];
    }

    
    
	public function setTopicId($data) {
        $this->_aData['topic_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setDateRead($data) {
        $this->_aData['date_read']=$data;
    }
    public function setCommentCountLast($data) {
        $this->_aData['comment_count_last']=$data;
    }
    public function setCommentIdLast($data) {
        $this->_aData['comment_id_last']=$data;
    }
}
?>