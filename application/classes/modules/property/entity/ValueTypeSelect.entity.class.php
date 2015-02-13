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
 * Объект управления типом select
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_EntityValueTypeSelect extends ModuleProperty_EntityValueType
{

    public function getValueForDisplay()
    {
        $oValue = $this->getValueObject();
        $aValues = $oValue->getDataOne('values');
        return is_array($aValues) ? join(', ', $aValues) : '';
    }

    public function isEmpty()
    {
        $oValue = $this->getValueObject();
        $aValues = $oValue->getDataOne('values');
        return $aValues ? false : true;
    }

    public function getValueForForm()
    {
        $oValue = $this->getValueObject();
        $aValues = $oValue->getDataOne('values');
        return $aValues;
    }

    public function validate()
    {
        $oProperty = $this->getValueObject()->getProperty();

        $iValue = $this->getValueForValidate();
        if (is_array($iValue)) {
            $iValue = array_filter($iValue);
        }
        if (!$iValue and $oProperty->getValidateRuleOne('allowEmpty')) {
            return true;
        }
        if (is_array($iValue)) {
            if ($oProperty->getValidateRuleOne('allowMany')) {
                if ($oProperty->getValidateRuleOne('max') and count($iValue) > $oProperty->getValidateRuleOne('max')) {
                    return 'Максимально можно выбрать только ' . $oProperty->getValidateRuleOne('max') . ' элемента';
                }
                if ($oProperty->getValidateRuleOne('min') and count($iValue) < $oProperty->getValidateRuleOne('min')) {
                    return 'Минимально можно выбрать только ' . $oProperty->getValidateRuleOne('min') . ' элемента';
                }
                /**
                 * Для безопасности
                 */
                $aValues = array();
                foreach ($iValue as $iV) {
                    $aValues[] = (int)$iV;
                }
                if (count($aValues) == count($this->Property_GetSelectItemsByFilter(array(
                                'property_id' => $oProperty->getId(),
                                'id in'       => $aValues
                            )))
                ) {
                    $this->setValueForValidate($aValues);
                    return true;
                } else {
                    return 'Проверьте корректность выбранных элементов';
                }
            } elseif (count($iValue) == 1) {
                $iValue = (int)reset($iValue);
            } else {
                return 'Можно выбрать только один элемент';
            }
        }
        /**
         * Проверяем значение
         */
        if ($oSelect = $this->Property_GetSelectByIdAndPropertyId($iValue, $oProperty->getId())) {
            return true;
        }
        return 'Необходимо выбрать значение';
    }

    public function setValue($mValue)
    {
        $this->resetAllValue();
        $oValue = $this->getValueObject();
        $oProperty = $oValue->getProperty();

        $aValues = array();
        /**
         * Сохраняем с data, т.к. может быть множественный выбор
         */
        if ($mValue) {
            if (is_array($mValue)) {
                $aSelectItems = $this->Property_GetSelectItemsByFilter(array(
                        'property_id' => $oProperty->getId(),
                        'id in'       => $mValue
                    ));
                foreach ($aSelectItems as $oSelect) {
                    $aValues[$oSelect->getId()] = $oSelect->getValue();
                }
            } else {
                if ($oSelect = $this->Property_GetSelectByIdAndPropertyId($mValue, $oProperty->getId())) {
                    $aValues[$oSelect->getId()] = $oSelect->getValue();
                }
            }
        }
        $oValue->setData($aValues ? array('values' => $aValues) : array());
    }

    /**
     * Дополнительная обработка перед сохранением значения
     */
    public function beforeSaveValue()
    {
        $oValue = $this->getValueObject();
        if ($aValues = $oValue->getData()) {
            foreach ($aValues['values'] as $k => $v) {
                $oSelect = Engine::GetEntity('ModuleProperty_EntityValueSelect');
                $oSelect->setPropertyId($oValue->getPropertyId());
                $oSelect->setTargetType($oValue->getTargetType());
                $oSelect->setTargetId($oValue->getTargetId());
                $oSelect->setSelectId($k);
                $oSelect->Add();
            }
        }
    }

    public function prepareValidateRulesRaw($aRulesRaw)
    {
        $aRules = array();
        $aRules['allowEmpty'] = isset($aRulesRaw['allowEmpty']) ? false : true;
        $aRules['allowMany'] = isset($aRulesRaw['allowMany']) ? true : false;

        if (isset($aRulesRaw['max']) and is_numeric($aRulesRaw['max'])) {
            $aRules['max'] = (int)$aRulesRaw['max'];
        }
        if (isset($aRulesRaw['min']) and is_numeric($aRulesRaw['min'])) {
            $aRules['min'] = (int)$aRulesRaw['min'];
        }
        return $aRules;
    }

    public function removeValue()
    {
        $oValue = $this->getValueObject();
        /**
         * Удаляем значения select'а из дополнительной таблицы
         */
        if ($aSelects = $this->Property_GetValueSelectItemsByFilter(array(
                'property_id' => $oValue->getPropertyId(),
                'target_id'   => $oValue->getTargetId()
            ))
        ) {
            foreach ($aSelects as $oSelect) {
                $oSelect->Delete();
            }
        }
    }
}