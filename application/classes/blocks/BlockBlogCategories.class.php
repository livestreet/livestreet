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
 * Обрабатывает блок категорий для блогов
 *
 * @package blocks
 * @since 1.1
 */
class BlockBlogCategories extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		if (!Config::Get('module.blog.category_allow')) {
			return;
		}
		$aCategories=$this->Blog_GetCategoriesTree();
		$aBlogsAll=$this->Blog_GetBlogsByFilter(array('exclude_type' => 'personal'),array(),1,1,array());
		$this->Viewer_Assign("aBlogCategories",$aCategories);
		$this->Viewer_Assign("iCountBlogsAll",$aBlogsAll['count']);
	}
}
?>