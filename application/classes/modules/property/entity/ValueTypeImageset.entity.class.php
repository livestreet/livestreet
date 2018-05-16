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
    public function getValueForDisplay($aFilter = [])
    {
        $aMedia = $this->Media_GetMediaItemsByFilter(array_merge([
            '#join' => [
                "JOIN ".Config::Get('db.table.media_target')." as mt ON mt.media_id = t.id "
                . "AND mt.target_type = 'imageset' AND mt.target_id = ?d" => 
                [$this->oValue->getId()]
            ]
        ], $aFilter));
        return $aMedia;
    }
    
    public function getMedia($aFilter = []) {
        return $this->getValueForDisplay($aFilter);
    }

    public function isEmpty()
    {
        $aData = $this->oValue->getData();
        return !sizeof($aData);
    }
    
    public function beforeSaveValue() { 
        $mValue = $this->getValueForValidate();
        
        $aMediaTargets = $this->getMediaTargetsImageset($mValue, 'media_id');        
        
        $this->Media_DeleteTargetItemsByFilter( $this->getMediaTargetsFilter($mValue) );
        
        $aMediaTargetIds = [];
        foreach($aMediaTargets as $oMediaTarget){
            $oMediaTarget->Add();
            $aMediaTargetIds[] = $oMediaTarget->getId();
        }
        
        $this->oValue->setData( $aMediaTargetIds );
                
        $this->Media_ReplaceTargetTmpById('imageset', $this->oValue->getId());
        
        return true;
    }

    public function getValueForForm()
    {
        return $this->oValue->getId();
    }
    
    public function getImageSize()
    {
        return $this->getDataOne('size');
    }
    
    public function getMediaTargetsImageset($iTargetForm, $indexFrom = 'id') {
        
        $aFilter = $this->getMediaTargetsFilter($iTargetForm);

        $aFilter['#index-from'] = $indexFrom;
            
        return $this->Media_GetTargetItemsByFilter($aFilter);
    }
    
    public function getMediaTargetsFilter($iTargetForm) {
        $aFilter = [
            'target_type' => 'imageset'
        ];
        if( $this->oValue->_isNew() ){
            $aFilter['#where'] = [
                "(t.target_id = ?d OR t.target_tmp = ?d)" => [$iTargetForm, $iTargetForm]
            ];
        }else{
            $aFilter['#where'] = [
                "(t.target_id = ?d OR t.target_id = ?d OR t.target_tmp = ?d)" => [$this->oValue->getId(), $iTargetForm, $iTargetForm]
            ];
        } 
        
        return $aFilter;
    }

    public function validate()
    {
        $mValue = $this->getValueForValidate();
                
        $oProperty = $this->oValue->getProperty();        
        if( !$mValue and !$oProperty->getValidateRuleOne('allowEmpty')){
            return $this->Lang_Get('property.notices.validate_value_file_empty');
        }

        $aMediaTargets = $this->getMediaTargetsImageset($mValue);                

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
        
        if (isset($aParamsRaw['size']) and preg_match('#^(\d+)?(x)?(\d+)?([a-z]{2,10})?$#Ui', $aParamsRaw['size'])) {
            $aParams['size'] = htmlspecialchars($aParamsRaw['size']);
        }

        return $aParams;
    }
    
    public function getParamsDefault()
    {
        return array(
            'count_min' => 1,
            'count_max' => 10,
            'size' => '100x100crop'
        );
    }
   
}