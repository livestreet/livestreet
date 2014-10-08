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
 * Поведение, которое необходимо добавлять к сущности (entity) у которой добавляются категории
 *
 * @package application.modules.category
 * @since 2.0
 */
class ModuleCategory_BehaviorEntity extends Behavior
{
    /**
     * Дефолтные параметры
     *
     * @var array
     */
    protected $aParams = array(
        // Уникальный код
        'target_type'                    => '',
        // Имя инпута (select) на форме, который содержит список категорий
        'form_field'                     => 'categories',
        // Автоматически брать текущую категорию из реквеста
        'form_fill_current_from_request' => true,
        // Возможность выбирать несколько категорий
        'multiple'                       => false,
        // Автоматическая валидация категорий (актуально при ORM)
        'validate_enable'                => true,
        // Поле сущности, в котором хранятся категории. Если null, то используется имя из form_field
        'validate_field'                 => null,
        // Обязательное заполнение категории
        'validate_require'               => false,
        // Получать значение валидации не из сущности, а из реквеста (используется поле form_field)
        'validate_from_request'          => false,
        // Минимальное количество категорий, доступное для выбора
        'validate_min'                   => 1,
        // Максимальное количество категорий, доступное для выбора
        'validate_max'                   => 5,
        // Возможность выбрать только те категории, у которых нет дочерних
        'validate_only_without_children' => false,
        // Колбек для подсчета количества объектов у категории. Необходим, например, если необходимо учитывать объекты только с определенным статусом (доступен для публикации).
        // Указывать можно строкой с полным вызовом метода модуля, например, "PluginArticle_Main_GetCountArticle"
        // В качестве параметров передается список ID категорий и тип
        'callback_count_target'          => null,
    );
    /**
     * Список хуков
     *
     * @var array
     */
    protected $aHooks = array(
        'validate_after' => 'CallbackValidateAfter',
        'after_save'     => 'CallbackAfterSave',
        'after_delete'   => 'CallbackAfterDelete',
    );

    /**
     * Инициализация
     */
    protected function Init()
    {
        parent::Init();
        if (!$this->getParam('validate_field')) {
            $this->aParams['validate_field'] = $this->getParam('form_field');
        }
    }

    /**
     * Коллбэк
     * Выполняется при инициализации сущности
     *
     * @param $aParams
     */
    public function CallbackValidateAfter($aParams)
    {
        if ($aParams['bResult'] and $this->getParam('validate_enable')) {
            $aFields = $aParams['aFields'];
            if (is_null($aFields) or in_array($this->getParam('validate_field'), $aFields)) {
                $oValidator = $this->Validate_CreateValidator('categories_check', $this,
                    $this->getParam('validate_field'));
                $oValidator->validateEntity($this->oObject, $aFields);
                $aParams['bResult'] = !$this->oObject->_hasValidateErrors();
            }
        }
    }

    /**
     * Коллбэк
     * Выполняется после сохранения сущности
     */
    public function CallbackAfterSave()
    {
        $this->Category_SaveCategories($this->oObject, $this->getParam('target_type'),
            $this->getParam('callback_count_target'));
    }

    /**
     * Коллбэк
     * Выполняется после удаления сущности
     */
    public function CallbackAfterDelete()
    {
        $this->Category_RemoveCategories($this->oObject, $this->getParam('target_type'),
            $this->getParam('callback_count_target'));
    }

    /**
     * Дополнительный метод для сущности
     * Запускает валидацию дополнительных полей
     *
     * @param $mValue
     *
     * @return bool|string
     */
    public function ValidateCategoriesCheck($mValue)
    {
        /**
         * Проверяем тип категрий
         */
        if (!$oTypeCategory = $this->Category_GetTypeByTargetType($this->getParam('target_type'))) {
            return 'Неверный тип категорий';
        }

        if ($this->getParam('validate_from_request')) {
            $mValue = getRequest($this->getParam('form_field'));
        }
        /**
         * Значение может быть числом, массивом, строкой с разделением через запятую
         */
        if (!is_array($mValue)) {
            if ($this->getParam('multiple')) {
                $mValue = explode(',', $mValue);
            } else {
                $mValue = array($mValue);
            }
        }
        /**
         * Проверяем наличие категорий в БД
         */
        $aCategories = $this->Category_ValidateCategoryArray($mValue, $oTypeCategory->getId(), true);
        if (!$aCategories) {
            $aCategories = array();
        }

        if ($this->getParam('validate_require') and !$aCategories) {
            return 'Необходимо выбрать категорию';
        }
        if (!$this->getParam('multiple') and count($aCategories) > 1) {
            $aCategories = array_slice($aCategories, 0, 1);
        }
        if ($this->getParam('multiple') and $aCategories and (count($aCategories) < $this->getParam('validate_min') or count($aCategories) > $this->getParam('validate_max'))) {
            return 'Количество категорий должно быть от ' . $this->getParam('validate_min') . ' до ' . $this->getParam('validate_max');
        }
        if ($this->getParam('validate_only_without_children')) {
            foreach ($aCategories as $oCategory) {
                if ($oCategory->getChildren()) {
                    return 'Для выбора доступны только конечные категории';
                }
            }
        }
        /**
         * Сохраняем необходимый список категорий для последующего сохранения в БД
         */
        $this->oObject->_setData(array('_categories_for_save' => array_keys($aCategories)));
        return true;
    }

    /**
     * Возвращает список категорий сущности
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->Category_GetEntityCategories($this->oObject, $this->getCategoryTargetType());
    }

    /**
     * Возвращает количество категорий
     *
     * @return array
     */
    public function getCountCategories()
    {
        return count($this->getCategories());
    }

    /**
     * Возвращает одну категорию сущности
     * Если объект может иметь несколько категорий, то вернется первая
     *
     * @return ModuleCategory_EntityCategory|null
     */
    public function getCategory()
    {
        $aCategories = $this->getCategories();
        $oCategory = reset($aCategories);
        return $oCategory ? $oCategory : null;
    }

    /**
     * Возвращает тип объекта для категорий
     *
     * @return string
     */
    public function getCategoryTargetType()
    {
        if ($sType = $this->getParam('target_type')) {
            return $sType;
        }
        /**
         * Иначе дополнительно смотрим на наличие данного метода у сущности
         * Это необходимо, если тип вычисляется динамически по какой-то своей логике
         */
        if (func_method_exists($this->oObject, 'getCategoryTargetType', 'public')) {
            return call_user_func(array($this->oObject, 'getCategoryTargetType'));
        }
    }
}