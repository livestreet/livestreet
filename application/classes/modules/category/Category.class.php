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
 * Модуль управления универсальными категориями
 *
 * @package application.modules.category
 * @since 2.0
 */
class ModuleCategory extends ModuleORM
{
    /**
     * Список состояний типов объектов
     */
    const TARGET_STATE_ACTIVE = 1;
    const TARGET_STATE_NOT_ACTIVE = 2;
    const TARGET_STATE_REMOVE = 3;

    /**
     * Возвращает список категорий сущности
     *
     * @param $oTarget
     * @param $sTargetType
     *
     * @return array
     */
    public function GetEntityCategories($oTarget, $sTargetType)
    {
        $aCategories = $oTarget->_getDataOne('_categories');
        if (is_null($aCategories)) {
            $this->AttachCategoriesForTargetItems($oTarget, $sTargetType);
            return $oTarget->_getDataOne('_categories');
        }
        return $aCategories;
    }

    /**
     * Обработка фильтра ORM запросов
     *
     * @param array $aFilter
     * @param array $sEntityFull
     * @param string $sTargetType
     *
     * @return array
     */
    public function RewriteFilter($aFilter, $sEntityFull, $sTargetType)
    {
        $oEntitySample = Engine::GetEntity($sEntityFull);

        if (!isset($aFilter['#join'])) {
            $aFilter['#join'] = array();
        }

        if (!isset($aFilter['#select'])) {
            $aFilter['#select'] = array();
        }

        if (array_key_exists('#category', $aFilter)) {
            $aCategoryId = $aFilter['#category'];
            if (!is_array($aCategoryId)) {
                $aCategoryId = array($aCategoryId);
            }
            $sJoin = "JOIN " . Config::Get('db.table.category_target') . " category ON
					t.`{$oEntitySample->_getPrimaryKey()}` = category.target_id and
					category.target_type = '{$sTargetType}' and
					category.category_id IN ( ?a ) ";
            $aFilter['#join'][$sJoin] = array($aCategoryId);
            if (count($aFilter['#select'])) {
                $aFilter['#select'][] = "distinct t.`{$oEntitySample->_getPrimaryKey()}`";
            } else {
                $aFilter['#select'][] = "distinct t.`{$oEntitySample->_getPrimaryKey()}`";
                $aFilter['#select'][] = 't.*';
            }
        }
        return $aFilter;
    }

    /**
     * Переопределяем метод для возможности цеплять свои кастомные данные при ORM запросах - свойства
     *
     * @param array $aResult
     * @param array $aFilter
     * @param string $sTargetType
     */
    public function RewriteGetItemsByFilter($aResult, $aFilter, $sTargetType)
    {
        if (!$aResult) {
            return;
        }
        /**
         * Список на входе может быть двух видов:
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
        /**
         * Проверяем необходимость цеплять категории
         */
        if (isset($aFilter['#with']['#category'])) {
            $this->AttachCategoriesForTargetItems($aEntitiesWork, $sTargetType);
        }
    }

    /**
     * Цепляет для списка объектов категории
     *
     * @param array $aEntityItems
     * @param string $sTargetType
     */
    public function AttachCategoriesForTargetItems($aEntityItems, $sTargetType)
    {
        if (!is_array($aEntityItems)) {
            $aEntityItems = array($aEntityItems);
        }
        $aEntitiesId = array();
        foreach ($aEntityItems as $oEntity) {
            $aEntitiesId[] = $oEntity->getId();
        }
        /**
         * Получаем категории для всех объектов
         */
        $sEntityCategory = $this->_NormalizeEntityRootName('Category');
        $sEntityTarget = $this->_NormalizeEntityRootName('Target');
        $aCategories = $this->GetCategoryItemsByFilter(array(
            '#join'        => array(
                "JOIN " . Config::Get('db.table.category_target') . " category_target ON
																	t.id = category_target.category_id and
																	category_target.target_type = '{$sTargetType}' and
																	category_target.target_id IN ( ?a )
																	" => array($aEntitiesId)
            ),
            '#select'      => array(
                't.*',
                'category_target.target_id'
            ),
            '#index-group' => 'target_id',
            '#cache'       => array(
                null,
                array(
                    $sEntityCategory . '_save',
                    $sEntityCategory . '_delete',
                    $sEntityTarget . '_save',
                    $sEntityTarget . '_delete'
                )
            )
        ));
        /**
         * Собираем данные
         */
        foreach ($aEntityItems as $oEntity) {
            if (isset($aCategories[$oEntity->_getPrimaryKeyValue()])) {
                $oEntity->_setData(array('_categories' => $aCategories[$oEntity->_getPrimaryKeyValue()]));
            } else {
                $oEntity->_setData(array('_categories' => array()));
            }
        }
    }

