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
 * Файл плагина должен находиться в каталоге /plugins/plgname/ и иметь название PluginPlgname.class.php
 *
 * @package engine
 * @since 1.0
 */
abstract class Plugin extends LsObject {
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
	/**
	 * Массив наследуемых классов плагина
	 *
	 * @var array
	 */
	protected $aInherits=array();

	/**
	 * Метод инициализации плагина
	 *
	 */
	public function Init() {
	}
	/**
	 * Передает информацию о делегатах в модуль ModulePlugin
	 * Вызывается Engine перед инициализацией плагина
	 * @see Engine::LoadPlugins
	 */
	final function Delegate() {
		$aDelegates=$this->GetDelegates();
		foreach ($aDelegates as $sObjectName=>$aParams) {
			foreach ($aParams as $sFrom=>$sTo) {
				$this->Plugin_Delegate($sObjectName,$sFrom,$sTo,get_class($this));
			}
		}

		$aInherits=$this->GetInherits();
		foreach ($aInherits as $sObjectName=>$aParams) {
			foreach ($aParams as $sFrom=>$sTo) {
				$this->Plugin_Inherit($sFrom,$sTo,get_class($this));
			}
		}
	}
	/**
	 * Возвращает массив наследников
	 *
	 * @return array
	 */
	final function GetInherits() {
		$aReturn=array();
		if(is_array($this->aInherits) and count($this->aInherits)) {
			foreach ($this->aInherits as $sObjectName=>$aParams) {
				if(is_array($aParams) and count($aParams)) {
					foreach ($aParams as $sFrom=>$sTo) {
						if (is_int($sFrom)) {
							$sFrom=$sTo;
							$sTo=null;
						}
						list($sFrom,$sTo)=$this->MakeDelegateParams($sObjectName,$sFrom,$sTo);
						$aReturn[$sObjectName][$sFrom]=$sTo;
					}
				}
			}
		}
		return $aReturn;
	}
	/**
	 * Возвращает массив делегатов
	 *
	 * @return array
	 */
	final function GetDelegates() {
		$aReturn=array();
		if(is_array($this->aDelegates) and count($this->aDelegates)) {
			foreach ($this->aDelegates as $sObjectName=>$aParams) {
				if(is_array($aParams) and count($aParams)) {
					foreach ($aParams as $sFrom=>$sTo) {
						if (is_int($sFrom)) {
							$sFrom=$sTo;
							$sTo=null;
						}
						list($sFrom,$sTo)=$this->MakeDelegateParams($sObjectName,$sFrom,$sTo);
						$aReturn[$sObjectName][$sFrom]=$sTo;
					}
				}
			}
		}
		return $aReturn;
	}
	/**
	 * Преобразовывает краткую форму имен делегатов в полную
	 *
	 * @param $sObjectName	Название типа объекта делегата
	 * @see ModulePlugin::aDelegates
	 * @param $sFrom	Что делегируем
	 * @param $sTo		Что делегирует
	 * @return array
	 */
	public function MakeDelegateParams($sObjectName,$sFrom,$sTo) {
		/**
		 * Если не указан делегат TO, считаем, что делегатом является
		 * одноименный объект текущего плагина
		 */
		if ($sObjectName=='template') {
			if(!$sTo) {
				$sTo = self::GetTemplatePath(get_class($this)).$sFrom;
			} else {
				$sTo=preg_replace("/^_/",$this->GetTemplatePath(get_class($this)),$sTo);
			}
		} else {
			if(!$sTo) {
				$sTo = get_class($this).'_'.$sFrom;
			} else {
				$sTo=preg_replace("/^_/",get_class($this).'_',$sTo);
			}
		}
		return array($sFrom,$sTo);
	}
	/**
	 * Метод активации плагина
	 *
	 * @return bool
	 */
	public function Activate() {
		return true;
	}
	/**
	 * Метод деактивации плагина
	 *
	 * @return bool
	 */
	public function Deactivate() {
		return true;
	}
	/**
	 * Транслирует на базу данных запросы из указанного файла
	 * @see ModuleDatabase::ExportSQL
	 *
	 * @param  string $sFilePath	Полный путь до файла с SQL
	 * @return array
	 */
	protected function ExportSQL($sFilePath) {
		return $this->Database_ExportSQL($sFilePath);
	}
	/**
	 * Выполняет SQL
	 * @see ModuleDatabase::ExportSQLQuery
	 *
	 * @param string $sSql	Строка SQL запроса
	 * @return array
	 */
	protected function ExportSQLQuery($sSql) {
		return $this->Database_ExportSQLQuery($sSql);
	}
	/**
	 * Проверяет наличие таблицы в БД
	 * @see ModuleDatabase::isTableExists
	 *
	 * @param string $sTableName	Название таблицы, необходимо перед именем таблицы добавлять "prefix_", это позволит учитывать произвольный префикс таблиц у пользователя
	 * <pre>
	 * prefix_topic
	 * </pre>
	 * @return bool
	 */
	protected function isTableExists($sTableName) {
		return $this->Database_isTableExists($sTableName);
	}
	/**
	 * Проверяет наличие поля в таблице
	 * @see ModuleDatabase::isFieldExists
	 *
	 * @param string $sTableName	Название таблицы, необходимо перед именем таблицы добавлять "prefix_", это позволит учитывать произвольный префикс таблиц у пользователя
	 * @param string $sFieldName	Название поля в таблице
	 * @return bool
	 */
	protected function isFieldExists($sTableName,$sFieldName) {
		return $this->Database_isFieldExists($sTableName,$sFieldName);
	}

