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

class ModuleBlog_EntityBlogUser extends Entity 
{    
    public function getBlogId() {
        return $this->_aData['blog_id'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getIsModerator() {
        return ($this->getUserRole()==ModuleBlog::BLOG_USER_ROLE_MODERATOR);
    }
    public function getIsAdministrator() {
        return ($this->getUserRole()==ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
    }
	public function getUserRole() {
		return $this->_aData['user_role'];
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
        if($data && !$this->getIsModerator()) {
        	/**
        	 * Повышаем статус до модератора
        	 */
        	$this->setUserRole(ModuleBlog::BLOG_USER_ROLE_MODERATOR);
        }
    }
    public function setIsAdministrator($data) {
        if($data && !$this->getIsAdministrator()) {
        	/**
        	 * Повышаем статус до администратора
        	 */
        	$this->setUserRole(ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
        }
    }
    public function setUserRole($data) {
    	$this->_aData['user_role']=$data;
    }

	public function setBlog($data) {
        $this->_aData['blog']=$data;
    }
    public function setUser($data) {
        $this->_aData['user']=$data;
    }
}
?>