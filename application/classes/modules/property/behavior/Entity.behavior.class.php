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
 * Поведение для подключения функционала дополнительных полей к сущностям
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_BehaviorEntity extends Behavior
{
    /**
     * Дефолтные параметры
     *
     * @var array
     */
    protected $aParams = array(
        'target_type' => '',
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
     * Коллбэк
     * Выполняется при инициализации сущности
     *
     * @param $aParams
     */
    public function CallbackValidateAfter($aParams)
    {
        if ($aParams['bResult']) {
            $aFields = $aParams['aFields'];
            if (is_null($aFields) or in_array('properties', $aFields)) {
                $oValidator = $this->Validate_CreateValidator('properties_check', $this, 'properties');
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
        $this->Property_UpdatePropertiesValue($this->oObject->getPropertiesObject(), $this->oObject);
    }

    /**
     * Коллбэк
     * Выполняется после удаления сущности
     */
    public function CallbackAfterDelete()
    {
        $this->Property_RemovePropertiesValue($this->oObject);
    }

    /**
     * Дополнительный метод для сущности
     * Запускает валидацию дополнительных полей
     *
     * @return mixed
     */
    public function ValidatePropertiesCheck()
    {
        return $this->Property_ValidateEntityPropertiesCheck($this->oObject);
    }

    /**
     * Возвращает полный список свойств сущности
     *
     * @return mixed
     */
    public function getPropertyList()
    {
        return $this->Property_GetEntityPropertyList($this->oObject);
    }

    /**
     * Возвращает значение конкретного свойства
     * @see ModuleProperty_EntityValue::getValueForDisplay
     *
     * @param int|string $sPropertyId ID или код свойства
     *
     * @return mixed
     */
    public function getPropertyValue($sPropertyId)
    {
        return $this->Property_GetEntityPropertyValue($this->oObject, $sPropertyId);
    }

    /**
     * Возвращает объект конкретного свойства сущности
     *
     * @param int|string $sPropertyId ID или код свойства
     *
     * @return ModuleProperty_EntityProperty|null
     */
    public function getProperty($sPropertyId)
    {
        return $this->Property_GetEntityProperty($this->oObject, $sPropertyId);
    }

    /**
     * Возвращает тип объекта для дополнительных полей
     *
     * @return string
     */
    public function getPropertyTargetType()
    {
        if ($sType = $this->getParam('target_type')) {
            return $sType;
        }
        /**
         * Иначе дополнительно смотрим на наличие данного метода у сущности
         * Это необходимо, если тип вычисляется динамически по какой-то своей логике
         */
        if (func_method_exists($this->oObject, 'getPropertyTargetType', 'public')) {
            return call_user_func(array($this->oObject, 'getPropertyTargetType'));
        }
    }
}