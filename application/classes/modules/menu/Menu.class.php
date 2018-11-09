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
 * Description of Menu
 *
 * @author oleg
 */
class ModuleMenu extends ModuleORM {
    
    const STATE_ITEM_ENABLE = 1;
    const STATE_ITEM_DISABLE = 0;
    const STATE_ITEM_ACTIVE = 2;
    
    private $aMenus = [];

    public function Init() {
        parent::Init();
    }
    
    /**
     * Возвращает дерево пунктов
     *
     * @param int $sId MenuId
     *
     * @return array
     */
    public function GetItemsTreeByMenuId($sId)
    {
        $aItems = $this->LoadTreeOfItem(array('menu_id' => $sId));
        return ModuleORM::buildTree($aItems);
    }
    
    public function Get($sName) {
        if( !isset($this->aMenus[$sName]) ){
            $this->aMenus[$sName] = $this->GetMenuByName($sName);
        }
        
        return $this->aMenus[$sName];
    }
    
    public function GetMenuByName($sName) {
        if(!$oMenu = $this->GetMenuByFilter(['name' => $sName])){
            return null;
        }
        $aItemsTree = $this->LoadTreeOfItem([
            'menu_id' => $oMenu->getId(),
            '#order' => ['priority' => 'asc']
        ]);
        
        if(is_array($aItemsTree)){
            foreach ($aItemsTree as $oItem) {
                $oItem->setParent($oMenu);
            }
        }
        
        $oMenu->setChildren($aItemsTree);
        return $oMenu;
    }
}
