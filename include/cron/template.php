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
define('SYS_HACKER_CONSOLE',false);

$sDirRoot=dirname(dirname(dirname(__FILE__)));
set_include_path(get_include_path().PATH_SEPARATOR.$sDirRoot);
chdir($sDirRoot);

require_once($sDirRoot."/config/loader.php");
require_once($sDirRoot."/engine/classes/Cron.class.php");

class TemplateCacheCleanCron extends Cron {
	protected $sProcessName='TemplateCacheCleanCron';
	/**
	 * Находим все кеш-файлы js и css и удаляем их с сервера
	 */
	public function Client() {
		$sDir = Config::Get('path.smarty.cache');
		/**
		 * Выбираем все файлы кеша
		 */
		$aFiles = glob($sDir. DIRECTORY_SEPARATOR ."*.{css,js}", GLOB_BRACE);
		if (!$aFiles) $aFiles=array();
		
		print PHP_EOL."Cache files count: ".count($aFiles);
		
		foreach ($aFiles as $sFilePath) {
			@unlink($sFilePath);
		}
	}
}

/**
 * Создаем объект крон-процесса 
 */
$app=new TemplateCacheCleanCron();
print $app->Exec();
?>