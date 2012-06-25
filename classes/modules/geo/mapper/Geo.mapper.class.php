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
 * Объект маппера для работы с БД
 *
 * @package modules.geo
 * @since 1.0
 */
class ModuleGeo_MapperGeo extends Mapper {
	/**
	 * Добавляет связь объекта с гео-объектом в БД
	 *
	 * @param ModuleGeo_EntityTarget $oTarget	Объект связи с владельцем
	 * @return ModuleGeo_EntityTarget|bool
	 */
	public function AddTarget($oTarget) {
		$sql = "INSERT INTO ".Config::Get('db.table.geo_target')." SET ?a ";
		if ($this->oDb->query($sql,$oTarget->_getData())) {
			return true;
		}
		return false;
	}
	/**
	 * Возвращает список связей по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @param int $iCount	Возвращает количество элементов
	 * @param int $iCurrPage	Номер страницы
	 * @param int $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetTargets($aFilter,&$iCount,$iCurrPage,$iPerPage) {
		if (isset($aFilter['target_id']) and !is_array($aFilter['target_id'])) {
			$aFilter['target_id']=array($aFilter['target_id']);
		}

		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.geo_target')."
				WHERE
					1 = 1
					{ AND geo_type = ? }
					{ AND geo_id = ?d }
					{ AND target_type = ? }
					{ AND target_id IN ( ?a ) }
					{ AND country_id = ?d }
					{ AND region_id = ?d }
					{ AND city_id = ?d }
				ORDER BY target_id DESC
				LIMIT ?d, ?d ;
					";
		$aResult=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,
										  isset($aFilter['geo_type']) ? $aFilter['geo_type'] : DBSIMPLE_SKIP,
										  isset($aFilter['geo_id']) ? $aFilter['geo_id'] : DBSIMPLE_SKIP,
										  isset($aFilter['target_type']) ? $aFilter['target_type'] : DBSIMPLE_SKIP,
										  (isset($aFilter['target_id']) and count($aFilter['target_id'])) ? $aFilter['target_id'] : DBSIMPLE_SKIP,
										  isset($aFilter['country_id']) ? $aFilter['country_id'] : DBSIMPLE_SKIP,
										  isset($aFilter['region_id']) ? $aFilter['region_id'] : DBSIMPLE_SKIP,
										  isset($aFilter['city_id']) ? $aFilter['city_id'] : DBSIMPLE_SKIP,

										  ($iCurrPage-1)*$iPerPage, $iPerPage
		)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('ModuleGeo_EntityTarget',$aRow);
			}
		}
		return $aResult;
	}
	/**
	 * Возвращает список стран сгруппированных по количеству использований в данном типе объектов
	 *
	 * @param string $sTargetType	Тип владельца
	 * @param int $iLimit	Количество элементов
	 * @return array
	 */
	public function GetGroupCountriesByTargetType($sTargetType,$iLimit) {
		$sql = "
			SELECT
				t.count,
				g.*
			FROM (
					SELECT
						count(*) as count,
						country_id
					FROM
						".Config::Get('db.table.geo_target')."
					WHERE target_type = ? and country_id IS NOT NULL
					GROUP BY country_id ORDER BY count DESC LIMIT 0, ?d
				) as t
				JOIN ".Config::Get('db.table.geo_country')." as g on t.country_id=g.id
			ORDER BY g.name_ru
		";
		$aResult=array();
		if ($aRows=$this->oDb->select($sql,$sTargetType,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('ModuleGeo_EntityCountry',$aRow);
			}
		}
		return $aResult;
	}
	/**
	 * Возвращает список городов сгруппированных по количеству использований в данном типе объектов
	 *
	 * @param string $sTargetType	Тип владельца
	 * @param int $iLimit	Количество элементов
	 * @return array
	 */
	public function GetGroupCitiesByTargetType($sTargetType,$iLimit) {
		$sql = "
			SELECT
				t.count,
				g.*
			FROM (
					SELECT
						count(*) as count,
						city_id
					FROM
						".Config::Get('db.table.geo_target')."
					WHERE target_type = ? and city_id IS NOT NULL
					GROUP BY city_id ORDER BY count DESC LIMIT 0, ?d
				) as t
				JOIN ".Config::Get('db.table.geo_city')." as g on t.city_id=g.id
			ORDER BY g.name_ru
		";
		$aResult=array();
		if ($aRows=$this->oDb->select($sql,$sTargetType,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('ModuleGeo_EntityCity',$aRow);
			}
		}
		return $aResult;
	}
	/**
	 * Удаляет связи по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @return bool|int
	 */
	public function DeleteTargets($aFilter) {
		if (!$aFilter) {
			return false;
		}
		$sql = "DELETE
				FROM
					".Config::Get('db.table.geo_target')."
				WHERE
					1 = 1
					{ AND geo_type = ? }
					{ AND geo_id = ?d }
					{ AND target_type = ? }
					{ AND target_id = ?d }
					{ AND country_id = ?d }
					{ AND region_id = ?d }
					{ AND city_id = ?d }
				";
		return $this->oDb->query($sql,
								 isset($aFilter['geo_type']) ? $aFilter['geo_type'] : DBSIMPLE_SKIP,
								 isset($aFilter['geo_id']) ? $aFilter['geo_id'] : DBSIMPLE_SKIP,
								 isset($aFilter['target_type']) ? $aFilter['target_type'] : DBSIMPLE_SKIP,
								 isset($aFilter['target_id']) ? $aFilter['target_id'] : DBSIMPLE_SKIP,
								 isset($aFilter['country_id']) ? $aFilter['country_id'] : DBSIMPLE_SKIP,
								 isset($aFilter['region_id']) ? $aFilter['region_id'] : DBSIMPLE_SKIP,
								 isset($aFilter['city_id']) ? $aFilter['city_id'] : DBSIMPLE_SKIP
		);
	}
	/**
	 * Возвращает список стран по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @param array $aOrder	Сортировка
	 * @param int $iCount	Возвращает количество элементов
	 * @param int $iCurrPage	Номер страницы
	 * @param int $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetCountries($aFilter,$aOrder,&$iCount,$iCurrPage,$iPerPage) {
		$aOrderAllow=array('id','name_ru','name_en','sort');
		$sOrder='';
		foreach ($aOrder as $key=>$value) {
			if (!in_array($key,$aOrderAllow)) {
				unset($aOrder[$key]);
			} elseif (in_array($value,array('asc','desc'))) {
				$sOrder.=" {$key} {$value},";
			}
		}
		$sOrder=trim($sOrder,',');
		if ($sOrder=='') {
			$sOrder=' id desc ';
		}

		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.geo_country')."
				WHERE
					1 = 1
					{ AND id = ?d }
					{ AND name_ru = ? }
					{ AND name_ru LIKE ? }
					{ AND name_en = ? }
					{ AND name_en LIKE ? }
					{ AND code = ? }

				ORDER by {$sOrder}
				LIMIT ?d, ?d ;
					";
		$aResult=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,
										  isset($aFilter['id']) ? $aFilter['id'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_ru']) ? $aFilter['name_ru'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_ru_like']) ? $aFilter['name_ru_like'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_en']) ? $aFilter['name_en'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_en_like']) ? $aFilter['name_en_like'] : DBSIMPLE_SKIP,
										  isset($aFilter['code']) ? $aFilter['code'] : DBSIMPLE_SKIP,
										  ($iCurrPage-1)*$iPerPage, $iPerPage
		)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('ModuleGeo_EntityCountry',$aRow);
			}
		}
		return $aResult;
	}
	/**
	 * Возвращает список стран по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @param array $aOrder	Сортировка
	 * @param int $iCount	Возвращает количество элементов
	 * @param int $iCurrPage	Номер страницы
	 * @param int $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetRegions($aFilter,$aOrder,&$iCount,$iCurrPage,$iPerPage) {
		$aOrderAllow=array('id','name_ru','name_en','sort','country_id');
		$sOrder='';
		foreach ($aOrder as $key=>$value) {
			if (!in_array($key,$aOrderAllow)) {
				unset($aOrder[$key]);
			} elseif (in_array($value,array('asc','desc'))) {
				$sOrder.=" {$key} {$value},";
			}
		}
		$sOrder=trim($sOrder,',');
		if ($sOrder=='') {
			$sOrder=' id desc ';
		}

		if (isset($aFilter['country_id']) and !is_array($aFilter['country_id'])) {
			$aFilter['country_id']=array($aFilter['country_id']);
		}

		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.geo_region')."
				WHERE
					1 = 1
					{ AND id = ?d }
					{ AND name_ru = ? }
					{ AND name_ru LIKE ? }
					{ AND name_en = ? }
					{ AND name_en LIKE ? }
					{ AND country_id IN ( ?a ) }

				ORDER by {$sOrder}
				LIMIT ?d, ?d ;
					";
		$aResult=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,
										  isset($aFilter['id']) ? $aFilter['id'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_ru']) ? $aFilter['name_ru'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_ru_like']) ? $aFilter['name_ru_like'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_en']) ? $aFilter['name_en'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_en_like']) ? $aFilter['name_en_like'] : DBSIMPLE_SKIP,
										  (isset($aFilter['country_id']) && count($aFilter['country_id'])) ? $aFilter['country_id'] : DBSIMPLE_SKIP,
										  ($iCurrPage-1)*$iPerPage, $iPerPage
		)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('ModuleGeo_EntityRegion',$aRow);
			}
		}
		return $aResult;
	}
	/**
	 * Возвращает список стран по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @param array $aOrder	Сортировка
	 * @param int $iCount	Возвращает количество элементов
	 * @param int $iCurrPage	Номер страницы
	 * @param int $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetCities($aFilter,$aOrder,&$iCount,$iCurrPage,$iPerPage) {
		$aOrderAllow=array('id','name_ru','name_en','sort','country_id','region_id');
		$sOrder='';
		foreach ($aOrder as $key=>$value) {
			if (!in_array($key,$aOrderAllow)) {
				unset($aOrder[$key]);
			} elseif (in_array($value,array('asc','desc'))) {
				$sOrder.=" {$key} {$value},";
			}
		}
		$sOrder=trim($sOrder,',');
		if ($sOrder=='') {
			$sOrder=' id desc ';
		}

		if (isset($aFilter['country_id']) and !is_array($aFilter['country_id'])) {
			$aFilter['country_id']=array($aFilter['country_id']);
		}
		if (isset($aFilter['region_id']) and !is_array($aFilter['region_id'])) {
			$aFilter['region_id']=array($aFilter['region_id']);
		}

		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.geo_city')."
				WHERE
					1 = 1
					{ AND id = ?d }
					{ AND name_ru = ? }
					{ AND name_ru LIKE ? }
					{ AND name_en = ? }
					{ AND name_en LIKE ? }
					{ AND country_id IN ( ?a ) }
					{ AND region_id IN ( ?a ) }

				ORDER by {$sOrder}
				LIMIT ?d, ?d ;
					";
		$aResult=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,
										  isset($aFilter['id']) ? $aFilter['id'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_ru']) ? $aFilter['name_ru'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_ru_like']) ? $aFilter['name_ru_like'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_en']) ? $aFilter['name_en'] : DBSIMPLE_SKIP,
										  isset($aFilter['name_en_like']) ? $aFilter['name_en_like'] : DBSIMPLE_SKIP,
										  (isset($aFilter['country_id']) && count($aFilter['country_id'])) ? $aFilter['country_id'] : DBSIMPLE_SKIP,
										  (isset($aFilter['region_id']) && count($aFilter['region_id'])) ? $aFilter['region_id'] : DBSIMPLE_SKIP,
										  ($iCurrPage-1)*$iPerPage, $iPerPage
		)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('ModuleGeo_EntityCity',$aRow);
			}
		}
		return $aResult;
	}
}
?>