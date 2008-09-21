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
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('index','EventLogin');	
		$this->AddEvent('exit','EventExit');	
	}
	/**
	 * Обрабатываем процесс залогинивания
	 *
	 */
	protected function EventLogin() {	
		/**
		 * Если нажали кнопку "Войти"
		 */
		if (isset($_REQUEST['submit_login'])) {
			/**
			 * Проверяем есть ли такой юзер по логину
			 */
			if ($oUser=$this->User_GetUserByLogin(getRequest('login'))) {	
				/**
				 * Сверяем хеши паролей и проверяем активен ли юзер
				 */
				if ($oUser->getPassword()==func_encrypt(getRequest('password')) and $oUser->getActivate()) {
					/**
					 * Авторизуем
					 */
					$this->User_Authorization($oUser);	
					/**
					 * Перенаправляем на страницу с которой произошла авторизация
					 */
					$sBackUrl=$_SERVER['HTTP_REFERER'];					
					if (strpos($sBackUrl,DIR_WEB_ROOT.'/login')===false) {
						func_header_location($sBackUrl);
					} else {
						func_header_location(DIR_WEB_ROOT.'/');
					}
				}
			}			
			$this->Viewer_Assign('bLoginError',true);
		}
		$this->Viewer_AddHtmlTitle('Вход на сайт');
	}
	/**
	 * Обрабатываем процесс разлогинивания
	 *
	 */
	protected function EventExit() {
		$this->User_Logout();
		$this->Viewer_Assign('bRefreshToHome',true);
	}
}
?>