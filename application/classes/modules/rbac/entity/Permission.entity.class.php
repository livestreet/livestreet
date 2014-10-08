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
 * Сущность разрешения
 *
 * @package application.modules.rbac
 * @since 2.0
 */
class ModuleRbac_EntityPermission extends EntityORM
{
    /**
     * Определяем правила валидации
     *
     * @var array
     */
    protected $aValidateRules = array(
        array('title', 'string', 'max' => 200, 'min' => 1, 'allowEmpty' => false),
        array('msg_error', 'string', 'max' => 250, 'min' => 1, 'allowEmpty' => true),
        array('code', 'regexp', 'pattern' => '/^[\w\-_]+$/i', 'allowEmpty' => false),
        array('plugin', 'regexp', 'pattern' => '/^[\w\-_]+$/i', 'allowEmpty' => true),
        array('code', 'check_code'),
        array('group_id', 'check_group'),
    );

    /**
     * Связи ORM
     *
     * @var array
     */
    protected $aRelations = array(
        'roles' => array(
            self::RELATION_TYPE_MANY_TO_MANY,
            'ModuleRbac_EntityRole',
            'role_id',
            'ModuleRbac_EntityRolePermission',
            'permission_id'
        ),
    );

    /**
     * Валидация группы
     *
     * @return bool|string
     */
    public function ValidateCheckGroup()
    {
        if ($this->getGroupId()) {
            if ($oObject = $this->Rbac_GetGroupById($this->getGroupId())) {
                $this->setGroupId($oObject->getId());
            } else {
                return 'Неверная группа';
            }
        } else {
            $this->setGroupId(null);
        }
        return true;
    }

    /**
     * Валидация кода
     *
     * @return bool|string
     */
    public function ValidateCheckCode()
    {
        $sPlugin = $this->getPlugin() ? $this->getPlugin() : '';
        if ($oObject = $this->Rbac_GetPermissionByCodeAndPlugin($this->getCode(), $sPlugin)) {
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
     * Возвращает URL админки для редактирования
     *
     * @return string
     */
    public function getUrlAdminUpdate()
    {
        return Router::GetPath('admin/users/rbac/permission-update/' . $this->getId());
    }

    /**
     * Возвращает URL админки для удаления
     *
     * @return string
     */
    public function getUrlAdminRemove()
    {
        return Router::GetPath('admin/users/rbac/permission-remove/' . $this->getId());
    }

    public function getTitleLang()
    {
        return $this->Lang_Get($this->getTitle());
    }
}