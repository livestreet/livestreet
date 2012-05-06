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
 * Блок настройки списка блогов в ленте
 *
 * @package blocks
 * @since 1.0
 */
class BlockUserfeedBlogs extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		/**
		 * Пользователь авторизован?
		 */
		if ($oUserCurrent = $this->User_getUserCurrent()) {
			$aUserSubscribes = $this->Userfeed_getUserSubscribes($oUserCurrent->getId());
			/**
			 * Получаем список ID блогов, в которых состоит пользователь
			 */
			$aBlogsId = $this->Blog_getBlogUsersByUserId($oUserCurrent->getId(), array(ModuleBlog::BLOG_USER_ROLE_USER,ModuleBlog::BLOG_USER_ROLE_MODERATOR,ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR),true);
			/**
			 * Получаем список ID блогов, которые создал пользователь
			 */
			$aBlogsOwnerId=$this->Blog_GetBlogsByOwnerId($oUserCurrent->getId(),true);
			$aBlogsId=array_merge($aBlogsId,$aBlogsOwnerId);

			$aBlogs=$this->Blog_GetBlogsAdditionalData($aBlogsId,array('owner'=>array()),array('blog_title'=>'asc'));
			/**
			 * Выводим в шаблон
			 */
			$this->Viewer_Assign('aUserfeedSubscribedBlogs', $aUserSubscribes['blogs']);
			$this->Viewer_Assign('aUserfeedBlogs', $aBlogs);
		}
	}
}