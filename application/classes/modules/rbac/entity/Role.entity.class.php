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
 * Сущность роли, которая назначается пользователям
 *
 * @package application.modules.rbac
 * @since 2.0
 */
class ModuleRbac_EntityRole extends EntityORM
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
        array('pid', 'parent_role'),
    );
    /**
     * Связи ORM
     *
     * @var array
     */
    protected $aRelations = array(
        'permissions' => array(
            self::RELATION_TYPE_MANY_TO_MANY,
            'ModuleRbac_EntityPermission',
            'permission_id',
            'ModuleRbac_EntityRolePermission',
            'role_id'
        ),
        self::RELATION_TYPE_TREE,
    );

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
     * Выполняется перед удалением
     *
     * @return bool
     */
    protected function beforeDelete()
    {
        if ($bResult = parent::beforeDelete()) {
            /**
             * Запускаем удаление дочерних ролей
             */
            if ($aCildren = $this->getChildren()) {
                foreach ($aCildren as $oChildren) {
                    $oChildren->Delete();
                }
            }
        }
        return $bResult;
    }

    /**
     * Валидация кода
     *
     * @return bool|string
     */
    public function ValidateCheckCode()
    {
        if ($oObject = $this->Rbac_GetRoleByCode($this->getCode())) {
            if ($this->getId() != $oObject->getId()) {
                return 'Код должен быть уникальным';
            }
        }
        return true;
    }

    /**
     * Проверка родительской роли
     *
     * @param string $sValue Валидируемое значение
     * @param array $aParams Параметры
     * @return bool
     */
    public function ValidateParentRole($sValue, $aParams)
    {
        if ($this->getPid()) {
            if ($oRole = $this->Rbac_GetRoleById($this->getPid())) {
                if ($oRole->getId() == $this->getId()) {
                    return 'Попытка вложить роль в саму себя';
                }
            } else {
                return 'Неверная роль';
            }
        } else {
            $this->setPid(null);
        }
        return true;
    }

    /**
     * Возвращает количество пользователей с данной ролью
     *
     * @return mixed
     */
    public function getCountUsers()
    {
        return $this->Rbac_GetCountUsersByRole($this);
    }

    /**
     * Возвращает URL админки для различных действий над ролью, например, редактирование
     *
     * @param $sAction
     *
     * @return string
     */
    public function getUrlAdminAction($sAction)
    {
        return Router::GetPath('admin/users/rbac/role-' . $sAction . '/' . $this->getId());
    }
}