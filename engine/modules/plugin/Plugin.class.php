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
 * Модуль управления плагинами сообщений
 *
 */
class LsPlugin extends Module {
	/**
	 * Файл содержащий информацию об активированных плагинах
	 *
	 * @var string
	 */
	const PLUGIN_ACTIVATION_FILE = 'plugins.dat';
	/**
	 * Файл описания плагина
	 *
	 * @var string 
	 */
	const PLUGIN_README_FILE = 'readme.txt';
	
	/**
	 * Путь к директории с плагинами
	 * 
	 * @var string
	 */
	protected $sPluginsDir;
	
	/**
	 * Список плагинов
	 *
	 * @var unknown_type
	 */
	protected $aPluginsList=array();
	
	/**
	 * Список engine-rewrite`ов (модули, экшены, сущности)
	 *
	 * @var array
	 */
	protected $aDelegate=array(
		'module' => array(),
		'action' => array(),
		'entity' => array()
	);
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {
		$this->sPluginsDir=Config::Get('path.root.server').'/plugins/';
	}
	
	/**
	 * Получает список информации о всех плагинах, загруженных в plugin-директорию
	 *
	 * @return array
	 */
	public function GetList() {
		$aList=array_map('basename',glob($this->sPluginsDir.'*',GLOB_ONLYDIR));
		$aActivePlugins=$this->GetActivePlugins();

		foreach($aList as $sPlugin) {			
			$this->aPluginsList[$sPlugin] = array(
				'code'        => $sPlugin,
				'is_active'   => in_array($sPlugin,$aActivePlugins),
				'name'        => '',
				'description' => '',
				'author'      => '',
				'homepage'    => '',
				'version'     => ''
			);

			
			$sReadme = $this->sPluginsDir.$sPlugin.'/'.self::PLUGIN_README_FILE;
			if(is_file($sReadme)) {
				$aInfo = file($sReadme);
				foreach ($aInfo as $sParam) {
					list($sKey,$sValue) = explode(':',trim($sParam),2);
					$sKey=strtolower($sKey);
					$sValue=$this->Text_Parser(trim($sValue));
					
					$this->aPluginsList[$sPlugin][$sKey]=$sValue;
				}
			}
		}
		
		return $this->aPluginsList;
	}
	
	public function Toggle($sPlugin,$sAction) {
		$aPlugins=$this->GetList();
		if(!isset($aPlugins[$sPlugin])) return null;
		
		$sPluginName=ucfirst($sPlugin);
		
		switch ($sAction) {
			case 'activate':
			case 'deactivate':
				$sAction=ucfirst($sAction);
				
				$sFile="{$this->sPluginsDir}{$sPlugin}/Plugin{$sPluginName}.class.php";
				if(is_file($sFile)) {
					require_once($sFile);
					
					$sClassName="Plugin{$sPluginName}";
					$oPlugin=new $sClassName;
					$bResult=$oPlugin->$sAction();
				}
				
				if($bResult) {
					/**
					 * Переопределяем список активированных пользователем плагинов
					 */
					$aActivePlugins=$this->GetActivePlugins();
					if($sAction=='Activate') {
						/**
						 * Вносим данные в файл об активации плагина
						 */
						$aActivePlugins[] = $sPlugin;
					} else {
						/**
						 * Вносим данные в файл о деактивации плагина
						 */
						$aIndex=array_keys($aActivePlugins,$sPlugin);
						if(is_array($aIndex)) {
							unset($aActivePlugins[array_shift($aIndex)]);
						}
					}
					$this->SetActivePlugins($aActivePlugins);
				}
				return $bResult;
			
			default:
				return null;
		}
	}
	
	/**
	 * Возвращает список активированных плагинов в системе
	 *
	 * @return array
	 */
	public function GetActivePlugins() {
		/**
		 * Читаем данные из файла PLUGINS.DAT
		 */		
		$aPlugins=@file($this->sPluginsDir.self::PLUGIN_ACTIVATION_FILE);
		$aPlugins =(is_array($aPlugins))?array_unique(array_map('trim',$aPlugins)):array();
		
		return $aPlugins;
	}
	
	/**
	 * Записывает список активных плагинов в файл PLUGINS.DAT
	 *
	 * @param array|string $aPlugins
	 */
	public function SetActivePlugins($aPlugins) {
		if(!is_array($aPlugins)) $aPlugins = array($aPlugins);
		$aPlugins=array_unique(array_map('trim',$aPlugins));
		
		/**
		 * Записываем данные в файл PLUGINS.DAT
		 */
		file_put_contents($this->sPluginsDir.self::PLUGIN_ACTIVATION_FILE, implode(PHP_EOL,$aPlugins));
	}
	
	
	public function Delete($aPlugins) {
		if(!is_array($aPlugins)) $aPlugins=array($aPlugins);
		
		$aActivePlugins=$this->GetActivePlugins();
		foreach ($aPlugins as $sPluginCode) {
			/**
			 * Если плагин активен, деактивируем его
			 */
			if(in_array($sPluginCode,$aActivePlugins)) $this->Toggle($sPluginCode,'deactivate');
			
			/**
			 * Удаляем директорию с плагином
			 */
			func_rmdir($this->sPluginsDir.$sPluginCode);
		}
	}
	
	/**
	 * Перенаправление вызовов на модули, экшены, сущности
	 *
	 * @param  string $sType
	 * @param  string $sFrom
	 * @param  string $sTo
	 */
	public function Delegate($sType,$sFrom,$sTo) {
		if(!in_array($sType,array('module','action','entity')) or !$sFrom or !$sTo) return null;
		$this->aDelegate[$sType][trim($sFrom)]=trim($sTo);
	}
	
	/**
	 * Возвращает делегат модуля, экшена, сущности. 
	 * Если делегат не определен, отдает переданный в качестве sender`a параметр
	 *
	 * @param  string $sType
	 * @param  string $sFrom
	 * @return string
	 */
	public function GetDelegate($sType,$sFrom) {
		return $this->isDelegated($sType,$sFrom)?$this->aDelegate[$sType][$sFrom]:$sFrom;
	}
	
	/**
	 * Возвращает true, если установлено правило делегирования
	 *
	 * @param  string $sType
	 * @param  string $sFrom
	 * @return bool
	 */
	public function isDelegated($sType,$sFrom) {
		if(!in_array($sType,array('module','action','entity')) or !$sFrom) return false;
		return isset($this->aDelegate[$sType][$sFrom]);
	}
	
	/**
	 * При завершении работы модуля
	 *
	 */
	public function Shutdown() {
	
	}
}
?>