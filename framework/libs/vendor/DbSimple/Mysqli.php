<?php
/**
 * DbSimple_Mysqli: MySQLi database.
 * (C) Dk Lab, http://en.dklab.ru
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * See http://www.gnu.org/copyleft/lesser.html
 *
 * Placeholders are emulated because of logging purposes.
 *
 * @author Andrey Stavitsky
 *
 * @version 2.x $Id$
 */
require_once dirname(__FILE__).'/Generic.php';

/**
 * Database class for MySQL.
 */
class DbSimple_Mysqli extends DbSimple_Generic_Database {
    protected $link;
    protected  $isMySQLnd;

    public function DbSimple_Mysqli($dsn) {
        $p = DbSimple_Generic::parseDSN($dsn);
        $base = preg_replace('{^/}s', '', $dsn['path']);
        if (!class_exists('mysqli'))
            return $this->_setLastError('-1', 'mysqli extension is not loaded', 'mysqli');

        try {
            $this->link = mysqli_init();

            $this->link->options(MYSQLI_OPT_CONNECT_TIMEOUT,
                isset($dsn['timeout']) && $dsn['timeout'] ? $dsn['timeout'] : 0);

            $this->link->real_connect((isset($dsn['persist']) && $dsn['persist']) ? 'p:' . $dsn['host'] : $dsn['host'],
                $dsn['user'], isset($dsn['pass']) ? $dsn['pass'] : '', $base,
                empty($dsn['port']) ? NULL : $dsn['port'], NULL,
                (isset($dsn['compression']) && $dsn['compression'])
                    ? MYSQLI_CLIENT_COMPRESS : NULL);

            $this->link->set_charset((isset($dsn['enc']) ? $dsn['enc'] : 'UTF8'));

            $this->isMySQLnd = method_exists('mysqli_result', 'fetch_all');
        } catch (mysqli_sql_exception $e) {
            $this->_setLastError($e->getCode(), $e->getMessage(), 'new mysqli');
        }
    }

    public function _performGetPlaceholderIgnoreRe() {
        return '
			"   (?> [^"\\\\]+|\\\\"|\\\\)*    "   |
			\'  (?> [^\'\\\\]+|\\\\\'|\\\\)* \'   |
			`   (?> [^`]+ | ``)*              `   |   # backticks
			/\* .*?                          \*/      # comments
		';
    }

    public function _performEscape($s, $isIdent = false) {
        if (!$isIdent) {
            return "'" . $this->link->escape_string($s) . "'";
        } else {
            return "`" . str_replace('`', '``', $s) . "`";
        }
    }

    public function _performTransaction($parameters = null) {
        return $this->link->query('BEGIN');
    }

    public function _performCommit() {
        return $this->link->query('COMMIT');
    }

    public function _performRollback() {
        return $this->link->query('ROLLBACK');
    }

    public function _performQuery($queryMain) {
        $this->_lastQuery = $queryMain;

        $this->_expandPlaceholders($queryMain, false);

        $result = $this->link->query($queryMain[0]);

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

    public function _performTransformQuery(&$queryMain, $how) {
        // If we also need to calculate total number of found rows...
        switch ($how) {
            // Prepare total calculation (if possible)
            case 'CALC_TOTAL':
                $m = null;
                if (preg_match('/^(\s* SELECT)(.*)/six', $queryMain[0], $m))
                    $queryMain[0] = $m[1] . ' SQL_CALC_FOUND_ROWS' . $m[2];
                return true;

            // Perform total calculation.
            case 'GET_TOTAL':
                // Built-in calculation available?
                $queryMain = array('SELECT FOUND_ROWS()');
                return true;
        }
        return false;
    }

    public function _setDbError($obj, $q) {
        $info = $obj ? $obj : $this->link;
        return $this->_setLastError($info->errno, $info->error, $q);
    }

    function& _performNewBlob($blobid = null) {
        $obj = new DbSimple_Mysql_Blob($this, $blobid);
        return $obj;
    }

    function _performGetBlobFieldNames($result) {
        $blobFields = array();
        while ($finfo = $result->link->fetch_field()) {
            $type = $finfo->type;
            if (strpos($type, "BLOB") !== false) $blobFields[] = $finfo->name;
        }
        return $blobFields;
    }

    public function _performFetch($result) {
        if ($this->isMySQLnd)
            return $result;

        $row = $result->fetch_assoc();

        if ($this->link->error)
            return $this->_setDbError($this->link, $this->_lastQuery);

        if ($row === false) {
            $result->close();
            return null;
        }

        return $row;
    }
}

class DbSimple_Mysql_Blob extends DbSimple_Generic_Blob {
    // MySQL does not support separate BLOB fetching.
    var $blobdata = null;
    var $curSeek = 0;

    function DbSimple_Mysql_Blob(&$database, $blobdata = null) {
        $this->blobdata = $blobdata;
        $this->curSeek = 0;
    }

    function read($len) {
        $p = $this->curSeek;
        $this->curSeek = min($this->curSeek + $len, strlen($this->blobdata));
        return substr($this->blobdata, $this->curSeek, $len);
    }

    function write($data) {
        $this->blobdata .= $data;
    }

    function close() {
        return $this->blobdata;
    }

    function length() {
        return strlen($this->blobdata);
    }
}

?>