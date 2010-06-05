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
 * Обрабатывает настройки профила юзера
 *
 */
class ActionSettings extends Action {
	/**
	 * Какое меню активно
	 *
	 * @var unknown_type
	 */
	protected $sMenuItemSelect='settings';
	/**
	 * Какое подменю активно
	 *
	 * @var unknown_type
	 */
	protected $sMenuSubItemSelect='profile';
	/**
	 * Текущий юзер
	 *
	 * @var unknown_type
	 */
	protected $oUserCurrent=null;
	
	/**
	 * Инициализация 
	 *
	 * @return unknown
	 */
	public function Init() {
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error'); 
		}
		/**
		 * Получаем текущего юзера
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		$this->SetDefaultEvent('profile');	
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu'));
	}
	
	protected function RegisterEvent() {		
		$this->AddEvent('profile','EventProfile');		
		$this->AddEvent('invite','EventInvite');	
		$this->AddEvent('tuning','EventTuning');			
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	protected function EventTuning() {
		$this->sMenuItemSelect='settings';
		$this->sMenuSubItemSelect='tuning';
		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_tuning'));
		
		if (isPost('submit_settings_tuning')) {
			$this->Security_ValidateSendForm();			
			
			$this->oUserCurrent->setSettingsNoticeNewTopic( getRequest('settings_notice_new_topic') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewComment( getRequest('settings_notice_new_comment') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewTalk( getRequest('settings_notice_new_talk') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeReplyComment( getRequest('settings_notice_reply_comment') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewFriend( getRequest('settings_notice_new_friend') ? 1 : 0 );
			$this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
			if ($this->User_Update($this->oUserCurrent)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('settings_tuning_submit_ok'));
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			}
		}
	}
	
	/**
	 * Показ и обработка формы приглаешний
	 *
	 * @return unknown
	 */
	protected function EventInvite() {		
		if (!Config::Get('general.reg.invite')) {
			return parent::EventNotFound();
		}
		
		$this->sMenuItemSelect='invite';
		$this->sMenuSubItemSelect='';		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_invite'));		
		
		if (isPost('submit_invite')) {
			$this->Security_ValidateSendForm();			
			
			$bError=false;
			if (!$this->ACL_CanSendInvite($this->oUserCurrent) and !$this->oUserCurrent->isAdministrator()) {
				$this->Message_AddError($this->Lang_Get('settings_invite_available_no'),$this->Lang_Get('error'));		
				$bError=true;		
			}
			if (!func_check(getRequest('invite_mail'),'mail')) {
				$this->Message_AddError($this->Lang_Get('settings_invite_mail_error'),$this->Lang_Get('error'));		
				$bError=true;		
			}
			if (!$bError) {
				$oInvite=$this->User_GenerateInvite($this->oUserCurrent);
				$this->Notify_SendInvite($this->oUserCurrent,getRequest('invite_mail'),$oInvite);
				$this->Message_AddNoticeSingle($this->Lang_Get('settings_invite_submit_ok'));
			}
		}
		
		$this->Viewer_Assign('iCountInviteAvailable',$this->User_GetCountInviteAvailable($this->oUserCurrent));
		$this->Viewer_Assign('iCountInviteUsed',$this->User_GetCountInviteUsed($this->oUserCurrent->getId()));		
	}
	
