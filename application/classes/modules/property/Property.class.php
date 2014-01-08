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
class ModuleProperty extends ModuleORM {
	/**
	 * Список возможных типов свойств/полей
	 */
	const PROPERTY_TYPE_INT='int';
	const PROPERTY_TYPE_FLOAT='float';
	const PROPERTY_TYPE_VARCHAR='varchar';
	const PROPERTY_TYPE_TEXT='text';
	const PROPERTY_TYPE_CHECKBOX='checkbox';
	const PROPERTY_TYPE_TAGS='tags';
	const PROPERTY_TYPE_VIDEO_LINK='video_link';
	const PROPERTY_TYPE_SELECT='select';

	protected $oMapper=null;
	/**
	 * Список доступных типов полей
	 *
	 * @var array
	 */
	protected $aPropertyTypes=array(
		self::PROPERTY_TYPE_INT,self::PROPERTY_TYPE_FLOAT,self::PROPERTY_TYPE_VARCHAR,self::PROPERTY_TYPE_TEXT,self::PROPERTY_TYPE_CHECKBOX,self::PROPERTY_TYPE_TAGS,self::PROPERTY_TYPE_VIDEO_LINK,self::PROPERTY_TYPE_SELECT
	);
	/**
	 * Список разрешенных типов
	 * На данный момент допустимы параметры entity=>ModuleTest_EntityTest - указывает на класс сущности
	 * name=>Статьи
	 *
	 * @var array
	 */
	protected $aTargetTypes=array(

	);

