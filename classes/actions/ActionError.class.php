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
	 * Список специфических HTTP ошибок для которых необходимо отдавать header
	 *
	 * @var array
	 */
	protected $aHttpErrors=array(
		'404' => array(
			'header' => '404 Not Found',
		),
	);
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
		$this->AddEventPreg('/^\d{3}$/i','EventError');
	}
	/**
	 * Вывод ошибки
	 *
	 */
	protected function EventError() {
		/**
		 * Если евент равен одной из ошибок из $aHttpErrors, то шлем браузеру специфичный header
		 * Например, для 404 в хидере будет послан браузеру заголовок HTTP/1.1 404 Not Found
		 */
		if (array_key_exists($this->sCurrentEvent,$this->aHttpErrors)) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error_'.$this->sCurrentEvent),$this->sCurrentEvent);
			$aHttpError=$this->aHttpErrors[$this->sCurrentEvent];
			if (isset($aHttpError['header'])) {
				$sProtocol=isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
				header("{$sProtocol} {$aHttpError['header']}");
			}
		}
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($this->Lang_Get('error'));
		$this->SetTemplateAction('index');
	}
}
?>