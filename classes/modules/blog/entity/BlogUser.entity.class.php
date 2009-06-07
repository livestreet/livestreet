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

class BlogEntity_BlogUser extends Entity 
{    
    public function getBlogId() {
        return $this->_aData['blog_id'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getIsModerator() {
        return $this->_aData['is_moderator'];
    }
    public function getIsAdministrator() {
        return $this->_aData['is_administrator'];
    }

    public function getBlog() {
        return $this->_aData['blog'];
    }  
    public function getUser() {
        return $this->_aData['user'];
    }  

   
  
    
	public function setBlogId($data) {
        $this->_aData['blog_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setIsModerator($data) {
        $this->_aData['is_moderator']=$data;
    }
    public function setIsAdministrator($data) {
        $this->_aData['is_administrator']=$data;
    }

	public function setBlog($data) {
        $this->_aData['blog']=$data;
    }
    public function setUser($data) {
        $this->_aData['user']=$data;
    }
}
?>