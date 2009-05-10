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
 * Загружает конфиги модулей вида /config/modules/[module_name]/config.php
 */
$sDirConfig=DIR_SERVER_ROOT.'/config/modules/';
if ($hDirConfig = opendir($sDirConfig)) {
	while (false !== ($sDirModule = readdir($hDirConfig))) {
		if ($sDirModule !='.' and $sDirModule !='..' and is_dir($sDirConfig.$sDirModule)) {
			$sFileConfig=$sDirConfig.$sDirModule.'/config.php';
			if (file_exists($sFileConfig)) {
				require_once($sFileConfig);
			}
		}
	}
	closedir($hDirConfig);
}

/**
 * Инклудим все *.php файлы из каталога /include/
 */
$sDirInclude=DIR_SERVER_ROOT.'/include/';
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

?>