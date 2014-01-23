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
 * Обработка блока с редактированием свойств объекта
 *
 * @package blocks
 * @since 1.0
 */
class BlockPropertyUpdate extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		$sTargetType=$this->GetParam('target_type');
		$iTargetId=$this->GetParam('target_id');
		/**
		 * Получаем набор свойств
		 */
		$aProperties=$this->Property_GetPropertiesForUpdate($sTargetType,$iTargetId);
		$this->Viewer_Assign('aProperties',$aProperties);
	}
}