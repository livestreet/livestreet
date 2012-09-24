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
 * Обработка главной страницы, т.е. УРЛа вида /index/
 *
 * @package actions
 * @since 1.0
 */
class ActionIndex extends Action {
	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->SetDefaultEvent('index');
	}
	/**
	 * Регистрация евентов
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^index$/i','EventIndex');
		$this->AddEventPreg('/^about/i','EventAbout');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Обработка эвента /index/
	 */
	protected function EventIndex() {
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}

	/**
	 * Обработка эвента /about/
	 */
	protected function EventAbout() {
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('about');
	}

}
?>