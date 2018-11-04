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
 * Description of BlockMenu
 *
 * @author oleg
 */
class BlockMenu extends Block {

    public function Exec() {
        $sNameMenu = $this->GetParam('name');
                
        if(!$oMenu = $this->Menu_Get($sNameMenu)){
            return false;
        }
        
        $this->Hook_Run('menu_before_prepare', ['menu' => &$oMenu]);
                
        $ItemsTree = $this->prepareItems($oMenu->getItems());
                        
        $this->Hook_Run('menu_after_prepare', ['items' => &$ItemsTree['items']]);
        
        $this->Viewer_Assign('activeItem', $this->GetParam('activeItem', null), true);  
        $this->Viewer_Assign('mods', $this->GetParam('mods', 'main'), true);  
        $this->Viewer_Assign('classes', $this->GetParam('classes', null), true); 
        $this->Viewer_Assign('params', $ItemsTree);
        
        $this->SetTemplate("component@menu");
    }
    
    public function prepareItems($ItemsTree) {
        if( !is_array($ItemsTree) or !count($ItemsTree) ){
            return null;
        }
        $aItemsNav = [];
        
        foreach ($ItemsTree as $ItemTree) {
            $aChildrens = $ItemTree->getChildren();
            $aItemsNav[] = [
                'url' =>        Router::GetPath( $ItemTree->getUrl() ),
                'name' =>       $ItemTree->getName(),
                'text' =>       $this->Lang_Get($ItemTree->getTitle()),
                'menu' =>       $this->prepareItems( $aChildrens )
            ];
        }
        return [ 'items' => $aItemsNav];
    }

}
