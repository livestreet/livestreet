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
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_MapperProperty extends Mapper
{

    public function GetPropertiesValueByTarget($sTargetType, $iTargetId)
    {
        $sql = "SELECT
                    v.*,
                    p.id  as prop_id,
                    p.target_type   as prop_target_type,
                    p.type   as prop_type ,
                    p.code  as prop_code,
                    p.title  as prop_title,
                    p.date_create  as prop_date_create,
                    p.sort  as prop_sort,
                    p.params  as prop_params
                FROM " . Config::Get('db.table.property') . " AS p
                	 LEFT JOIN " . Config::Get('db.table.property_value') . " as v on ( v.property_id=p.id and v.target_id = ?d )
                WHERE
                	p.target_type = ?
                ORDER BY
                    p.sort desc
				limit 0,100";

        $aResult = array();
        if ($aRows = $this->oDb->select($sql, $iTargetId, $sTargetType)) {
            foreach ($aRows as $aRow) {
                $aProperty = array();
                $aValue = array();
                foreach ($aRow as $k => $v) {
                    if (strpos($k, 'prop_') === 0) {
                        $aProperty[str_replace('prop_', '', $k)] = $v;
                    } else {
                        $aValue[$k] = $v;
                    }
                }
                $oProperty = Engine::GetEntity('ModuleProperty_EntityProperty', $aProperty);
                /**
                 * На случай, если нет еще значения свойства в БД
                 */
                $aValue['property_id'] = $oProperty->getId();
                $aValue['property_type'] = $oProperty->getType();
                $aValue['target_type'] = $sTargetType;
                $aValue['target_id'] = $iTargetId;
                $oProperty->setValue(Engine::GetEntity('ModuleProperty_EntityValue', $aValue));
                $aResult[$oProperty->getId()] = $oProperty;
            }
        }
        return $aResult;
    }

    public function GetPropertiesValueByTargetArray($sTargetType, $aTargetId)
    {
        if (!is_array($aTargetId)) {
            $aTargetId = array($aTargetId);
        }
        if (!$aTargetId) {
            return array();
        }
        $sql = "SELECT
                    v.*,
                    p.id  as prop_id,
                    p.target_type   as prop_target_type,
                    p.type   as prop_type ,
                    p.code  as prop_code,
                    p.title  as prop_title,
                    p.date_create  as prop_date_create,
                    p.sort  as prop_sort,
                    p.params  as prop_params
                FROM " . Config::Get('db.table.property') . " AS p
                	 LEFT JOIN " . Config::Get('db.table.property_value') . " as v on ( v.property_id=p.id and v.target_id IN ( ?a ) )
                WHERE
                	p.target_type = ?
                ORDER BY
                    p.sort desc ";

        $aResult = array();
        if ($aRows = $this->oDb->select($sql, $aTargetId, $sTargetType)) {
            return $aRows;
        }
        return $aResult;
    }

    public function RemoveValueTagsByTarget($sTargetType, $iTargetId, $iPropertyId)
    {
        $sql = "DELETE
                FROM " . Config::Get('db.table.property_value_tag') . "
                WHERE
                	target_id = ?d
                	and
                	target_type = ?
                	and
                	property_id = ?d
                	";
        if ($this->oDb->query($sql, $iTargetId, $sTargetType, $iPropertyId) !== false) {
            return true;
        }
        return false;
    }

    public function RemoveValueSelectsByTarget($sTargetType, $iTargetId, $iPropertyId)
    {
        $sql = "DELETE
                FROM " . Config::Get('db.table.property_value_select') . "
                WHERE
                	target_id = ?d
                	and
                	target_type = ?
                	and
                	property_id = ?d
                	";
        if ($this->oDb->query($sql, $iTargetId, $sTargetType, $iPropertyId) !== false) {
            return true;
        }
        return false;
    }

    public function RemoveValueByPropertyId($iPropertyId)
    {
        $sql = "DELETE
                FROM " . Config::Get('db.table.property_value') . "
                WHERE
                	property_id = ?d
                	";
        if ($this->oDb->query($sql, $iPropertyId) !== false) {
            return true;
        }
        return false;
    }

    public function RemoveValueTagByPropertyId($iPropertyId)
    {
        $sql = "DELETE
                FROM " . Config::Get('db.table.property_value_tag') . "
                WHERE
                	property_id = ?d
                	";
        if ($this->oDb->query($sql, $iPropertyId) !== false) {
            return true;
        }
        return false;
    }

    public function RemoveValueSelectByPropertyId($iPropertyId)
    {
        $sql = "DELETE
                FROM " . Config::Get('db.table.property_value_select') . "
                WHERE
                	property_id = ?d
                	";
        if ($this->oDb->query($sql, $iPropertyId) !== false) {
            return true;
        }
        return false;
    }

    public function RemoveSelectByPropertyId($iPropertyId)
    {
        $sql = "DELETE
                FROM " . Config::Get('db.table.property_select') . "
                WHERE
                	property_id = ?d
                	";
        if ($this->oDb->query($sql, $iPropertyId) !== false) {
            return true;
        }
        return false;
    }

    public function GetPropertyTagsByLike($sTag, $iPropertyId, $iLimit)
    {
        $sTag = mb_strtolower($sTag, "UTF-8");
        $sql = "SELECT
				text
			FROM
				" . Config::Get('db.table.property_value_tag') . "
			WHERE
				property_id = ?d and text LIKE ?
			GROUP BY
				text
			LIMIT 0, ?d
				";
        $aReturn = array();
        if ($aRows = $this->oDb->select($sql, $iPropertyId, $sTag . '%', $iLimit)) {
            foreach ($aRows as $aRow) {
                $aReturn[] = Engine::GetEntity('ModuleProperty_EntityValueTag', $aRow);
            }
        }
        return $aReturn;
    }

    public function GetPropertyTagsGroup($iPropertyId, $iLimit)
    {
        $sql = "SELECT
			text,
			count(text)	as count
			FROM
				" . Config::Get('db.table.property_value_tag') . "
			WHERE
				1=1
				property_id = ?d
			GROUP BY
				text
			ORDER BY
				count desc
			LIMIT 0, ?d
				";
        $aReturn = array();
        $aReturnSort = array();
        if ($aRows = $this->oDb->select(
            $sql,
            $iPropertyId,
            $iLimit
        )
        ) {
            foreach ($aRows as $aRow) {
                $aReturn[mb_strtolower($aRow['text'], 'UTF-8')] = $aRow;
            }
            ksort($aReturn);
            foreach ($aReturn as $aRow) {
                $aReturnSort[] = Engine::GetEntity('ModuleProperty_EntityValueTag', $aRow);
            }
        }
        return $aReturnSort;
    }

    public function GetTargetsByTag($iPropertyId, $sTag, &$iCount, $iCurrPage, $iPerPage)
    {
        $sql = "
							SELECT
								target_id
							FROM
								" . Config::Get('db.table.property_value_tag') . "
							WHERE
								property_id  = ?d
								and
								text = ?
                            ORDER BY target_id DESC
                            LIMIT ?d, ?d ";

        $aReturn = array();
        if ($aRows = $this->oDb->selectPage($iCount, $sql, $iPropertyId, $sTag, ($iCurrPage - 1) * $iPerPage,
            $iPerPage)
        ) {
            foreach ($aRows as $aTopic) {
                $aReturn[] = $aTopic['target_id'];
            }
        }
        return $aReturn;
    }

    public function UpdatePropertyByTargetType($sTargetType, $sTargetTypeNew)
    {
        $sql = "UPDATE
                 " . Config::Get('db.table.property') . "
                SET target_type = ?
                WHERE
                	target_type = ?
                	";
        if ($this->oDb->query($sql, $sTargetTypeNew, $sTargetType) !== false) {
            return true;
        }
        return false;
    }

    public function UpdatePropertyTargetByTargetType($sTargetType, $sTargetTypeNew)
    {
        $sql = "UPDATE
                 " . Config::Get('db.table.property_target') . "
                SET type = ?
                WHERE
                	type = ?
                	";
        if ($this->oDb->query($sql, $sTargetTypeNew, $sTargetType) !== false) {
            return true;
        }
        return false;
    }

    public function UpdatePropertySelectByTargetType($sTargetType, $sTargetTypeNew)
    {
        $sql = "UPDATE
                 " . Config::Get('db.table.property_select') . "
                SET target_type = ?
                WHERE
                	target_type = ?
                	";
        if ($this->oDb->query($sql, $sTargetTypeNew, $sTargetType) !== false) {
            return true;
        }
        return false;
    }

    public function UpdatePropertyValueByTargetType($sTargetType, $sTargetTypeNew)
    {
        $sql = "UPDATE
                 " . Config::Get('db.table.property_value') . "
                SET target_type = ?
                WHERE
                	target_type = ?
                	";
        if ($this->oDb->query($sql, $sTargetTypeNew, $sTargetType) !== false) {
            return true;
        }
        return false;
    }

    public function UpdatePropertyValueSelectByTargetType($sTargetType, $sTargetTypeNew)
    {
        $sql = "UPDATE
                 " . Config::Get('db.table.property_value_select') . "
                SET target_type = ?
                WHERE
                	target_type = ?
                	";
        if ($this->oDb->query($sql, $sTargetTypeNew, $sTargetType) !== false) {
            return true;
        }
        return false;
    }

    public function UpdatePropertyValueTagByTargetType($sTargetType, $sTargetTypeNew)
    {
        $sql = "UPDATE
                 " . Config::Get('db.table.property_value_tag') . "
                SET target_type = ?
                WHERE
                	target_type = ?
                	";
        if ($this->oDb->query($sql, $sTargetTypeNew, $sTargetType) !== false) {
            return true;
        }
        return false;
    }
}