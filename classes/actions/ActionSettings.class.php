<?
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
			$this->Message_AddErrorSingle('Настройки профиля для вас не доступны','Нет доступа');
			return Router::Action('error'); 
		}
		/**
		 * Получаем текущего юзера
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		$this->SetDefaultEvent('profile');	
		$this->Viewer_AddHtmlTitle('Настройки профиля');	
	}
	
	protected function RegisterEvent() {		
		$this->AddEvent('profile','EventProfile');						
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Выводит форму для редактирования профиля и обрабатывает её
	 *
	 */
	protected function EventProfile() {
		/**
		 * Если нажали кнопку "Сохранить"
		 */
		if (isset($_REQUEST['submit_profile_edit'])) {
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
			 */
			if (func_check(getRequest('profile_region'),'text',1,30)) {
				$this->oUserCurrent->setProfileRegion(getRequest('profile_region'));
			} else {
				$this->oUserCurrent->setProfileRegion(null);
			}
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
							$this->Message_AddError('Неверный текущий пароль','Ошибка');
						}
					} else {
						$bError=true;
						$this->Message_AddError('Пароли не совпадают','Ошибка');
					}
				} else {
					$bError=true;
					$this->Message_AddError('Неверный пароль, допустим от 5 символов','Ошибка');
				}
			}		
			/**
			 * Загрузка аватара, делаем ресайзы
			 */			
			if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {				
				$sFileTmp=$_FILES['avatar']['tmp_name'];
				if ($sFileAvatar=func_img_resize($sFileTmp,DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId(),'avatar_100x100',3000,3000,100,100)) {
					func_img_resize($sFileTmp,DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId(),'avatar_64x64',3000,3000,64,64);
					func_img_resize($sFileTmp,DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId(),'avatar_48x48',3000,3000,48,48);
					func_img_resize($sFileTmp,DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId(),'avatar_24x24',3000,3000,24,24);
					func_img_resize($sFileTmp,DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId(),'avatar',3000,3000);
					$this->oUserCurrent->setProfileAvatar(1);
					$aFileInfo=pathinfo($sFileAvatar);
					$this->oUserCurrent->setProfileAvatarType($aFileInfo['extension']);
				} else {
					$bError=true;
					$this->Message_AddError('Не удалось загрузить аватар','Ошибка');
				}
			}
			/**
			 * Удалить аватара
			 */
			if (isset($_REQUEST['avatar_delete'])) {
				$this->oUserCurrent->setProfileAvatar(0);
				@unlink(DIR_SERVER_ROOT.DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId().'/avatar_100x100.'.$this->oUserCurrent->getProfileAvatarType());
				@unlink(DIR_SERVER_ROOT.DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId().'/avatar_64x64.'.$this->oUserCurrent->getProfileAvatarType());
				@unlink(DIR_SERVER_ROOT.DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId().'/avatar_48x48.'.$this->oUserCurrent->getProfileAvatarType());
				@unlink(DIR_SERVER_ROOT.DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId().'/avatar_24x24.'.$this->oUserCurrent->getProfileAvatarType());
				@unlink(DIR_SERVER_ROOT.DIR_UPLOADS_IMAGES.'/'.$this->oUserCurrent->getId().'/avatar.'.$this->oUserCurrent->getProfileAvatarType());
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
					$this->Message_AddNoticeSingle('Профиль успешно сохранён','Ура');
				} else {
					$this->Message_AddErrorSingle('Возникли технические неполадки, пожалуйста повторите позже.','Внутреняя ошибка');
				}
			}
		}
	}	
}
?>