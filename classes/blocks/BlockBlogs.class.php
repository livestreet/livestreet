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
 */
class BlockBlogs extends Block {
	public function Exec() {
		if ($aResult=$this->Blog_GetBlogsRating(1,Config::Get('block.blogs.row'))) {
			$aBlogs=$aResult['collection'];
			$this->Viewer_Assign('aBlogs',$aBlogs);
			$sTextResult=$this->Viewer_Fetch("block.blogs_top.tpl");
			$this->Viewer_Assign('sBlogsTop',$sTextResult);
		}
	}
}
?>