	public function Init() {
		parent::Init();
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	/**
	 * Возвращает список типов объектов
	 *
	 * @return array
	 */
	public function GetTargetTypes() {
		return $this->aTargetTypes;
	}
	/**
	 * Добавляет в разрешенные новый тип
	 *
	 * @param string $sTargetType	Тип
	 * @param array $aParams	Параметры
	 * @return bool
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
	 * @param string $sTargetType	Тип
	 * @return bool
	 */
	public function IsAllowTargetType($sTargetType) {
		return in_array($sTargetType,array_keys($this->aTargetTypes));
	}
	/**
	 * Возвращает парметры нужного типа
	 *
	 * @param string $sTargetType
	 *
	 * @return mixed
	 */
	public function GetTargetTypeParams($sTargetType) {
		if ($this->IsAllowTargetType($sTargetType)) {
			return $this->aTargetTypes[$sTargetType];
		}
	}
	/**
	 * Проверяет разрешен ли тип поля
	 *
	 * @param string $sType
	 *
	 * @return bool
	 */
	public function IsAllowPropertyType($sType) {
		return in_array($sType,$this->aPropertyTypes);
	}
	/**
	 * Для каждого из свойств получает значение
	 *
	 * @param array $aProperties	Список свойств
	 * @param string $sTargetType	Тип объекта
	 * @param int $iTargetId	ID объекта
	 *
	 * @return bool
	 */
	public function AttachValueForProperties($aProperties,$sTargetType,$iTargetId) {
		if (!$aProperties) {
			return false;
		}
		/**
		 * Формируем список ID свойств
		 */
		$aPropertyIds=array();
		foreach($aProperties as $oProperty) {
			$aPropertyIds[]=$oProperty->getId();
		}
		/**
		 * Получаем список значений
		 */
		$aValues=$this->Property_GetValueItemsByFilter(array('target_id'=>$iTargetId,'target_type'=>$sTargetType,'property_id in'=>$aPropertyIds,'#index-from'=>'property_id'));
		/**
		 * Аттачим значения к свойствам
		 */
		foreach($aProperties as $oProperty) {
			if (isset($aValues[$oProperty->getId()])) {
				$oProperty->setValue($aValues[$oProperty->getId()]);
			} else {
				$oProperty->setValue(Engine::GetEntity('ModuleProperty_EntityValue',array('property_id'=>$oProperty->getId(),'property_type'=>$oProperty->getType(),'target_type'=>$sTargetType,'target_id'=>$iTargetId)));
			}
			$oProperty->getValue()->setProperty($oProperty);
		}
		return true;
	}
	/**
	 * Сохраняет текущие значения свойств
	 *
	 * @param array $aProperties
	 * @param Entity|int $oTarget Объект сущности или ID сущности
	 */
	public function UpdatePropertiesValue($aProperties,$oTarget) {
		if ($aProperties) {
			foreach($aProperties as $oProperty) {
				$oValue=$oProperty->getValue();
				$oValue->setTargetId(is_object($oTarget) ? $oTarget->getId() : $oTarget);
				$oValue->setPropertyType($oProperty->getType());
				$oValue->Save();
			}
		}
	}
	/**
	 * Удаление всех свойств у конкретного объекта/сущности
	 *
	 * @param Entity $oTarget
	 */
	public function RemovePropertiesValue($oTarget) {
		$aProperties=$this->Property_GetPropertyItemsByFilter(array('target_type'=>$oTarget->getPropertyTargetType()));
		if ($aProperties) {
			$this->AttachValueForProperties($aProperties,$oTarget->getPropertyTargetType(),$oTarget->getId());
			foreach($aProperties as $oProperty) {
				$oValue=$oProperty->getValue();
				if ($oValue and $oValue->getId()) {
					$oValueType=$oValue->getValueTypeObject();
					/**
					 * Кастомное удаление
					 */
					$oValueType->removeValue();
					/**
					 * Удаляем основные данные
					 */
					$oValue->Delete();
				}
			}
		}
	}
	/**
	 * Валидирует значение свойств у объекта
	 *
	 * @param Entity $oTarget
	 *
	 * @return bool|string
	 */
	public function ValidateEntityPropertiesCheck($oTarget) {
		/**
		 * Пробуем получить свойства из реквеста
		 */
		$oTarget->setProperties($oTarget->getProperties() ? $oTarget->getProperties() : getRequest('property'));
		$aPropertiesValue=$oTarget->getProperties();
		$aPropertiesResult=array();
		/**
		 * Получаем весь список свойств у объекта
		 */
		$aPropertiesObject=$this->Property_GetPropertyItemsByFilter(array('target_type'=>$oTarget->getPropertyTargetType()));
		$this->Property_AttachValueForProperties($aPropertiesObject,$oTarget->getPropertyTargetType(),$oTarget->getId());
		foreach($aPropertiesObject as $oProperty) {
			$oValue=$oProperty->getValue();
			$sValue=isset($aPropertiesValue[$oProperty->getId()]) ? $aPropertiesValue[$oProperty->getId()] : null;
			/**
			 * Валидируем значение
			 */
			$oValueType=$oValue->getValueTypeObject();
			$oValueType->setValueForValidate($sValue);
			if (true===($sRes=$oValueType->validate())) {
				$oValueType->setValue($oValueType->getValueForValidate());
				$aPropertiesResult[]=$oProperty;
			} else {
				return $sRes ? $sRes : 'Неверное значение аттрибута: '.$oProperty->getTitle();
			}
		}
		$oTarget->setPropertiesObject($aPropertiesResult);
		return true;
	}
	/**
	 * Возвращает значение свойсва у объекта
	 *
	 * @param Entity $oTarget	Объект сущности
	 * @param int $sPropertyId	ID свойства
	 *
	 * @return null|mixed
	 */
	public function GetEntityPropertyValue($oTarget,$sPropertyId) {
		if ($oProperty=$this->GetEntityPropertyValueObject($oTarget,$sPropertyId)) {
			return $oProperty->getValue()->getValueForDisplay();
		}
		return null;
	}
	/**
	 * Возвращает объект свойства сущности
	 *
	 * @param Entity $oTarget	Объект сущности
	 * @param int $sPropertyId	ID свойства
	 *
	 * @return null|ModuleProperty_EntityProperty
	 */
	public function GetEntityProperty($oTarget,$sPropertyId) {
		if ($oProperty=$this->GetEntityPropertyValueObject($oTarget,$sPropertyId)) {
			return $oProperty;
		}
		return null;
	}
	/**
	 * Возвращает список свойств сущности
	 *
	 * @param Entity $oTarget Объект сущности
	 *
	 * @return array
	 */
	public function GetEntityPropertyList($oTarget) {
		if (!$oTarget->getPropertyIsLoadAll()) {
			$aProperties=$this->oMapper->GetPropertiesValueByTarget($oTarget->getPropertyTargetType(),$oTarget->getId());
			$this->AttachPropertiesForTarget($oTarget,$aProperties);
		}
		return $oTarget->_getDataOne('property_list');
	}
	/**
	 * Служебный метод для аттача свойст к сущности
	 *
	 * @param Entity $oTarget Объект сущности
	 * @param array $aProperties	Список свойств
	 */
	protected function AttachPropertiesForTarget($oTarget,$aProperties) {
		$oTarget->setPropertyList($aProperties);
		$oTarget->setPropertyIsLoadAll(true);
		$aMapperCode=array();
		foreach($aProperties as $oProperty) {
			$aMapperCode[$oProperty->getCode()]=$oProperty->getId();
		}
		$oTarget->setPropertyMapperCode($aMapperCode);
	}
	/**
	 * Возвращает объект свойства
	 *
	 * @param Entity $oTarget	Объект сущности
	 * @param array $sPropertyId 	ID свойства
	 *
	 * @return null
	 */
	public function GetEntityPropertyValueObject($oTarget,$sPropertyId) {
		if (!$oTarget->getPropertyIsLoadAll()) {
			/**
			 * Загружаем все свойства
			 */
			$aProperties=$this->oMapper->GetPropertiesValueByTarget($oTarget->getPropertyTargetType(),$oTarget->getId());
			$this->AttachPropertiesForTarget($oTarget,$aProperties);
		}

		if (!is_numeric($sPropertyId)) {
			$aMapperCode=$oTarget->getPropertyMapperCode();
			if (isset($aMapperCode[$sPropertyId])) {
				$sPropertyId=$aMapperCode[$sPropertyId];
			} else {
				return null;
			}
		}
		$aProperties=$oTarget->getPropertyList();
		if (isset($aProperties[$sPropertyId])) {
			return $aProperties[$sPropertyId];
		}
		return null;
	}
	/**
	 * Переопределяем метод для возможности цеплять свои кастомные данные при ORM запросах - свойства
	 *
	 * @param array $aEntitiesWork
	 * @param array $aFilter
	 * @param null|string  $sEntityFull
	 */
	public function RewriteGetItemsByFilter($aEntitiesWork,$aFilter=array(),$sEntityFull=null) {
		if (!$aEntitiesWork) {
			return;
		}
		$oEntityFirst=reset($aEntitiesWork);
		$sTargetType=$oEntityFirst->getPropertyTargetType();
		/**
		 * Проверяем зарегистрирован ли такой тип
		 */
		if (!$this->IsAllowTargetType($sTargetType)) {
			return;
		}
		/**
		 * Проверяем необходимость цеплять свойства
		 */
		if (isset($aFilter['#properties']) and $aFilter['#properties']) {
			$aEntitiesId=array();
			foreach($aEntitiesWork as $oEntity) {
				$aEntitiesId[]=$oEntity->getId();
			}
			/**
			 * Получаем все свойства со значениями для всех объектов
			 */
			$aResult=$this->oMapper->GetPropertiesValueByTargetArray($sTargetType,$aEntitiesId);
			if ($aResult) {
				/**
				 * Формируем список свойств и значений
				 */
				$aProperties=array();
				$aValues=array();
				foreach($aResult as $aRow) {
					$aPropertyData=array();
					$aValueData=array();
					foreach($aRow as $k=>$v) {
						if (strpos($k,'prop_')===0) {
							$aPropertyData[str_replace('prop_','',$k)]=$v;
						} else {
							$aValueData[$k]=$v;
						}
					}

					if (!isset($aProperties[$aRow['prop_id']])) {
						$oProperty=Engine::GetEntity('ModuleProperty_EntityProperty',$aPropertyData);
						$aProperties[$aRow['prop_id']]=$oProperty;
					}
					if ($aRow['target_id']) {
						$sKey=$aRow['property_id'].'_'.$aRow['target_id'];
						$aValues[$sKey]=Engine::GetEntity('ModuleProperty_EntityValue',$aValueData);
					}
				}
				/**
				 * Собираем данные
				 */
				foreach($aEntitiesWork as $oEntity) {
					$aPropertiesClone=array();
					foreach($aProperties as $oProperty) {
						$oPropertyNew=clone $oProperty;
						$sKey=$oProperty->getId().'_'.$oEntity->getId();
						if (isset($aValues[$sKey])) {
							$oValue=$aValues[$sKey];
						} else {
							$oValue=Engine::GetEntity('ModuleProperty_EntityValue',array('property_type'=>$oProperty->getType(),'property_id'=>$oProperty->getId(),'target_type'=>$oProperty->getTargetType(),'target_id'=>$oEntity->getId()));
						}
						$oPropertyNew->setValue($oValue);
						$oValue->setProperty($oPropertyNew);
						$aPropertiesClone[$oPropertyNew->getId()]=$oPropertyNew;
					}
					$this->AttachPropertiesForTarget($oEntity,$aPropertiesClone);
				}
			}
		}
	}
	/**
	 * Обработка фильтра ORM запросов
	 *
	 * @param array $aFilter
	 * @param array $sEntityFull
	 *
	 * @return array
	 */
	public function RewriteFilter($aFilter,$sEntityFull) {
		$oEntitySample=Engine::GetEntity($sEntityFull);

		if (!isset($aFilter['#join'])) {
			$aFilter['#join']=array();
		}
		$aPropFields=array();
		foreach ($aFilter as $k=>$v) {
			if (preg_match('@^#prop:(.+)$@i',$k,$aMatch)) {
				/**
				 * Сначала формируем список полей с операндами
				 */
				$aK=explode(' ',trim($aMatch[1]),2);
				$sPropCurrent=$aK[0];
				$sConditionCurrent=' = ';
				if (count($aK)>1) {
					$sConditionCurrent=strtolower($aK[1]);
				}
				$aPropFields[$sPropCurrent]=array('value'=>$v,'condition'=>$sConditionCurrent);
			}
		}
		/**
		 * Проверяем на наличие сортировки по полям
		 */
		$aOrders=array();
		if (isset($aFilter['#order'])) {
			if(!is_array($aFilter['#order'])) {
				$aFilter['#order'] = array($aFilter['#order']);
			}
			foreach ($aFilter['#order'] as $key=>$value) {
				$aKeys=explode(':',$key);
				if (count($aKeys)==2 and strtolower($aKeys[0])=='prop') {
					$aOrders[$aKeys[1]]=array('way'=>$value,'replace'=>$key);
				}
			}
		}
		/**
		 * Получаем данные по полям
		 */
		if ($aPropFields) {
			$sTargetType=$oEntitySample->getPropertyTargetType();
			$aProperties=$this->Property_GetPropertyItemsByFilter(array('code in'=>array_keys($aPropFields),'target_type'=>$sTargetType));
			$iPropNum=0;
			foreach($aProperties as $oProperty) {
				/**
				 * По каждому полю строим JOIN запрос
				 */
				$sCondition=$aPropFields[$oProperty->getCode()]['condition'];
				$bIsArray=in_array(strtolower($sCondition),array('in','not in')) ? true : false;
				if (in_array($oProperty->getType(),array(ModuleProperty::PROPERTY_TYPE_INT,ModuleProperty::PROPERTY_TYPE_CHECKBOX))) {
					$sFieldValue="value_int";
					$sConditionFull=$sCondition.($bIsArray ? ' (?a) ' : ' ?d ');
				} elseif ($oProperty->getType()==ModuleProperty::PROPERTY_TYPE_FLOAT) {
					$sFieldValue="value_float";
					$sConditionFull=$sCondition.($bIsArray ? ' (?a) ' : ' ?f ');
				} elseif (in_array($oProperty->getType(),array(ModuleProperty::PROPERTY_TYPE_VARCHAR,ModuleProperty::PROPERTY_TYPE_TAGS,ModuleProperty::PROPERTY_TYPE_VIDEO_LINK))) {
					$sFieldValue="value_varchar";
					$sConditionFull=$sCondition.($bIsArray ? ' (?a) ' : ' ? ');
				} elseif ($oProperty->getType()==ModuleProperty::PROPERTY_TYPE_TEXT) {
					$sFieldValue="value_text";
					$sConditionFull=$sCondition.($bIsArray ? ' (?a) ' : ' ? ');
				} else {
					$sFieldValue="value_varchar";
					$sConditionFull=$sCondition.($bIsArray ? ' (?a) ' : ' ? ');
				}
				$iPropNum++;
				$sJoin="JOIN prefix_property_value propv{$iPropNum} ON
					t.`{$oEntitySample->_getPrimaryKey()}` = propv{$iPropNum}.target_id and
					propv{$iPropNum}.target_type = '{$sTargetType}' and
					propv{$iPropNum}.property_id = {$oProperty->getId()} and
					propv{$iPropNum}.{$sFieldValue} {$sConditionFull}";
				$aFilter['#join'][$sJoin]=array($aPropFields[$oProperty->getCode()]['value']);
				/**
				 * Проверяем на сортировку по текущему полю
				 */
				if (isset($aOrders[$oProperty->getCode()])) {
					$aOrders[$oProperty->getCode()]['field']="propv{$iPropNum}.{$sFieldValue}";
				}
			}
		}
		/**
		 * Подменяем сортировку
		 */
		foreach($aOrders as $aItem) {
			if (isset($aFilter['#order'][$aItem['replace']])) {
				$aFilter['#order']=$this->ArrayReplaceKey($aFilter['#order'],$aItem['replace'],$aItem['field']);
			}
		}
		return $aFilter;
	}
	/**
	 * Служебный метод для замены ключа в массиве
	 *
	 * @param array $aArray
	 * @param string $sKeyOld
	 * @param string $sKeyNew
	 *
	 * @return array|bool
	 */
	protected function ArrayReplaceKey($aArray,$sKeyOld,$sKeyNew) {
		$aKeys = array_keys($aArray);
		if (false === $iIndex = array_search($sKeyOld, $aKeys)) {
			return false;
		}
		$aKeys[$iIndex] = $sKeyNew;
		return array_combine($aKeys,array_values($aArray));
	}
	/**
	 * Удаляет теги свойства у сущности
	 *
	 * @param string $sTargetType	Тип объекта сущности
	 * @param int $iTargetId	ID объекта сущности
	 * @param int $iPropertyId	ID свойства
	 *
	 * @return mixed
	 */
	public function RemoveValueTagsByTarget($sTargetType,$iTargetId,$iPropertyId) {
		// сбрасываем кеш
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('ModuleProperty_EntityValueTag_delete'));
		return $this->oMapper->RemoveValueTagsByTarget($sTargetType,$iTargetId,$iPropertyId);
	}
	/**
	 * Удаляет значения типа select
	 *
	 * @param string $sTargetType	Тип объекта сущности
	 * @param int $iTargetId	ID объекта сущности
	 * @param int $iPropertyId	ID свойства
	 *
	 * @return mixed
	 */
	public function RemoveValueSelectsByTarget($sTargetType,$iTargetId,$iPropertyId) {
		// сбрасываем кеш
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('ModuleProperty_EntityValueSelect_delete'));
		return $this->oMapper->RemoveValueSelectsByTarget($sTargetType,$iTargetId,$iPropertyId);
	}
	/**
	 * Возвращает список тегов/знаяений свойства. Используется для авкомплиттера тегов.
	 *
	 * @param string $sTag
	 * @param int $iPropertyId
	 * @param int $iLimit
	 *
	 * @return mixed
	 */
	public function GetPropertyTagsByLike($sTag,$iPropertyId,$iLimit) {
		return $this->oMapper->GetPropertyTagsByLike($sTag,$iPropertyId,$iLimit);
	}
	/**
	 * Возвращет список группированных тегов с их количеством для необходимого свойства
	 *
	 * @param int $iPropertyId
	 * @param int $iLimit
	 *
	 * @return mixed
	 */
	public function GetPropertyTagsGroup($iPropertyId,$iLimit) {
		return $this->oMapper->GetPropertyTagsGroup($iPropertyId,$iLimit);
	}
	/**
	 * Формирует и возвращает облако тегов необходимого свойства
	 *
	 * @param int $iPropertyId
	 * @param int $iLimit
	 *
	 * @return mixed
	 */
	public function GetPropertyTagsCloud($iPropertyId,$iLimit) {
		$aTags=$this->Property_GetPropertyTagsGroup($iPropertyId,$iLimit);
		if ($aTags) {
			$this->Tools_MakeCloud($aTags);
		}
		return $aTags;
	}
	/**
	 * Список ID сущностей по тегу конкретного свойства
	 *
	 * @param int $iPropertyId
	 * @param string $sTag
	 * @param int $iCurrPage
	 * @param int $iPerPage
	 *
	 * @return array
	 */
	public function GetTargetsByTag($iPropertyId,$sTag,$iCurrPage,$iPerPage) {
		return array('collection'=>$this->oMapper->GetTargetsByTag($iPropertyId,$sTag,$iCount,$iCurrPage,$iPerPage),'count'=>$iCount);
	}
}