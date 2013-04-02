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
 * Выступает в качестве врапера для стандартного механизма сессий
 *
 * @package engine.modules
 * @since 1.0
 */
class ModuleSession extends Module {
	/**
	 * ID  сессии
	 *
	 * @var null|string
	 */
	protected $sId=null;
	/**
	 * Данные сессии
	 *
	 * @var array
	 */
	protected $aData=array();
	/**
	 * Список user-agent'ов для флеш плеера
	 * Используется для передачи ID сессии при обращениии к сайту через flash, например, загрузка файлов через flash
	 *
	 * @var array
	 */
	protected $aFlashUserAgent=array(
		'Shockwave Flash'
	);
	/**
	 * Использовать или нет стандартный механизм сессий
	 * ВНИМАНИЕ! Не рекомендуется ставить false - т.к. этот режим до конца не протестирован
	 *
	 * @var bool
	 */
	protected $bUseStandartSession=true;

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
				/**
				 * Попытка подменить идентификатор имени сессии через куку
				 */
				if (isset($_COOKIE[Config::Get ('sys.session.name')]) and !is_string($_COOKIE[Config::Get ('sys.session.name')])) {
					die("Hacking attemp! Please check cookie PHP session name.");
				}
				/**
				 * Попытка подменить идентификатор имени сессии в реквесте
				 */
				$aRequest=array_merge($_GET,$_POST); // Исключаем попадаение $_COOKIE в реквест
				if (@ini_get ('session.use_only_cookies') === "0" and isset($aRequest[Config::Get ('sys.session.name')]) and !is_string($aRequest[Config::Get ('sys.session.name')])) {
					die("Hacking attemp! Please check cookie PHP session name.");
				}
				/**
				 * Даем возможность флешу задавать id сессии
				 */
				$sUserAgent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
				if ($sUserAgent and (in_array($sUserAgent,$this->aFlashUserAgent) or strpos($sUserAgent,"Adobe Flash Player")===0) and is_string(getRequest('SSID')) and preg_match("/^[\w\d]{5,40}$/",getRequest('SSID'))) {
					session_id(getRequest('SSID'));
				} else {
					session_regenerate_id();
				}
				session_start();
			}
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
	 * @return string
	 */
	protected function GenerateId() {
		return md5(func_generator().time());
	}
	/**
	 * Читает данные сессии в aData
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
	 * @param string $sName	Имя параметра
	 * @return mixed|null
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
	 * @param string $sName	Имя параметра
	 * @param mixed $data	Данные
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
	 * @param string $sName	Имя параметра
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