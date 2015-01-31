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
 * Обработка блока с редактированием свойств объекта
 *
 * @package application.blocks
 * @since 2.0
 */
class BlockPropertyUpdate extends Block
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
            /**
             * Определяем нужное нам поведение
             */
            if ($oBehavior instanceof ModuleProperty_BehaviorEntity) {
                /**
                 * Если в параметрах был тип, то переопределяем значение. Это необходимо для корректной работы, когда тип динамический.
                 */
                if ($sTargetType) {
                    $oBehavior->setParam('target_type', $sTargetType);
                }
                $aProperties = $this->Property_GetPropertiesForUpdate($oBehavior->getPropertyTargetType(),
                    $oTarget->getId());
                $this->Viewer_Assign('properties', $aProperties, true);
                break;
            }
        }

        $this->SetTemplate('component@property.input.list');
    }
}