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

class ModuleTopic_EntityTopicTag extends Entity 
{    
    public function getId() {
        return $this->_getDataOne('topic_tag_id');
    }  
    public function getTopicId() {
        return $this->_getDataOne('topic_id');
    }
    public function getUserId() {
        return $this->_getDataOne('user_id');
    }
    public function getBlogId() {
        return $this->_getDataOne('blog_id');
    }
    public function getText() {
        return $this->_getDataOne('topic_tag_text');
    }
    
    public function getCount() {
        return $this->_getDataOne('count');
    }
    public function getSize() {
        return $this->_getDataOne('size');
    }

  
    
	public function setId($data) {
        $this->_aData['topic_tag_id']=$data;
    }
    public function setTopicId($data) {
        $this->_aData['topic_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setBlogId($data) {
        $this->_aData['blog_id']=$data;
    }
    public function setText($data) {
        $this->_aData['topic_tag_text']=$data;
    }
    
	public function setSize($data) {
        $this->_aData['size']=$data;
    }
}
?>