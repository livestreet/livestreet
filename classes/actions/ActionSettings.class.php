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
 * Экшен обрабтки настроек профиля юзера (/settings/)
 *
 * @package actions
 * @since 1.0
 */
class ActionSettings extends Action {
	/**
	 * Какое меню активно
	 *
	 * @var string
	 */
	protected $sMenuItemSelect='settings';
	/**
	 * Какое подменю активно
	 *
	 * @var string
	 */
	protected $sMenuSubItemSelect='profile';
	/**
	 * Текущий юзер
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent=null;

	/**
	 * Инициализация
	 *
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
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu'));
	}
	/**
	 * Регистрация евентов
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^profile$/i','/^upload-avatar/i','/^$/i','EventUploadAvatar');
		$this->AddEventPreg('/^profile$/i','/^resize-avatar/i','/^$/i','EventResizeAvatar');
		$this->AddEventPreg('/^profile$/i','/^remove-avatar/i','/^$/i','EventRemoveAvatar');
		$this->AddEventPreg('/^profile$/i','/^cancel-avatar/i','/^$/i','EventCancelAvatar');
		$this->AddEventPreg('/^profile$/i','/^upload-foto/i','/^$/i','EventUploadFoto');
		$this->AddEventPreg('/^profile$/i','/^resize-foto/i','/^$/i','EventResizeFoto');
		$this->AddEventPreg('/^profile$/i','/^remove-foto/i','/^$/i','EventRemoveFoto');
		$this->AddEventPreg('/^profile$/i','/^cancel-foto/i','/^$/i','EventCancelFoto');
		$this->AddEvent('profile','EventProfile');
		$this->AddEvent('invite','EventInvite');
		$this->AddEvent('tuning','EventTuning');
		$this->AddEvent('account','EventAccount');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Загрузка временной картинки фото для последущего ресайза
	 */
	protected function EventUploadFoto() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('jsonIframe',false);