    /**
     * Возвращает дерево категорий
     *
     * @param int $sId Type ID
     *
     * @return array
     */
    public function GetCategoriesTreeByType($sId)
    {
        $aCategories = $this->LoadTreeOfCategory(array('type_id' => $sId));
        return ModuleORM::buildTree($aCategories);
    }

    /**
     * Возвращает дерево категорий
     *
     * @param string $sCode Type code
     *
     * @return array
     */
    public function GetCategoriesTreeByTargetType($sCode)
    {
        if ($oType = $this->GetTypeByTargetType($sCode)) {
            return $this->GetCategoriesTreeByType($oType->getId());
        }
        return array();
    }

    /**
     * Валидирует список категория
     *
     * @param array $aCategoryId
     * @param int $iType
     * @param bool $bReturnObjects
     *
     * @return array|bool
     */
    public function ValidateCategoryArray($aCategoryId, $iType, $bReturnObjects = false)
    {
        if (!is_array($aCategoryId)) {
            return false;
        }
        $aIds = array();
        foreach ($aCategoryId as $iId) {
            $aIds[] = (int)$iId;
        }
        if ($aIds and $aCategories = $this->GetCategoryItemsByFilter(array(
                    'id in'   => $aIds,
                    'type_id' => $iType,
                    '#index-from-primary'
                ))
        ) {
            if ($bReturnObjects) {
                return $aCategories;
            } else {
                return array_keys($aCategories);
            }
        }
        return false;
    }

    /**
     * Сохраняет категории для объекта
     *
     * @param $oTarget
     * @param $sTargetType
     * @param $mCallbackCountTarget
     */
    public function SaveCategories($oTarget, $sTargetType, $mCallbackCountTarget = null)
    {
        $aCategoriesId = $oTarget->_getDataOne('_categories_for_save');
        if (!is_array($aCategoriesId)) {
            return;
        }
        /**
         * Удаляем текущие связи
         */
        $aCategoryIdChanged = $this->RemoveRelation($oTarget->_getPrimaryKeyValue(), $sTargetType);
        /**
         * Создаем
         */
        $this->CreateRelation($aCategoriesId, $oTarget->_getPrimaryKeyValue(), $sTargetType);
        /**
         * Полный список категорий, которые затронули изменения
         */
        $aCategoryIdChanged = array_merge($aCategoryIdChanged, $aCategoriesId);
        /**
         * Подсчитываем количество новое элементов для каждой категории
         */
        $this->UpdateCountTarget($aCategoryIdChanged, $sTargetType, $mCallbackCountTarget);

        $oTarget->_setData(array('_categories_for_save' => null));
    }

    /**
     * Обновляет количество элементов у категорий (поле count_target в таблице категорий)
     *
     * @param      $aCategoryId
     * @param      $sTargetType
     * @param null $mCallback
     */
    protected function UpdateCountTarget($aCategoryId, $sTargetType, $mCallback = null)
    {
        if (!is_array($aCategoryId)) {
            $aCategoryId = array($aCategoryId);
        }
        if (!count($aCategoryId)) {
            return;
        }
        $aCategories = $this->GetCategoryItemsByArrayId($aCategoryId);
        foreach ($aCategories as $oCategory) {
            if ($mCallback) {
                if (is_string($mCallback)) {
                    $mCallback = array($this, $mCallback);
                }
                $iCount = call_user_func_array($mCallback, array($oCategory, $sTargetType));
            } else {
                $iCount = $this->GetCountItemsByFilter(array('category_id' => $oCategory->getId()),
                    'ModuleCategory_EntityTarget');
            }
            $oCategory->setCountTarget($iCount);
            $oCategory->Update();
        }
    }

