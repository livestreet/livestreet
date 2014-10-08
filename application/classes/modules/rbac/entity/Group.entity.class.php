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
 * Сущность группы для логического объединения разрешений
 *
 * @package application.modules.rbac
 * @since 2.0
 */
class ModuleRbac_EntityGroup extends EntityORM
{
    /**
     * Определяем правила валидации
     *
     * @var array
     */
    protected $aValidateRules = array(
        array('title', 'string', 'max' => 200, 'min' => 1, 'allowEmpty' => false),
        array('code', 'regexp', 'pattern' => '/^[\w\-_]+$/i', 'allowEmpty' => false),
        array('code', 'check_code'),
    );
    /**
     * Связи ORM
     *
     * @var array
     */
    protected $aRelations = array(
        'permissions' => array(self::RELATION_TYPE_HAS_MANY, 'ModuleRbac_EntityPermission', 'group_id'),
    );

    /**
     * Валидация кода группы
     *
     * @return bool|string
     */
    public function ValidateCheckCode()
    {
        if ($oObject = $this->Rbac_GetGroupByCode($this->getCode())) {
            if ($this->getId() != $oObject->getId()) {
                return 'Код должен быть уникальным';
            }
        }
        return true;
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
     * Выполняется перед удалением сущности
     *
     * @return bool
     */
    protected function beforeDelete()
    {
        if ($bResult = parent::beforeDelete()) {
            /**
             * Нужно сбросить группу у разрешений
             */
            $aPermissionItems = $this->Rbac_GetPermissionItemsByGroupId($this->getId());
            foreach ($aPermissionItems as $oPermission) {
                $oPermission->setGroupId(null);
                $oPermission->Update();
            }
        }
        return $bResult;
    }

    /**
     * Возвращает URL админки для редактирования
     *
     * @return string
     */
    public function getUrlAdminUpdate()
    {
        return Router::GetPath('admin/users/rbac/group-update/' . $this->getId());
    }

    /**
     * Возвращает URL админки для удаления
     *
     * @return string
     */
    public function getUrlAdminRemove()
    {
        return Router::GetPath('admin/users/rbac/group-remove/' . $this->getId());
    }
}