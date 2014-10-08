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
 * Сущность категории
 *
 * @package application.modules.category
 * @since 2.0
 */
class ModuleCategory_EntityCategory extends EntityORM
{

    /**
     * Определяем правила валидации
     *
     * @var array
     */
    protected $aValidateRules = array(
        array('title', 'string', 'max' => 200, 'min' => 1, 'allowEmpty' => false),
        array('description', 'string', 'max' => 5000, 'min' => 1, 'allowEmpty' => true),
        array('url', 'regexp', 'pattern' => '/^[\w\-_]+$/i', 'allowEmpty' => false),
        array('order', 'number', 'integerOnly' => true),
        array('pid', 'parent_category'),
        array('order', 'order_check'),
    );

    protected $aRelations = array(
        'type' => array(self::RELATION_TYPE_BELONGS_TO, 'ModuleCategory_EntityType', 'type_id'),
        self::RELATION_TYPE_TREE
    );

    /**
     * Проверка родительской категории
     *
     * @param string $sValue Валидируемое значение
     * @param array $aParams Параметры
     * @return bool
     */
    public function ValidateParentCategory($sValue, $aParams)
    {
        if ($this->getPid()) {
            if ($oCategory = $this->Category_GetCategoryById($this->getPid())) {
                if ($oCategory->getId() == $this->getId()) {
                    return 'Попытка вложить категорию в саму себя';
                }
                if ($oCategory->getTypeId() != $this->getTypeId()) {
                    return 'Неверная родительская категория';
                }
                $this->setUrlFull($oCategory->getUrlFull() . '/' . $this->getUrl());
            } else {
                return 'Неверная категория';
            }
        } else {
            $this->setPid(null);
            $this->setUrlFull($this->getUrl());
        }
        return true;
    }

    /**
     * Установка дефолтной сортировки
     *
     * @param string $sValue Валидируемое значение
     * @param array $aParams Параметры
     * @return bool
     */
    public function ValidateOrderCheck($sValue, $aParams)
    {
        if (!$this->getSort()) {
            $this->setSort(100);
        }
        return true;
    }

    /**
     * Выполняется перед удалением
     *
     * @return bool
     */
    protected function beforeDelete()
    {
        if ($bResult = parent::beforeDelete()) {
            /**
             * Запускаем удаление дочерних категорий
             */
            if ($aCildren = $this->getChildren()) {
                foreach ($aCildren as $oChildren) {
                    $oChildren->Delete();
                }
            }
            /**
             * Удаляем связь с таргетом
             */
            if ($aTargets = $this->Category_GetTargetItemsByCategoryId($this->getId())) {
                foreach ($aTargets as $oTarget) {
                    $oTarget->Delete();
                    /**
                     * TODO: Нужно запустить хук, что мы удалили такую-то связь
                     */
                }
            }
        }
        return $bResult;
    }

    /**
     * Переопределяем имя поля с родителем
     * Т.к. по дефолту в деревьях используется поле parent_id
     *
     * @return string
     */
    public function _getTreeParentKey()
    {
        return 'pid';
    }

    /**
     * Выполняется перед сохранением
     *
     * @return bool
     */
    protected function beforeSave()
    {
        if ($bResult = parent::beforeSave()) {
            if ($this->_isNew()) {
                $this->setDateCreate(date("Y-m-d H:i:s"));
            }
        }
        return $bResult;
    }

    /**
     * Возвращает URL категории
     * Этот метод необходимо переопределить из плагина и возвращать свой URL для нужного типа категорий
     *
     * @return string
     */
    public function getWebUrl()
    {
        return null;
    }

    /**
     * Возвращает объект типа категории с использованием кеширования на время сессии
     *
     * @return ModuleCategory_EntityType
     */
    public function getTypeByCacheLife()
    {
        $sKey = 'category_type_' . (string)$this->getTypeId();
        if (false === ($oType = $this->Cache_GetLife($sKey))) {
            $oType = $this->getType();
            $this->Cache_SetLife($oType, $sKey);
        }
        return $oType;
    }

    /**
     * Возвращает URL админки для редактирования
     *
     * @return string
     */
    public function getUrlAdminUpdate()
    {
        return Router::GetPath('admin/categories/' . $this->getTypeByCacheLife()->getTargetType() . '/update/' . $this->getId());
    }

    /**
     * Возвращает URL админки для удаления
     *
     * @return string
     */
    public function getUrlAdminRemove()
    {
        return Router::GetPath('admin/categories/' . $this->getTypeByCacheLife()->getTargetType() . '/remove/' . $this->getId());
    }

    /**
     * Возвращает список дополнительных данных
     *
     * @return array|mixed
     */
    public function getData()
    {
        $aData = @unserialize($this->_getDataOne('data'));
        if (!$aData) {
            $aData = array();
        }
        return $aData;
    }

    /**
     * Устанавливает список дополнительня данных
     *
     * @param $aRules
     */
    public function setData($aRules)
    {
        $this->_aData['data'] = @serialize($aRules);
    }

    /**
     * Возвращает данные по конкретному ключу
     *
     * @param $sKey
     *
     * @return null
     */
    public function getDataOne($sKey)
    {
        $aData = $this->getData();
        if (isset($aData[$sKey])) {
            return $aData[$sKey];
        }
        return null;
    }

    /**
     * Устанваливает данные для конкретного ключа
     *
     * @param $sKey
     * @param $mValue
     */
    public function setDataOne($sKey, $mValue)
    {
        $aData = $this->getData();
        $aData[$sKey] = $mValue;
        $this->setData($aData);
    }

    /**
     * Возвращает сумму значений по ключу для всех потомков, включая себя
     *
     * @param $sKey
     *
     * @return null
     */
    public function getDataOneSumDescendants($sKey)
    {
        $iResult = $this->getDataOne($sKey);
        $aChildren = $this->getDescendants();
        foreach ($aChildren as $oItem) {
            $iResult += $oItem->getDataOne($sKey);
        }
        return $iResult;
    }

    /**
     * Возвращает количество таргетов (объектов) для всех потомков, включая себя
     *
     * @return mixed
     */
    public function getCountTargetOfDescendants()
    {
        $iCount = $this->getCountTarget();
        $aChildren = $this->getDescendants();
        foreach ($aChildren as $oItem) {
            $iCount += $oItem->getCountTarget();
        }
        return $iCount;
    }
}