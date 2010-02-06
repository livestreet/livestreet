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
 * Класс обработки УРЛа вида /comments/
 *
 */
class ActionPlugins extends Action {	
	
	/**
	 * Главное меню
	 *
	 * @var string
	 */
	protected $sMenuHeadItemSelect='plugins';
	
	public function Init() {
		if(!$this->User_IsAuthorization() or !$oUserCurrent=$this->User_GetUserCurrent() or !$oUserCurrent->isAdministrator()) {
			return parent::EventNotFound();
		}
		
		$this->oUserCurrent=$oUserCurrent;
	}
	
	protected function RegisterEvent() {	
		$this->AddEventPreg('/^(page(\d+))?$/i','EventIndex');								
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */	
	
	
	protected function EventIndex() {		
		/**
		 * Обработка удаления плагинов
		 */
		if (isPost('submit_plugins_del')) {
			$this->Security_ValidateSendForm();
			
			$aPluginsDelete=getRequest('plugins_del');
			if (is_array($aPluginsDelete)) {
				$this->Plugin_Delete(array_keys($aPluginsDelete));				
			}
		}
		
		if($sPlugin=getRequest('plugin',null,'get') and $sAction=getRequest('action',null,'get')) {
			return $this->SubmitManagePlugin($sPlugin,$sAction);
		}
		/**
		 * Передан ли номер страницы
		 */			
		$iPage=	preg_match("/^\d+$/i",$this->GetEventMatch(2)) ? $this->GetEventMatch(2) : 1;
		/**
		 * Получаем список блогов
		 */
		$aPlugins=$this->Plugin_GetList();
		/**
		 * Загружаем переменные в шаблон
		 */						
		$this->Viewer_Assign("aPlugins",$aPlugins);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('plugins_administartion_title'));
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
	
	/**
	 * Активация\деактивация плагина
	 *
	 * @param string $sPlugin
	 * @param string $sAction
	 */
	protected function SubmitManagePlugin($sPlugin,$sAction) {
		if(!in_array($sAction,array('activate','deactivate'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('plugins_unknown_action'),$this->Lang_Get('error'),true);
			Router::Location(Router::GetPath('plugins'));
		}
		/**
		 * Активируем\деактивируем плагин
		 */
		if($bResult=$this->Plugin_Toggle($sPlugin,$sAction)) {
			$this->Message_AddNotice($this->Lang_Get('plugins_action_ok'),$this->Lang_Get('attention'),true);	
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'),true);			
		}
		/**
		 * Возвращаем на страницу управления плагинами
		 */
		Router::Location(Router::GetPath('plugins'));
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
?>