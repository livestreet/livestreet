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
 * Маппер для работы с БД
 *
 * @package modules.notify
 * @since 1.0
 */
class ModuleNotify_MapperNotify extends Mapper {
	/**
	 * Добавляет задание
	 *
	 * @param ModuleNotify_EntityTask $oNotifyTask	Объект задания
	 * @return bool
	 */
	public function AddTask(ModuleNotify_EntityTask $oNotifyTask) {
		$sql = "
			INSERT INTO ".Config::Get('db.table.notify_task')." 
				( user_login, user_mail, notify_subject, notify_text, date_created, notify_task_status )
			VALUES
				( ?, ?, ?, ?, ?, ?d )
		";

		if ($this->oDb->query(
			$sql,
			$oNotifyTask->getUserLogin(),
			$oNotifyTask->getUserMail(),
			$oNotifyTask->getNotifySubject(),
			$oNotifyTask->getNotifyText(),
			$oNotifyTask->getDateCreated(),
			$oNotifyTask->getTaskStatus()
		)===0) {
			return true;
		}
		return false;
	}
	/**
	 * Добавляет задания списком
	 *
	 * @param array $aTasks	Список объектов заданий
	 * @return bool
	 */
	public function AddTaskArray($aTasks) {
		if(!is_array($aTasks)&&count($aTasks)==0) {
			return false;
		}

		$aValues=array();
		foreach ($aTasks as $oTask) {
			$aValues[]="(".implode(',',
								   array(
									   $this->oDb->escape($oTask->getUserLogin()),
									   $this->oDb->escape($oTask->getUserMail()),
									   $this->oDb->escape($oTask->getNotifySubject()),
									   $this->oDb->escape($oTask->getNotifyText()),
									   $this->oDb->escape($oTask->getDateCreated()),
									   $this->oDb->escape($oTask->getTaskStatus())
								   )
			).")";
		}
		$sql = "
			INSERT INTO ".Config::Get('db.table.notify_task')." 
				( user_login, user_mail, notify_subject, notify_text, date_created, notify_task_status )
			VALUES 
				".implode(', ', $aValues);

		return $this->oDb->query($sql);
	}
	/**
	 * Удаляет задание
	 *
	 * @param ModuleNotify_EntityTask $oNotifyTask Объект задания
	 * @return bool
	 */
	public function DeleteTask(ModuleNotify_EntityTask $oNotifyTask) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.notify_task')." 
			WHERE
				notify_task_id = ?d			
		";
		if ($this->oDb->query($sql,$oNotifyTask->getTaskId())) {
			return true;
		}
		return false;
	}
	/**
	 * Удаляет отложенные Notify-задания по списку идентификаторов
	 *
	 * @param  array $aTaskId	Список ID заданий на отправку
	 * @return bool
	 */
	public function DeleteTaskByArrayId($aTaskId) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.notify_task')." 
			WHERE
				notify_task_id IN(?a)			
		";
		if ($this->oDb->query($sql,$aTaskId)) {
			return true;
		}
		return false;
	}
	/**
	 * Получает массив заданий на публикацию из базы с указанным количественным ограничением (выборка FIFO)
	 *
	 * @param  int	$iLimit	Количество
	 * @return array
	 */
	public function GetTasks($iLimit) {
		$sql = "SELECT *
				FROM ".Config::Get('db.table.notify_task')."	
				ORDER BY date_created ASC
				LIMIT ?d";
		$aTasks=array();
		if ($aRows=$this->oDb->select($sql,$iLimit)) {
			foreach ($aRows as $aTask) {
				$aTasks[]=Engine::GetEntity('Notify_Task',$aTask);
			}
		}
		return $aTasks;
	}
}
?>