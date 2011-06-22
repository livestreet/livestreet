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

    public function read($iEventTypes, $aUsesrList, $iCount, $iFromId)
    {
        $sql = 'SELECT * FROM ' . Config::Get('db.table.stream_event'). ' WHERE
               event_type & ?d AND initiator IN (?a)';
        $aParams = array($iEventTypes, $aUsesrList);
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

    public function addEvent($oUserId, $iEventType, $iTargetId)
    {
        $sql = 'INSERT INTO ' . Config::Get('db.table.stream_event'). ' SET
                event_type = ?d, target_id = ?d, initiator = ?d';
        $this->oDb->query($sql, $iEventType, $iTargetId, $oUserId);
    }

    public function deleteEvent($oUser, $iEventType, $iTargetId)
    {
        $sql = 'DELETE FROM' . Config::Get('db.table.stream_event'). ' WHERE
                event_type = ?d AND target_id = ?d AND initiator = ?d';
        $this->oDb->query($sql, $iEventType, $iTargetId, $oUser->getId());
    }

    public function getUserSubscribes($iUserId)
    {
        $sql = 'SELECT target_user_id FROM ' . Config::Get('db.table.stream_subscribe') . ' WHERE user_id = ?d';
        return $this->oDb->selectCol($sql, $iUserId);
    }

    public function getUserConfig($iUserId)
    {
        $this->initUserConfig($iUserId);
        $sql = 'SELECT * FROM ' . Config::Get('db.table.stream_config') . ' WHERE user_id = ?d';
        $ret = $this->oDb->selectRow($sql, $iUserId);
        return $ret;
    }

    public function switchUserEventType($iUserId, $iEventType)
    {
        $sql = 'UPDATE ' . Config::Get('db.table.stream_config') . ' SET
                event_types = event_types ^ ?d
                WHERE user_id = ?d';
        $this->oDb->query($sql, $iEventType, $iUserId);
    }

    public function initUserConfig($iUserId)
    {
        $sql = 'SELECT * FROM ' . Config::Get('db.table.stream_config') . ' WHERE user_id = ?d';
        if (!$this->oDb->select($sql, $iUserId)) {
            $sql = 'INSERT INTO ' . Config::Get('db.table.stream_config') . ' SET user_id = ?d, event_types = ?d';
            $this->oDb->query($sql, $iUserId, ModuleStream::EVENT_ALL);
        }
    }
}