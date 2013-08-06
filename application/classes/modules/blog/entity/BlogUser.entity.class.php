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

/**
 * Сущность связи пользователя и блога
 *
 * @package modules.blog
 * @since 1.0
 */
class ModuleBlog_EntityBlogUser extends Entity {
	/**
	 * Возвращает ID блога
	 *
	 * @return int|null
	 */
	public function getBlogId() {
		return $this->_getDataOne('blog_id');
	}
	/**
	 * Возвращает ID пользователя
	 *
	 * @return int|null
	 */
	public function getUserId() {
		return $this->_getDataOne('user_id');
	}
	/**
	 * Возвращает статус модератор пользователь или нет
	 *
	 * @return bool
	 */
	public function getIsModerator() {
		return ($this->getUserRole()==ModuleBlog::BLOG_USER_ROLE_MODERATOR);
	}
	/**
	 * Возвращает статус администратор пользователь или нет
	 *
	 * @return bool
	 */
	public function getIsAdministrator() {
		return ($this->getUserRole()==ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
	}
	/**
	 * Возвращает текущую роль пользователя в блоге
	 *
	 * @return int|null
	 */
	public function getUserRole() {
		return $this->_getDataOne('user_role');
	}
	/**
	 * Возвращает объект блога
	 *
	 * @return ModuleBlog_EntityBlog|null
	 */
	public function getBlog() {
		return $this->_getDataOne('blog');
	}
	/**
	 * Возвращает объект пользователя
	 *
	 * @return ModuleUser_EntityUser|null
	 */
	public function getUser() {
		return $this->_getDataOne('user');
	}


	/**
	 * Устанавливает ID блога
	 *
	 * @param int $data
	 */
	public function setBlogId($data) {
		$this->_aData['blog_id']=$data;
	}
	/**
	 * Устанавливает ID пользователя
	 *
	 * @param int $data
	 */
	public function setUserId($data) {
		$this->_aData['user_id']=$data;
	}
	/**
	 * Устанавливает статус модератора блога
	 *
	 * @param bool $data
	 */
	public function setIsModerator($data) {
		if($data && !$this->getIsModerator()) {
			/**
			 * Повышаем статус до модератора
			 */
			$this->setUserRole(ModuleBlog::BLOG_USER_ROLE_MODERATOR);
		}
	}
	/**
	 * Устанавливает статус администратора блога
	 *
	 * @param bool $data
	 */
	public function setIsAdministrator($data) {
		if($data && !$this->getIsAdministrator()) {
			/**
			 * Повышаем статус до администратора
			 */
			$this->setUserRole(ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
		}
	}
	/**
	 * Устанавливает роль пользователя
	 *
	 * @param int $data
	 */
	public function setUserRole($data) {
		$this->_aData['user_role']=$data;
	}
	/**
	 * Устанавливает блог
	 *
	 * @param ModuleBlog_EntityBlog $data
	 */
	public function setBlog($data) {
		$this->_aData['blog']=$data;
	}
	/**
	 * Устанавливаем пользователя
	 *
	 * @param ModuleUser_EntityUser $data
	 */
	public function setUser($data) {
		$this->_aData['user']=$data;
	}
}
?>