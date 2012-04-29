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
 * Экшен обработки УРЛа вида /error/ т.е. ошибок
 *
 * @package actions
 * @since 1.0
 */
class ActionError extends Action {
	/**
	 * Инициализация экшена
	 *
	 */
	public function Init() {
		/**
		 * Устанавливаем дефолтный евент
		 */
		$this->SetDefaultEvent('index');
		/**
		 * Запрешаем отображать статистику выполнения
		 */
		Router::SetIsShowStats(false);
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEvent('index','EventError');
		$this->AddEvent('404','EventError');
	}
	/**
	 * Вывод ошибки
	 *
	 */
	protected function EventError() {
		/**
		 * Если эвент равен 404, то значит нужно в хидере послать браузеру HTTP/1.1 404 Not Found
		 */
		if ($this->sCurrentEvent=='404') {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error_404'),'404');
			$sProtocol=isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
			header("{$sProtocol} 404 Not Found");
		}
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($this->Lang_Get('error'));
		$this->SetTemplateAction('index');
	}
}
?>