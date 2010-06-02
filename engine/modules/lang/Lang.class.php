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
class ModuleLang extends Module {		
	/**
	 * Текущий язык ресурса
	 *
	 * @var string
	 */
	protected $sCurrentLang;
	/**
	 * Язык ресурса, используемый по умолчанию
	 *
	 * @var string
	 */	
	protected $sDefaultLang;
	/**
	 * Путь к языковым файлам
	 *
	 * @var string
	 */	
	protected $sLangPath;
	/**
	 * @var array
	 */
	protected $aLangMsg=array();
	
	/**
	 * Инициализация модуля
	 *
	 * @return null
	 */
	public function Init() {
		$this->Hook_Run('lang_init_start');
		
		$this->sCurrentLang = Config::Get('lang.current');
		$this->sDefaultLang = Config::Get('lang.default');
		$this->sLangPath = Config::Get('lang.path');
		$this->InitLang();
	}
	/**
	 * Инициализирует языковой файл
	 *
	 * @return null
	 */
	protected function InitLang() {		
		/**
		 * Если используется кеширование через memcaсhed, то сохраняем данные языкового файла в кеш
		 */
		if (Config::Get('sys.cache.type')=='memory') {			
			if (false === ($this->aLangMsg = $this->Cache_Get("lang_{$this->sCurrentLang}"))) {
				$this->aLangMsg=array();
				$this->LoadLangFiles($this->sDefaultLang);			
				if($this->sCurrentLang!=$this->sDefaultLang) $this->LoadLangFiles($this->sCurrentLang);
				$this->Cache_Set($this->aLangMsg, "lang_{$this->sCurrentLang}", array(), 60*60);
			}
		} else {
			$this->LoadLangFiles($this->sDefaultLang);
			if($this->sCurrentLang!=$this->sDefaultLang) $this->LoadLangFiles($this->sCurrentLang);
		}

		/**
		 * Загружаем в шаблон
		 */
		$this->Viewer_Assign('aLang',$this->aLangMsg);		
	}
	/**
	 * Загружает текстовки из языковых файлов
	 *
	 * @return null
	 */
	protected function LoadLangFiles($sLangName) {
		$sLangFilePath = $this->sLangPath.'/'.$sLangName.'.php'; 
		if(file_exists($sLangFilePath)) {
			$this->AddMessages(include($sLangFilePath));							
		}
		/**
		 * Ищет языковые файлы модулей и объединяет их с текущим
		 */
		$sDirConfig=$this->sLangPath.'/modules/';
		if ($hDirConfig = opendir($sDirConfig)) {
			while (false !== ($sDirModule = readdir($hDirConfig))) {
				if ($sDirModule !='.' and $sDirModule !='..' and is_dir($sDirConfig.$sDirModule)) {
					$sFileConfig=$sDirConfig.$sDirModule.'/'.$sLangName.'.php';
					if (file_exists($sFileConfig)) {
						$this->AddMessages(include($sFileConfig));
					}					
				}
			}
			closedir($hDirConfig);
		}
		
		/**
		 * Ищет языковые файлы актвиированных плагинов
		 */
		if($aPluginList = @file(Config::Get('path.root.server').'/plugins/plugins.dat')) {
			$aPluginList=array_map('trim',$aPluginList);			
			$sDir=Config::Get('path.root.server').'/plugins/';
			
			foreach ($aPluginList as $sPluginName) {
				$aFiles=glob($sDir.$sPluginName.'/templates/language/'.$sLangName.'.php');
				if($aFiles and count($aFiles)) {
						foreach ($aFiles as $sFile) {
							if (file_exists($sFile)) {
								$this->AddMessages(include($sFile));
							}
					}
				}
			}

		}
		
		/**
		 * Ищет языковой файл текущего шаблона
		 */
		$this->LoadLangFileTemplate($sLangName);
	}
	
	/**
	 * Загружает языковой файл текущего шаблона
	 *
	 * @param unknown_type $sLangName
	 */
	public function LoadLangFileTemplate($sLangName) {
		$sFile=Config::Get('path.smarty.template').'/settings/language/'.$sLangName.'.php';
		if (file_exists($sFile)) {
			$this->AddMessages(include($sFile));
		}
	}
	/**
	 * Установить текущий язык
	 *
	 * @param string $sLang
	 */
	public function SetLang($sLang) {
		$this->sCurrentLang=$sLang;
		$this->InitLang();
	}
	/**
	 * Получить текущий язык
	 *
	 * @return string
	 */
	public function GetLang() {
		return $this->sCurrentLang;
	}
	/**
	 * Получить дефолтный язык
	 * 
	 * @return string
	 */
	public function GetLangDefault() {
		return $this->sDefaultLang;
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

			if(is_array($aReplace)&&count($aReplace)&&is_string($sTranslate)) { 
				foreach ($aReplace as $sFrom => $sTo) {
					$aReplacePairs["%%{$sFrom}%%"]=$sTo;
				}
				$sTranslate=strtr($sTranslate,$aReplacePairs);
			}

			if(Config::Get('module.lang.delete_undefined') and is_string($sTranslate)) {
				$sTranslate=preg_replace("/\%\%[\S]+\%\%/U",'',$sTranslate);
			}
			return $sTranslate;
		}
		return 'NOT_FOUND_LANG_TEXT';
	}
	
	/**
     * Добавить к текстовкам массив сообщений
     *
     * @param array $aMessages     
     */
	public function AddMessages($aMessages) {
		if (is_array($aMessages)) {
			if (count($this->aLangMsg)==0) {
				$this->aLangMsg = $aMessages;
			} else {
				$this->aLangMsg = array_merge($this->aLangMsg, $aMessages);
			}
		}
	}

	/**
     * Добавить к текстовкам отдельное сообщение
     *
     * @param string $sKey
     * @param string $sMessage     
     */
	public function AddMessage($sKey, $sMessage) {
		$this->aLangMsg[$sKey] = $sMessage;
	}
	
	/**
	 * Завершаем работу модуля
	 *
	 */
	public function Shutdown() {
	}
}
?>