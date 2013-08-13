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
 * Обрабатывает блок облака тегов для избранного
 *
 * @package blocks
 * @since 1.0
 */
class BlockTagsFavouriteTopic extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		/**
		 * Пользователь авторизован?
		 */
		if ($oUserCurrent = $this->User_getUserCurrent()) {
			if (!($oUser=$this->getParam('user'))) {
				$oUser=$oUserCurrent;
			}
			/**
			 * Получаем список тегов
			 */
			$aTags=$this->oEngine->Favourite_GetGroupTags($oUser->getId(),'topic',null,70);
			/**
			 * Расчитываем логарифмическое облако тегов
			 */
			$this->Tools_MakeCloud($aTags);
			/**
			 * Устанавливаем шаблон вывода
			 */
			$this->Viewer_Assign("aFavouriteTopicTags",$aTags);
			/**
			 * Получаем список тегов пользователя
			 */
			$aTags=$this->oEngine->Favourite_GetGroupTags($oUser->getId(),'topic',true,70);
			/**
			 * Расчитываем логарифмическое облако тегов
			 */
			$this->Tools_MakeCloud($aTags);
			/**
			 * Устанавливаем шаблон вывода
			 */
			$this->Viewer_Assign("aFavouriteTopicUserTags",$aTags);
			$this->Viewer_Assign("oFavouriteUser",$oUser);
		}
	}
}
?>