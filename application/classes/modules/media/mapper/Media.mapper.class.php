<?php
/**
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
 * @package modules.media
 * @since 1.0
 */
class ModuleMedia_MapperMedia extends Mapper {

	public function GetMediaByTarget($sTargetType,$iTargetId,$iUserId=null) {
		$sql = "SELECT
                    m.*
                FROM ".Config::Get('db.table.media_target')." AS t
                	 JOIN ".Config::Get('db.table.media')." as m on ( m.id=t.media_id { and m.user_id = ?d } )
                WHERE
                	t.target_id = ?d
                	AND
                	t.target_type = ?
                ORDER BY
                    m.id desc
				limit 0,500";

		$aResult = array();
		if ($aRows = $this->oDb->select($sql,$iUserId ? $iUserId : DBSIMPLE_SKIP,$iTargetId, $sTargetType)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('ModuleMedia_EntityMedia',$aRow);
			}
		}
		return $aResult;
	}

	public function GetMediaByTargetTmp($sTargetTmp,$iUserId=null) {
		$sql = "SELECT
                    m.*
                FROM ".Config::Get('db.table.media_target')." AS t
                	 JOIN ".Config::Get('db.table.media')." as m on ( m.id=t.media_id { and m.user_id = ?d } )
                WHERE
                	t.target_tmp = ?
                ORDER BY
                    m.id desc
				limit 0,500";

		$aResult = array();
		if ($aRows = $this->oDb->select($sql,$iUserId ? $iUserId : DBSIMPLE_SKIP,$sTargetTmp)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('ModuleMedia_EntityMedia',$aRow);
			}
		}
		return $aResult;
	}
}