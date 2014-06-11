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

require_once(dirname(dirname(dirname(__DIR__))).'/bootstrap/start.php');

class CronTemplateCacheClean extends Cron {
	/**
	 * Находим все кеш-файлы js и css и удаляем их с сервера
	 */
	public function Client() {
		/**
		 * Выбираем все файлы кеша
		 */
		$aFiles = glob(Config::Get('path.smarty.cache'). DIRECTORY_SEPARATOR ."*\*.{css,js}", GLOB_BRACE);
		if (!$aFiles) $aFiles=array();
		
		$this->Log("Cache files count: ".count($aFiles));
		
		foreach ($aFiles as $sFilePath) {
			@unlink($sFilePath);
		}
	}
}

/**
 * Создаем объект крон-процесса 
 */
$app=new CronTemplateCacheClean();
print $app->Exec();