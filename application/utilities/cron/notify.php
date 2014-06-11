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

class CronNotify extends Cron {
	/**
	 * Выбираем пул заданий и рассылаем по ним e-mail
	 */
	public function Client() {
		$aNotifyTasks = $this->Notify_GetTasksDelayed(Config::Get('module.notify.per_process'));
		
		if(empty($aNotifyTasks)) {
			$this->Log("No tasks are found.");
			return;
		}
		/**
		 * Последовательно загружаем задания на публикацию
		 */
		$aArrayId=array();
		foreach ($aNotifyTasks as $oTask) {
			$this->Notify_SendTask($oTask);
			$aArrayId[]=$oTask->getTaskId();
		}
		$this->Log("Send notify: ".count($aArrayId));
		/**
		 * Удаляем отработанные задания
		 */
		$this->Notify_DeleteTaskByArrayId($aArrayId);
	}
}

$sLockFilePath=Config::Get('sys.cache.dir').'CronNotify.lock';
/**
 * Создаем объект крон-процесса, 
 * передавая параметром путь к лок-файлу
 */
$app=new CronNotify($sLockFilePath);
print $app->Exec();