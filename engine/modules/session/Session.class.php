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
class LsSession extends Module {
	protected $sId=null;
	protected $aData=array();	
	
	/**
	 * Использовать или нет стандартный механизм сессий
	 *
	 * @var bool
	 */
	protected $bUseStandartSession=SYS_SESSION_STANDART;
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {
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
			session_name(SYS_SESSION_NAME);			
			session_set_cookie_params(SYS_SESSION_TIMEOUT,SYS_SESSION_PATH,SYS_SESSION_HOST);
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
		if (isset($_COOKIE[SYS_SESSION_NAME])) {
			$this->sId=$_COOKIE[SYS_SESSION_NAME];
		} else {
			/**
			 * Иначе создаём новый и записываем его в куку
			 */
			$this->sId=$this->GenerateId();
			setcookie(SYS_SESSION_NAME,$this->sId,time()+SYS_SESSION_TIMEOUT,SYS_SESSION_PATH,SYS_SESSION_HOST);
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
		$this->Cache_Set($this->aData,$this->sId,array(),SYS_SESSION_TIMEOUT);
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
			setcookie(SYS_SESSION_NAME,'',1,SYS_SESSION_PATH,SYS_SESSION_HOST);
		}
	}
}
?>