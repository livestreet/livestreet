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
 * Экшен обработки УРЛа вида /admin/
 *
 * @package actions
 * @since 1.0
 */
class ActionAdmin extends Action {
	/**
	 * Текущий пользователь
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent=null;
	/**
	 * Главное меню
	 *
	 * @var string
	 */
	protected $sMenuHeadItemSelect='admin';

	/**
	 * Инициализация
	 *
	 * @return string
	 */
	public function Init() {
		/**
		 * Если нет прав доступа - перекидываем на 404 страницу
		 */
		if(!$this->User_IsAuthorization() or !$oUserCurrent=$this->User_GetUserCurrent() or !$oUserCurrent->isAdministrator()) {
			return parent::EventNotFound();
		}
		$this->SetDefaultEvent('index');

		$this->oUserCurrent=$oUserCurrent;
	}
	/**
	 * Регистрация евентов
	 */
	protected function RegisterEvent() {
		$this->AddEvent('index','EventIndex');
		$this->AddEvent('plugins','EventPlugins');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Отображение главной страницы админки
	 */
	protected function EventIndex() {
		/**
		 * Определяем доступность установки расширенной админ-панели
		 */
		$aPluginsAll=func_list_plugins(true);
		if (in_array('admin',$aPluginsAll)) {
			$this->Viewer_Assign('bAvailableAdminPlugin',true);
		}
	}
	/**
	 * Страница со списком плагинов
	 *
	 */
	protected function EventPlugins() {
		$this->sMenuHeadItemSelect='plugins';
		/**
		 * Обработка удаления плагинов
		 */
		if (isPost('submit_plugins_del')) {
			$this->Security_ValidateSendForm();

			$aPluginsDelete=getRequest('plugin_del');
			if (is_array($aPluginsDelete)) {
				$this->Plugin_Delete(array_keys($aPluginsDelete));
			}
		}
		/**
		 * Получаем название плагина и действие
		 */
		if($sPlugin=getRequestStr('plugin',null,'get') and $sAction=getRequestStr('action',null,'get')) {
			return $this->SubmitManagePlugin($sPlugin,$sAction);
		}
		/**
		 * Получаем список блогов
		 */
		$aPlugins=$this->Plugin_GetList(array('order'=>'name'));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign("aPlugins",$aPlugins);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('admin.plugins.title'));
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('plugins');
	}
	/**
	 * Активация\деактивация плагина
	 *
	 * @param string $sPlugin	Имя плагина
	 * @param string $sAction	Действие
	 */
	protected function SubmitManagePlugin($sPlugin,$sAction) {
		$this->Security_ValidateSendForm();
		if(!in_array($sAction,array('activate','deactivate'))) {
			$this->Message_AddError($this->Lang_Get('admin.plugins.notices.unknown_action'),$this->Lang_Get('error'),true);
			Router::Location(Router::GetPath('plugins'));
		}
		/**
		 * Активируем\деактивируем плагин
		 */
		if($bResult=$this->Plugin_Toggle($sPlugin,$sAction)) {
			$this->Message_AddNotice($this->Lang_Get('admin.plugins.notices.action_ok'),$this->Lang_Get('attention'),true);
		} else {
			if(!($aMessages=$this->Message_GetErrorSession()) or !count($aMessages)) $this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'),true);
		}
		/**
		 * Возвращаем на страницу управления плагинами
		 */
		Router::Location(Router::GetPath('admin').'plugins/');
	}
	/**
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {
		/**
		 * Загружаем в шаблон необходимые переменные
		 */
		$this->Viewer_Assign('sMenuHeadItemSelect',$this->sMenuHeadItemSelect);
	}
}