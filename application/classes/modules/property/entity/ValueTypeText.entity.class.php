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
 * Объект управления типом text
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_EntityValueTypeText extends ModuleProperty_EntityValueType
{

    public function getValueForDisplay()
    {
        return $this->getValueObject()->getValueText();
    }

    public function isEmpty()
    {
        return $this->getValueObject()->getValueText() ? false : true;
    }

    public function getValueForForm()
    {
        $oValue = $this->getValueObject();
        return htmlspecialchars($oValue->getDataOne('text_source'));
    }

    public function validate()
    {
        return $this->validateStandart('string');
    }

    public function setValue($mValue)
    {
        $this->resetAllValue();
        $oValue = $this->getValueObject();
        $oProperty = $oValue->getProperty();

        $oValue->setDataOne('text_source', $mValue);
        if ($oProperty->getParam('use_html')) {
            $mValue = $this->Text_Parser($mValue);
        } else {
            $mValue = htmlspecialchars($mValue);
        }
        $oValue->setValueText($mValue ? $mValue : null);
    }

    public function prepareValidateRulesRaw($aRulesRaw)
    {
        $aRules = array();
        $aRules['allowEmpty'] = isset($aRulesRaw['allowEmpty']) ? false : true;

        if (isset($aRulesRaw['max']) and is_numeric($aRulesRaw['max'])) {
            $aRules['max'] = (int)$aRulesRaw['max'];
        }
        if (isset($aRulesRaw['min']) and is_numeric($aRulesRaw['min'])) {
            $aRules['min'] = (int)$aRulesRaw['min'];
        }
        return $aRules;
    }

    public function prepareParamsRaw($aParamsRaw)
    {
        $aParams = array();
        $aParams['use_html'] = isset($aParamsRaw['use_html']) ? true : false;

        return $aParams;
    }
}