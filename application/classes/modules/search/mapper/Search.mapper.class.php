<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Маппер для работы с БД
 *
 * @package application.modules.search
 * @since 2.0
 */
class ModuleSearch_MapperSearch extends Mapper
{

    public function SearchTopics($sRegexp, &$iCount, $iCurrPage, $iPerPage)
    {
        $sql = "SELECT
                    DISTINCT t.topic_id,
                    CASE WHEN (LOWER(t.topic_title) REGEXP ?) THEN 1 ELSE 0 END +
                    CASE WHEN (LOWER(tc.topic_text) REGEXP ?) THEN 1 ELSE 0 END AS weight
                FROM " . Config::Get('db.table.topic') . " AS t
                    INNER JOIN " . Config::Get('db.table.topic_content') . " AS tc ON tc.topic_id=t.topic_id
                WHERE
                    (t.topic_publish=1) AND ((LOWER(t.topic_title) REGEXP ?) OR (LOWER(tc.topic_text) REGEXP ?))
                ORDER BY
                    weight DESC, t.topic_id DESC
                LIMIT ?d, ?d";
        $aResult = array();
        if ($aRows = $this->oDb->selectPage($iCount, $sql, $sRegexp, $sRegexp, $sRegexp, $sRegexp,
            ($iCurrPage - 1) * $iPerPage, $iPerPage)
        ) {
            foreach ($aRows as $aRow) {
                $aResult[] = $aRow['topic_id'];
            }
        }
        return $aResult;
    }

    public function SearchComments($sRegexp, &$iCount, $iCurrPage, $iPerPage, $sTargetType)
    {
        if (!is_array($sTargetType)) {
            $sTargetType = array($sTargetType);
        }
        $sql = "SELECT
                    DISTINCT c.comment_id
                FROM " . Config::Get('db.table.comment') . " AS c
                WHERE
                    (c.comment_delete=0 AND c.target_type IN ( ?a ) ) AND (LOWER(c.comment_text) REGEXP ?)
                ORDER BY
                    c.comment_id DESC
                LIMIT ?d, ?d";
        $aResult = array();
        if ($aRows = $this->oDb->selectPage($iCount, $sql, $sTargetType, $sRegexp, ($iCurrPage - 1) * $iPerPage,
            $iPerPage)
        ) {
            foreach ($aRows as $aRow) {
                $aResult[] = $aRow['comment_id'];
            }
        }
        return $aResult;
    }

}