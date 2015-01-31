<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Обработка блока с редактированием категорий объекта
 *
 * @package application.blocks
 * @since   2.0
 */
class BlockFieldCategory extends Block
{
    /**
     * Запуск обработки
     */
    public function Exec()
    {
        $sEntity = $this->GetParam('entity');
        $oTarget = $this->GetParam('target');
        $sTargetType = $this->GetParam('target_type');

        if (!$oTarget) {
            $oTarget = Engine::GetEntity($sEntity);
        }

        $aBehaviors = $oTarget->GetBehaviors();
        foreach ($aBehaviors as $oBehavior) {
            if ($oBehavior instanceof ModuleCategory_BehaviorEntity) {
                /**
                 * Если в параметрах был тип, то переопределяем значение. Это необходимо для корректной работы, когда тип динамический.
                 */
                if ($sTargetType) {
                    $oBehavior->setParam('target_type', $sTargetType);
                }
                /**
                 * Нужное нам поведение - получаем список текущих категорий
                 */
                $this->Viewer_Assign('categoriesSelected', $oBehavior->getCategories(), true);
                /**
                 * Загружаем параметры
                 */
                $aParams = $oBehavior->getParams();
                $this->Viewer_Assign('params', $aParams, true);
                /**
                 * Загружаем список доступных категорий
                 */
                $this->Viewer_Assign('categories',
                    $this->Category_GetCategoriesTreeByTargetType($oBehavior->getCategoryTargetType()), true);
                break;
            }
        }

        $this->SetTemplate('component@field.category');
    }
}