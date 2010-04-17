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
	const PLUGIN_XML_FILE = 'plugin.xml';
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
	 * Список engine-rewrite`ов (модули, экшены, сущности, шаблоны)
	 *
	 * @var array
	 */
	protected $aDelegates=array(
		'module' => array(),
		'action' => array(),
		'entity' => array(),
		'template' => array()
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
		if ($aPaths=glob($this->sPluginsDir.'*',GLOB_ONLYDIR)) {
			$aList=array_map('basename',$aPaths);
			$aActivePlugins=$this->GetActivePlugins();
			foreach($aList as $sPlugin) {
				$this->aPluginsList[$sPlugin] = array(
				'code'      => $sPlugin,
				'is_active' => in_array($sPlugin,$aActivePlugins)
				);

				/**
			 	* Считываем данные из XML файла описания
			 	*/
				$sPluginXML = $this->sPluginsDir.$sPlugin.'/'.self::PLUGIN_XML_FILE;
				if($oXml = @simplexml_load_file($sPluginXML)) {
					/**
				 	* Обрабатываем данные, считанные из XML-описания
				 	*/
					$sLang=$this->Lang_GetLang();

					$this->Xlang($oXml,'name',$sLang);
					$this->Xlang($oXml,'author',$sLang);
					$this->Xlang($oXml,'description',$sLang);
					$oXml->homepage=$this->Text_Parser($oXml->homepage);

					$this->aPluginsList[$sPlugin]['property']=$oXml;
				} else {
					/**
				 	* Если XML-файл описания отсутствует, или не является валидным XML,
				 	* удаляем плагин из списка
				 	*/
					unset($this->aPluginsList[$sPlugin]);
				}
			}
		}
		return $this->aPluginsList;
	}
	
	/**
	 * Получает значение параметра из XML на основе языковой разметки
	 *
	 * @param SimpleXMLElement $oXml
	 * @param string           $sProperty
	 * @param string           $sLang
	 */
	protected function Xlang($oXml,$sProperty,$sLang) {
		$sProperty=trim($sProperty);
		
		$oXml->$sProperty->data = count($data=$oXml->xpath("{$sProperty}/lang[@name='{$sLang}']")) 
			? $this->Text_Parser(trim((string)array_shift($data)))
			: $this->Text_Parser(trim((string)array_shift($oXml->xpath("{$sProperty}/lang[@name='default']"))));	
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
				
					if($sAction=='Activate') {
						/**
						 * Проверяем совместимость с версией LS 						 
						 */
						if(defined('LS_VERSION') 
							and version_compare(LS_VERSION,$aPlugins[$sPlugin]['property']->requires->livestreet,'=<')) {
								$this->Message_AddError(
									$this->Lang_Get(
										'plugins_activation_version_error',
										array(
											'version'=>$aPlugins[$sPlugin]['property']->requires->livestreet)
										),
									$this->Lang_Get('error'),
									true
								);
								return;
						}
						/**
						 * Проверяем наличие require-плагинов
						 */
						if($aPlugins[$sPlugin]['property']->requires->plugins) {
							$aActivePlugins=$this->GetActivePlugins();
							$iConflict=0;
							foreach ($aPlugins[$sPlugin]['property']->requires->plugins->children() as $sReqPlugin) {
								if(!in_array($sReqPlugin,$aActivePlugins)) {
									$iConflict++;
									$this->Message_AddError(
										$this->Lang_Get('plugins_activation_requires_error',
											array(
												'plugin'=>ucfirst($sReqPlugin)
											)
										),
										$this->Lang_Get('error'),
										true
									);
								}
							}
							if($iConflict) { return; }							
						}
						
						/**
						 * Проверяем, не вступает ли данный плагин в конфликт с уже активированными
						 * (по поводу объявленных делегатов) 
						 */
						$aPluginDelegates=$oPlugin->GetDelegates();
						$iConflict=0;
						foreach ($this->aDelegates as $sGroup=>$aReplaceList) {
							$iCount=0;
							if(isset($aPluginDelegates[$sGroup]) 
								and is_array($aPluginDelegates[$sGroup])
									and $iCount=count($aOverlap=array_intersect_key($aReplaceList,$aPluginDelegates[$sGroup]))) {
										$iConflict+=$iCount;
										foreach ($aOverlap as $sResource=>$aConflict) {
											$this->Message_AddError(
												$this->Lang_Get('plugins_activation_overlap', array(
														'resource'=>$sResource,
														'delegate'=>$aConflict['delegate'],
														'plugin'  =>$aConflict['sign']
												)), 
												$this->Lang_Get('error'), true
											);									
										}
							}
							if($iCount){ return; }
						}
					}
					
					$bResult=$oPlugin->$sAction();
				} else {
					/**
					 * Исполняемый файл плагина не найден
					 */
					$this->Message_AddError($this->Lang_Get('plugins_activation_file_not_found'),$this->Lang_Get('error'),true);
					return;
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
	
	/**
	 * Удаляет плагины с сервера
	 *
	 * @param array $aPlugins
	 */
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
	 * @param  string $sSign
	 */
	public function Delegate($sType,$sFrom,$sTo,$sSign=__CLASS__) {
		/**
		 * Запрещаем неподписанные делегаты
		 */
		if(!is_string($sSign) or !strlen($sSign)) return null;
		if(!in_array($sType,array_keys($this->aDelegates)) or !$sFrom or !$sTo) return null;
		
		$this->aDelegates[$sType][trim($sFrom)]=array(
			'delegate'=>trim($sTo),
			'sign'=>$sSign
		);
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
		return $this->isDelegater($sType,$sFrom)?$this->aDelegates[$sType][$sFrom]['delegate']:$sFrom;
	}

	/**
	 * Возвращает делегирующий объект по имени делегата
	 * 
	 * @param  string $sType Объект
	 * @param  string $sTo   Делегат
	 * @return string
	 */
	public function GetDelegater($sType,$sTo) {
 		/**
		 * Фильтруем меппер делегатов
		 * @var array
		 */
		$aDelegateMapper=array_filter(
			$this->aDelegates[$sType], 
			create_function('$item','return $item["delegate"]=="'.$sTo.'";')
		);
		if(!is_array($aDelegateMapper) and !count($aDelegateMapper)) return $sTo;
		
		/**
		 * Получаем ключ первого элемента массива (это название делегирующего экшена)
		 */
		return array_shift(array_keys($aDelegateMapper));
	}
	
	/**
	 * Возвращает подпись делегата модуля, экшена, сущности. 
	 *
	 * @param  string $sType
	 * @param  string $sFrom
	 * @return string|null
	 */
	public function GetDelegateSign($sType,$sFrom) {
		return $this->isDelegater($sType,$sFrom)?$this->aDelegates[$sType][$sFrom]['sign']:null;
	}
	
	/**
	 * Возвращает true, если установлено правило делегирования 
	 * и класс является базовым в данном правиле
	 *
	 * @param  string $sType
	 * @param  string $sFrom
	 * @return bool
	 */
	public function isDelegater($sType,$sFrom) {
		if(!in_array($sType,array_keys($this->aDelegates)) or !$sFrom) return false;
		return isset($this->aDelegates[$sType][$sFrom]['delegate']);
	}
	
	/**
	 * Возвращает true, если устано
	 * 
	 * @param  string $sType
	 * @param  string $sTo
	 * @return bool
	 */
	public function isDelegated($sType,$sTo) {
		/**
		 * Фильтруем меппер делегатов
		 * @var array
		 */
		$aDelegateMapper=array_filter(
			$this->aDelegates[$sType], 
			create_function('$item','return $item["delegate"]=="'.$sTo.'";')
		);
		return (is_array($aDelegateMapper) and count($aDelegateMapper));		
	}
	
	/**
	 * Возвращает список объектов, доступных для делегирования
	 * 
	 * @return array
	 */
	public function GetDelegateObjectList() {
		return array_keys($this->aDelegates);
	}
	
	/**
	 * При завершении работы модуля
	 *
	 */
	public function Shutdown() {
	}
}
?>