    /**
     * Удаляет категории у объекта
     *
     * @param $oTarget
     * @param $sTargetType
     * @param $mCallbackCountTarget
     */
    public function RemoveCategories($oTarget, $sTargetType, $mCallbackCountTarget = null)
    {
        $aCategoryIdChanged = $this->RemoveRelation($oTarget->_getPrimaryKeyValue(), $sTargetType);
        /**
         * Подсчитываем количество новое элементов для каждой категории
         */
        $this->UpdateCountTarget($aCategoryIdChanged, $sTargetType, $mCallbackCountTarget);
    }

    /**
     * Создает новую связь конкретного объекта с категориями
     *
     * @param array $aCategoryId
     * @param int $iTargetId
     * @param int|string $iType type_id или target_type
     *
     * @return bool
     */
    public function CreateRelation($aCategoryId, $iTargetId, $iType)
    {
        if (!$aCategoryId or (is_array($aCategoryId) and !count($aCategoryId))) {
            return false;
        }
        if (!is_array($aCategoryId)) {
            $aCategoryId = array($aCategoryId);
        }
        if (is_numeric($iType)) {
            $oType = $this->GetTypeById($iType);
        } else {
            $oType = $this->GetTypeByTargetType($iType);
        }
        if (!$oType) {
            return false;
        }
        foreach ($aCategoryId as $iCategoryId) {
            if (!$this->GetTargetByCategoryIdAndTargetIdAndTypeId($iCategoryId, $iTargetId, $oType->getId())) {
                $oTarget = Engine::GetEntity('ModuleCategory_EntityTarget');
                $oTarget->setCategoryId($iCategoryId);
                $oTarget->setTargetId($iTargetId);
                $oTarget->setTargetType($oType->getTargetType());
                $oTarget->setTypeId($oType->getId());
                $oTarget->Add();
            }
        }
        return true;
    }

    /**
     * Удаляет связь конкретного объекта с категориями
     *
     * @param int $iTargetId
     * @param int|string $iType type_id или target_type
     *
     * @return bool|array
     */
    public function RemoveRelation($iTargetId, $iType)
    {
        if (!is_numeric($iType)) {
            if ($oType = $this->GetTypeByTargetType($iType)) {
                $iType = $oType->getId();
            } else {
                return false;
            }
        }
        $aRemovedCategory = array();
        $aTargets = $this->GetTargetItemsByTargetIdAndTypeId($iTargetId, $iType);
        foreach ($aTargets as $oTarget) {
            $oTarget->Delete();
            $aRemovedCategory[] = $oTarget->getCategoryId();
        }
        return $aRemovedCategory;
    }

    /**
     * Возвращает список категорий по категории
     *
     * @param      $oCategory
     * @param bool $bIncludeChild Возвращать все дочернии категории
     *
     * @return array|null
     */
    public function GetCategoriesIdByCategory($oCategory, $bIncludeChild = false)
    {
        if (is_object($oCategory)) {
            $iCategoryId = $oCategory->getId();
        } else {
            $iCategoryId = $oCategory;
        }
        $aCategoryId = array($iCategoryId);
        if ($bIncludeChild) {
            /**
             * Сначала получаем полный список категорий текущего типа
             */
            if (!is_object($oCategory)) {
                $oCategory = $this->GetCategoryById($iCategoryId);
            }
            if ($oCategory) {
                if ($aChildren = $oCategory->getDescendants()) {
                    foreach ($aChildren as $oCategoryChild) {
                        $aCategoryId[] = $oCategoryChild->getId();
                    }
                }
            }
        }
        return $aCategoryId;
    }

