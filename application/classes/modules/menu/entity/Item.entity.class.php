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
class ModuleMenu_EntityItem extends ModuleMenu_EntityAbstractItem{
    
    protected $aRelations = [
        'menu' => [self::RELATION_TYPE_BELONGS_TO, "ModuleMenu_EntityMenu", 'menu_id'],
        self::RELATION_TYPE_TREE
    ];
    
    public function __construct($aData) {
        parent::__construct($aData);
        $this->setState(ModuleMenu::STATE_ITEM_ENABLE);
    }
    
    /**
     * Определяем правила валидации
     *
     * @var array
     */
    public $aValidateRules = array(
        array('title', 'string', 'max' => 250, 'min' => 1, 'allowEmpty' => false),
        array('name', 'string', 'max' => 30, 'min' => 1, 'allowEmpty' => true),
        array('url', 'string', 'max' => 1000, 'min' => 1, 'allowEmpty' => false),
        array('enable', 'number'),
        array('active', 'number'),
        array('pid', 'parent_item'),
        array('priority', 'number'),
        array('menu_id', 'menu_id'),
    );
    
    public function _getTreeParentKey()
    {
        return 'pid';
    }

    public function beforeSave()
    {
        if(!parent::beforeSave()){
            return false;
        }
        
        if(!$this->_getDataOne('enable')){ 
            $this->setState(ModuleMenu::STATE_ITEM_DISABLE);
            return true;
        }
        $this->setState(ModuleMenu::STATE_ITEM_ENABLE);
        
        if($this->_getDataOne('active')){
            $this->setState(ModuleMenu::STATE_ITEM_ACTIVE);
        }   
        return true;
    }
    
    public function afterDelete() {
        parent::afterDelete();
        
        $aChildrenItems = $this->getChildren();
        foreach ($aChildrenItems as $oItem) {
            $oItem->setState(ModuleMenu::STATE_ITEM_DISABLE);
            $oItem->setPid(null);
            $oItem->Save();
        }
    }

    public function ValidateParentItem($sValue, $aParams)
    {
        if(!$sValue){
            return true;
        }
        if (!$oItem = $this->Menu_GetItemById($this->getPid())) {
            return $this->Lang_Get('menu.message.no_find_parent_item');
        }
            
        return true;
    }
    
  
    public function ValidateMenuId($sValue, $aParams)
    {
                
        if (!$oMenu = $this->Menu_GetMenuById($this->getMenuId())) {
            return $this->Lang_Get('menu.message.no_find_menu');
        }
            
        return true;
    }
    
    public function getEnable() {
        if($this->getState()){
            return 1;
        }
        return 0;
    }
    
    public function getActive() {
        if($this->getState() == ModuleMenu::STATE_ITEM_ACTIVE){
            return 1;
        }
        return 0;
    }
    
   
}
