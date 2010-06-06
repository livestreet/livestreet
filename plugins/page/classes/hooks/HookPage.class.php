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

/**
 * Регистрация хука для вывода меню страниц
 *
 */
class PluginPage_HookPage extends Hook {
	public function RegisterHook() {
		$this->AddHook('template_main_menu','Menu');
	}

	public function Menu() {
		$aPages=$this->PluginPage_Page_GetPages(array('pid'=>null,'main'=>1,'active'=>1));
		
		
		
		$this->Viewer_Assign('aPagesMain',$aPages);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'main_menu.tpl');
	}
}
?>