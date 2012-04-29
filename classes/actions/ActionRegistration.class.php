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
 * Экшен обработки регистрации
 *
 * @package actions
 * @since 1.0
 */
class ActionRegistration extends Action {
	/**
	 * Инициализация
	 *
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
		/**
		 * Устанавливаем title страницы
		 */
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

		$this->AddEvent('ajax-validate-fields','EventAjaxValidateFields');
		$this->AddEvent('ajax-registration','EventAjaxRegistration');
	}



	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Ajax валидация форму регистрации
	 */
	protected function EventAjaxValidateFields() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Создаем объект пользователя и устанавливаем сценарий валидации
		 */
		$oUser=Engine::GetEntity('ModuleUser_EntityUser');
		$oUser->_setValidateScenario('registration');
		/**
		 * Пробегаем по переданным полям/значениям и валидируем их каждое в отдельности
		 */
		$aFields=getRequest('fields');
		if (is_array($aFields)) {
			foreach($aFields as $aField) {
				if (isset($aField['field']) and isset($aField['value'])) {
					$this->Hook_Run('registration_validate_field', array('aField'=>&$aField));

					$sField=$aField['field'];
					$sValue=$aField['value'];
					/**
					 * Список полей для валидации
					 */
					switch($sField){
						case 'login':
							$oUser->setLogin($sValue);
							break;
						case 'mail':
							$oUser->setMail($sValue);
							break;
						case 'captcha':
							$oUser->setCaptcha($sValue);
							break;
						case 'password':
							$oUser->setPassword($sValue);
							break;
						case 'password_confirm':
							$oUser->setPasswordConfirm($sValue);
							$oUser->setPassword(isset($aField['params']['password']) ? $aField['params']['password'] : null);
							break;
						default:
							continue;
							break;
					}
					/**
					 * Валидируем поле
					 */
					$oUser->_Validate(array($sField),false);
				}
			}
		}
		/**
		 * Возникли ошибки?
		 */
		if ($oUser->_hasValidateErrors()) {
			/**
			 * Получаем ошибки
			 */
			$this->Viewer_AssignAjax('aErrors',$oUser->_getValidateErrors());
		}
	}
	/**
	 * Обработка Ajax регистрации
	 */
	protected function EventAjaxRegistration() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Создаем объект пользователя и устанавливаем сценарий валидации
		 */
		$oUser=Engine::GetEntity('ModuleUser_EntityUser');
		$oUser->_setValidateScenario('registration');
		/**
		 * Заполняем поля (данные)
		 */
		$oUser->setLogin(getRequest('login'));
		$oUser->setMail(getRequest('mail'));
		$oUser->setPassword(getRequest('password'));
		$oUser->setPasswordConfirm(getRequest('password_confirm'));
		$oUser->setCaptcha(getRequest('captcha'));
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
		$this->Hook_Run('registration_validate_before', array('oUser'=>$oUser));
		/**
		 * Запускаем валидацию
		 */
		if ($oUser->_Validate()) {
			$this->Hook_Run('registration_validate_after', array('oUser'=>$oUser));
			$oUser->setPassword(md5($oUser->getPassword()));
			if ($this->User_Add($oUser)) {
				$this->Hook_Run('registration_after', array('oUser'=>$oUser));
				/**
				 * Убиваем каптчу
				 */
				unset($_SESSION['captcha_keystring']);
				/**
				 * Подписываем пользователя на дефолтные события в ленте активности
				 */
				$this->Stream_switchUserEventDefaultTypes($oUser->getId());
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
					$this->Viewer_AssignAjax('sUrlRedirect',Router::GetPath('registration').'confirm/');
				} else {
					$this->Notify_SendRegistration($oUser,getRequest('password'));
					$oUser=$this->User_GetUserById($oUser->getId());
					/**
					 * Сразу авторизуем
					 */
					$this->User_Authorization($oUser,false);
					$this->DropInviteRegister();
					/**
					 * Определяем URL для редиректа после авторизации
					 */
					$sUrl=Config::Get('module.user.redirect_after_registration');
					if (getRequest('return-path')) {
						$sUrl=getRequest('return-path');
					}
					$this->Viewer_AssignAjax('sUrlRedirect',$sUrl ? $sUrl : Config::Get('path.root.web'));
					$this->Message_AddNoticeSingle($this->Lang_Get('registration_ok'));
				}
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
				return;
			}
		} else {
			/**
			 * Получаем ошибки
			 */
			$this->Viewer_AssignAjax('aErrors',$oUser->_getValidateErrors());
		}
	}
	/**
	 * Показывает страничку регистрации
	 * Просто вывод шаблона
	 */
	protected function EventIndex() {

	}
	/**
	 * Обрабатывает активацию аккаунта
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
		/**
		 * Обработка отправки формы с кодом приглашения
		 */
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
	 * Пытается ли юзер зарегистрироваться с помощью кода приглашения
	 *
	 * @return bool
	 */
	protected function CheckInviteRegister() {
		if ($this->Session_Get('invite_code')) {
			return true;
		}
		return false;
	}
	/**
	 * Вожвращает код приглашения из сессии
	 *
	 * @return string
	 */
	protected function GetInviteRegister() {
		return $this->Session_Get('invite_code');
	}
	/**
	 * Удаляет код приглашения из сессии
	 */
	protected function DropInviteRegister() {
		if (Config::Get('general.reg.invite')) {
			$this->Session_Drop('invite_code');
		}
	}
	/**
	 * Просто выводит шаблон для подтверждения регистрации
	 *
	 */
	protected function EventConfirm() {
	}
}
?>