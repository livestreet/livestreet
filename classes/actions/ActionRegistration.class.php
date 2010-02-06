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
 * Обрабатывает регистрацию
 *
 */
class ActionRegistration extends Action {
	/**
	 * Инициализация
	 *
	 * @return unknown
	 */
	public function Init() {
		/**
		 * Проверяем аторизован ли юзер
		 */
		if ($this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('registration_is_authorization'),$this->Lang_Get('attention'));
			return Router::Action('error'); 
		}
		/**
		 * Если включены инвайты то перенаправляем на страницу регистрации по инвайтам
		 */
		if (!$this->User_IsAuthorization() and Config::Get('general.reg.invite') and !in_array(Router::GetActionEvent(),array('invite','activate','confirm')) and !$this->CheckInviteRegister()) {			
			return Router::Action('registration','invite');			
		}
		
		$this->SetDefaultEvent('index');
		$this->Viewer_AddHtmlTitle($this->Lang_Get('registration'));
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('index','EventIndex');			
		$this->AddEvent('confirm','EventConfirm');
		$this->AddEvent('activate','EventActivate');
		$this->AddEvent('invite','EventInvite');
	}
	
	
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Показывает страничку регистрации и обрабатывает её
	 *
	 * @return unknown
	 */
	protected function EventIndex() {			
		/**
		 * Если нажали кнопку "Зарегистрироваться"
		 */
		if (isPost('submit_register')) {
			//Проверяем  входные данные
			$bError=false;
			/**
			 * Проверка логина
			 */
			if (!func_check(getRequest('login'),'login',3,30)) {
				$this->Message_AddError($this->Lang_Get('registration_login_error'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * Проверка мыла
			 */
			if (!func_check(getRequest('mail'),'mail')) {
				$this->Message_AddError($this->Lang_Get('registration_mail_error'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * Проверка пароля
			 */
			if (!func_check(getRequest('password'),'password',5)) {
				$this->Message_AddError($this->Lang_Get('registration_password_error'),$this->Lang_Get('error'));
				$bError=true;
			} elseif (getRequest('password')!=getRequest('password_confirm')) {
				$this->Message_AddError($this->Lang_Get('registration_password_error_different'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * Проверка капчи(циферки с картинки)
			 */
			if (!isset($_SESSION['captcha_keystring']) or $_SESSION['captcha_keystring']!=strtolower(getRequest('captcha'))) {
				$this->Message_AddError($this->Lang_Get('registration_captcha_error'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * А не занят ли логин?
			 */
			if ($this->User_GetUserByLogin(getRequest('login'))) {
				$this->Message_AddError($this->Lang_Get('registration_login_error_used'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * А не занято ли мыло?
			 */
			if ($this->User_GetUserByMail(getRequest('mail'))) {
				$this->Message_AddError($this->Lang_Get('registration_mail_error_used'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * Если всё то пробуем зарегить
			 */
			if (!$bError) {
				/**
				 * Создаем юзера
				 */
				$oUser=Engine::GetEntity('User');
				$oUser->setLogin(getRequest('login'));
				$oUser->setMail(getRequest('mail'));
				$oUser->setPassword(func_encrypt(getRequest('password')));
				$oUser->setDateRegister(date("Y-m-d H:i:s"));
				$oUser->setIpRegister(func_getIp());
				/**
				 * Если используется активация, то генерим код активации
				 */
				if (Config::Get('general.reg.activation')) {
					$oUser->setActivate(0);
					$oUser->setActivateKey(md5(func_generator().time()));
				} else {
					$oUser->setActivate(1);
					$oUser->setActivateKey(null);
				}					
				/**
				 * Регистрируем
				 */
				if ($this->User_Add($oUser)) {	
					/**
					 * Убиваем каптчу
					 */
					unset($_SESSION['captcha_keystring']);
					/**
					 * Создаем персональный блог
					 */
					$this->Blog_CreatePersonalBlog($oUser);		
					
					
					/**
					 * Если юзер зарегистрировался по приглашению то обновляем инвайт
					 */
					if (Config::Get('general.reg.invite') and $oInvite=$this->User_GetInviteByCode($this->GetInviteRegister())) {
						$oInvite->setUserToId($oUser->getId());
						$oInvite->setDateUsed(date("Y-m-d H:i:s"));
						$oInvite->setUsed(1);
						$this->User_UpdateInvite($oInvite);
					}
					/**
					 * Если стоит регистрация с активацией то проводим её
					 */
					if (Config::Get('general.reg.activation')) {
						/**
						 * Отправляем на мыло письмо о подтверждении регистрации						 
						 */					
						$this->Notify_SendRegistrationActivate($oUser,getRequest('password'));
						Router::Location(Router::GetPath('registration').'confirm/');						
					} else {
						$this->Notify_SendRegistration($oUser,getRequest('password'));
						$this->Viewer_Assign('bRefreshToHome',true);
						$oUser=$this->User_GetUserById($oUser->getId());
						$this->User_Authorization($oUser,false);
						$this->SetTemplateAction('ok');
						$this->DropInviteRegister();
					}								
				} else {
					$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
					return Router::Action('error'); 
				}
			}
		}
	}
	/**
	 * Обрабатывает активацию аккаунта
	 *
	 * @return unknown
	 */
	protected function EventActivate() {		
		$bError=false;
		/**
		 * Проверяет передан ли код активации
		 */
		$sActivateKey=$this->GetParam(0);
		if (!func_check($sActivateKey,'md5')) {				
			$bError=true;
		}	
		/**
		 * Проверяет верный ли код активации
		 */
		if (!($oUser=$this->User_GetUserByActivateKey($sActivateKey))) {
			$bError=true;
		}
		/**
		 * 
		 */
		if ($oUser and $oUser->getActivate()) {
			$this->Message_AddErrorSingle($this->Lang_Get('registration_activate_error_reactivate'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Если что то не то
		 */
		if ($bError) {
			$this->Message_AddErrorSingle($this->Lang_Get('registration_activate_error_code'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Активируем
		 */
		$oUser->setActivate(1);
		$oUser->setDateActivate(date("Y-m-d H:i:s"));
		/**
		 * Сохраняем юзера
		 */
		if ($this->User_Update($oUser)) {
			$this->DropInviteRegister();
			$this->Viewer_Assign('bRefreshToHome',true);						
			$this->User_Authorization($oUser,false);						
			return;
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return Router::Action('error');
		}
	}
	/**
	 * Обработка кода приглашения при включеном режиме инвайтов
	 *
	 */
	protected function EventInvite() {	
		if (!Config::Get('general.reg.invite')) {
			return parent::EventNotFound();
		}
			
		if (isPost('submit_invite')) {
			/**
			 * проверяем код приглашения на валидность
			 */
			if ($this->CheckInviteRegister()) {
				$sInviteId=$this->GetInviteRegister();
			} else {
				$sInviteId=getRequest('invite_code');
			}			
			$oInvate=$this->User_GetInviteByCode($sInviteId);
			if ($oInvate) {
				if (!$this->CheckInviteRegister()) {
					$this->Session_Set('invite_code',$oInvate->getCode());
				}
				return Router::Action('registration');
			} else {
				$this->Message_AddError($this->Lang_Get('registration_invite_code_error'),$this->Lang_Get('error'));				
			}
		}									
	}
	/**
	 * Путается ли юзер зарегистрироваться с помощью кода приглашения
	 *
	 * @return unknown
	 */
	protected function CheckInviteRegister() {
		if ($this->Session_Get('invite_code')) {
			return true;
		}
		return false;
	}
	
	protected function GetInviteRegister() {		
		return $this->Session_Get('invite_code');
	}
	
	protected function DropInviteRegister() {
		if (Config::Get('general.reg.invite')) {
			$this->Session_Drop('invite_code');
		}
	}
		
	/**
	 * Просто выводит шаблон
	 *
	 */
	protected function EventConfirm() {											
	}
}
?>