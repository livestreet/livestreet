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
 * Обрабатывые авторизацию
 *
 */
class ActionLogin extends Action {
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->SetDefaultEvent('index');
		Router::SetIsShowStats(false);
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('index','EventLogin');	
		$this->AddEvent('exit','EventExit');	
		$this->AddEvent('reminder','EventReminder');	
	}
	/**
	 * Обрабатываем процесс залогинивания
	 *
	 */
	protected function EventLogin() {	
		/**
		 * Если нажали кнопку "Войти"
		 */
		if (isPost('submit_login') and is_string(getRequest('login')) and is_string(getRequest('password'))) {
			/**
			 * Проверяем есть ли такой юзер по логину
			 */
			if ((func_check(getRequest('login'),'mail') and $oUser=$this->User_GetUserByMail(getRequest('login')))  or  $oUser=$this->User_GetUserByLogin(getRequest('login'))) {	
				/**
				 * Сверяем хеши паролей и проверяем активен ли юзер
				 */
				if ($oUser->getPassword()==func_encrypt(getRequest('password')) and $oUser->getActivate()) {
					$bRemember=getRequest('remember',false) ? true : false;
					/**
					 * Авторизуем
					 */
					$this->User_Authorization($oUser,$bRemember);	
					/**
					 * Перенаправляем на страницу с которой произошла авторизация
					 */
					if (isset($_SERVER['HTTP_REFERER'])) {
						$sBackUrl=$_SERVER['HTTP_REFERER'];
						if (strpos($sBackUrl,Router::GetPath('login'))===false) {
							Router::Location($sBackUrl);
						}
					}					 
					Router::Location(Config::Get('path.root.web').'/');
				}
			}			
			$this->Viewer_Assign('bLoginError',true);
		}
		$this->Viewer_AddHtmlTitle($this->Lang_Get('login'));
	}
	/**
	 * Обрабатываем процесс разлогинивания
	 *
	 */
	protected function EventExit() {
		$this->Security_ValidateSendForm();
		$this->User_Logout();
		$this->Viewer_Assign('bRefreshToHome',true);
	}
	/**
	 * Обработка напоминания пароля
	 *
	 */
	protected function EventReminder() {
		$this->Viewer_AddHtmlTitle($this->Lang_Get('password_reminder'));
		
		if ($this->GetParam(0)=='send') {
			$this->SetTemplateAction('reminder_send');
			return ;
		}
		
		/**
		 * Проверка кода на восстановление пароля и генерация нового пароля
		 */
		if (func_check($this->GetParam(0),'md5')) {
			if ($oReminder=$this->User_GetReminderByCode($this->GetParam(0))) {
				if (!$oReminder->getIsUsed() and strtotime($oReminder->getDateExpire())>time() and $oUser=$this->User_GetUserById($oReminder->getUserId())) {
					$sNewPassword=func_generator(7);
					$oUser->setPassword(func_encrypt($sNewPassword));
					if ($this->User_Update($oUser)) {
						$oReminder->setDateUsed(date("Y-m-d H:i:s"));
						$oReminder->setIsUsed(1);
						$this->User_UpdateReminder($oReminder);
						$this->Notify_SendReminderPassword($oUser,$sNewPassword);
						$this->SetTemplateAction('reminder_confirm');
						return ;
					}					
				}
			}
			$this->Message_AddErrorSingle($this->Lang_Get('password_reminder_bad_code'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Обрабатываем запрос на смену пароля
		 */
		if (isPost('submit_reminder')) {
			if ((func_check(getRequest('mail'),'mail') and $oUser=$this->User_GetUserByMail(getRequest('mail')))) {	
				/**
				 * Формируем и отправляем ссылку на смену пароля
				 */
				$oReminder=Engine::GetEntity('User_Reminder');
				$oReminder->setCode(func_generator(32));
				$oReminder->setDateAdd(date("Y-m-d H:i:s"));
				$oReminder->setDateExpire(date("Y-m-d H:i:s",time()+60*60*24*7));
				$oReminder->setDateUsed(null);
				$oReminder->setIsUsed(0);
				$oReminder->setUserId($oUser->getId());
				if ($this->User_AddReminder($oReminder)) {					
					$this->Notify_SendReminderCode($oUser,$oReminder);
					Router::Location(Router::GetPath('login').'reminder/send/');
				}
			} else {
				$this->Message_AddError($this->Lang_Get('password_reminder_bad_email'),$this->Lang_Get('error'));
			}
		}
	}
}
?>