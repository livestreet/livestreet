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
 * @package modules.rbac
 * @since 1.0
 */
class ModuleRbac_MapperRbac extends Mapper {


	public function GetRoleWithPermissions() {
		$sql = "SELECT
					r.role_id,
					p.code,
					p.title,
					p.msg_error
				FROM
					".Config::Get('db.table.rbac_role_permission')." as r
					LEFT JOIN ".Config::Get('db.table.rbac_permission')." as p ON r.permission_id=p.id
				WHERE
					p.state = ?d ; ";
		if ($aRows=$this->oDb->select($sql,ModuleRbac::PERMISSION_STATE_ACTIVE)) {
			return $aRows;
		}
		return null;
	}
}