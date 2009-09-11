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

class NotifyCron extends Cron {
	protected $sProcessName='NotifyCron';
	/**
	 * Выбираем пул заданий и рассылаем по ним e-mail
	 */
	public function Client() {
		$aNotifyTasks = $this->oEngine->Notify_GetTasksDelayed(Config::Get('module.notify.per_process'));
		
		if(empty($aNotifyTasks)) {
			print PHP_EOL."No tasks are found.";
			return;
		}
		/**
		 * Последовательно загружаем задания на публикацию
		 */
		$aArrayId=array();
		foreach ($aNotifyTasks as $oTask) {
			$this->oEngine->Notify_SendTask($oTask);
			$aArrayId[]=$oTask->getTaskId();
		}
		print PHP_EOL."Send notify: ".count($aArrayId);
		/**
		 * Удаляем отработанные задания
		 */
		$this->oEngine->Notify_DeleteTaskByArrayId($aArrayId);
	}
}

$sLockFilePath=Config::Get('sys.cache.dir').'notify.lock';
/**
 * Создаем объект крон-процесса, 
 * передавая параметром путь к лок-файлу
 */
$app=new NotifyCron($sLockFilePath);
print $app->Exec();
?>