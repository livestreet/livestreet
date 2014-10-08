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
 * @package application.modules.media
 * @since 2.0
 */
class ModuleMedia_MapperMedia extends Mapper
{

    public function GetMediaByTarget($sTargetType, $iTargetId, $iUserId = null)
    {
        $sFieldsJoinReturn = $this->GetFieldsRelationTarget();
        $sql = "SELECT
					{$sFieldsJoinReturn},
                    m.*
                FROM " . Config::Get('db.table.media_target') . " AS t
                	 JOIN " . Config::Get('db.table.media') . " as m on ( m.id=t.media_id { and m.user_id = ?d } )
                WHERE
                	t.target_id = ?d
                	AND
                	t.target_type = ?
                ORDER BY
                    m.id desc
				limit 0,500";

        $aResult = array();
        if ($aRows = $this->oDb->select($sql, $iUserId ? $iUserId : DBSIMPLE_SKIP, $iTargetId, $sTargetType)) {
            $aResult = $this->PrepareResultTarget($aRows);
        }
        return $aResult;
    }

    public function GetMediaByTargetTmp($sTargetTmp, $iUserId = null)
    {
        $sFieldsJoinReturn = $this->GetFieldsRelationTarget();
        $sql = "SELECT
					{$sFieldsJoinReturn},
                    m.*
                FROM " . Config::Get('db.table.media_target') . " AS t
                	 JOIN " . Config::Get('db.table.media') . " as m on ( m.id=t.media_id { and m.user_id = ?d } )
                WHERE
                	t.target_tmp = ?
                ORDER BY
                    m.id desc
				limit 0,500";

        $aResult = array();
        if ($aRows = $this->oDb->select($sql, $iUserId ? $iUserId : DBSIMPLE_SKIP, $sTargetTmp)) {
            $aResult = $this->PrepareResultTarget($aRows);
        }
        return $aResult;
    }

    public function RemoveTargetByTypeAndId($sTargetType, $iTargetId)
    {
        $sql = "DELETE
                FROM " . Config::Get('db.table.media_target') . "
                WHERE
                	target_id = ?d
                	AND
                	target_type = ?
                	";
        if ($this->oDb->query($sql, $iTargetId, $sTargetType) !== false) {
            return true;
        }
        return false;
    }

    protected function GetFieldsRelationTarget()
    {
        $oEntityJoinSample = Engine::GetEntity('ModuleMedia_EntityTarget');
        /**
         * Формируем список полей для возврата у таблице связей
         */
        $aFieldsJoinReturn = $oEntityJoinSample->_getFields();
        foreach ($aFieldsJoinReturn as $k => $sField) {
            if (!is_numeric($k)) {
                // Удаляем служебные (примари) поля
                unset($aFieldsJoinReturn[$k]);
                continue;
            }
            $aFieldsJoinReturn[$k] = "t.`{$sField}` as t_join_{$sField}";
        }
        $sFieldsJoinReturn = join(', ', $aFieldsJoinReturn);
        return $sFieldsJoinReturn;
    }

    protected function PrepareResultTarget($aRows)
    {
        $aResult = array();
        foreach ($aRows as $aRow) {
            $aData = array();
            $aDataRelation = array();
            foreach ($aRow as $k => $v) {
                if (strpos($k, 't_join_') === 0) {
                    $aDataRelation[str_replace('t_join_', '', $k)] = $v;
                } else {
                    $aData[$k] = $v;
                }
            }
            $aData['_relation_entity'] = Engine::GetEntity('ModuleMedia_EntityTarget', $aDataRelation);
            $oEntity = Engine::GetEntity('ModuleMedia_EntityMedia', $aData);
            $oEntity->_SetIsNew(false);
            $aResult[] = $oEntity;
        }
        return $aResult;
    }
}