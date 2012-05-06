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
 * Экшен обработки УРЛа вида /my/
 * Оставлен только для редиректов со старых УРЛ на новые
 *
 * @package actions
 * @since 1.0
 */
class ActionMy extends Action {
	/**
	 * Объект юзера чей профиль мы смотрим
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserProfile=null;

	/**
	 * Инициализация
	 */
	public function Init() {
	}
	/**
	 * Регистрируем евенты
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^.+$/i','/^(page(\d+))?$/i','EventTopics');
		$this->AddEventPreg('/^.+$/i','/^blog$/i','/^(page(\d+))?$/i','EventTopics');
		$this->AddEventPreg('/^.+$/i','/^comment$/i','/^(page(\d+))?$/i','EventComments');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Выводит список топиков которые написал юзер
	 * Перенаправляет на профиль пользователя
	 *
	 */
	protected function EventTopics() {
		/**
		 * Получаем логин из УРЛа
		 */
		$sUserLogin=$this->sCurrentEvent;
		/**
		 * Проверяем есть ли такой юзер
		 */
		if (!($this->oUserProfile=$this->User_GetUserByLogin($sUserLogin))) {
			return parent::EventNotFound();
		}
		/**
		 * Передан ли номер страницы
		 */
		if ($this->GetParamEventMatch(0,0)=='blog') {
			$iPage=$this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;
		} else {
			$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;
		}
		/**
		 * Выполняем редирект на новый URL, в новых версиях LS экшен "my" будет удален
		 */
		$sPage=$iPage==1 ? '' : "page{$iPage}/";
		Router::Location($this->oUserProfile->getUserWebPath().'created/topics/'.$sPage);
	}

	/**
	 * Выводит список комментариев которые написал юзер
	 * Перенаправляет на профиль пользователя
	 *
	 */
	protected function EventComments() {
		/**
		 * Получаем логин из УРЛа
		 */
		$sUserLogin=$this->sCurrentEvent;
		/**
		 * Проверяем есть ли такой юзер
		 */
		if (!($this->oUserProfile=$this->User_GetUserByLogin($sUserLogin))) {
			return parent::EventNotFound();
		}
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;
		/**
		 * Выполняем редирект на новый URL, в новых версиях LS экшен "my" будет удален
		 */
		$sPage=$iPage==1 ? '' : "page{$iPage}/";
		Router::Location($this->oUserProfile->getUserWebPath().'created/comments/'.$sPage);
	}
}
?>