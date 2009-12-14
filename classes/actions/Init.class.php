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
 * Класс инициализации экшенов.
 * Он вызывается всегда перед запуском любого экшена. Может выполнять какие то инициализирующие действия, а так же может помочь при введении инвайтов,
 * т.е. перенапрявлять всех неавторизованных юзеров на страницу регистрации по приглашению
 *
 */
class Init {
	/**
	 * 
	 */
	protected $oEngine=null;
	/**
	 * Текущий юзер
	 *
	 * @var unknown_type
	 */
	protected $oUserCurrent=null;
	/**
	 * Конструктор
	 *
	 */
	public function __construct($oEngine) {		
		$this->oEngine=$oEngine;
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}
	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 *
	 * @param string $sName
	 * @param array $aArgs
	 * @return unknown
	 */
	public function __call($sName,$aArgs) {
		return $this->oEngine->_CallModule($sName,$aArgs);
	}
	
	/**
	 * Логика инициализации
	 *
	 */
	public function InitAction() {	
		/**
		 * Проверяем наличие директории install
		 */
		if(is_dir(rtrim(Config::Get('path.root.server'),'/').'/install')){
			$this->Message_AddErrorSingle($this->Lang_Get('install_directory_exists'));
			Router::Action('error');
		}
		
		if (!$this->oUserCurrent and Config::Get('general.close') and Router::GetAction()!='registration' and Router::GetAction()!='login') {			
			Router::Action('login');
		}
		$this->Hook_Run('init_action');
	}
}
?>