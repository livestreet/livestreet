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
 * Объект управления типом tags
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_EntityValueTypeTags extends ModuleProperty_EntityValueType
{

    public function getValueForDisplay()
    {
        return htmlspecialchars($this->getValueObject()->getValueVarchar());
    }

    public function validate()
    {
        return $this->validateStandart('tags');
    }

    public function setValue($mValue)
    {
        $this->resetAllValue();
        $oValue = $this->getValueObject();
        $oValue->setValueVarchar($mValue ? $mValue : null);
    }

    /**
     * Дополнительная обработка перед сохранением значения
     */
    public function beforeSaveValue()
    {
        /**
         * Заливаем теги в отдельную таблицу
         */
        if ($aTags = $this->getTagsArray()) {
            $oValue = $this->getValueObject();
            foreach ($aTags as $sTag) {
                $oTag = Engine::GetEntity('ModuleProperty_EntityValueTag');
                $oTag->setPropertyId($oValue->getPropertyId());
                $oTag->setTargetType($oValue->getTargetType());
                $oTag->setTargetId($oValue->getTargetId());
                $oTag->setText($sTag);
                $oTag->Add();
            }
        }
    }

    public function getTagsArray()
    {
        $sTags = $this->getValueObject()->getValueVarchar();
        if ($sTags) {
            return explode(',', $sTags);
        }
        return array();
    }

    public function prepareValidateRulesRaw($aRulesRaw)
    {
        $aRules = array();
        $aRules['allowEmpty'] = isset($aRulesRaw['allowEmpty']) ? false : true;

        if (isset($aRulesRaw['countMax']) and ($iCount = (int)$aRulesRaw['countMax']) > 0) {
            $aRules['countMax'] = $iCount;
        }
        if (isset($aRulesRaw['countMin']) and ($iCount = (int)$aRulesRaw['countMin']) > 0) {
            $aRules['countMin'] = $iCount;
        }
        return $aRules;
    }

    public function removeValue()
    {
        $oValue = $this->getValueObject();
        /**
         * Удаляем теги из дополнительной таблицы
         */
        if ($aTags = $this->Property_GetValueTagItemsByFilter(array(
                'property_id' => $oValue->getPropertyId(),
                'target_id'   => $oValue->getTargetId()
            ))
        ) {
            foreach ($aTags as $oTag) {
                $oTag->Delete();
            }
        }
    }
}