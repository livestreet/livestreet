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

class ModuleMedia_EntityTarget extends EntityORM {

	protected $aValidateRules=array(

	);

	protected $aRelations=array(
		'media' => array(self::RELATION_TYPE_BELONGS_TO,'ModuleMedia_EntityMedia','media_id'),
	);

	protected function beforeSave() {
		if ($this->_isNew()) {
			$this->setDateAdd(date("Y-m-d H:i:s"));
		}
		return true;
	}
}