    /**
     * Пересобирает полные URL дочерних категорий
     *
     * @param      $oCategoryStart
     * @param bool $bStart
     */
    public function RebuildCategoryUrlFull($oCategoryStart, $bStart = true)
    {
        static $aRebuildIds;
        if ($bStart) {
            $aRebuildIds = array();
        }

        if (is_null($oCategoryStart->getId())) {
            $aCategories = $this->GetCategoryItemsByFilter(array(
                    '#where'  => array('pid is null' => array()),
                    'type_id' => $oCategoryStart->getTypeId()
                ));
        } else {
            $aCategories = $this->GetCategoryItemsByFilter(array(
                    'pid'     => $oCategoryStart->getId(),
                    'type_id' => $oCategoryStart->getTypeId()
                ));
        }

        foreach ($aCategories as $oCategory) {
            if ($oCategory->getId() == $oCategoryStart->getId()) {
                continue;
            }
            if (in_array($oCategory->getId(), $aRebuildIds)) {
                continue;
            }
            $aRebuildIds[] = $oCategory->getId();
            $oCategory->setUrlFull($oCategoryStart->getUrlFull() . '/' . $oCategory->getUrl());
            $oCategory->Update();
            $this->RebuildCategoryUrlFull($oCategory, false);
        }
    }

    /**
     * Возвращает список ID таргетов по списку категорий
     *
     * @param $aCategoryId
     * @param $sTargetType
     * @param $iPage
     * @param $iPerPage
     *
     * @return array
     */
    public function GetTargetIdsByCategoriesId($aCategoryId, $sTargetType, $iPage, $iPerPage)
    {
        if (!is_array($aCategoryId)) {
            $aCategoryId = array($aCategoryId);
        }
        if (!count($aCategoryId)) {
            return array();
        }
        $aTargetItems = $this->GetTargetItemsByFilter(array(
                'category_id in' => $aCategoryId,
                'target_type'    => $sTargetType,
                '#page'          => array($iPage, $iPerPage),
                '#index-from'    => 'target_id'
            ));
        return array_keys($aTargetItems['collection']);
    }

    /**
     * Возвращает список ID таргетов по категории
     *
     * @param      $oCategory
     * @param      $sTargetType
     * @param      $iPage
     * @param      $iPerPage
     * @param bool $bIncludeChild
     *
     * @return array
     */
    public function GetTargetIdsByCategory($oCategory, $sTargetType, $iPage, $iPerPage, $bIncludeChild = false)
    {
        $aCategoryId = $this->GetCategoriesIdByCategory($oCategory, $bIncludeChild);

        return $this->GetTargetIdsByCategoriesId($aCategoryId, $sTargetType, $iPage, $iPerPage);
    }

    /**
     * Создает новый тип объекта в БД для категорий
     *
     * @param string $sType
     * @param string $sTitle
     * @param array $aParams
     * @param bool $bRewrite
     *
     * @return bool|ModuleCategory_EntityType
     */
    public function CreateTargetType($sType, $sTitle, $aParams = array(), $bRewrite = false)
    {
        /**
         * Проверяем есть ли уже такой тип
         */
        if ($oType = $this->GetTypeByTargetType($sType)) {
            if (!$bRewrite) {
                return false;
            }
        } else {
            $oType = Engine::GetEntity('ModuleCategory_EntityType');
            $oType->setTargetType($sType);
        }
        $oType->setState(self::TARGET_STATE_ACTIVE);
        $oType->setTitle(htmlspecialchars($sTitle));
        $oType->setParams($aParams);
        if ($oType->Save()) {
            return $oType;
        }
        return false;
    }

    /**
     * Отключает тип объекта для категорий
     *
     * @param string $sType
     * @param int $iState self::TARGET_STATE_NOT_ACTIVE или self::TARGET_STATE_REMOVE
     */
    public function RemoveTargetType($sType, $iState = self::TARGET_STATE_NOT_ACTIVE)
    {
        if ($oType = $this->GetTypeByTargetType($sType)) {
            $oType->setState($iState);
            $oType->Save();
        }
    }

    /**
     * Парсинг текста с учетом конкретной категории
     *
     * @param string $sText
     * @param ModuleCategory_EntityCategory $oCategory
     *
     * @return string
     */
    public function ParserText($sText, $oCategory)
    {
        $this->Text_AddParams(array('oCategory' => $oCategory));
        $sResult = $this->Text_Parser($sText);
        $this->Text_RemoveParams(array('oCategory'));
        return $sResult;
    }

}