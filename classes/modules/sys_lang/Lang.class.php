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
 * Модуль поддержки языковых файлов
 *
 */
class Lang extends Module {		
	
	protected $sCurrentLang=LANG_CURRENT;
	protected $sLangPath=LANG_PATH;
	protected $aLangMsg=array();
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {	
		$this->InitLang();					
	}
	/**
	 * Инициализирует языковой файл
	 *
	 */
	protected function InitLang() {		
		/**
		 * Если используется кеширование через memcaсhed, то сохраняем данные языкового файла в кеш
		 */
		if (SYS_CACHE_TYPE=='memory') {			
			if (false === ($this->aLangMsg = $this->Cache_Get("lang_{$this->sCurrentLang}"))) {
				$this->aLangMsg=include($this->sLangPath.'/'.$this->sCurrentLang.'.php');
				$this->Cache_Set($this->aLangMsg, "lang_{$this->sCurrentLang}", array(), 60*60);
			}			
		} else {
			$this->aLangMsg=include($this->sLangPath.'/'.$this->sCurrentLang.'.php');
		}	
		/**
		 * Загружаем в шаблон
		 */
		$this->Viewer_Assign('aLang',$this->aLangMsg);
	}
	/**
	 * Установить текущий язык
	 *
	 * @param unknown_type $sLang
	 */
	public function SetLang($sLang) {
		$this->sCurrentLang=$sLang;
		$this->InitLang();
	}
	/**
	 * Получить текущий язык
	 *
	 * @return unknown
	 */
	public function GetLang() {
		return $this->sCurrentLang;
	}
	/**
	 * Получает текстовку по её имени
	 *
	 * @param unknown_type $sName
	 */
	public function Get($sName) {
		if (isset($this->aLangMsg[$sName])) {
			return $this->aLangMsg[$sName];
		}
		return 'NOT_FOUND_LANG_TEXT';
	}
	
}
?>