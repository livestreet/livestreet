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
 * Объект управления типом imageset
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_EntityValueTypeImageset extends ModuleProperty_EntityValueType
{
    public function getValueForDisplay()
    {
        return $this->getValueObject()->getValueInt();
    }

    public function isEmpty()
    {
        return is_null($this->getValueObject()->getValueInt()) ? true : false;
    }
    
    public function beforeSaveValue() { 
        $aMediaTargets = $this->getMediaTargetsImageset();  
        
        $this->oValue->setData( array_keys($aMediaTargets) );
                
        $this->Media_ReplaceTargetTmpById('imageset', $this->oValue->getId());
        
        return true;
    }

    public function getValueForForm()
    {
        $oValue = $this->getValueObject();
        $oProperty = $oValue->getProperty();
        return $oValue->_isNew() ? $oProperty->getParam('default') : $oValue->getValueInt();
    }
    
    public function getMediaTargetsImageset() {
        $mValue = $this->getValueForValidate();
        $aFilter = [
            '#where' => [
                "(t.target_id = ?d OR t.target_tmp = ?d)" => [$mValue, $mValue]
            ],
            '#index-from' => 'id',
            'target_type' => 'imageset'
        ];        
        return $this->Media_GetTargetItemsByFilter($aFilter);
    }

    public function validate()
    {
        $mValue = $this->getValueForValidate();
        
        $oProperty = $this->oValue->getProperty();
        if( !$mValue and $oProperty->getValidateRuleOne('allowEmpty')){
            return true;
        }
        
        $aMediaTargets = $this->getMediaTargetsImageset();                

        if($iMin = $oProperty->getValidateRuleOne('count_min') and $iMin > sizeof($aMediaTargets)){
            return $this->Lang_Get('property.notices.validate_value_select_min', array('count' => $iMin));
        }

        if($iMax = $oProperty->getValidateRuleOne('count_max') and $iMax < sizeof($aMediaTargets)){
            return $this->Lang_Get('property.notices.validate_value_select_max', array('count' => $iMax));
        }
        
        $this->oValue->setData( array_keys($aMediaTargets) );
        
        
        return true;
    }


    public function prepareValidateRulesRaw($aRulesRaw)
    {
        $aRules = array();
        $aRules['allowEmpty'] = isset($aRulesRaw['allowEmpty']) ? false : true;

        if (isset($aRulesRaw['count_max']) and is_numeric($aRulesRaw['count_max'])) {
            $aRules['count_max'] = (int)$aRulesRaw['count_max'];
        }
        if (isset($aRulesRaw['count_min']) and is_numeric($aRulesRaw['count_min'])) {
            $aRules['count_min'] = (int)$aRulesRaw['count_min'];
        }
        return $aRules;
    }
    
    public function prepareParamsRaw($aParamsRaw)
    {
        $aParams = array();

        if (isset($aParamsRaw['count_min'])) {
            $aParams['count_min'] = htmlspecialchars($aParamsRaw['count_min']);
        }
        
         if (isset($aParamsRaw['count_max'])) {
            $aParams['count_max'] = htmlspecialchars($aParamsRaw['count_max']);
        }

        return $aParams;
    }
    
    public function getParamsDefault()
    {
        return array(
            'count_min' => 1,
            'count_max' => 10
        );
    }
   
}