<?php

class ModuleStream_MapperStream extends Mapper
{
	public function subscribeUser($iUserId, $iTargetUserId)
	{
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE
				user_id = ?d AND target_user_id = ?d';
		if (!$this->oDb->select($sql, $iUserId, $iTargetUserId)) {
			$sql = 'INSERT INTO ' . Config::Get('db.table.stream_subscribe') . ' SET
					user_id = ?d, target_user_id = ?d';
			$this->oDb->query($sql, $iUserId, $iTargetUserId);
		}
	}

	public function unsubscribeUser($iUserId, $iTargetUserId)
	{
		$sql = 'DELETE FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE
			user_id = ?d AND target_user_id = ?d';
		$this->oDb->query($sql, $iUserId, $iTargetUserId);
	}

	public function read($aEventTypes, $aUsesrList, $iCount, $iFromId)
	{
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_event'). ' WHERE
				event_type IN (?a) AND initiator IN (?a)';
		$aParams = array($aEventTypes, $aUsesrList);
		if ($iFromId) {
			$sql .= ' AND id < ?d';
			$aParams[] = $iFromId;
		}
		$sql .= ' ORDER BY id DESC';
		if ($iCount) {
			$sql .= ' LIMIT 0,?d';
			$aParams[] = $iCount;
		}
		return call_user_func_array(array($this->oDb, 'select'), array_merge(array($sql), $aParams));
	}

	public function addEvent($oUserId, $sEventType, $iTargetId)
	{
		$sql = 'INSERT INTO ' . Config::Get('db.table.stream_event'). ' SET
				event_type = ?, target_id = ?d, initiator = ?d';
		$this->oDb->query($sql, $sEventType, $iTargetId, $oUserId);
	}

	public function deleteEvent($oUser, $sEventType, $iTargetId)
	{
		$sql = 'DELETE FROM' . Config::Get('db.table.stream_event'). ' WHERE
				event_type = ? AND target_id = ?d AND initiator = ?d';
		$this->oDb->query($sql, $sEventType, $iTargetId, $oUser->getId());
	}

	public function getUserSubscribes($iUserId)
	{
		$sql = 'SELECT target_user_id FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE user_id = ?d';
		return $this->oDb->selectCol($sql, $iUserId);
	}

	public function getTypesList($iUserId)
	{
		$sql = 'SELECT event_type FROM ' . Config::Get('db.table.stream_user_type') . ' WHERE user_id = ?d';
		$ret = $this->oDb->selectCol($sql, $iUserId);
		return $ret;
	}

	public function switchUserEventType($iUserId, $sEventType)
	{
		$sql = 'SELECT * FROM ' . Config::Get('db.table.stream_user_type') . ' WHERE user_id = ?d AND event_type = ?';
		if ($this->oDb->select($sql, $iUserId, $sEventType)) {
			$sql = 'DELETE FROM ' . Config::Get('db.table.stream_user_type') . ' WHERE user_id = ?d AND event_type = ?';
		} else {
			$sql = 'INSERT INTO  '. Config::Get('db.table.stream_user_type') . ' SET user_id = ?d , event_type = ?';
		}
		$this->oDb->query($sql, $iUserId, $sEventType);
	}
}