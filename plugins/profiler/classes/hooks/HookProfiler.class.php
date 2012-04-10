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
 * Регистрация хуков
 *
 */
class PluginProfiler_HookProfiler extends Hook {

	public function RegisterHook() {
		/**
		 * Хук для вставки HTML кода
		 */
		if ($oUserCurrent=$this->User_GetUserCurrent() and $oUserCurrent->isAdministrator()) {
			$this->AddHook('template_body_end', 'Profiler');
		}
	}

	/**
	 * Выводим HTML
	 *
	 */
	public function Profiler() {
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'link.tpl');
	}
}
?>