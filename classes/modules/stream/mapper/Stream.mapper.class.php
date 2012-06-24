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
 * Объект маппера для работы с БД
 *
 * @package modules.stream
 * @since 1.0
 */
class ModuleStream_MapperStream extends Mapper {
	/**
	 * Добавление события в БД
	 *
	 * @param ModuleStream_EntityEvent $oObject
	 * @return int|bool
	 */
	public function AddEvent($oObject) {
		$sql = "INSERT INTO ".Config::Get('db.table.stream_event')." SET ?a ";
		if ($iId=$this->oDb->query($sql,$oObject->_getData())) {
			return $iId;
		}
		return false;
	}
	/**
	 * Получает событие по типу и его ID
	 *
	 * @param string $sEventType	Тип
	 * @param int $iTargetId	ID владельца события
	 * @param int|null $iUserId	ID пользователя
	 * @return ModuleStream_EntityEvent
	 */
	public function GetEventByTarget($sEventType, $iTargetId, $iUserId=null) {
		$sql = "SELECT * FROM
					".Config::Get('db.table.stream_event')."
				WHERE target_id = ?d AND event_type = ? { AND user_id = ?d } ";
		if ($aRow=$this->oDb->selectRow($sql,$iTargetId,$sEventType,is_null($iUserId) ? DBSIMPLE_SKIP : $iUserId)) {
			return Engine::GetEntity('ModuleStream_EntityEvent',$aRow);
		}
		return null;
	}
	/**
	 * Обновление события
	 *
	 * @param ModuleStream_EntityEvent $oObject	Объект события
	 * @return int
	 */
	public function UpdateEvent($oObject) {
		$sql = "UPDATE ".Config::Get('db.table.stream_event')." SET ?a WHERE id = ?d ";
		return $this->oDb->query($sql,$oObject->_getData(array('publish')),$oObject->getId());
	}
	/**
	 * Получение типов событий, на которые подписан пользователь
	 *
	 * @param int $iUserId	ID пользователя
	 * @return array
	 */
	public function getTypesList($iUserId) {
		$sql = 'SELECT event_type FROM ' . Config::Get('db.table.stream_user_type') . ' WHERE user_id = ?d';
		$aRet = $this->oDb->selectCol($sql, $iUserId);
		return $aRet;
	}
	/**
	 * Получение списка пользователей, на которых подписан пользователь
	 *
	 * @param int $iUserId	ID пользователя
	 * @return array
	 */
	public function getUserSubscribes($iUserId) {
		$sql = 'SELECT target_user_id FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE user_id = ?d';
		return $this->oDb->selectCol($sql, $iUserId);
	}
	/**
	 * Чтение событий
	 *
	 * @param array $aEventTypes	Список типов событий
	 * @param array|null $aUsersList	Список пользователей, чьи события читать
	 * @param int $iCount	Количество
	 * @param int $iFromId	ID события с которого начинать выборку
	 * @return array
	 */
	public function Read($aEventTypes, $aUsersList, $iCount, $iFromId) {
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_event'). '
				WHERE
					event_type IN (?a) 
					{ AND user_id IN (?a) }
					AND publish = 1
					{ AND id < ?d }	
				ORDER BY id DESC
				{ LIMIT 0,?d }';

		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$aEventTypes,(!is_null($aUsersList) and count($aUsersList)) ? $aUsersList : DBSIMPLE_SKIP,!is_null($iFromId) ? $iFromId : DBSIMPLE_SKIP,!is_null($iCount) ? $iCount : DBSIMPLE_SKIP)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('Stream_Event',$aRow);
			}
		}
		return $aReturn;
	}
	/**
	 * Количество событий для пользователя
	 *
	 * @param array $aEventTypes	Список типов событий
	 * @param array|null $aUserId	ID пользователя
	 * @return int
	 */
	public function GetCount($aEventTypes, $aUserId) {
		if (!is_null($aUserId) and !is_array($aUserId)) {
			$aUserId=array($aUserId);
		}
		$sql = 'SELECT count(*) as c FROM ' . Config::Get('db.table.stream_event'). '
				WHERE
					event_type IN (?a)
					{ AND user_id IN (?a) }
					AND publish = 1 ';
		if ($aRow=$this->oDb->selectRow($sql,$aEventTypes,(!is_null($aUserId) and count($aUserId)) ? $aUserId : DBSIMPLE_SKIP)) {
			return $aRow['c'];
		}
		return 0;
	}
	/**
	 * Редактирование списка событий, на которые подписан юзер
	 *
	 * @param int $iUserId	ID пользователя
	 * @param string $sEventType	Тип
	 * @return bool
	 */
	public function switchUserEventType($iUserId, $sEventType) {
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_user_type') . ' WHERE user_id = ?d AND event_type = ?';
		if ($this->oDb->select($sql, $iUserId, $sEventType)) {
			$sql = 'DELETE FROM ' . Config::Get('db.table.stream_user_type') . ' WHERE user_id = ?d AND event_type = ?';
		} else {
			$sql = 'INSERT INTO  '. Config::Get('db.table.stream_user_type') . ' SET user_id = ?d , event_type = ?';
		}
		$this->oDb->query($sql, $iUserId, $sEventType);
	}
	/**
	 * Подписать пользователя
	 *
	 * @param int $iUserId	ID пользователя
	 * @param int $iTargetUserId	ID пользователя на которого подписываем
	 */
	public function subscribeUser($iUserId, $iTargetUserId) {
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE
				user_id = ?d AND target_user_id = ?d';
		if (!$this->oDb->select($sql, $iUserId, $iTargetUserId)) {
			$sql = 'INSERT INTO ' . Config::Get('db.table.stream_subscribe') . ' SET
					user_id = ?d, target_user_id = ?d';
			$this->oDb->query($sql, $iUserId, $iTargetUserId);
		}
	}
	/**
	 * Отписать пользователя
	 *
	 * @param int $iUserId	ID пользователя
	 * @param int $iTargetUserId	ID пользователя на которого подписываем
	 */
	public function unsubscribeUser($iUserId, $iTargetUserId) {
		$sql = 'DELETE FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE
			user_id = ?d AND target_user_id = ?d';
		$this->oDb->query($sql, $iUserId, $iTargetUserId);
	}
	/**
	 * Проверяет подписан ли пользователь на конкретного пользователя
	 *
	 * @param $iUserId	ID пользователя
	 * @param $iTargetUserId	ID пользователя на которого подписан
	 * @return bool
	 */
	public function IsSubscribe($iUserId,$iTargetUserId) {
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE
				user_id = ?d AND target_user_id = ?d LIMIT 0,1';
		if ($this->oDb->selectRow($sql, $iUserId, $iTargetUserId)) {
			return true;
		}
		return false;
	}
}