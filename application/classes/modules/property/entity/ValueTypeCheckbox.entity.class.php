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
 * Объект управления типом checkbox
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_EntityValueTypeCheckbox extends ModuleProperty_EntityValueType
{

    public function getValueForDisplay()
    {
        return $this->getValueObject()->getValueInt() ? 'да' : 'нет';
    }

    public function getValueForForm()
    {
        $oValue = $this->getValueObject();
        $oProperty = $oValue->getProperty();
        return $oValue->_isNew() ? $oProperty->getParam('default') : $oValue->getValueInt();
    }

    public function isEmpty()
    {
        return false;
    }

    public function validate()
    {
        $sValue = $this->getValueForValidate();
        $this->setValueForValidate($sValue ? 1 : 0);
        return true;
    }

    public function setValue($mValue)
    {
        $this->resetAllValue();
        $oValue = $this->getValueObject();
        $oProperty = $oValue->getProperty();
        $oValue->setValueInt($mValue ? $oProperty->getParam('default_value') : 0);
    }

    public function prepareParamsRaw($aParamsRaw)
    {
        $aParams = array();

        $aParams['default'] = isset($aParamsRaw['default']) ? true : false;
        if (isset($aParamsRaw['default_value'])) {
            $aParams['default_value'] = htmlspecialchars($aParamsRaw['default_value']);
        }

        return $aParams;
    }

    public function getParamsDefault()
    {
        return array(
            'default_value' => 1,
        );
    }
}