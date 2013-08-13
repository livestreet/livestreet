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
 * Обрабатывает блок облака тегов городов юзеров
 *
 * @package blocks
 * @since 1.0
 */
class BlockTagsCity extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		/**
		 * Получаем города
		 */
		$aCities=$this->Geo_GetGroupCitiesByTargetType('user',20);
		/**
		 * Формируем облако тегов
		 */
		$this->Tools_MakeCloud($aCities);
		/**
		 * Выводим в шаблон
		 */
		$this->Viewer_Assign("aCityList",$aCities);
	}
}
?>