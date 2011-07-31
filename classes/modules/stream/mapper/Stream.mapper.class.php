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

class ModuleStream_MapperStream extends Mapper {

	public function AddEvent($oObject) {
		$sql = "INSERT INTO ".Config::Get('db.table.stream_event')." SET ?a ";			
		if ($iId=$this->oDb->query($sql,$oObject->_getData())) {
			return $iId;
		}		
		return false;
	}	
	
	public function UpdateEvent($oObject) {
		$sql = "UPDATE ".Config::Get('db.table.stream_event')." SET ?a WHERE id = ?d ";			
		return $this->oDb->query($sql,$oObject->_getData(array('publish')),$oObject->getId());
	}
	
	
	public function getTypesList($iUserId) {
		$sql = 'SELECT event_type FROM ' . Config::Get('db.table.stream_user_type') . ' WHERE user_id = ?d';
		$aRet = $this->oDb->selectCol($sql, $iUserId);
		return $aRet;
	}

	public function getUserSubscribes($iUserId) {
		$sql = 'SELECT target_user_id FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE user_id = ?d';
		return $this->oDb->selectCol($sql, $iUserId);
	}

	public function Read($aEventTypes, $aUsersList, $iCount, $iFromId) {
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_event'). '
				WHERE
					event_type IN (?a) 
					AND user_id IN (?a)
					{ AND id < ?d }	
				ORDER BY id DESC
				{ LIMIT 0,?d }';

		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$aEventTypes,$aUsersList,!is_null($iFromId) ? $iFromId : DBSIMPLE_SKIP,!is_null($iCount) ? $iCount : DBSIMPLE_SKIP)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('Stream_Event',$aRow);
			}
		}
		return $aReturn;
	}

	public function switchUserEventType($iUserId, $sEventType) {
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_user_type') . ' WHERE user_id = ?d AND event_type = ?';
		if ($this->oDb->select($sql, $iUserId, $sEventType)) {
			$sql = 'DELETE FROM ' . Config::Get('db.table.stream_user_type') . ' WHERE user_id = ?d AND event_type = ?';
		} else {
			$sql = 'INSERT INTO  '. Config::Get('db.table.stream_user_type') . ' SET user_id = ?d , event_type = ?';
		}
		$this->oDb->query($sql, $iUserId, $sEventType);
	}

	public function subscribeUser($iUserId, $iTargetUserId) {
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE
				user_id = ?d AND target_user_id = ?d';
		if (!$this->oDb->select($sql, $iUserId, $iTargetUserId)) {
			$sql = 'INSERT INTO ' . Config::Get('db.table.stream_subscribe') . ' SET
					user_id = ?d, target_user_id = ?d';
			$this->oDb->query($sql, $iUserId, $iTargetUserId);
		}
	}

	public function unsubscribeUser($iUserId, $iTargetUserId) {
		$sql = 'DELETE FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE
			user_id = ?d AND target_user_id = ?d';
		$this->oDb->query($sql, $iUserId, $iTargetUserId);
	}

}