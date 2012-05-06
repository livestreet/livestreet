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
 * @package blocks
 * @since 1.0
 */
class BlockTags extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		/**
		 * Получаем список тегов
		 */
		$aTags=$this->oEngine->Topic_GetOpenTopicTags(Config::Get('block.tags.tags_count'));
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
		/**
		 * Теги пользователя
		 */
		if ($oUserCurrent=$this->User_getUserCurrent()) {
			$aTags=$this->oEngine->Topic_GetOpenTopicTags(Config::Get('block.tags.personal_tags_count'), $oUserCurrent->getId());
			/**
			 * Расчитываем логарифмическое облако тегов
			 */
			if ($aTags) {
				$this->Tools_MakeCloud($aTags);
				/**
				 * Устанавливаем шаблон вывода
				 */
				$this->Viewer_Assign("aTagsUser",$aTags);
			}
		}
	}
}
?>