	/**
	 * Выводит форму для редактирования профиля и обрабатывает её
	 *
	 */
	protected function EventProfile() {
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_profile'));
		/**
		 * Если нажали кнопку "Сохранить"
		 */
		if (isPost('submit_profile_edit')) {
			$this->Security_ValidateSendForm();
						
			$bError=false;			
			/**
		 	* Заполняем профиль из полей формы
		 	*/
			/**
			 * Проверяем имя
			 */
			if (func_check(getRequest('profile_name'),'text',2,20)) {
				$this->oUserCurrent->setProfileName(getRequest('profile_name'));
			} else {
				$this->oUserCurrent->setProfileName(null);
			}
			/**
			 * Проверка мыла
			 */
			if (func_check(getRequest('mail'),'mail')) {
				if ($oUserMail=$this->User_GetUserByMail(getRequest('mail')) and $oUserMail->getId()!=$this->oUserCurrent->getId()) {
					$this->Message_AddError($this->Lang_Get('settings_profile_mail_error_used'),$this->Lang_Get('error'));
					$bError=true;
				} else {
					$this->oUserCurrent->setMail(getRequest('mail'));
				}				
			} else {
				$this->Message_AddError($this->Lang_Get('settings_profile_mail_error'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * Проверяем пол
			 */
			if (in_array(getRequest('profile_sex'),array('man','woman','other'))) {
				$this->oUserCurrent->setProfileSex(getRequest('profile_sex'));
			} else {
				$this->oUserCurrent->setProfileSex('other');
			}
			/**
			 * Проверяем дату рождения
			 */
			if (func_check(getRequest('profile_birthday_day'),'id',1,2) and func_check(getRequest('profile_birthday_month'),'id',1,2) and func_check(getRequest('profile_birthday_year'),'id',4,4)) {
				$this->oUserCurrent->setProfileBirthday(date("Y-m-d H:i:s",mktime(0,0,0,getRequest('profile_birthday_month'),getRequest('profile_birthday_day'),getRequest('profile_birthday_year'))));
			} else {
				$this->oUserCurrent->setProfileBirthday(null);
			}
			/**
			 * Проверяем страну
			 */
			if (func_check(getRequest('profile_country'),'text',1,30)) {
				$this->oUserCurrent->setProfileCountry(getRequest('profile_country'));
			} else {
				$this->oUserCurrent->setProfileCountry(null);
			}
			/**
			 * Проверяем регион
			 * пока отключим регион, т.к. не понятно нужен ли он вообще =)
			 */
			/*
			if (func_check(getRequest('profile_region'),'text',1,30)) {
				$this->oUserCurrent->setProfileRegion(getRequest('profile_region'));
			} else {
				$this->oUserCurrent->setProfileRegion(null);
			}
			*/
			/**
			 * Проверяем город
			 */
			if (func_check(getRequest('profile_city'),'text',1,30)) {
				$this->oUserCurrent->setProfileCity(getRequest('profile_city'));
			} else {
				$this->oUserCurrent->setProfileCity(null);
			}
			/**
			 * Проверяем ICQ
			 */
			if (func_check(getRequest('profile_icq'),'id',4,15)) {
				$this->oUserCurrent->setProfileIcq(getRequest('profile_icq'));
			} else {
				$this->oUserCurrent->setProfileIcq(null);
			}
			/**
			 * Проверяем сайт
			 */
			if (func_check(getRequest('profile_site'),'text',3,200)) {
				$this->oUserCurrent->setProfileSite(getRequest('profile_site'));
			} else {
				$this->oUserCurrent->setProfileSite(null);
			} 
			/**
			 * Проверяем название сайта
			 */
			if (func_check(getRequest('profile_site_name'),'text',3,50)) {
				$this->oUserCurrent->setProfileSiteName(getRequest('profile_site_name'));
			} else {
				$this->oUserCurrent->setProfileSiteName(null);
			} 
			/**
			 * Проверяем информацию о себе
			 */
			if (func_check(getRequest('profile_about'),'text',1,3000)) {
				$this->oUserCurrent->setProfileAbout(getRequest('profile_about'));
			} else {
				$this->oUserCurrent->setProfileAbout(null);
			} 		
			/**
			 * Проверка на смену пароля
			 */			
			if (getRequest('password','')!='') {
				if (func_check(getRequest('password'),'password',5)) {
					if (getRequest('password')==getRequest('password_confirm')) {
						if (func_encrypt(getRequest('password_now'))==$this->oUserCurrent->getPassword()) {
							$this->oUserCurrent->setPassword(func_encrypt(getRequest('password')));
						} else {
							$bError=true;
							$this->Message_AddError($this->Lang_Get('settings_profile_password_current_error'),$this->Lang_Get('error'));
						}
					} else {
						$bError=true;
						$this->Message_AddError($this->Lang_Get('settings_profile_password_confirm_error'),$this->Lang_Get('error'));
					}
				} else {
					$bError=true;
					$this->Message_AddError($this->Lang_Get('settings_profile_password_new_error'),$this->Lang_Get('error'));
				}
			}		
			/**
			 * Загрузка аватара, делаем ресайзы
			 */		
			if (isset($_FILES['avatar']) and is_uploaded_file($_FILES['avatar']['tmp_name'])) {
				/**
				 * Получаем список текущих аватаров
				 */
				$sPathOld = $this->oUserCurrent->getProfileAvatar();
				$aUserAvatars = array();
				if($sPathOld) {
					foreach (array_merge(Config::Get('module.user.avatar_size'),array(100)) as $iSize) {
						$aUserAvatars[$iSize] = $this->oUserCurrent->getProfileAvatarPath($iSize);
					}
				}
				
				if($sPath=$this->User_UploadAvatar($_FILES['avatar'],$this->oUserCurrent)) {
					$this->oUserCurrent->setProfileAvatar($sPath);
					/**
					 * Удаляем старые, если путь не совпадает с текущими аватарками
					 */
					if($sPathOld and $sPath!=$sPathOld and count($aUserAvatars)) {
						foreach ($aUserAvatars as $iSize=>$sAvatarPath) {
							@unlink($this->Image_GetServerPath($sAvatarPath));
						}
					}
				} else {
					$bError=true;
					$this->Message_AddError($this->Lang_Get('settings_profile_avatar_error'),$this->Lang_Get('error'));					
				}
			}
			/**
			 * Удалить аватара
			 */
			if (getRequest('avatar_delete')) {
				$this->User_DeleteAvatar($this->oUserCurrent);
				$this->oUserCurrent->setProfileAvatar(null);		
			}
			/**
			 * Загрузка фото, делаем ресайзы
			 */			
			if (isset($_FILES['foto']) and is_uploaded_file($_FILES['foto']['tmp_name'])) {				
				if ($sFileFoto=$this->User_UploadFoto($_FILES['foto'],$this->oUserCurrent)) {	
					$this->oUserCurrent->setProfileFoto($sFileFoto);			
				} else {
					$bError=true;
					$this->Message_AddError($this->Lang_Get('settings_profile_foto_error'),$this->Lang_Get('error'));
				}
			}
			/**
			 * Удалить фото
			 */
			if (isset($_REQUEST['foto_delete'])) {
				$this->User_DeleteFoto($this->oUserCurrent);
				$this->oUserCurrent->setProfileFoto(null);
			}
			/**
			 * Ставим дату последнего изменения профиля
			 */
			$this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
			/**
			 * Сохраняем изменения профиля
		 	*/		
			if (!$bError) {
				if ($this->User_Update($this->oUserCurrent)) {
					/**
					 * Добавляем страну
					 */
					if ($this->oUserCurrent->getProfileCountry()) {
						if (!($oCountry=$this->User_GetCountryByName($this->oUserCurrent->getProfileCountry()))) {
							$oCountry=Engine::GetEntity('User_Country');
							$oCountry->setName($this->oUserCurrent->getProfileCountry());
							$this->User_AddCountry($oCountry);
						}
						$this->User_SetCountryUser($oCountry->getId(),$this->oUserCurrent->getId());
					}
					/**
					 * Добавляем город
					 */
					if ($this->oUserCurrent->getProfileCity()) {
						if (!($oCity=$this->User_GetCityByName($this->oUserCurrent->getProfileCity()))) {
							$oCity=Engine::GetEntity('User_City');
							$oCity->setName($this->oUserCurrent->getProfileCity());
							$this->User_AddCity($oCity);
						}
						$this->User_SetCityUser($oCity->getId(),$this->oUserCurrent->getId());
					}
					
					$this->Message_AddNoticeSingle($this->Lang_Get('settings_profile_submit_ok'));
				} else {
					$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
				}
			}
		}
	}	
	
	/**
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {		
		/**
		 * Загружаем в шаблон необходимые переменные
		 */
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
		
		$this->Hook_Run('action_shutdown_settings');	
	}
}
?>