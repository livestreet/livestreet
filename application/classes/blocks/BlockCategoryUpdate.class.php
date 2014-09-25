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
 * Обработка блока с редактированием категорий объекта
 *
 * @package blocks
 * @since   2.0
 */
class BlockCategoryUpdate extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		$sEntity = $this->GetParam('entity');
		$oTarget = $this->GetParam('target');

		if (!$oTarget) {
			$oTarget=Engine::GetEntity($sEntity);
		}

		if ($oTarget) {
			$aBehaviors=$oTarget->GetBehaviors();
			foreach($aBehaviors as $oBehavior) {
				if ($oBehavior instanceof ModuleCategory_BehaviorEntity) {
					/**
					 * Нужное нам поведение - получаем список текущих категорий
					 */
					$this->Viewer_Assign('categoriesSelected', $oBehavior->getCategories(), true);
					/**
					 * Загружаем параметры
					 */
					$aParams=$oBehavior->getParams();
					$this->Viewer_Assign('params', $aParams, true);
					/**
					 * Загружаем список доступных категорий
					 */
					$this->Viewer_Assign('categories', $this->Category_GetCategoriesTreeByTargetType($aParams['target_type']), true);
					break;
				}
			}
		}
	}
}