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
 * Модуль Geo - привязка объектов к географии (страна/регион/город)
 * Терминология:
 *		объект - который привязываем к гео-объекту
 * 		гео-объект - географический объект(страна/регион/город)
 */
class ModuleGeo extends Module {
			
	protected $oMapper;
	protected $oUserCurrent;
	/**
	 * Список доступных типов объектов
	 * На данный момент доступен параметр allow_multi=>1 - указывает на возможность создавать несколько связей для одного объекта
	 *
	 * @var array
	 */
	protected $aTargetTypes=array(
		'user'=>array(),
	);
	/**
	 * Список доступных типов гео-объектов
	 *
	 * @var array
	 */
	protected $aGeoTypes=array(
		'country',
		'region',
		'city',
	);
			
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapper=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}

	/**
	 * Возвращает список типов объектов для облаты
	 */
	public function GetTargetTypes() {
		return $this->aTargetTypes;
	}

	/**
	 * Добавляет в разрешенные новый тип
	 * @param unknown_type $sTargetType
	 */
	public function AddTargetType($sTargetType,$aParams=array()) {
		if (!array_key_exists($sTargetType,$this->aTargetTypes)) {
			$this->aTargetTypes[$sTargetType]=$aParams;
			return true;
		}
		return false;
	}

	/**
	 * Проверяет разрешен ли данный тип
	 *
	 * @param $sTargetType
	 * @return bool
	 */
	public function IsAllowTargetType($sTargetType) {
		return in_array($sTargetType,array_keys($this->aTargetTypes));
	}

	/**
	 * Проверяет разрешен ли данный гео-тип
	 *
	 * @param $sTargetType
	 * @return bool
	 */
	public function IsAllowGeoType($sGeoType) {
		return in_array($sGeoType,$this->aGeoTypes);
	}

	/**
	 * Проверка объекта
	 *
	 * @param string $sTargetType
	 * @param int $iTargetId
	 */
	public function CheckTarget($sTargetType,$iTargetId) {
		if (!$this->IsAllowTargetType($sTargetType)) {
			return false;
		}
		$sMethod = 'CheckTarget'.func_camelize($sTargetType);
		if (method_exists($this,$sMethod)) {
			return $this->$sMethod($iTargetId);
		}
		return false;
	}

	/**
	 * Проверка на возможность нескольких связей
	 *
	 * @param $sTargetType
	 * @return bool
	 */
	public function IsAllowTargetMulti($sTargetType) {
		if ($this->IsAllowTargetType($sTargetType)) {
			if (isset($this->aTargetTypes[$sTargetType]['allow_multi']) and $this->aTargetTypes[$sTargetType]['allow_multi']) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Добавляет связь объекта с гео-объектом в БД
	 *
	 * @param $oTarget
	 * @return ModuleGeo_EntityTarget | bool
	 */
	public function AddTarget($oTarget) {
		if ($this->oMapper->AddTarget($oTarget)) {
			return $oTarget;
		}
		return false;
	}

	/**
	 * Создание связи
	 *
	 * @param $oGeoObject
	 * @param $sTargetType
	 * @param $iTargetId
	 * @return bool|ModuleGeo_EntityTarget
	 */
	public function CreateTarget($oGeoObject,$sTargetType,$iTargetId) {
		/**
		 * Проверяем объект на валидность
		 */
		if (!$this->CheckTarget($sTargetType,$iTargetId)) {
			return false;
		}
		/**
		 * Проверяем есть ли уже у этого объекта другие связи
		 */
		$aTargets=$this->GetTargets(array('target_type'=>$sTargetType,'target_id'=>$iTargetId),1,1);
		if ($aTargets['count']) {
			if ($this->IsAllowTargetMulti($sTargetType)) {
				/**
				 * Разрешено несколько связей
				 * Проверяем есть ли уже связь с данным гео-объектом, если есть то возвращаем его
				 */
				$aTargetSelf=$this->GetTargets(array('target_type'=>$sTargetType,'target_id'=>$iTargetId,'geo_type'=>$oGeoObject->getType(),'geo_id'=>$oGeoObject->getId()),1,1);
				if (isset($aTargetSelf['collection'][0])) {
					return $aTargetSelf['collection'][0];
				}
			} else {
				/**
				 * Есть другие связи и несколько связей запрещено - удаляем имеющиеся связи
				 */
				$this->DeleteTargets(array('target_type'=>$sTargetType,'target_id'=>$iTargetId));
			}
		}
		/**
		 * Создаем связь
		 */
		$oTarget=Engine::GetEntity('ModuleGeo_EntityTarget');
		$oTarget->setGeoType($oGeoObject->getType());
		$oTarget->setGeoId($oGeoObject->getId());
		$oTarget->setTargetType($sTargetType);
		$oTarget->setTargetId($iTargetId);
		if ($oGeoObject->getType()=='city') {
			$oTarget->setCountryId($oGeoObject->getCountryId());
			$oTarget->setRegionId($oGeoObject->getRegionId());
			$oTarget->setCityId($oGeoObject->getId());
		} elseif ($oGeoObject->getType()=='region') {
			$oTarget->setCountryId($oGeoObject->getCountryId());
			$oTarget->setRegionId($oGeoObject->getId());
		} elseif ($oGeoObject->getType()=='country') {
			$oTarget->setCountryId($oGeoObject->getId());
		}
		return $this->AddTarget($oTarget);
	}

	/**
	 * Возвращает список связей по фильтру
	 *
	 * @param $aFilter
	 * @param $iCurrPage
	 * @param $iPerPage
	 * @return array
	 */
	public function GetTargets($aFilter,$iCurrPage,$iPerPage) {
		return array('collection'=>$this->oMapper->GetTargets($aFilter,$iCount,$iCurrPage,$iPerPage),'count'=>$iCount);
	}

	/**
	 * Возвращает первый объект связи по объекту
	 *
	 * @param $sTargetType
	 * @param $iTargetId
	 * @return null
	 */
	public function GetTargetByTarget($sTargetType,$iTargetId) {
		$aTargets=$this->GetTargets(array('target_type'=>$sTargetType,'target_id'=>$iTargetId),1,1);
		if (isset($aTargets['collection'][0])) {
			return $aTargets['collection'][0];
		}
		return null;
	}
	/**
	 * Удаляет связи по фильтру
	 *
	 * @param $aFilter
	 * @return mixed
	 */
	public function DeleteTargets($aFilter) {
		return $this->oMapper->DeleteTargets($aFilter);
	}

	/**
	 * Удаление всех связей объекта
	 *
	 * @param $sTargetType
	 * @param $iTargetId
	 * @return mixed
	 */
	public function DeleteTargetsByTarget($sTargetType,$iTargetId) {
		return $this->DeleteTargets(array('target_type'=>$sTargetType,'target_id'=>$iTargetId));
	}

	/**
	 * Возвращает список стран по фильтру
	 *
	 * @param $aFilter
	 * @param $aOrder
	 * @param $iCurrPage
	 * @param $iPerPage
	 * @return array
	 */
	public function GetCountries($aFilter,$aOrder,$iCurrPage,$iPerPage) {
		return array('collection'=>$this->oMapper->GetCountries($aFilter,$aOrder,$iCount,$iCurrPage,$iPerPage),'count'=>$iCount);
	}

	/**
	 * Возвращает список регионов по фильтру
	 *
	 * @param $aFilter
	 * @param $aOrder
	 * @param $iCurrPage
	 * @param $iPerPage
	 * @return array
	 */
	public function GetRegions($aFilter,$aOrder,$iCurrPage,$iPerPage) {
		return array('collection'=>$this->oMapper->GetRegions($aFilter,$aOrder,$iCount,$iCurrPage,$iPerPage),'count'=>$iCount);
	}

	/**
	 * Возвращает список городов по фильтру
	 *
	 * @param $aFilter
	 * @param $aOrder
	 * @param $iCurrPage
	 * @param $iPerPage
	 * @return array
	 */
	public function GetCities($aFilter,$aOrder,$iCurrPage,$iPerPage) {
		return array('collection'=>$this->oMapper->GetCities($aFilter,$aOrder,$iCount,$iCurrPage,$iPerPage),'count'=>$iCount);
	}

	/**
	 * Возвращает страну по ID
	 *
	 * @param $iId
	 * @return ModuleGeo_EntityCountry
	 */
	public function GetCountryById($iId) {
		$aRes=$this->GetCountries(array('id'=>$iId),array(),1,1);
		if (isset($aRes['collection'][0])) {
			return $aRes['collection'][0];
		}
		return null;
	}

	/**
	 * Возвращает регион по ID
	 *
	 * @param $iId
	 * @return ModuleGeo_EntityRegion
	 */
	public function GetRegionById($iId) {
		$aRes=$this->GetRegions(array('id'=>$iId),array(),1,1);
		if (isset($aRes['collection'][0])) {
			return $aRes['collection'][0];
		}
		return null;
	}

	/**
	 * Возвращает регион по ID
	 *
	 * @param $iId
	 * @return ModuleGeo_EntityCity
	 */
	public function GetCityById($iId) {
		$aRes=$this->GetCities(array('id'=>$iId),array(),1,1);
		if (isset($aRes['collection'][0])) {
			return $aRes['collection'][0];
		}
		return null;
	}

	/**
	 * Возвращает гео-объект
	 *
	 * @param $sType
	 * @param $iId
	 */
	public function GetGeoObject($sType,$iId) {
		$sType=strtolower($sType);
		if (!$this->IsAllowGeoType($sType)) {
			return null;
		}
		switch($sType) {
			case 'country':
				return $this->GetCountryById($iId);
				break;
			case 'region':
				return $this->GetRegionById($iId);
				break;
			case 'city':
				return $this->GetCityById($iId);
				break;
			default:
				return null;
		}
	}

	/**
	 * Возвращает первый гео-объект для объекта
	 *
	 * @param $sTargetType
	 * @param $iTargetId
	 * @return ModuleGeo_EntityCity|ModuleGeo_EntityCountry|ModuleGeo_EntityRegion|null
	 */
	public function GetGeoObjectByTarget($sTargetType,$iTargetId) {
		$aTargets=$this->GetTargets(array('target_type'=>$sTargetType,'target_id'=>$iTargetId),1,1);
		if (isset($aTargets['collection'][0])) {
			$oTarget=$aTargets['collection'][0];
			return $this->GetGeoObject($oTarget->getGeoType(),$oTarget->getGeoId());
		}
		return null;
	}

	/**
	 * Возвращает список стран сгруппированных по количеству использований в данном типе объектов
	 *
	 * @param $sTargetType
	 * @param $iLimit
	 * @return mixed
	 */
	public function GetGroupCountriesByTargetType($sTargetType,$iLimit) {
		return $this->oMapper->GetGroupCountriesByTargetType($sTargetType,$iLimit);
	}

	/**
	 * Возвращает список городов сгруппированных по количеству использований в данном типе объектов
	 *
	 * @param $sTargetType
	 * @param $iLimit
	 * @return mixed
	 */
	public function GetGroupCitiesByTargetType($sTargetType,$iLimit) {
		return $this->oMapper->GetGroupCitiesByTargetType($sTargetType,$iLimit);
	}

	/**
	 * Проверка объекта с типом "user"
	 * Название метода формируется автоматически
	 *
	 * @param int $iTargetId
	 */
	public function CheckTargetUser($iTargetId) {
		if ($oUser=$this->User_GetUserById($iTargetId)) {
			return true;
		}
		return false;
	}

}
?>