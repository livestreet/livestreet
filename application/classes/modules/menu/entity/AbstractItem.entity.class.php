<?php

/*
 * LiveStreet CMS
 * Copyright © 2018 OOO "ЛС-СОФТ"
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
 * @author Oleg Demodov <boxmilo@gmail.com>
 *
 */

/**
 * Description of Item
 *
 * @author oleg
 */
class ModuleMenu_EntityAbstractItem extends EntityORM{
    
    
    public function find($sName) {
        return $this->recursiveSearch($sName, $this->getChildren());
    }
    
    public function recursiveSearch($sName, $aItems) {
        if(!is_array($aItems)){
            return null;
        }
        foreach ($aItems as $oItem) {
            if($oItem->getName() == $sName){
                return $oItem;
            }
            if($mResult =  $this->recursiveSearch($sName, $oItem->getChildren())){
                return $mResult;
            }
        }
        return null;
    }
    
    private function findIndex($aItems, $sName){        
        if(!is_array($aItems)){
            return false;
        }
        
        foreach ($aItems as $key => $oItem) {
            if($oItem->getName() == $sName){
                return $key;
            }
        }
        return false;
    }
    
    public function after($oItem) {
        if(!is_array($oItem)){
            $oItem = [$oItem];
        }
        
        if(get_class($this) == "ModuleMenu_EntityMenu"){
            return $this;
        } 
        
        if(!$oParent = $this->getParent()){
            return $this;
        }    
        
        $oParent->spliceChild($this->getName(), 1, $oItem);
        
        return $this;
    } 
    
    public function before($oItem) {
        if(get_class($this) == "ModuleMenu_EntityMenu"){
            return $this;
        }
        
        if(!is_array($oItem)){
            $oItem = [$oItem];
        }
        
        if(!$oParent = $this->getParent()){
            return $this;
        }
        
        $oParent->spliceChild($this->getName(), 0, $oItem);
        
        return $this;
    }
    
    public function remove() {
        if(get_class($this) == "ModuleMenu_EntityMenu"){
            return $this;
        }
        
        if(!$oParent = $this->getParent()){
            return $this;
        }
        
        $oParent->spliceChild($this->getName(), 0, [], 1);
    }
    
    public function spliceChild($sName, $iOffset, $aItems, $iRemove=0){
        
        $aChildrens = $this->getChildren();
        
        if(!is_array($aChildrens)){
            return $this;
        }
        
        if(($iKey = $this->findIndex($aChildrens, $sName)) === false){
            return $this;
        }
        
        array_splice($aChildrens, $iKey?($iKey+$iOffset):$iKey, $iRemove, $aItems);   
        
        $this->setChildren($aChildrens);
        
        return $this;
    }


    public function appendChild($oItem) {
        $aItems = $this->getChildren();
        
        if(!is_array($aItems)){
            $aItems= [];
        }
        
        $aItems[] = $oItem;
        $this->setChildren($aItems);
        return $this;
    }
    
    public function prependChild($oItem) {
        $aItems = $this->getChildren();
        
        if(!is_array($aItems)){
            $aItems= [];
        }
        
        array_unshift($aItems, $oItem);
        $this->setChildren($aItems);
        return $this;
    }
}
