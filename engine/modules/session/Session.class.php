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
 * Модуль для работы с сессиями
 * Заменяет стандартный механизм сессий(session)
 *
 */
class ModuleSession extends Module {
	protected $sId=null;
	protected $aData=array();	
	
	/**
	 * Использовать или нет стандартный механизм сессий
	 *
	 * @var bool
	 */
	protected $bUseStandartSession;
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {
		$this->bUseStandartSession = Config::Get('sys.session.standart');
		/**
		 * Стартуем сессию
		 */
		$this->Start();
	}

	/**
	 * Старт сессии
	 *
	 */
	protected function Start() {
		if ($this->bUseStandartSession) {
			session_name(Config::Get('sys.session.name'));			
			session_set_cookie_params(
				Config::Get('sys.session.timeout'),
				Config::Get('sys.session.path'),
				Config::Get('sys.session.host')
			);
			if(!session_id()) {
				session_regenerate_id();
			}
			session_start();			
		} else {
			$this->SetId();
			$this->ReadData();
		}
	}
		
	
	/**
	 * Устанавливает уникальный идентификатор сессии
	 *
	 */
	protected function SetId() {
		/**
		 * Если идентификатор есть в куках то берем его
		 */
		if (isset($_COOKIE[Config::Get('sys.session.name')])) {
			$this->sId=$_COOKIE[Config::Get('sys.session.name')];
		} else {
			/**
			 * Иначе создаём новый и записываем его в куку
			 */
			$this->sId=$this->GenerateId();
			setcookie(
				Config::Get('sys.session.name'),
				$this->sId,time()+Config::Get('sys.session.timeout'),
				Config::Get('sys.session.path'),
				Config::Get('sys.session.host')
			);
		}
	}
	
	/**
	 * Получает идентификатор текущей сессии
	 *
	 */	
	public function GetId() {
		if ($this->bUseStandartSession) {
			return session_id();
		} else {
			return $this->sId;
		}
	}
	
	/**
	 * Гинерирует уникальный идентификатор
	 *
	 * @return unknown
	 */
	protected function GenerateId() {
		return md5(func_generator().time());
	}
	
	/**
	 * Читает данные сессии
	 *
	 */
	protected function ReadData() {
		$this->aData=$this->Cache_Get($this->sId);
	}
	
	/**
	 * Сохраняет данные сессии
	 *
	 */
	protected function Save() {
		$this->Cache_Set($this->aData,$this->sId,array(),Config::Get('sys.session.timeout'));
	}
	
	/**
	 * Получает значение из сессии
	 *
	 * @param string $sName
	 * @return unknown
	 */
	public function Get($sName) {
		if ($this->bUseStandartSession) {
			return isset($_SESSION[$sName]) ? $_SESSION[$sName] : null;
		} else {
			return isset($this->aData[$sName]) ? $this->aData[$sName] : null;
		}
	}
	
	/**
	 * Записывает значение в сессию
	 *
	 * @param string $sName
	 * @param unknown_type $data
	 */
	public function Set($sName,$data) {
		if ($this->bUseStandartSession) {
			$_SESSION[$sName]=$data;
		} else {
			$this->aData[$sName]=$data;
			$this->Save();
		}		
	}
	
	/**
	 * Удаляет значение из сессии
	 *
	 * @param string $sName
	 */
	public function Drop($sName) {
		if ($this->bUseStandartSession) {
			unset($_SESSION[$sName]);
		} else {
			unset($this->aData[$sName]);
			$this->Save();
		}
	}
	
	/**
	 * Получает разом все данные сессии
	 *
	 * @return array
	 */
	public function GetData() {
		if ($this->bUseStandartSession) {
			return $_SESSION;
		} else {
			return $this->aData;
		}
	}
	
	/**
	 * Завершает сессию, дропая все данные
	 *
	 */
	public function DropSession() {
		if ($this->bUseStandartSession) {
			unset($_SESSION);
			session_destroy();
		} else {
			unset($this->sId);
			unset($this->aData);
			setcookie(
				Config::Get('sys.session.name'),
				'',1,
				Config::Get('sys.session.path'),
				Config::Get('sys.session.host')
			);
		}
	}
}
?>