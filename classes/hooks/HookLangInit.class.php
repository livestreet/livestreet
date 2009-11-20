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

class HookLangInit extends Hook {   
	public function RegisterHook() {
		$this->AddHook('init_action','InitLang',__CLASS__,0);
	}

	public function InitLang($aVars) {
		$this->Lang_GetLang();
	}
}
?>