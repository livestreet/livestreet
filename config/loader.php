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
 * Operations with Config object
 */
require_once(dirname(dirname(__FILE__))."/engine/lib/internal/ConfigSimple/Config.class.php");
Config::LoadFromFile(dirname(__FILE__).'/config.php');

/**
 * Загружает конфиги модулей вида /config/modules/[module_name]/config.php
 */
$sDirConfig=Config::get('path.root.server').'/config/modules/';
if ($hDirConfig = opendir($sDirConfig)) {
	while (false !== ($sDirModule = readdir($hDirConfig))) {
		if ($sDirModule !='.' and $sDirModule !='..' and is_dir($sDirConfig.$sDirModule)) {
			$sFileConfig=$sDirConfig.$sDirModule.'/config.php';
			if (file_exists($sFileConfig)) {
				$aConfig = include($sFileConfig);
				if(!empty($aConfig) && is_array($aConfig)) {
					// Если конфиг этого модуля пуст, то загружаем массив целиком
					$sKey = "module.$sDirModule";
					if(!Config::isExist($sKey)) {
						Config::Set($sKey,$aConfig);
					} else {
						// Если уже существую привязанные к модулю ключи,
						// то сливаем старые и новое значения ассоциативно
						Config::Set(
							$sKey,
							func_array_merge_assoc(Config::Get($sKey), $aConfig) 
						);
					}
				}
			}
		}
	}
	closedir($hDirConfig);
}


/**
 * Инклудим все *.php файлы из каталога {path.root.engine}/include/ - это файлы ядра
 */
$sDirInclude=Config::get('path.root.engine').'/include/';
if ($hDirInclude = opendir($sDirInclude)) {
	while (false !== ($sFileInclude = readdir($hDirInclude))) {
		$sFileIncludePathFull=$sDirInclude.$sFileInclude;
		if ($sFileInclude !='.' and $sFileInclude !='..' and is_file($sFileIncludePathFull)) {
			$aPathInfo=pathinfo($sFileIncludePathFull);
			if (strtolower($aPathInfo['extension'])=='php') {
				require_once($sDirInclude.$sFileInclude);
			}
		}
	}
	closedir($hDirInclude);
}

/**
 * Инклудим все *.php файлы из каталога {path.root.server}/include/ - пользовательские файлы
 */
$sDirInclude=Config::get('path.root.server').'/include/';
if ($hDirInclude = opendir($sDirInclude)) {
	while (false !== ($sFileInclude = readdir($hDirInclude))) {
		$sFileIncludePathFull=$sDirInclude.$sFileInclude;
		if ($sFileInclude !='.' and $sFileInclude !='..' and is_file($sFileIncludePathFull)) {
			$aPathInfo=pathinfo($sFileIncludePathFull);
			if (strtolower($aPathInfo['extension'])=='php') {
				require_once($sDirInclude.$sFileInclude);
			}
		}
	}
	closedir($hDirInclude);
}

/**
 * Ищет routes-конфиги модулей и объединяет их с текущим
 * @see Router.class.php
 */
$sDirConfig=Config::get('path.root.server').'/config/modules/';
if ($hDirConfig = opendir($sDirConfig)) {
	while (false !== ($sDirModule = readdir($hDirConfig))) {
		if ($sDirModule !='.' and $sDirModule !='..' and is_dir($sDirConfig.$sDirModule)) {
			$sFileConfig=$sDirConfig.$sDirModule.'/config.route.php';
			if (file_exists($sFileConfig)) {
				$aConfig = include($sFileConfig);
				if(!empty($aConfig) && is_array($aConfig)) {
					// Если конфиг этого модуля пуст, то загружаем массив целиком
					$sKey = "router";
					if(!Config::isExist($sKey)) {
						Config::Set($sKey,$aConfig);
					} else {
						// Если уже существую привязанные к модулю ключи,
						// то сливаем старые и новое значения ассоциативно
						Config::Set(
							$sKey,
							func_array_merge_assoc(Config::Get($sKey), $aConfig) 
						);
					}
				}
			}
		}
	}
	closedir($hDirConfig);
}

/**
 * Подгружаем файлы локального и продакшн-конфига
 */
if(file_exists(Config::Get('path.root.server').'/config/config.local.php')) {
	Config::LoadFromFile(Config::Get('path.root.server').'/config/config.local.php',false);
}
if(file_exists(Config::Get('path.root.server').'/config/config.stable.php')) {
	Config::LoadFromFile(Config::Get('path.root.server').'/config/config.stable.php',false);
}

/**
 * Загружает конфиги плагинов вида /plugins/[plugin_name]/config/*.php
 * и include-файлы /plugins/[plugin_name]/include/*.php
 */
$sPluginsDir = Config::Get('path.root.server').'/plugins';
$sPluginsListFile = $sPluginsDir.'/plugins.dat';
if($aPluginsList=@file($sPluginsListFile)) {
	$aPluginsList=array_map('trim',$aPluginsList);
	foreach ($aPluginsList as $sPlugin) {
		$aConfigFiles = glob($sPluginsDir.'/'.$sPlugin.'/config/*.php');
		if($aConfigFiles and count($aConfigFiles)>0) {
			$aConfig=array();
			foreach ($aConfigFiles as $sPath) {
				$aConfig = include($sPath);
				if(!empty($aConfig) && is_array($aConfig)) {
					// Если конфиг этого плагина пуст, то загружаем массив целиком
					$sKey = "plugin.$sPlugin";
					if(!Config::isExist($sKey)) {
						Config::Set($sKey,$aConfig);
					} else {
						// Если уже существую привязанные к плагину ключи,
						// то сливаем старые и новое значения ассоциативно
						Config::Set(
							$sKey,
							func_array_merge_assoc(Config::Get($sKey), $aConfig) 
						);
					}
				}
			}
		}
		/**
		 * Подключаем include-файлы
		 */
		$aIncludeFiles = glob($sPluginsDir.'/'.$sPlugin.'/include/*.php');
		if($aIncludeFiles and count($aIncludeFiles)) {
			foreach ($aIncludeFiles as $sPath) {
				require_once($sPath);
			}		
		}
	}
}

/**
 * Загружает конфиг текущего шаблона
 */
if(file_exists(Config::Get('path.smarty.template').'/settings/config/config.php')) {
	Config::LoadFromFile(Config::Get('path.smarty.template').'/settings/config/config.php',false);
}
?>