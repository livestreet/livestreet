<?
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
 */
class BlockBlogs extends Block {
	public function Exec() {
		/**
		 * Получаем список блогов
		 */
		$aBlogs=$this->Blog_GetBlogsRating(20);		
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign("aBlogs",$aBlogs);
	}
}
?>