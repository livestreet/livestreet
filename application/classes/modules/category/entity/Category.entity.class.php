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
class ModuleCategory_EntityCategory extends EntityORM {

	protected $aRelations=array(
		'type' => array(self::RELATION_TYPE_BELONGS_TO,'ModuleCategory_EntityType','type_id'),
	);

	/**
	 * Выполняется перед сохранением
	 *
	 * @return bool
	 */
	protected function beforeSave() {
		if ($bResult=parent::beforeSave()) {
			if ($this->_isNew()) {
				$this->setDateCreate(date("Y-m-d H:i:s"));
			}
		}
		return $bResult;
	}
	/**
	 * Возвращает URL категории
	 * Этот метод можно переопределить из плагина и возвращать свой URL для нужного типа категорий
	 *
	 * @return string
	 */
	public function getWebUrl() {
		return Router::GetPath('category').$this->getUrlFull().'/';
	}
	/**
	 * Возвращает объект типа категории с использованием кеширования на время сессии
	 *
	 * @return ModuleCategory_EntityType
	 */
	public function getTypeByCacheLife() {
		$sKey='category_type_'.(string)$this->getTypeId();
		if (false===($oType=$this->Cache_GetLife($sKey))) {
			$oType=$this->getType();
			$this->Cache_SetLife($oType,$sKey);
		}
		return $oType;
	}
}