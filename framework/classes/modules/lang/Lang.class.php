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
 * @package engine.modules
 * @since 1.0
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
	 * Список языковых текстовок
	 *
	 * @var array
	 */
	protected $aLangMsg=array();
	/**
	 * Список текстовок для JS
	 *
	 * @var array
	 */
	protected $aLangMsgJs=array();

	/**
	 * Инициализация модуля
	 *
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
	 */
	protected function InitLang() {
		/**
		 * Если используется кеширование через memcaсhed, то сохраняем данные языкового файла в кеш
		 */
		if (Config::Get('sys.cache.type')=='memory') {
			if (false === ($this->aLangMsg = $this->Cache_Get("lang_{$this->sCurrentLang}_".Config::Get('view.skin')))) {
				$this->aLangMsg=array();
				$this->LoadLangFiles($this->sDefaultLang);
				if($this->sCurrentLang!=$this->sDefaultLang) $this->LoadLangFiles($this->sCurrentLang);
				$this->Cache_Set($this->aLangMsg, "lang_{$this->sCurrentLang}_".Config::Get('view.skin'), array(), 60*60);
			}
		} else {
			$this->LoadLangFiles($this->sDefaultLang);
			if($this->sCurrentLang!=$this->sDefaultLang) $this->LoadLangFiles($this->sCurrentLang);
		}

		$this->LoadLangJs();
		/**
		 * Загружаем в шаблон
		 */
		$this->Viewer_Assign('aLang',$this->aLangMsg);
	}
	/**
	 * Загружает из конфига текстовки для JS
	 *
	 */
	protected function LoadLangJs() {
		$aMsg=Config::Get('lang.load_to_js');
		if (is_array($aMsg) and count($aMsg)) {
			$this->aLangMsgJs=$aMsg;
		}
	}
	/**
	 * Прогружает в шаблон текстовки в виде JS
	 *
	 */
	protected function AssignToJs() {
		$aLangMsg=array();
		foreach ($this->aLangMsgJs as $sName) {
			$aLangMsg[$sName]=$this->Get($sName,array(),false);
		}
		$this->Viewer_Assign('aLangJs',$aLangMsg);
	}
	/**
	 * Добавляет текстовку к JS
	 *
	 * @param array $aKeys	Список текстовок
	 */
	public function AddLangJs($aKeys) {
		if (!is_array($aKeys)) {
			$aKeys=array($aKeys);
		}
		$this->aLangMsgJs=array_merge($this->aLangMsgJs,$aKeys);
	}
	/**
	 * Загружает текстовки из языковых файлов
	 *
	 * @param $sLangName	Язык для загрузки
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
						$this->AddMessages(include($sFileConfig), array('category' =>'module', 'name' =>$sDirModule));
					}
				}
			}
			closedir($hDirConfig);
		}
		/**
		 * Ищет языковые файлы актвиированных плагинов
		 */
		if($aPluginList = Engine::getInstance()->GetPlugins()) {
			$aPluginList=array_keys($aPluginList);
			$sDir=Config::Get('path.application.plugins.server').'/';

			foreach ($aPluginList as $sPluginName) {
				$aFiles=glob($sDir.$sPluginName.'/templates/'.Config::Get('lang.dir').'/'.$sLangName.'.php');
				if($aFiles and count($aFiles)) {
					foreach ($aFiles as $sFile) {
						if (file_exists($sFile)) {
							$this->AddMessages(include($sFile), array('category' =>'plugin', 'name' =>$sPluginName));
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
	 * @param string $sLangName	Язык для загрузки
	 */
	public function LoadLangFileTemplate($sLangName) {
		$sFile=Config::Get('path.smarty.template').'/settings/'.Config::Get('lang.dir').'/'.$sLangName.'.php';
		if (file_exists($sFile)) {
			$this->AddMessages(include($sFile));
		}
	}
	/**
	 * Установить текущий язык
	 *
	 * @param string $sLang	Название языка
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
	 * @return array
	 */
	public function GetLangMsg() {
		return $this->aLangMsg;
	}
	/**
	 * Получает текстовку по её имени
	 *
	 * @param  string $sName	Имя текстовки
	 * @param  array  $aReplace	Список параметром для замены в текстовке
	 * @param  bool  $bDelete	Удалять или нет параметры, которые не были заменены
	 * @return string
	 */
	public function Get($sName,$aReplace=array(),$bDelete=true) {
		if (strpos($sName, '.')) {
			$sLang = $this->aLangMsg;
			$aKeys = explode('.', $sName);
			foreach ($aKeys as $k) {
				if (isset($sLang[$k])) {
					$sLang = $sLang[$k];
				} else {
					return  'NOT_FOUND_LANG_TEXT';
				}
			}
		} else {
			if (isset($this->aLangMsg[$sName])) {
				$sLang=$this->aLangMsg[$sName];
			} else {
				return 'NOT_FOUND_LANG_TEXT';
			}
		}

		if(is_array($aReplace)&&count($aReplace)&&is_string($sLang)) {
			foreach ($aReplace as $sFrom => $sTo) {
				$aReplacePairs["%%{$sFrom}%%"]=$sTo;
			}
			$sLang=strtr($sLang,$aReplacePairs);
		}

		if(Config::Get('module.lang.delete_undefined') and $bDelete and is_string($sLang)) {
			$sLang=preg_replace("/\%\%[\S]+\%\%/U",'',$sLang);
		}
		return $sLang;
	}
	/**
	 * Добавить к текстовкам массив сообщений
	 *
	 * @param array $aMessages     Список текстовок для добавления
	 * @param array|null $aParams	Параметры, позволяют хранить текстовки в структурированном виде, например, тестовки плагина "test" получать как Get('plugin.name.test')
	 */
	public function AddMessages($aMessages, $aParams = null) {
		if (is_array($aMessages)) {
			if (isset($aParams['name'])) {
				$sMsgs=$aMessages;
				if (isset($aParams['category'])) {
					if (isset($this->aLangMsg[$aParams['category']][$aParams['name']])) {
						$sMsgs=array_merge($this->aLangMsg[$aParams['category']][$aParams['name']],$sMsgs);
					}
					$this->aLangMsg[$aParams['category']][$aParams['name']]=$sMsgs;
				} else {
					if (isset($this->aLangMsg[$aParams['name']])) {
						$sMsgs=array_merge($this->aLangMsg[$aParams['name']],$sMsgs);
					}
					$this->aLangMsg[$aParams['name']]=$sMsgs;
				}
			} else {
				$this->aLangMsg = array_merge($this->aLangMsg, $aMessages);
			}
		}
	}
	/**
	 * Добавить к текстовкам отдельное сообщение
	 *
	 * @param string $sKey	Имя текстовки
	 * @param string $sMessage	Значение текстовки
	 */
	public function AddMessage($sKey, $sMessage) {
		$this->aLangMsg[$sKey] = $sMessage;
	}
	/**
	 * Завершаем работу модуля
	 *
	 */
	public function Shutdown() {
		/**
		 * Делаем выгрузку необходимых текстовок в шаблон в виде js
		 */
		$this->AssignToJs();
	}
}
?>