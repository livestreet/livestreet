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
 * Обработка блока с комментариями
 *
 */
class BlockComments extends Block {
	public function Exec() {
		/**
		 * Получаем список комментов
		 */
		$aComments=$this->oEngine->Comment_GetCommentsAllGroup(20);		
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign("aComments",$aComments);
	}
}
?>