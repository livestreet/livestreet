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
 * Модуль поддержки языковых файлов
 *
 */
class LsLang extends Module {		
	
	protected $sCurrentLang;
	protected $sLangPath;
	protected $aLangMsg=array();
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {	
		$this->sCurrentLang = Config::Get('lang.current');
		$this->sLangPath = Config::Get('lang.path');
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
		if (Config::Get('sys.cache.type')=='memory') {			
			if (false === ($this->aLangMsg = $this->Cache_Get("lang_{$this->sCurrentLang}"))) {
				$this->LoadLangFiles();
				$this->Cache_Set($this->aLangMsg, "lang_{$this->sCurrentLang}", array(), 60*60);
			}			
		} else {
			$this->LoadLangFiles();
		}	
		/**
		 * Загружаем в шаблон
		 */
		$this->Viewer_Assign('aLang',$this->aLangMsg);
	}
	/**
	 * Загружает текстовки из языковых файлов
	 *
	 */
	protected function LoadLangFiles() {
		$this->aLangMsg=include($this->sLangPath.'/'.$this->sCurrentLang.'.php');
		/**
		 * Ищет конфиги языковых файлов и объединяет их с текущим
		 */
		$sDirConfig=$this->sLangPath.'/modules/';
		if ($hDirConfig = opendir($sDirConfig)) {
			while (false !== ($sDirModule = readdir($hDirConfig))) {
				if ($sDirModule !='.' and $sDirModule !='..' and is_dir($sDirConfig.$sDirModule)) {
					$sFileConfig=$sDirConfig.$sDirModule.'/'.$this->sCurrentLang.'.php';
					if (file_exists($sFileConfig)) {
						$aLangModule=include($sFileConfig);						
						$this->aLangMsg=array_merge_recursive($this->aLangMsg,$aLangModule);
					}					
				}
			}
			closedir($hDirConfig);
		}
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
	 * Получить список текстовок
	 *
	 * @return unknown
	 */
	public function GetLangMsg() {
		return $this->aLangMsg;
	}
	/**
	 * Получает текстовку по её имени
	 *
	 * @param  string $sName
	 * @param  array  $aReplace
	 * @return string
	 */
	public function Get($sName,$aReplace=array()) {
		if (isset($this->aLangMsg[$sName])) {
			$sTranslate=$this->aLangMsg[$sName];

			if(is_array($aReplace)&&count($aReplace)) { 
				foreach ($aReplace as $sFrom => $sTo) {
					$aReplacePairs["%%{$sFrom}%%"]=$sTo;
				}
				$sTranslate=strtr($sTranslate,$aReplacePairs);				
			}

			if(Config::Get('module.lang.delete_undefined')) {
				$sTranslate=preg_replace("/\%\%[\S]+\%\%/U",'',$sTranslate);
			}
			return $sTranslate;
		}
		return 'NOT_FOUND_LANG_TEXT';
	}
	
}
?>