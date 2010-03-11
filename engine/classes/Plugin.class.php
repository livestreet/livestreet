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
 * Абстракция плагина, от которой наследуются все плагины
 *
 */
abstract class Plugin extends Object {
	/**
	 * Путь к шаблонам с учетом наличия соответствующего skin`a
	 *
	 * @var array
	 */
	static protected $aTemplatePath=array();
	/**
	 * Web-адрес директорий шаблонов с учетом наличия соответствующего skin`a
	 *
	 * @var array
	 */
	static protected $aTemplateWebPath=array();		
	/**
	 * Массив делегатов плагина
	 *
	 * @var array
	 */
	protected $aDelegates=array();
	
	public function __construct() {

	}

	/**
	 * Функция инициализации плагина
	 *
	 */
	public function Init() {
	}
	
	/**
	 * Передает информацию о делегатах на Plugin-модуль
	 * Вызывается Engine перед инициализацией плагина
	 */
	final function Delegate() {
		/**
		 * Получаем название плагина
		 */
		preg_match('/^Plugin([\w]+)$/i',get_class($this),$aMatches);
		$sPluginName=strtolower($aMatches[1]);
		/**
		 * Получаем список объектов, подлежащих делегации
		 */
		$aObjects=$this->Plugin_GetDelegateObjectList();
		
		/**
		 * Считываем данные из XML файла описания
		 */
		$sPluginXML = Config::Get('path.root.server').'/plugins/'.$sPluginName.'/'.LsPlugin::PLUGIN_README_FILE;
		if($oXml = @simplexml_load_file($sPluginXML)) {
			foreach($aObjects as $sObjectName) {
				if(is_array($data=$oXml->xpath("delegate/{$sObjectName}/item"))) {
					foreach($data as $aDelegate) {
						$this->Plugin_Delegate($sObjectName,$aDelegate->from,$aDelegate->to,get_class($this));
					}
				}
			}
		}
		
		if(is_array($this->aDelegates) and count($this->aDelegates)) {
			foreach ($this->aDelegates as $sObjectName=>$aParams) {
				if(is_array($aParams) and count($aParams)) {
					foreach ($aParams as $sFrom=>$sTo) {
						$this->Plugin_Delegate($sObjectName,$sFrom,$sTo,get_class($this));
					}
				}
			}
		}
	}
	
	/**
	 * Возвращает массив делегатов
	 *
	 * @return array
	 */
	final function GetDelegates() {
		return $this->aDelegates;
	}
	
	/**
	 * Функция активации плагина
	 *
	 */
	public function Activate() {
		return true;
	}
	/**
	 * Функция деактивации плагина
	 *
	 */
	public function Deactivate() {
		return true;
	}
	/**
	 * Транслирует на базу данных запросы из указанного файла
	 * 
	 * @param  string $sFilePath
	 * @return array
	 */
	protected function ExportSQL($sFilePath) {
		$sFileQuery = @file_get_contents($sFilePath);
		/**
		 * Замена префикса таблиц
		 */
		$sFileQuery = str_replace('prefix_', Config::Get('db.table.prefix'), $sFileQuery);

		/**
		 * Массивы запросов и пустой контейнер для сбора ошибок
		 */
		$aErrors = array();
		$aQuery=explode(';',$sFileQuery);
		/**
		 * Выполняем запросы по очереди
		 */
		foreach($aQuery as $sQuery){
			$sQuery = trim($sQuery);
			/**
			 * Заменяем движек, если таковой указан в запросе
			 */
			if(Config::Get('db.tables.engine')!='InnoDB') $sQuery=str_ireplace('ENGINE=InnoDB', "ENGINE=".Config::Get('db.tables.engine'),$sQuery);
			
			if($sQuery!='') {
				$bResult=$this->Database_GetConnect()->query($sQuery);
				if(!$bResult) $aErrors[] = mysql_error();
			}
		}
				
		/**
		 * Возвращаем результат выполнения, взависимости от количества ошибок 
		 */
		if(count($aErrors)==0) {
			return array('result'=>true,'errors'=>null);
		}
		return array('result'=>false,'errors'=>$aErrors);
	}
	
	public function __call($sName,$aArgs) {
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
	
	/**
	 * Возвращает правильный путь к директории шаблонов
	 *
	 * @return string
	 */
	static public function GetTemplatePath($sName) {	
		$sName = preg_match('/^Plugin([\w]+)$/i',$sName,$aMatches)
			? strtolower($aMatches[1])
			: strtolower($sName);
		if(!isset(self::$aTemplatePath[$sName])) {	
			$sTemplateName=in_array(Config::Get('view.skin'),array_map('basename',glob(Config::Get('path.root.server').'/plugins/'.$sName.'/templates/skin/*',GLOB_ONLYDIR)))
				? Config::Get('view.skin')
				: 'default';
			
			$sDir=Config::Get('path.root.server')."/plugins/{$sName}/templates/skin/{$sTemplateName}";
			self::$aTemplatePath[$sName] = is_dir($sDir) ? $sDir : null;
		}
		
		return self::$aTemplatePath[$sName];
	}	
	
	/**
	 * Возвращает правильный web-адрес директории шаблонов
	 *
	 * @return string
	 */
	static public function GetTemplateWebPath($sName) {
		$sName = preg_match('/^Plugin([\w]+)$/i',$sName,$aMatches)
			? strtolower($aMatches[1])
			: strtolower($sName);
		if(!isset(self::$aTemplateWebPath[$sName])) {	
			$sTemplateName=in_array(Config::Get('view.skin'),array_map('basename',glob(Config::Get('path.root.server').'/plugins/'.$sName.'/templates/skin/*',GLOB_ONLYDIR)))
				? Config::Get('view.skin')
				: 'default';
			
			self::$aTemplateWebPath[$sName]=Config::Get('path.root.web')."/plugins/{$sName}/templates/skin/{$sTemplateName}";
		}
		
		return self::$aTemplateWebPath[$sName];
	}
	
	/**
	 * Устанавливает значение пути до шаблонов плагина
	 *
	 * @param  string $sName
	 * @param  string $sTemplatePath
	 * @return bool
	 */
	static public function SetTemplatePath($sName,$sTemplatePath) {
		if(!is_dir($sTemplatePath)) return false;
		self::$aTemplatePath[$sName]=$sTemplatePath;
	}

	/**
	 * Устанавливает значение web-пути до шаблонов плагина
	 *
	 * @param  string $sName
	 * @param  string $sTemplatePath
	 * @return bool
	 */
	static public function SetTemplateWebPath($sName,$sTemplatePath) {
		self::$aTemplateWebPath[$sName]=$sTemplatePath;
	}	
}
?>