	/**
	 * Добавляет новый тип в поле enum(перечисление)
	 * @see ModuleDatabase::addEnumType
	 *
	 * @param string $sTableName	Название таблицы, необходимо перед именем таблицы добавлять "prefix_", это позволит учитывать произвольный префикс таблиц у пользователя
	 * @param string $sFieldName	Название поля в таблице
	 * @param string $sType			Название типа
	 */
	protected function addEnumType($sTableName,$sFieldName,$sType) {
		$this->Database_addEnumType($sTableName,$sFieldName,$sType);
	}
	/**
	 * Возвращает версию плагина
	 *
	 * @return string|null
	 */
	public function GetVersion() {
		preg_match('/^Plugin([\w]+)$/i',get_class($this),$aMatches);
		$sPluginXML = Config::Get('path.application.plugins.server').'/'.func_underscore($aMatches[1]).'/'.ModulePlugin::PLUGIN_XML_FILE;
		if($oXml = @simplexml_load_file($sPluginXML)) {
			return (string)$oXml->version;
		}
		return null;
	}
	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 * @see Engine::_CallModule
	 *
	 * @param string $sName Имя метода
	 * @param array $aArgs Аргументы
	 * @return mixed
	 */
	public function __call($sName,$aArgs) {
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
	/**
	 * Возвращает полный серверный путь до плагина
	 *
	 * @param string $sName
	 * @return string
	 */
	static public function GetPath($sName) {
		$sName = preg_match('/^Plugin([\w]+)(_[\w]+)?$/Ui',$sName,$aMatches)
			? func_underscore($aMatches[1])
			: func_underscore($sName);

		return Config::Get('path.application.plugins.server').'/'.$sName.'/';
	}
	/**
	 * Возвращает полный web-адрес до плагина
	 *
	 * @param string $sName
	 * @return string
	 */
	static public function GetWebPath($sName) {
		$sName = preg_match('/^Plugin([\w]+)(_[\w]+)?$/Ui',$sName,$aMatches)
			? func_underscore($aMatches[1])
			: func_underscore($sName);

		return Config::Get('path.root.web').'/application/plugins/'.$sName.'/';
	}
	/**
	 * Возвращает правильный серверный путь к директории шаблонов с учетом текущего шаблона
	 * Если пользователь использует шаблон которого нет в плагине, то возвращает путь до шабона плагина 'default'
	 *
	 * @param string $sName	Название плагина или его класс
	 * @return string|null
	 */
	static public function GetTemplatePath($sName) {
		$sName = preg_match('/^Plugin([\w]+)(_[\w]+)?$/Ui',$sName,$aMatches)
			? func_underscore($aMatches[1])
			: func_underscore($sName);
		if(!isset(self::$aTemplatePath[$sName])) {
			$aPaths=glob(Config::Get('path.application.plugins.server').'/'.$sName.'/templates/skin/*',GLOB_ONLYDIR);
			$sTemplateName=($aPaths and in_array(Config::Get('view.skin'),array_map('basename',$aPaths)))
				? Config::Get('view.skin')
				: 'default';

			$sDir=Config::Get('path.application.plugins.server')."/{$sName}/templates/skin/{$sTemplateName}/";
			self::$aTemplatePath[$sName] = is_dir($sDir) ? $sDir : null;
		}
		return self::$aTemplatePath[$sName];
	}
	/**
	 * Возвращает правильный web-адрес директории шаблонов
	 * Если пользователь использует шаблон которого нет в плагине, то возвращает путь до шабона плагина 'default'
	 *
	 * @param string $sName	Название плагина или его класс
	 * @return string
	 */
	static public function GetTemplateWebPath($sName) {
		$sName = preg_match('/^Plugin([\w]+)(_[\w]+)?$/Ui',$sName,$aMatches)
			? func_underscore($aMatches[1])
			: func_underscore($sName);
		if(!isset(self::$aTemplateWebPath[$sName])) {
			$aPaths=glob(Config::Get('path.application.plugins.server').'/'.$sName.'/templates/skin/*',GLOB_ONLYDIR);
			$sTemplateName=($aPaths and in_array(Config::Get('view.skin'),array_map('basename',$aPaths)))
				? Config::Get('view.skin')
				: 'default';

			self::$aTemplateWebPath[$sName]=Config::Get('path.application.plugins.web')."/{$sName}/templates/skin/{$sTemplateName}/";
		}
		return self::$aTemplateWebPath[$sName];
	}
	/**
	 * Устанавливает значение серверного пути до шаблонов плагина
	 *
	 * @param  string $sName	Имя плагина
	 * @param  string $sTemplatePath	Серверный путь до шаблона
	 * @return bool
	 */
	static public function SetTemplatePath($sName,$sTemplatePath) {
		if(!is_dir($sTemplatePath)) return false;
		self::$aTemplatePath[$sName]=$sTemplatePath;
		return true;
	}
	/**
	 * Устанавливает значение web-пути до шаблонов плагина
	 *
	 * @param  string $sName	Имя плагина
	 * @param  string $sTemplatePath	Серверный путь до шаблона
	 */
	static public function SetTemplateWebPath($sName,$sTemplatePath) {
		self::$aTemplateWebPath[$sName]=$sTemplatePath;
	}
}
?>