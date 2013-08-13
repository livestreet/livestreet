<?php
/**
 * DbSimple_Mysql: MySQL database.
 * (C) Dk Lab, http://en.dklab.ru
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * See http://www.gnu.org/copyleft/lesser.html
 *
 * Placeholders end blobs are emulated.
 *
 * @author Dmitry Koterov, http://forum.dklab.ru/users/DmitryKoterov/
 * @author Konstantin Zhinko, http://forum.dklab.ru/users/KonstantinGinkoTit/
 * 
 * @version 2.x $Id: Mysql.php 163 2007-01-10 09:47:49Z dk $
 */
require_once dirname(__FILE__).'/Mysqli.php';


/**
 * Database class for MySQL.
 */
class DbSimple_MysqliWrapper extends DbSimple_Mysqli
{

    public function _performQuery($queryMain) {

        $this->_lastQuery = $queryMain;

        $this->_expandPlaceholders($queryMain, false);

        $oProfiler=ProfilerSimple::getInstance();
        $iTimeId=$oProfiler->Start('query',$queryMain[0]);

        $result = $this->link->query($queryMain[0]);

        $oProfiler->Stop($iTimeId);

        if (!$result)
            return $this->_setDbError($this->link, $queryMain[0]);

        if ($this->link->errno != 0)
            return $this->_setDbError($this->link, $queryMain[0]);

        if (preg_match('/^\s* INSERT \s+/six', $queryMain[0]))
            return $this->link->insert_id;

        if ($this->link->field_count == 0)
            return $this->link->affected_rows;

        if ($this->isMySQLnd) {
            $res = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
        } else {
            $res = $result;
        }

        return $res;
    }
}
?>