		if(!isset($_FILES['foto']['tmp_name'])) {
			return false;
		}
		/**
		 * Копируем загруженный файл
		 */
		$sFileTmp=Config::Get('sys.cache.dir').func_generator();
		if (!move_uploaded_file($_FILES['foto']['tmp_name'],$sFileTmp)) {
			return false;
		}
		/**
		 * Ресайзим и сохраняем именьшенную копию
		 * Храним две копии - мелкую для показа пользователю и крупную в качестве исходной для ресайза
		 */
		$sDir=Config::Get('path.uploads.images')."/tmp/fotos/{$this->oUserCurrent->getId()}";
		if ($sFile=$this->Image_Resize($sFileTmp,$sDir,'original',Config::Get('view.img_max_width'),Config::Get('view.img_max_height'),1000,null,true)) {
			if ($sFilePreview=$this->Image_Resize($sFileTmp,$sDir,'preview',Config::Get('view.img_max_width'),Config::Get('view.img_max_height'),200,null,true)) {
				/**
				 * Сохраняем в сессии временный файл с изображением
				 */
				$this->Session_Set('sFotoFileTmp',$sFile);
				$this->Session_Set('sFotoFilePreviewTmp',$sFilePreview);
				$this->Viewer_AssignAjax('sTmpFile',$this->Image_GetWebPath($sFilePreview));
				unlink($sFileTmp);
				return;
			}
		}
		$this->Message_AddError($this->Image_GetLastError(),$this->Lang_Get('error'));
		unlink($sFileTmp);
	}
	/**
	 * Вырезает из временной фотки область нужного размера, ту что задал пользователь
	 */
	protected function EventResizeFoto() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Достаем из сессии временный файл
		 */
		$sFile=$this->Session_Get('sFotoFileTmp');
		$sFilePreview=$this->Session_Get('sFotoFilePreviewTmp');
		if (!file_exists($sFile)) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}
		/**
		 * Определяем размер большого фото для подсчета множителя пропорции
		 */
		$fRation=1;
		if ($aSizeFile=getimagesize($sFile) and isset($aSizeFile[0])) {
			$fRation=$aSizeFile[0]/200; // 200 - размер превью по которой пользователь определяет область для ресайза
			if ($fRation<1) {
				$fRation=1;
			}
		}
		/**
		 * Получаем размер области из параметров
		 */
		$aSize=array();
		$aSizeTmp=getRequest('size');
		if (isset($aSizeTmp['x']) and $aSizeTmp['x'] and isset($aSizeTmp['y']) and isset($aSizeTmp['x2']) and isset($aSizeTmp['y2'])) {
			$aSize=array('x1'=>round($fRation*$aSizeTmp['x']),'y1'=>round($fRation*$aSizeTmp['y']),'x2'=>round($fRation*$aSizeTmp['x2']),'y2'=>round($fRation*$aSizeTmp['y2']));
		}
		/**
		 * Вырезаем аватарку
		 */
		if ($sFileWeb=$this->User_UploadFoto($sFile,$this->oUserCurrent,$aSize)) {
			/**
			 * Удаляем старые аватарки
			 */
			$this->oUserCurrent->setProfileFoto($sFileWeb);
			$this->User_Update($this->oUserCurrent);

			$this->Image_RemoveFile($sFilePreview);
			/**
			 * Удаляем из сессии
			 */
			$this->Session_Drop('sFotoFileTmp');
			$this->Session_Drop('sFotoFilePreviewTmp');
			$this->Viewer_AssignAjax('sFile',$this->oUserCurrent->getProfileFoto());
			$this->Viewer_AssignAjax('sTitleUpload',$this->Lang_Get('settings_profile_photo_change'));
		} else {
			$this->Message_AddError($this->Lang_Get('settings_profile_avatar_error'),$this->Lang_Get('error'));
		}
	}
	/**
	 * Удаляет фото
	 */
	protected function EventRemoveFoto() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Удаляем
		 */
		$this->User_DeleteFoto($this->oUserCurrent);
		$this->oUserCurrent->setProfileFoto(null);
		$this->User_Update($this->oUserCurrent);
		/**
		 * Возвращает дефолтную аватарку
		 */
		$this->Viewer_AssignAjax('sFile',$this->oUserCurrent->getProfileFotoDefault());
		$this->Viewer_AssignAjax('sTitleUpload',$this->Lang_Get('settings_profile_photo_upload'));
	}
	/**
	 * Отмена ресайза фотки, необходимо удалить временный файл
	 */
	protected function EventCancelFoto() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Достаем из сессии файл и удаляем
		 */
		$sFile=$this->Session_Get('sFotoFileTmp');
		$this->Image_RemoveFile($sFile);

		$sFile=$this->Session_Get('sFotoFilePreviewTmp');
		$this->Image_RemoveFile($sFile);
		/**
		 * Удаляем из сессии
		 */
		$this->Session_Drop('sFotoFileTmp');
		$this->Session_Drop('sFotoFilePreviewTmp');
	}
	/**
	 * Загрузка временной картинки для аватара
	 */
	protected function EventUploadAvatar() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('jsonIframe',false);

		if(!isset($_FILES['avatar']['tmp_name'])) {
			return false;
		}
		/**
		 * Копируем загруженный файл
		 */
		$sFileTmp=Config::Get('sys.cache.dir').func_generator();
		if (!move_uploaded_file($_FILES['avatar']['tmp_name'],$sFileTmp)) {
			return false;
		}
		/**
		 * Ресайзим и сохраняем уменьшенную копию
		 */
		$sDir=Config::Get('path.uploads.images')."/tmp/avatars/{$this->oUserCurrent->getId()}";
		if ($sFileAvatar=$this->Image_Resize($sFileTmp,$sDir,'original',Config::Get('view.img_max_width'),Config::Get('view.img_max_height'),200,null,true)) {
			/**
			 * Зписываем в сессию
			 */
			$this->Session_Set('sAvatarFileTmp',$sFileAvatar);
			$this->Viewer_AssignAjax('sTmpFile',$this->Image_GetWebPath($sFileAvatar));
		} else {
			$this->Message_AddError($this->Image_GetLastError(),$this->Lang_Get('error'));
		}
		unlink($sFileTmp);
	}
	/**
	 * Вырезает из временной аватарки область нужного размера, ту что задал пользователь
	 */
	protected function EventResizeAvatar() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Получаем файл из сессии
		 */
		$sFileAvatar=$this->Session_Get('sAvatarFileTmp');
		if (!file_exists($sFileAvatar)) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}
		/**
		 * Получаем размер области из параметров
		 */
		$aSize=array();
		$aSizeTmp=getRequest('size');
		if (isset($aSizeTmp['x']) and $aSizeTmp['x'] and isset($aSizeTmp['y']) and isset($aSizeTmp['x2']) and isset($aSizeTmp['y2'])) {
			$aSize=array('x1'=>$aSizeTmp['x'],'y1'=>$aSizeTmp['y'],'x2'=>$aSizeTmp['x2'],'y2'=>$aSizeTmp['y2']);
		}
		/**
		 * Вырезаем аватарку
		 */
		if ($sFileWeb=$this->User_UploadAvatar($sFileAvatar,$this->oUserCurrent,$aSize)) {
			/**
			 * Удаляем старые аватарки
			 */
			if ($sFileWeb!=$this->oUserCurrent->getProfileAvatar()) {
				$this->User_DeleteAvatar($this->oUserCurrent);
			}
			$this->oUserCurrent->setProfileAvatar($sFileWeb);

			$this->User_Update($this->oUserCurrent);
			$this->Session_Drop('sAvatarFileTmp');
			$this->Viewer_AssignAjax('sFile',$this->oUserCurrent->getProfileAvatarPath(100));
			$this->Viewer_AssignAjax('sTitleUpload',$this->Lang_Get('settings_profile_avatar_change'));
		} else {
			$this->Message_AddError($this->Lang_Get('settings_profile_avatar_error'),$this->Lang_Get('error'));
		}
	}
	/**
	 * Удаляет аватар
	 */
	protected function EventRemoveAvatar() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Удаляем
		 */
		$this->User_DeleteAvatar($this->oUserCurrent);
		$this->oUserCurrent->setProfileAvatar(null);
		$this->User_Update($this->oUserCurrent);
		/**
		 * Возвращает дефолтную аватарку
		 */
		$this->Viewer_AssignAjax('sFile',$this->oUserCurrent->getProfileAvatarPath(100));
		$this->Viewer_AssignAjax('sTitleUpload',$this->Lang_Get('settings_profile_avatar_upload'));
	}
	/**
	 * Отмена ресайза аватарки, необходимо удалить временный файл
	 */
	protected function EventCancelAvatar() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Достаем из сессии файл и удаляем
		 */
		$sFileAvatar=$this->Session_Get('sAvatarFileTmp');
		$this->Image_RemoveFile($sFileAvatar);
		$this->Session_Drop('sAvatarFileTmp');
	}
	/**
	 * Дополнительные настройки сайта
	 */
	protected function EventTuning() {
		$this->sMenuItemSelect='settings';
		$this->sMenuSubItemSelect='tuning';

		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_tuning'));
		/**
		 * Если отправили форму с настройками - сохраняем
		 */
		if (isPost('submit_settings_tuning')) {
			$this->Security_ValidateSendForm();

			$this->oUserCurrent->setSettingsNoticeNewTopic( getRequest('settings_notice_new_topic') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewComment( getRequest('settings_notice_new_comment') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewTalk( getRequest('settings_notice_new_talk') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeReplyComment( getRequest('settings_notice_reply_comment') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewFriend( getRequest('settings_notice_new_friend') ? 1 : 0 );
			$this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
			/**
			 * Запускаем выполнение хуков
			 */
			$this->Hook_Run('settings_tuning_save_before', array('oUser'=>$this->oUserCurrent));
			if ($this->User_Update($this->oUserCurrent)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('settings_tuning_submit_ok'));
				$this->Hook_Run('settings_tuning_save_after', array('oUser'=>$this->oUserCurrent));
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			}
		}
	}
	/**
	 * Показ и обработка формы приглаешний
	 *
	 */
	protected function EventInvite() {
		/**
		 * Только при активном режиме инвайтов
		 */
		if (!Config::Get('general.reg.invite')) {
			return parent::EventNotFound();
		}

		$this->sMenuItemSelect='invite';
		$this->sMenuSubItemSelect='';
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_invite'));
		/**
		 * Если отправили форму
		 */
		if (isPost('submit_invite')) {
			$this->Security_ValidateSendForm();

			$bError=false;
			/**
			 * Есть права на отправку инфайтов?
			 */
			if (!$this->ACL_CanSendInvite($this->oUserCurrent) and !$this->oUserCurrent->isAdministrator()) {
				$this->Message_AddError($this->Lang_Get('settings_invite_available_no'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * Емайл корректен?
			 */
			if (!func_check(getRequest('invite_mail'),'mail')) {
				$this->Message_AddError($this->Lang_Get('settings_invite_mail_error'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * Запускаем выполнение хуков
			 */
			$this->Hook_Run('settings_invate_send_before', array('oUser'=>$this->oUserCurrent));
			/**
			 * Если нет ошибок, то отправляем инвайт
			 */
			if (!$bError) {
				$oInvite=$this->User_GenerateInvite($this->oUserCurrent);
				$this->Notify_SendInvite($this->oUserCurrent,getRequest('invite_mail'),$oInvite);
				$this->Message_AddNoticeSingle($this->Lang_Get('settings_invite_submit_ok'));
				$this->Hook_Run('settings_invate_send_after', array('oUser'=>$this->oUserCurrent));
			}
		}

		$this->Viewer_Assign('iCountInviteAvailable',$this->User_GetCountInviteAvailable($this->oUserCurrent));
		$this->Viewer_Assign('iCountInviteUsed',$this->User_GetCountInviteUsed($this->oUserCurrent->getId()));
	}
	/**
	 * Форма смены пароля, емайла
	 */
	protected function EventAccount() {
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_profile'));
		$this->sMenuSubItemSelect='account';
		/**
		 * Если нажали кнопку "Сохранить"
		 */
		if (isPost('submit_account_edit')) {
			$this->Security_ValidateSendForm();

			$bError=false;
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
			 * Ставим дату последнего изменения
			 */
			$this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
			/**
			 * Запускаем выполнение хуков
			 */
			$this->Hook_Run('settings_account_save_before', array('oUser'=>$this->oUserCurrent,'bError'=>&$bError));
			/**
			 * Сохраняем изменения
			 */
			if (!$bError) {
				if ($this->User_Update($this->oUserCurrent)) {
					$this->Message_AddNoticeSingle($this->Lang_Get('settings_account_submit_ok'));
					$this->Hook_Run('settings_account_save_after', array('oUser'=>$this->oUserCurrent));
				} else {
					$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
				}
			}
		}
	}
	/**
	 * Выводит форму для редактирования профиля и обрабатывает её
	 *
	 */
	protected function EventProfile() {
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_profile'));
		$this->Viewer_Assign('aUserFields',$this->User_getUserFields(''));
		$this->Viewer_Assign('aUserFieldsContact',$this->User_getUserFields(array('contact','social')));
		/**
		 * Загружаем в шаблон JS текстовки
		 */
		$this->Lang_AddLangJs(array(
								  'settings_profile_field_error_max'
							  ));
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
			 * Определяем гео-объект
			 */
			if (getRequest('geo_city')) {
				$oGeoObject=$this->Geo_GetGeoObject('city',getRequest('geo_city'));
			} elseif (getRequest('geo_region')) {
				$oGeoObject=$this->Geo_GetGeoObject('region',getRequest('geo_region'));
			} elseif (getRequest('geo_country')) {
				$oGeoObject=$this->Geo_GetGeoObject('country',getRequest('geo_country'));
			} else {
				$oGeoObject=null;
			}
			/**
			 * Проверяем имя
			 */
			if (func_check(getRequest('profile_name'),'text',2,Config::Get('module.user.name_max'))) {
				$this->oUserCurrent->setProfileName(getRequest('profile_name'));
			} else {
				$this->oUserCurrent->setProfileName(null);
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
			 * Проверяем информацию о себе
			 */
			if (func_check(getRequest('profile_about'),'text',1,3000)) {
				$this->oUserCurrent->setProfileAbout($this->Text_Parser(getRequest('profile_about')));
			} else {
				$this->oUserCurrent->setProfileAbout(null);
			}
			/**
			 * Ставим дату последнего изменения профиля
			 */
			$this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
			/**
			 * Запускаем выполнение хуков
			 */
			$this->Hook_Run('settings_profile_save_before', array('oUser'=>$this->oUserCurrent,'bError'=>&$bError));
			/**
			 * Сохраняем изменения профиля
			 */
			if (!$bError) {
				if ($this->User_Update($this->oUserCurrent)) {
					/**
					 * Создаем связь с гео-объектом
					 */
					if ($oGeoObject) {
						$this->Geo_CreateTarget($oGeoObject,'user',$this->oUserCurrent->getId());
						if ($oCountry=$oGeoObject->getCountry()) {
							$this->oUserCurrent->setProfileCountry($oCountry->getName());
						} else {
							$this->oUserCurrent->setProfileCountry(null);
						}
						if ($oRegion=$oGeoObject->getRegion()) {
							$this->oUserCurrent->setProfileRegion($oRegion->getName());
						} else {
							$this->oUserCurrent->setProfileRegion(null);
						}
						if ($oCity=$oGeoObject->getCity()) {
							$this->oUserCurrent->setProfileCity($oCity->getName());
						} else {
							$this->oUserCurrent->setProfileCity(null);
						}
					} else {
						$this->Geo_DeleteTargetsByTarget('user',$this->oUserCurrent->getId());
						$this->oUserCurrent->setProfileCountry(null);
						$this->oUserCurrent->setProfileRegion(null);
						$this->oUserCurrent->setProfileCity(null);
					}
					$this->User_Update($this->oUserCurrent);

					/**
					 * Обрабатываем дополнительные поля, type = ''
					 */
					$aFields = $this->User_getUserFields('');
					$aData = array();
					foreach ($aFields as $iId => $aField) {
						if (isset($_REQUEST['profile_user_field_'.$iId])) {
							$aData[$iId] = getRequest('profile_user_field_'.$iId);
						}
					}
					$this->User_setUserFieldsValues($this->oUserCurrent->getId(), $aData);
					/**
					 * Динамические поля контактов, type = array('contact','social')
					 */
					$aType=array('contact','social');
					$aFields = $this->User_getUserFields($aType);
					/**
					 * Удаляем все поля с этим типом
					 */
					$this->User_DeleteUserFieldValues($this->oUserCurrent->getId(),$aType);
					$aFieldsContactType=getRequest('profile_user_field_type');
					$aFieldsContactValue=getRequest('profile_user_field_value');
					if (is_array($aFieldsContactType)) {
						foreach($aFieldsContactType as $k=>$v) {
							if (isset($aFields[$v]) and isset($aFieldsContactValue[$k])) {
								$this->User_setUserFieldsValues($this->oUserCurrent->getId(), array($v=>$aFieldsContactValue[$k]), Config::Get('module.user.userfield_max_identical'));
							}
						}
					}
					$this->Message_AddNoticeSingle($this->Lang_Get('settings_profile_submit_ok'));
					$this->Hook_Run('settings_profile_save_after', array('oUser'=>$this->oUserCurrent));
				} else {
					$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
				}
			}
		}
		/**
		 * Загружаем гео-объект привязки
		 */
		$oGeoTarget=$this->Geo_GetTargetByTarget('user',$this->oUserCurrent->getId());
		$this->Viewer_Assign('oGeoTarget',$oGeoTarget);
		/**
		 * Загружаем в шаблон список стран, регионов, городов
		 */
		$aCountries=$this->Geo_GetCountries(array(),array('sort'=>'asc'),1,300);
		$this->Viewer_Assign('aGeoCountries',$aCountries['collection']);
		if ($oGeoTarget) {
			if ($oGeoTarget->getCountryId()) {
				$aRegions=$this->Geo_GetRegions(array('country_id'=>$oGeoTarget->getCountryId()),array('sort'=>'asc'),1,500);
				$this->Viewer_Assign('aGeoRegions',$aRegions['collection']);
			}
			if ($oGeoTarget->getRegionId()) {
				$aCities=$this->Geo_GetCities(array('region_id'=>$oGeoTarget->getRegionId()),array('sort'=>'asc'),1,500);
				$this->Viewer_Assign('aGeoCities',$aCities['collection']);
			}
		}

	}
	/**
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {
		$iCountTopicFavourite=$this->Topic_GetCountTopicsFavouriteByUserId($this->oUserCurrent->getId());
		$iCountTopicUser=$this->Topic_GetCountTopicsPersonalByUser($this->oUserCurrent->getId(),1);
		$iCountCommentUser=$this->Comment_GetCountCommentsByUserId($this->oUserCurrent->getId(),'topic');
		$iCountCommentFavourite=$this->Comment_GetCountCommentsFavouriteByUserId($this->oUserCurrent->getId());
		$iCountNoteUser=$this->User_GetCountUserNotesByUserId($this->oUserCurrent->getId());

		$this->Viewer_Assign('oUserProfile',$this->oUserCurrent);
		$this->Viewer_Assign('iCountWallUser',$this->Wall_GetCountWall(array('wall_user_id'=>$this->oUserCurrent->getId(),'pid'=>null)));
		/**
		 * Общее число публикация и избранного
		 */
		$this->Viewer_Assign('iCountCreated',$iCountNoteUser+$iCountTopicUser+$iCountCommentUser);
		$this->Viewer_Assign('iCountFavourite',$iCountCommentFavourite+$iCountTopicFavourite);
		$this->Viewer_Assign('iCountFriendsUser',$this->User_GetCountUsersFriend($this->oUserCurrent->getId()));

		/**
		 * Загружаем в шаблон необходимые переменные
		 */
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);

		$this->Hook_Run('action_shutdown_settings');
	}
}
?>