<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Объект сущности гео-объекта
 *
 * @package modules.geo
 * @since 1.0
 */
class ModuleGeo_EntityGeo extends Entity {

	/**
	 * Возвращает имя гео-объекта в зависимости от языка
	 *
	 * @return string
	 */
	public function getName() {
		$sName='';
		$sLangDef=Config::get('lang.default');
		if ($sLangDef=='russian') {
			$sName=$this->getNameRu();
		} elseif ($sLangDef=='english') {
			$sName=$this->getNameEn();
		}

		$sLang=Config::get('lang.current');
		if ($sLang=='russian' and $this->getNameRu()) {
			$sName=$this->getNameRu();
		} elseif ($sLang=='english' and $this->getNameEn()) {
			$sName=$this->getNameEn();
		}
		return $sName;
	}
	/**
	 * Возвращает тип гео-объекта
	 *
	 * @return null|string
	 */
	public function getType() {
		if ($this instanceof ModuleGeo_EntityCity) {
			return 'city';
		} elseif ($this instanceof ModuleGeo_EntityRegion) {
			return 'region';
		} elseif ($this instanceof ModuleGeo_EntityCountry) {
			return 'country';
		}
		return null;
	}
	/**
	 * Возвращает гео-объект страны
	 *
	 * @return ModuleGeo_EntityGeo|null
	 */
	public function getCountry() {
		if ($this->getType()=='country') {
			return $this;
		}
		if ($oCountry=$this->_getDataOne('country')) {
			return $oCountry;
		}
		if ($this->getCountryId()) {
			$oCountry=$this->Geo_GetCountryById($this->getCountryId());
			return $this->_aData['country']=$oCountry;
		}
		return null;
	}
	/**
	 * Возвращает гео-объект региона
	 *
	 * @return ModuleGeo_EntityGeo|null
	 */
	public function getRegion() {
		if ($this->getType()=='region') {
			return $this;
		}
		if ($oRegion=$this->_getDataOne('region')) {
			return $oRegion;
		}
		if ($this->getRegionId()) {
			$oRegion=$this->Geo_GetRegionById($this->getRegionId());
			return $this->_aData['region']=$oRegion;
		}
		return null;
	}
	/**
	 * Возвращает гео-объект города
	 *
	 * @return ModuleGeo_EntityGeo|null
	 */
	public function getCity() {
		if ($this->getType()=='city') {
			return $this;
		}
		if ($oCity=$this->_getDataOne('city')) {
			return $oCity;
		}
		if ($this->getCityId()) {
			$oCity=$this->Geo_GetCityById($this->getCityId());
			return $this->_aData['city']=$oCity;
		}
		return null;
	}
}
?>