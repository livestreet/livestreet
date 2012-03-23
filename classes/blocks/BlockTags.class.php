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
 * Обрабатывает блок облака тегов
 *
 */
class BlockTags extends Block {
	public function Exec() {			
		/**
		 * Получаем список тегов
		 */
		$aTags=$this->oEngine->Topic_GetOpenTopicTags(70);
		/**
		 * Расчитываем логарифмическое облако тегов
		 */
		if ($aTags) {
			$this->Tools_MakeCloud($aTags);
			/**
		 	* Устанавливаем шаблон вывода
		 	*/
			$this->Viewer_Assign("aTags",$aTags);
		}
	}
}
?>