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
 * Модуль управления дополнительными полями
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty extends ModuleORM
{
    /**
     * Список возможных типов свойств/полей
     */
    const PROPERTY_TYPE_INT = 'int';
    const PROPERTY_TYPE_FLOAT = 'float';
    const PROPERTY_TYPE_VARCHAR = 'varchar';
    const PROPERTY_TYPE_TEXT = 'text';
    const PROPERTY_TYPE_CHECKBOX = 'checkbox';
    const PROPERTY_TYPE_TAGS = 'tags';
    const PROPERTY_TYPE_VIDEO_LINK = 'video_link';
    const PROPERTY_TYPE_SELECT = 'select';
    const PROPERTY_TYPE_DATE = 'date';
    const PROPERTY_TYPE_FILE = 'file';
    const PROPERTY_TYPE_IMAGE = 'image';
    /**
     * Список состояний типов объектов
     */
    const TARGET_STATE_ACTIVE = 1;
    const TARGET_STATE_NOT_ACTIVE = 2;
    const TARGET_STATE_REMOVE = 3;

    protected $oMapper = null;
    /**
     * Список доступных типов полей
     *
     * @var array
     */
    protected $aPropertyTypes = array(
        self::PROPERTY_TYPE_INT,
        self::PROPERTY_TYPE_FLOAT,
        self::PROPERTY_TYPE_VARCHAR,
        self::PROPERTY_TYPE_TEXT,
        self::PROPERTY_TYPE_CHECKBOX,
        self::PROPERTY_TYPE_TAGS,
        self::PROPERTY_TYPE_VIDEO_LINK,
        self::PROPERTY_TYPE_SELECT,
        self::PROPERTY_TYPE_DATE,
        self::PROPERTY_TYPE_FILE,
        self::PROPERTY_TYPE_IMAGE
    );
    /**
     * Список разрешенных типов
     * На данный момент допустимы параметры entity=>ModuleTest_EntityTest - указывает на класс сущности
     * name=>Статьи
     *
     * @var array
     */
    protected $aTargetTypes = array();

    public function Init()
    {
        parent::Init();
        $this->oMapper = Engine::GetMapper(__CLASS__);

        /**
         * Получаем типы из БД и активируем их
         */
        if ($aTargetItems = $this->GetTargetItemsByFilter(array('state' => self::TARGET_STATE_ACTIVE))) {
            foreach ($aTargetItems as $oTarget) {
                $this->Property_AddTargetType($oTarget->getType(), $oTarget->getParams());
            }
        }
    }

    /**
     * Возвращает список типов объектов
     *
     * @return array
     */
    public function GetTargetTypes()
    {
        return $this->aTargetTypes;
    }

    /**
     * Добавляет в разрешенные новый тип
     *
     * @param string $sTargetType Тип
     * @param array $aParams Параметры
     * @return bool
     */
    public function AddTargetType($sTargetType, $aParams = array())
    {
        if (!array_key_exists($sTargetType, $this->aTargetTypes)) {
            $this->aTargetTypes[$sTargetType] = $aParams;
            return true;
        }
        return false;
    }

    /**
     * Проверяет разрешен ли данный тип
     *
     * @param string $sTargetType Тип
     * @return bool
     */
    public function IsAllowTargetType($sTargetType)
    {
        return in_array($sTargetType, array_keys($this->aTargetTypes));
    }

    /**
     * Возвращает парметры нужного типа
     *
     * @param string $sTargetType
     *
     * @return mixed
     */
    public function GetTargetTypeParams($sTargetType)
    {
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
    public function IsAllowPropertyType($sType)
    {
        return in_array($sType, $this->aPropertyTypes);
    }

    /**
     * Для каждого из свойств получает значение
     *
     * @param array $aProperties Список свойств
     * @param string $sTargetType Тип объекта
     * @param int $iTargetId ID объекта
     *
     * @return bool
     */
    public function AttachValueForProperties($aProperties, $sTargetType, $iTargetId)
    {
        if (!$aProperties) {
            return false;
        }
        /**
         * Формируем список ID свойств
         */
        $aPropertyIds = array();
        foreach ($aProperties as $oProperty) {
            $aPropertyIds[] = $oProperty->getId();
        }
        /**
         * Получаем список значений
         */
        $aValues = $this->Property_GetValueItemsByFilter(array(
                'target_id'      => $iTargetId,
                'target_type'    => $sTargetType,
                'property_id in' => $aPropertyIds,
                '#index-from'    => 'property_id'
            ));
        /**
         * Аттачим значения к свойствам
         */
        foreach ($aProperties as $oProperty) {
            if (isset($aValues[$oProperty->getId()])) {
                $oProperty->setValue($aValues[$oProperty->getId()]);
            } else {
                $oProperty->setValue(Engine::GetEntity('ModuleProperty_EntityValue', array(
                            'property_id'   => $oProperty->getId(),
                            'property_type' => $oProperty->getType(),
                            'target_type'   => $sTargetType,
                            'target_id'     => $iTargetId
                        )));
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
    public function UpdatePropertiesValue($aProperties, $oTarget)
    {
        if ($aProperties) {
            foreach ($aProperties as $oProperty) {
                $oValue = $oProperty->getValue();
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
    public function RemovePropertiesValue($oTarget)
    {
        $aProperties = $this->Property_GetPropertyItemsByFilter(array('target_type' => $oTarget->property->getPropertyTargetType()));
        if ($aProperties) {
            $this->AttachValueForProperties($aProperties, $oTarget->property->getPropertyTargetType(),
                $oTarget->getId());
            foreach ($aProperties as $oProperty) {
                $oValue = $oProperty->getValue();
                if ($oValue and $oValue->getId()) {
                    $oValueType = $oValue->getValueTypeObject();
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
    public function ValidateEntityPropertiesCheck($oTarget)
    {
        /**
         * Пробуем получить свойства из реквеста
         */
        $oTarget->setProperties($oTarget->getProperties() ? $oTarget->getProperties() : getRequest('property'));
        $aPropertiesValue = $oTarget->getProperties();
        $aPropertiesResult = array();
        /**
         * Получаем весь список свойств у объекта
         */
        $aPropertiesObject = $this->Property_GetPropertyItemsByFilter(array('target_type' => $oTarget->property->getPropertyTargetType()));
        $this->Property_AttachValueForProperties($aPropertiesObject, $oTarget->property->getPropertyTargetType(),
            $oTarget->getId());
        foreach ($aPropertiesObject as $oProperty) {
            $oValue = $oProperty->getValue();
            $sValue = isset($aPropertiesValue[$oProperty->getId()]) ? $aPropertiesValue[$oProperty->getId()] : null;
            /**
             * Валидируем значение
             */
            $oValueType = $oValue->getValueTypeObject();
            $oValueType->setValueForValidate($sValue);
            if (true === ($sRes = $oValueType->validate())) {
                $oValueType->setValue($oValueType->getValueForValidate());
                $aPropertiesResult[$oProperty->getId()] = $oProperty;
            } else {
                return 'Поле "'.$oProperty->getTitle().'": '.($sRes ? $sRes : 'неверное значение');
            }
        }
        $oTarget->setPropertiesObject($aPropertiesResult);
        return true;
    }

    /**
     * Возвращает значение свойсва у объекта
     *
     * @param Entity $oTarget Объект сущности
     * @param int $sPropertyId ID свойства
     *
     * @return null|mixed
     */
    public function GetEntityPropertyValue($oTarget, $sPropertyId)
    {
        if ($oProperty = $this->GetEntityPropertyValueObject($oTarget, $sPropertyId)) {
            return $oProperty->getValue()->getValueForDisplay();
        }
        return null;
    }

    /**
     * Возвращает объект свойства сущности
     *
     * @param Entity $oTarget Объект сущности
     * @param int $sPropertyId ID свойства
     *
     * @return null|ModuleProperty_EntityProperty
     */
    public function GetEntityProperty($oTarget, $sPropertyId)
    {
        if ($oProperty = $this->GetEntityPropertyValueObject($oTarget, $sPropertyId)) {
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
    public function GetEntityPropertyList($oTarget)
    {
        $sTargetType = $oTarget->property->getPropertyTargetType();
        /**
         * Проверяем зарегистрирован ли такой тип
         */
        if (!$this->IsAllowTargetType($sTargetType)) {
            return array();
        }
        if (!$oTarget->getPropertyIsLoadAll()) {
            $aProperties = $this->oMapper->GetPropertiesValueByTarget($oTarget->property->getPropertyTargetType(),
                $oTarget->getId());
            $this->AttachPropertiesForTarget($oTarget, $aProperties);
        }
        return $oTarget->_getDataOne('property_list');
    }

    /**
     * Служебный метод для аттача свойст к сущности
     *
     * @param Entity $oTarget Объект сущности
     * @param array $aProperties Список свойств
     */
    public function AttachPropertiesForTarget($oTarget, $aProperties)
    {
        $oTarget->setPropertyList($aProperties);
        $oTarget->setPropertyIsLoadAll(true);
        $aMapperCode = array();
        foreach ($aProperties as $oProperty) {
            $aMapperCode[$oProperty->getCode()] = $oProperty->getId();
        }
        $oTarget->setPropertyMapperCode($aMapperCode);
    }

    /**
     * Возвращает объект свойства
     *
     * @param Entity $oTarget Объект сущности
     * @param array $sPropertyId ID свойства
     *
     * @return null
     */
    public function GetEntityPropertyValueObject($oTarget, $sPropertyId)
    {
        if (!$oTarget->getPropertyIsLoadAll()) {
            /**
             * Загружаем все свойства
             */
            $aProperties = $this->oMapper->GetPropertiesValueByTarget($oTarget->property->getPropertyTargetType(),
                $oTarget->getId());
            $this->AttachPropertiesForTarget($oTarget, $aProperties);
        }

        if (!is_numeric($sPropertyId)) {
            $aMapperCode = $oTarget->getPropertyMapperCode();
            if (isset($aMapperCode[$sPropertyId])) {
                $sPropertyId = $aMapperCode[$sPropertyId];
            } else {
                return null;
            }
        }
        $aProperties = $oTarget->property->getPropertyList();
        if (isset($aProperties[$sPropertyId])) {
            return $aProperties[$sPropertyId];
        }
        return null;
    }

    /**
     * Переопределяем метод для возможности цеплять свои кастомные данные при ORM запросах - свойства
     *
     * @param array $aResult
     * @param array $aFilter
     * @param null|string $sEntityFull
     */
    public function RewriteGetItemsByFilter($aResult, $aFilter = array(), $sEntityFull = null)
    {
        if (!$aResult) {
            return;
        }
        /**
         * Список на входе может быть двух видом:
         * 1 - одномерный массив
         * 2 - двумерный, если применялась группировка (использование '#index-group')
         *
         * Поэтому сначала сформируем линейный список
         */
        if (isset($aFilter['#index-group']) and $aFilter['#index-group']) {
            $aEntitiesWork = array();
            foreach ($aResult as $aItems) {
                foreach ($aItems as $oItem) {
                    $aEntitiesWork[] = $oItem;
                }
            }
        } else {
            $aEntitiesWork = $aResult;
        }

        if (!$aEntitiesWork) {
            return;
        }
        $oEntityFirst = reset($aEntitiesWork);
        if (!$oEntityFirst->property) {
            return;
        }
        $sTargetType = $oEntityFirst->property->getPropertyTargetType();
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
            $aEntitiesId = array();
            foreach ($aEntitiesWork as $oEntity) {
                $aEntitiesId[] = $oEntity->getId();
            }
            /**
             * Получаем все свойства со значениями для всех объектов
             */
            $aResult = $this->oMapper->GetPropertiesValueByTargetArray($sTargetType, $aEntitiesId);
            if ($aResult) {
                /**
                 * Формируем список свойств и значений
                 */
                $aProperties = array();
                $aValues = array();
                foreach ($aResult as $aRow) {
                    $aPropertyData = array();
                    $aValueData = array();
                    foreach ($aRow as $k => $v) {
                        if (strpos($k, 'prop_') === 0) {
                            $aPropertyData[str_replace('prop_', '', $k)] = $v;
                        } else {
                            $aValueData[$k] = $v;
                        }
                    }

                    if (!isset($aProperties[$aRow['prop_id']])) {
                        $oProperty = Engine::GetEntity('ModuleProperty_EntityProperty', $aPropertyData);
                        $aProperties[$aRow['prop_id']] = $oProperty;
                    }
                    if ($aRow['target_id']) {
                        $sKey = $aRow['property_id'] . '_' . $aRow['target_id'];
                        $aValues[$sKey] = Engine::GetEntity('ModuleProperty_EntityValue', $aValueData);
                    }
                }
                /**
                 * Собираем данные
                 */
                foreach ($aEntitiesWork as $oEntity) {
                    $aPropertiesClone = array();
                    foreach ($aProperties as $oProperty) {
                        $oPropertyNew = clone $oProperty;
                        $sKey = $oProperty->getId() . '_' . $oEntity->getId();
                        if (isset($aValues[$sKey])) {
                            $oValue = $aValues[$sKey];
                        } else {
                            $oValue = Engine::GetEntity('ModuleProperty_EntityValue', array(
                                    'property_type' => $oProperty->getType(),
                                    'property_id'   => $oProperty->getId(),
                                    'target_type'   => $oProperty->getTargetType(),
                                    'target_id'     => $oEntity->getId()
                                ));
                        }
                        $oPropertyNew->setValue($oValue);
                        $oValue->setProperty($oPropertyNew);
                        $aPropertiesClone[$oPropertyNew->getId()] = $oPropertyNew;
                    }
                    $this->AttachPropertiesForTarget($oEntity, $aPropertiesClone);
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
    public function RewriteFilter($aFilter, $sEntityFull)
    {
        $oEntitySample = Engine::GetEntity($sEntityFull);
        if (!$oEntitySample->property) {
            return $aFilter;
        }

        if (!isset($aFilter['#join'])) {
            $aFilter['#join'] = array();
        }
        $aPropFields = array();
        foreach ($aFilter as $k => $v) {
            if (preg_match('@^#prop:(.+)$@i', $k, $aMatch)) {
                /**
                 * Сначала формируем список полей с операндами
                 */
                $aK = explode(' ', trim($aMatch[1]), 2);
                $sPropCurrent = $aK[0];
                $sConditionCurrent = ' = ';
                if (count($aK) > 1) {
                    $sConditionCurrent = strtolower($aK[1]);
                }
                $aPropFields[$sPropCurrent] = array('value' => $v, 'condition' => $sConditionCurrent);
            }
        }
        /**
         * Проверяем на наличие сортировки по полям
         */
        $aOrders = array();
        if (isset($aFilter['#order'])) {
            if (!is_array($aFilter['#order'])) {
                $aFilter['#order'] = array($aFilter['#order']);
            }
            foreach ($aFilter['#order'] as $key => $value) {
                $aKeys = explode(':', $key);
                if (count($aKeys) == 2 and strtolower($aKeys[0]) == 'prop') {
                    $aOrders[$aKeys[1]] = array('way' => $value, 'replace' => $key);
                }
            }
        }
        /**
         * Получаем данные по полям
         */
        if ($aPropFields) {
            $sTargetType = $oEntitySample->property->getPropertyTargetType();
            $aProperties = $this->Property_GetPropertyItemsByFilter(array(
                    'code in'     => array_keys($aPropFields),
                    'target_type' => $sTargetType
                ));
            $iPropNum = 0;
            foreach ($aProperties as $oProperty) {
                /**
                 * По каждому полю строим JOIN запрос
                 */
                $sCondition = $aPropFields[$oProperty->getCode()]['condition'];
                $bIsArray = in_array(strtolower($sCondition), array('in', 'not in')) ? true : false;
                if (in_array($oProperty->getType(),
                    array(ModuleProperty::PROPERTY_TYPE_INT, ModuleProperty::PROPERTY_TYPE_CHECKBOX))) {
                    $sFieldValue = "value_int";
                    $sConditionFull = $sCondition . ($bIsArray ? ' (?a) ' : ' ?d ');
                } elseif ($oProperty->getType() == ModuleProperty::PROPERTY_TYPE_FLOAT) {
                    $sFieldValue = "value_float";
                    $sConditionFull = $sCondition . ($bIsArray ? ' (?a) ' : ' ?f ');
                } elseif (in_array($oProperty->getType(), array(
                        ModuleProperty::PROPERTY_TYPE_VARCHAR,
                        ModuleProperty::PROPERTY_TYPE_TAGS,
                        ModuleProperty::PROPERTY_TYPE_VIDEO_LINK
                    ))) {
                    $sFieldValue = "value_varchar";
                    $sConditionFull = $sCondition . ($bIsArray ? ' (?a) ' : ' ? ');
                } elseif ($oProperty->getType() == ModuleProperty::PROPERTY_TYPE_TEXT) {
                    $sFieldValue = "value_text";
                    $sConditionFull = $sCondition . ($bIsArray ? ' (?a) ' : ' ? ');
                } else {
                    $sFieldValue = "value_varchar";
                    $sConditionFull = $sCondition . ($bIsArray ? ' (?a) ' : ' ? ');
                }
                $iPropNum++;
                $sJoin = "JOIN " . Config::Get('db.table.property_value') . " propv{$iPropNum} ON
					t.`{$oEntitySample->_getPrimaryKey()}` = propv{$iPropNum}.target_id and
					propv{$iPropNum}.target_type = '{$sTargetType}' and
					propv{$iPropNum}.property_id = {$oProperty->getId()} and
					propv{$iPropNum}.{$sFieldValue} {$sConditionFull}";
                $aFilter['#join'][$sJoin] = array($aPropFields[$oProperty->getCode()]['value']);
                /**
                 * Проверяем на сортировку по текущему полю
                 */
                if (isset($aOrders[$oProperty->getCode()])) {
                    $aOrders[$oProperty->getCode()]['field'] = "propv{$iPropNum}.{$sFieldValue}";
                }
            }
        }
        /**
         * Подменяем сортировку
         */
        foreach ($aOrders as $aItem) {
            if (isset($aFilter['#order'][$aItem['replace']])) {
                $aFilter['#order'] = $this->ArrayReplaceKey($aFilter['#order'], $aItem['replace'], $aItem['field']);
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
    protected function ArrayReplaceKey($aArray, $sKeyOld, $sKeyNew)
    {
        $aKeys = array_keys($aArray);
        if (false === $iIndex = array_search($sKeyOld, $aKeys)) {
            return false;
        }
        $aKeys[$iIndex] = $sKeyNew;
        return array_combine($aKeys, array_values($aArray));
    }

    /**
     * Удаляет теги свойства у сущности
     *
     * @param string $sTargetType Тип объекта сущности
     * @param int $iTargetId ID объекта сущности
     * @param int $iPropertyId ID свойства
     *
     * @return mixed
     */
    public function RemoveValueTagsByTarget($sTargetType, $iTargetId, $iPropertyId)
    {
        // сбрасываем кеш
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('ModuleProperty_EntityValueTag_delete'));
        return $this->oMapper->RemoveValueTagsByTarget($sTargetType, $iTargetId, $iPropertyId);
    }

    /**
     * Удаляет значения типа select
     *
     * @param string $sTargetType Тип объекта сущности
     * @param int $iTargetId ID объекта сущности
     * @param int $iPropertyId ID свойства
     *
     * @return mixed
     */
    public function RemoveValueSelectsByTarget($sTargetType, $iTargetId, $iPropertyId)
    {
        // сбрасываем кеш
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('ModuleProperty_EntityValueSelect_delete'));
        return $this->oMapper->RemoveValueSelectsByTarget($sTargetType, $iTargetId, $iPropertyId);
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
    public function GetPropertyTagsByLike($sTag, $iPropertyId, $iLimit)
    {
        return $this->oMapper->GetPropertyTagsByLike($sTag, $iPropertyId, $iLimit);
    }

    /**
     * Возвращет список группированных тегов с их количеством для необходимого свойства
     *
     * @param int $iPropertyId
     * @param int $iLimit
     *
     * @return mixed
     */
    public function GetPropertyTagsGroup($iPropertyId, $iLimit)
    {
        return $this->oMapper->GetPropertyTagsGroup($iPropertyId, $iLimit);
    }

    /**
     * Формирует и возвращает облако тегов необходимого свойства
     *
     * @param int $iPropertyId
     * @param int $iLimit
     *
     * @return mixed
     */
    public function GetPropertyTagsCloud($iPropertyId, $iLimit)
    {
        $aTags = $this->Property_GetPropertyTagsGroup($iPropertyId, $iLimit);
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
    public function GetTargetsByTag($iPropertyId, $sTag, $iCurrPage, $iPerPage)
    {
        return array(
            'collection' => $this->oMapper->GetTargetsByTag($iPropertyId, $sTag, $iCount, $iCurrPage, $iPerPage),
            'count'      => $iCount
        );
    }

    /**
     * Производит изменение названия типа объекта, например "article" меняем на "news"
     *
     * @param $sType
     * @param $sTypeNew
     */
    public function ChangeTargetType($sType, $sTypeNew)
    {
        $this->oMapper->UpdatePropertyByTargetType($sType, $sTypeNew);
        $this->oMapper->UpdatePropertyTargetByTargetType($sType, $sTypeNew);
        $this->oMapper->UpdatePropertySelectByTargetType($sType, $sTypeNew);
        $this->oMapper->UpdatePropertyValueByTargetType($sType, $sTypeNew);
        $this->oMapper->UpdatePropertyValueSelectByTargetType($sType, $sTypeNew);
        $this->oMapper->UpdatePropertyValueTagByTargetType($sType, $sTypeNew);
        /**
         * Сбрасываем кеши
         */
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
            'ModuleProperty_EntityProperty_save',
            'ModuleProperty_EntityTarget_save',
            'ModuleProperty_EntitySelect_save',
            'ModuleProperty_EntityValue_save',
            'ModuleProperty_EntityValueSelect_save',
            'ModuleProperty_EntityValueTag_save',
        ));
    }

    /**
     * Создает новый тип объекта в БД для дополнительных полей
     *
     * @param string $sType
     * @param array $aParams
     * @param bool $bRewrite
     *
     * @return bool|ModuleProperty_EntityTarget
     */
    public function CreateTargetType($sType, $aParams, $bRewrite = false)
    {
        /**
         * Проверяем есть ли уже такой тип
         */
        if ($oTarget = $this->GetTargetByType($sType)) {
            if (!$bRewrite) {
                return false;
            }
        } else {
            $oTarget = Engine::GetEntity('ModuleProperty_EntityTarget');
            $oTarget->setType($sType);
        }
        $oTarget->setState(self::TARGET_STATE_ACTIVE);
        $oTarget->setParams($aParams);
        if ($oTarget->Save()) {
            return $oTarget;
        }
        return false;
    }

    /**
     * Отключает тип объекта для дополнительных полей
     *
     * @param string $sType
     * @param int $iState self::TARGET_STATE_NOT_ACTIVE или self::TARGET_STATE_REMOVE
     */
    public function RemoveTargetType($sType, $iState = self::TARGET_STATE_NOT_ACTIVE)
    {
        if ($oTarget = $this->GetTargetByType($sType)) {
            $oTarget->setState($iState);
            $oTarget->Save();
        }
    }

    /**
     * Возвращает набор полей/свойств для показа их на форме редактирования
     *
     * @param $sTargetType
     * @param $iTargetId
     *
     * @return mixed
     */
    public function GetPropertiesForUpdate($sTargetType, $iTargetId)
    {
        /**
         * Проверяем зарегистрирован ли такой тип
         */
        if (!$this->IsAllowTargetType($sTargetType)) {
            return array();
        }
        /**
         * Получаем набор свойств
         */
        $aProperties = $this->Property_GetPropertyItemsByFilter(array(
                'target_type' => $sTargetType,
                '#order'      => array('sort' => 'desc')
            ));
        $this->Property_AttachValueForProperties($aProperties, $sTargetType, $iTargetId);
        return $aProperties;
    }

    /**
     * Автоматическое создание дополнительного поля
     * TODO: учитывать $aAdditional для создание вариантов в типе select
     *
     * @param string $sTargetType Тип объекта дял которого добавляем поле
     * @param array $aData Данные поля: array('type'=>'int','title'=>'Название','code'=>'newfield','description'=>'Описание поля','sort'=>100);
     * @param bool $bSkipErrorUniqueCode Пропускать ошибку при дублировании кода поля (такое поле уже существует)
     * @param array $aValidateRules Данные валидатора поля, зависят от конкретного типа поля: array('allowEmpty'=>true,'max'=>1000)
     * @param array $aParams Дополнительные параметры поля, зависят от типа поля
     * @param array $aAdditional Дополнительные данные, которые нужно учитывать при создании поля, зависят от типа поля
     *
     * @return bool|ModuleProperty_EntityProperty
     */
    public function CreateTargetProperty(
        $sTargetType,
        $aData,
        $bSkipErrorUniqueCode = true,
        $aValidateRules = array(),
        $aParams = array(),
        $aAdditional = array()
    ) {
        /**
         * Если необходимо и поле уже существует, то пропускаем создание
         */
        if ($bSkipErrorUniqueCode and isset($aData['code']) and $this->GetPropertyByTargetTypeAndCode($sTargetType,
                $aData['code'])
        ) {
            return true;
        }

        $oProperty = Engine::GetEntity('ModuleProperty_EntityProperty');
        $oProperty->_setValidateScenario('auto');
        $oProperty->_setDataSafe($aData);
        $oProperty->setValidateRulesRaw($aValidateRules);
        $oProperty->setParamsRaw($aParams);
        $oProperty->setTargetType($sTargetType);
        if ($oProperty->_Validate()) {
            if ($oProperty->Add()) {
                return $oProperty;
            } else {
                return 'Возникла ошибка при добавлении поля';
            }
        } else {
            return $oProperty->_getValidateError();
        }
        return false;
    }

    /**
     * Используется для создания дефолтных дополнительных полей при активации плагина
     *
     * @param array $aProperties Список полей
     * <pre>
     * array(
     *    array(
     *        'data'=>array(
     *        'type'=>ModuleProperty::PROPERTY_TYPE_INT,
     *        'title'=>'Номер',
     *        'code'=>'number',
     *        'sort'=>100
     *    ),
     *    'validate_rule'=>array(
     *        'min'=>10
     *    ),
     *    'params'=>array(),
     *    'additional'=>array()
     *    )
     * );
     * </pre>
     * @param string $sTargetType Тип объекта
     *
     * @return bool
     */
    public function CreateDefaultTargetPropertyFromPlugin($aProperties, $sTargetType)
    {
        foreach ($aProperties as $aProperty) {
            $sResultMsg = $this->CreateTargetProperty($sTargetType, $aProperty['data'], true,
                $aProperty['validate_rule'], $aProperty['params'], $aProperty['additional']);
            if ($sResultMsg !== true and !is_object($sResultMsg)) {
                if (is_string($sResultMsg)) {
                    $this->Message_AddErrorSingle($sResultMsg, $this->Lang_Get('common.error.error'), true);
                }
                /**
                 * Отменяем добавление типа
                 */
                $this->RemoveTargetType($sTargetType, ModuleProperty::TARGET_STATE_NOT_ACTIVE);
                return false;
            }
        }
        return true;
    }

    public function RemoveValueByPropertyId($iPropertyId)
    {
        $bRes = $this->oMapper->RemoveValueByPropertyId($iPropertyId);
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('ModuleProperty_EntityValue_delete'));
        return $bRes;
    }

    public function RemoveValueTagByPropertyId($iPropertyId)
    {
        $bRes = $this->oMapper->RemoveValueTagByPropertyId($iPropertyId);
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('ModuleProperty_EntityValueTag_delete'));
        return $bRes;
    }

    public function RemoveValueSelectByPropertyId($iPropertyId)
    {
        $bRes = $this->oMapper->RemoveValueSelectByPropertyId($iPropertyId);
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('ModuleProperty_EntityValueSelect_delete'));
        return $bRes;
    }

    public function RemoveSelectByPropertyId($iPropertyId)
    {
        $bRes = $this->oMapper->RemoveSelectByPropertyId($iPropertyId);
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('ModuleProperty_EntitySelect_delete'));
        return $bRes;
    }

    public function CheckAllowTargetObject($sTargetType, $iTargetId, $aParams = array())
    {
        $sMethod = 'CheckAllowTargetObject' . func_camelize($sTargetType);
        if (method_exists($this, $sMethod)) {
            if (!array_key_exists('user', $aParams)) {
                $aParams['user'] = $this->oUserCurrent;
            }
            return $this->$sMethod($iTargetId, $aParams);
        }
        /**
         * По умолчанию считаем доступ разрешен
         */
        return true;
    }

}