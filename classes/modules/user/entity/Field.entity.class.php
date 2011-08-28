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

class ModuleUser_EntityField extends Entity 
{    
    public function getId() 
    {
        return $this->_aData['id'];
    }  
    public function getName() 
    {
        return $this->_aData['name'];
    }
    public function getTitle() 
    {
        return $this->_aData['title'];
    }
    public function getPattern() 
    {
        return $this->_aData['pattern'];
    }
    public function getValue($bEscapeValue = false, $bTransformed = false) 
    {
        if (!isset($this->_aData['value']) || !$this->_aData['value']) return '';
        if ($bEscapeValue)  $this->_aData['value'] = htmlspecialchars($this->_aData['value']);
        
        if ($bTransformed) {
            if (!$this->_aData['pattern']) return $this->_aData['value'];
            return str_replace('{*}', $this->_aData['value'], $this->_aData['pattern']);
        } else {
            return (isset($this->_aData['value'])) ? $this->_aData['value'] : '';
        }
    }
    
    
    
    public function setId($iId) {
        $this->_aData['id']=$iId;
    }
    public function setName($sName) {
        $this->_aData['name']=$sName;
    }    
    public function setTitle($sTitle) {
        $this->_aData['title']=$sTitle;
    }    
    public function setPattern($sPattern) {
        $this->_aData['pattern']=$sPattern;
    }    
    public function setValue($sValue) {
        $this->_aData['value']=$sValue;
    }    
}