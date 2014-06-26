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
 * @package modules.category
 * @since 1.0
 */
class ModuleCategory_MapperCategory extends Mapper {

	public function GetCategoriesByType($sId) {
		$sql = "SELECT
					id,
					id as ARRAY_KEY,
					pid as PARENT_KEY
				FROM
					".Config::Get('db.table.category')."
				WHERE
					type_id = ?d
				ORDER by `order` desc;
					";
		if ($aRows=$this->oDb->select($sql,$sId)) {
			return $aRows;
		}
		return null;
	}
}