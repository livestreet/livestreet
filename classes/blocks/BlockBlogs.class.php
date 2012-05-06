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
 * Обработка блока с рейтингом блогов
 *
 * @package blocks
 * @since 1.0
 */
class BlockBlogs extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		/**
		 * Получаем список блогов
		 */
		if ($aResult=$this->Blog_GetBlogsRating(1,Config::Get('block.blogs.row'))) {
			$aBlogs=$aResult['collection'];
			$oViewer=$this->Viewer_GetLocalViewer();
			$oViewer->Assign('aBlogs',$aBlogs);
			/**
			 * Формируем результат в виде шаблона и возвращаем
			 */
			$sTextResult=$oViewer->Fetch("blocks/block.blogs_top.tpl");
			$this->Viewer_Assign('sBlogsTop',$sTextResult);
		}
	}
}
?>