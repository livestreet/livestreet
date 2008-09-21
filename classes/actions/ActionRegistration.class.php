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
			$this->Message_AddErrorSingle('Вы уже зарегистрированы у нас и даже авторизованы!','Упс!');
			return Router::Action('error'); 
		}
		$this->SetDefaultEvent('index');
		$this->Viewer_AddHtmlTitle('Регистрация на сайте');
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('index','EventIndex');	
		$this->AddEvent('ok','EventOk');	
		$this->AddEvent('confirm','EventConfirm');
		$this->AddEvent('activate','EventActivate');
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
		if (isset($_REQUEST['submit_register'])) {
			//Проверяем  входные данные
			$bError=false;
			/**
			 * Проверка логина
			 */
			if (!func_check(getRequest('login'),'login',3,30)) {
				$this->Message_AddError('Неверный логин, допустим от 3 до 30 символов','Ошибка');
				$bError=true;
			}
			/**
			 * Проверка мыла
			 */
			if (!func_check(getRequest('mail'),'mail')) {
				$this->Message_AddError('Неверный формат e-mail','Ошибка');
				$bError=true;
			}
			/**
			 * Проверка пароля
			 */
			if (!func_check(getRequest('password'),'password',5)) {
				$this->Message_AddError('Неверный пароль, допустим от 5 символов','Ошибка');
				$bError=true;
			} elseif (getRequest('password')!=getRequest('password_confirm')) {
				$this->Message_AddError('Пароли не совпадают','Ошибка');
				$bError=true;
			}
			/**
			 * Проверка капчи(циферки с картинки)
			 */
			if ($_SESSION['captcha_keystring']!=strtolower(getRequest('captcha'))) {
				$this->Message_AddError('Неверный код','Ошибка');
				$bError=true;
			}
			/**
			 * А не занят ли логин?
			 */
			if ($this->User_GetUserByLogin(getRequest('login'))) {
				$this->Message_AddError('Этот логин уже занять','Ошибка');
				$bError=true;
			}
			/**
			 * А не занято ли мыло?
			 */
			if ($this->User_GetUserByMail(getRequest('mail'))) {
				$this->Message_AddError('Этот емайл уже занять','Ошибка');
				$bError=true;
			}
			/**
			 * Если всё то пробуем зарегить
			 */
			if (!$bError) {
				/**
				 * Создаем юзера
				 */
				$oUser=new UserEntity_User();
				$oUser->setLogin(getRequest('login'));
				$oUser->setMail(getRequest('mail'));
				$oUser->setPassword(func_encrypt(getRequest('password')));
				$oUser->setDateRegister(date("Y-m-d H:i:s"));
				$oUser->setIpRegister(func_getIp());
				/**
				 * Если используется активация, то генерим код активации
				 */
				if (USER_USE_ACTIVATION) {
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
					 * Создаем персональный блог
					 */
					$this->Blog_CreatePersonalBlog($oUser);		
					/**
					 * Если стоит регистрация с активацией то проводим её
					 */
					if (USER_USE_ACTIVATION) {
						/**
						 * Отправляем на мыло письмо о подтверждении регистрации
						 * По хорошему тескт письма нужно вынести в отдельный шаблон
						 */
						$this->Mail_SetAdress($oUser->getMail());
						$this->Mail_SetSubject('Регистрация');
						$this->Mail_SetBody('
							Вы зарегистрировались на сайте <a href="'.DIR_WEB_ROOT.'">'.SITE_NAME.'</a><br>
							Ваши регистрационные данные:<br>
							&nbsp;&nbsp;&nbsp;логин: <b>'.$oUser->getLogin().'</b><br>
							&nbsp;&nbsp;&nbsp;пароль: <b>'.getRequest('password').'</b><br>
							<br>
							Для завершения регистрации вам необходимо активировать аккаунт пройдя по ссылке: 
							<a href="'.DIR_WEB_ROOT.'/registration/activate/'.$oUser->getActivateKey().'/">'.DIR_WEB_ROOT.'/registration/activate/'.$oUser->getActivateKey().'/</a>
							<br>
							<br>
							С уважением, администрация сайта <a href="'.DIR_WEB_ROOT.'">'.SITE_NAME.'</a>
						');
						$this->Mail_setHTML();
						$this->Mail_Send();
						func_header_location(DIR_WEB_ROOT.'/registration/confirm/');						
					} else {
						$this->Mail_SetAdress($oUser->getMail());
						$this->Mail_SetSubject('Регистрация');
						$this->Mail_SetBody('
							Вы зарегистрировались на сайте <a href="'.DIR_WEB_ROOT.'">'.SITE_NAME.'</a><br>
							Ваши регистрационные данные:<br>
							&nbsp;&nbsp;&nbsp;логин: <b>'.$oUser->getLogin().'</b><br>
							&nbsp;&nbsp;&nbsp;пароль: <b>'.getRequest('password').'</b><br>							
							<br>
							С уважением, администрация сайта <a href="'.DIR_WEB_ROOT.'">'.SITE_NAME.'</a>
						');
						$this->Mail_setHTML();
						$this->Mail_Send();
					}
					func_header_location(DIR_WEB_ROOT.'/registration/ok/');			
				} else {
					$this->Message_AddErrorSingle('Возникли технические неполадки при регистрации, пожалуйста повторите регистрацию позже.','Внутреняя ошибка');
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
		 * Если что то не то
		 */
		if ($bError) {
			$this->Message_AddErrorSingle('Неверный код активации!','Ошибка');
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
			return;
		} else {
			$this->Message_AddErrorSingle('Возникли технические неполадки при активации, пожалуйста повторите активацию позже.','Внутреняя ошибка');
			return Router::Action('error');
		}
	}
	
	/**
	 * Просто выводит шаблон
	 *
	 */
	protected function EventOk() {											
	}
	/**
	 * Просто выводит шаблон
	 *
	 */
	protected function EventConfirm() {											
	}
}
?>