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
 * Класс обработки УРЛа вида /error/ т.е. ошибок
 *
 */
class ActionError extends Action {
	/**
	 * Инициализация экшена
	 *
	 */
	public function Init() {
		$this->SetDefaultEvent('index');
		Router::SetIsShowStats(false);
	}
	/**
	 * Регистрируем евент
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('index','EventError');		
		$this->AddEvent('404','EventError');		
	}
	/**
	 * То что делаем при выполнении евента, т.е. ничего :) просто выводим шаблон
	 *
	 */
	protected function EventError() {
		/**
		 * Если эвент равен 404, то значит нужно в хидере послать браузеру HTTP/1.1 404 Not Found
		 */		
		if ($this->sCurrentEvent=='404') {			
			$this->Message_AddErrorSingle($this->Lang_Get('system_error_404'),'404');
			header("HTTP/1.1 404 Not Found");
		}
		$this->Viewer_AddHtmlTitle($this->Lang_Get('error'));
		$this->SetTemplateAction('index');
	}
}
?>