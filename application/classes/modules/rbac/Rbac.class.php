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
 * Модуль управления правами на основе ролей и разрешений
 * Для проверки прав доступны два метода - для текущего пользователя и для любого.
 * <pre>
 *  // для текущего пользователя
 *    $this->Rbac_IsAllow('topic_create');
 *    // для конкретного пользователя с параметрами
 *    $this->Rbac_IsAllowUser($oUser,'topic_update',array('topic'=>$oTopic));
 *  // для плагина 'article', указывается код плагина
 *    $this->Rbac_IsAllow('article_create','article');
 *  // для плагина, где $this - любой текущий объект плагина (кроме Inherit классов)
 *    $this->Rbac_IsAllow('article_create',$this);
 *  // для плагина с параметрами
 *    $this->Rbac_IsAllow('article_update',$this,array('article'=>$oArticle));
 * </pre>
 *
 * @package application.modules.rbac
 * @since 2.0
 */
class ModuleRbac extends ModuleORM
{
    /**
     * Код системной гостевой роли.
     * Всем неавторизованным пользователям присваивается эта роль
     */
    const ROLE_CODE_GUEST = 'guest';
    /**
     * Статусы разрешений
     */
    const PERMISSION_STATE_ACTIVE = 1;
    const PERMISSION_STATE_INACTIVE = 0;
    /**
     * Статусы ролей
     */
    const ROLE_STATE_ACTIVE = 1;
    const ROLE_STATE_INACTIVE = 0;

    /**
     * Внутренний кеш ролей пользователя
     *
     * @var array
     */
    protected $aUserRoleCache = array();
    /**
     * Внутренний кеш всех ролей
     *
     * @var array
     */
    protected $aRoleCache = array();
    /**
     * Внутренний кеш разрешений для ролей
     *
     * @var array
     */
    protected $aRulePermissionCache = array();
    /**
     * Внутренний кеш всех используемых разрешений
     *
     * @var array
     */
    protected $aPermissionCache = array();
    /**
     * Хранит последнее сообщение о неудачной проверке прав
     *
     * @var null|string
     */
    protected $sMessageLast = null;
    /**
     * Объект маппера
     *
     * @var ModuleRbac_MapperRbac
     */
    protected $oMapper = null;

    /**
     * Инициализация модуля
     */
    public function Init()
    {
        parent::Init();
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Проверяет разрешение для текущего авторизованного пользователя
     *
     * @param string $sPermissionCode Код разрешения
     * @param mixed $aParamsOrPlugin Параметры или плагин
     * @param mixed $sPluginOrParams Плагин или параметры
     *
     * @return bool
     */
    public function IsAllow($sPermissionCode, $aParamsOrPlugin = array(), $sPluginOrParams = null)
    {
        return $this->IsAllowUser($this->User_GetUserCurrent(), $sPermissionCode, $aParamsOrPlugin, $sPluginOrParams);
    }

    /**
     * Проверяет разрешение для конкретного пользователя
     *
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @param string $sPermissionCode Код разрешения
     * @param mixed $aParamsOrPlugin Параметры или плагин
     * @param mixed $sPluginOrParams Плагин или параметры
     *
     * @return bool
     */
    public function IsAllowUser($oUser, $sPermissionCode, $aParamsOrPlugin = array(), $sPluginOrParams = null)
    {
        $aParams = array();
        $sPlugin = null;
        if (!is_array($sPluginOrParams)) {
            $sPlugin = $sPluginOrParams;
        } else {
            $aParams = $sPluginOrParams;
        }
        if (is_array($aParamsOrPlugin)) {
            $aParams = $aParamsOrPlugin;
        } else {
            $sPlugin = $aParamsOrPlugin;
        }
        return $this->IsAllowUserFull($oUser, $sPermissionCode, $aParams, $sPlugin);
    }

    /**
     * Проверяет разрешение для конкретного пользователя
     *
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @param string $sPermissionCode Код разрешения
     * @param array $aParams Параметры
     * @param mixed $sPlugin Плагин, можно указать код плагина, название класса или объект
     *
     * @return bool
     */
    protected function IsAllowUserFull($oUser, $sPermissionCode, $aParams = array(), $sPlugin = null)
    {
        if (!$sPermissionCode) {
            return false;
        }
        $sPlugin = $sPlugin ? Plugin::GetPluginCode($sPlugin) : '';
        /**
         * Загружаем все роли и пермишены
         */
        $this->LoadRoleAndPermissions();
        $sUserId = self::ROLE_CODE_GUEST;
        if ($oUser) {
            $sUserId = $oUser->getId();
        }
        /**
         * Смотрим роли в кеше
         */
        if (!isset($this->aUserRoleCache[$sUserId])) {
            if ($sUserId == self::ROLE_CODE_GUEST) {
                $aRoles = $this->GetRoleByCodeAndState(self::ROLE_CODE_GUEST, self::ROLE_STATE_ACTIVE);
                $aRoles = $aRoles ? array($aRoles) : array();
            } else {
                $aRoles = $this->GetRolesByUser($oUser);
            }
            $this->aUserRoleCache[$sUserId] = $aRoles;
        } else {
            $aRoles = $this->aUserRoleCache[$sUserId];
        }
        /**
         * Получаем пермишены для ролей
         */
        $sPermissionCode = func_underscore($sPermissionCode);
        $mResult = false;
        foreach ($aRoles as $oRole) {
            /**
             * У роли есть необходимый пермишен, то проверим на возможную кастомную обработку с параметрами
             */
            if ($this->CheckPermissionByRole($oRole, $sPermissionCode, $sPlugin)) {
                /**
                 * Проверяем на передачу коллбека
                 */
                if (isset($aParams['callback']) and is_callable($aParams['callback'])) {
                    $mResult = call_user_func($aParams['callback'], $oUser, $aParams);
                } else {
                    /**
                     * Для плагинов: CheckCustomPluginArticleCreate
                     * Для ядра: CheckCustomCreate
                     */
                    $sAdd = $sPlugin ? ('Plugin' . func_camelize($sPlugin)) : '';
                    $sMethod = 'CheckCustom' . $sAdd . func_camelize($sPermissionCode);
                    if (method_exists($this, $sMethod)) {
                        $mResult = call_user_func(array($this, $sMethod), $oUser, $aParams);
                    } else {
                        return true;
                    }
                }
                break;
            }
        }
        /**
         * Дефолтное сообщение об ошибке
         */
        $sMsg = 'У вас нет прав на "' . $sPermissionCode . '"';
        /**
         * Проверяем результат кастомной обработки
         */
        if ($mResult === true) {
            return true;
        } elseif (is_string($mResult)) {
            /**
             * Вернули кастомное сообщение об ошибке
             */
            $sMsg = $mResult;
        } else {
            /**
             * Формируем сообщение об ошибке
             */
            if (isset($this->aPermissionCache[$sPlugin][$sPermissionCode])) {
                $aPerm = $this->aPermissionCache[$sPlugin][$sPermissionCode];
                if ($aPerm['msg_error']) {
                    $sMsg = $this->Lang_Get($aPerm['msg_error']);
                } else {
                    $sMsg = 'У вас нет прав на "' . ($aPerm['title'] ? $aPerm['title'] : $aPerm['code']) . '"';
                }
            }
        }
        $this->sMessageLast = $sMsg;
        return false;
    }

    /**
     * Возвращает список ролей пользователя
     * На самом деле этот метод можно было бы заменить на $oUser->getRolesActive(), если бы сущность User была ORM
     *
     * @param ModuleUser_EntityUser|int $oUser
     * @param bool $bActiveOnly Учитывать только активные роли
     *
     * @return array
     */
    public function GetRolesByUser($oUser, $bActiveOnly = true)
    {
        if (!$oUser) {
            return array();
        }
        if (is_object($oUser)) {
            $iUserId = $oUser->getId();
        } else {
            $iUserId = $oUser;
        }
        /**
         * Сначала получаем все связи
         */
        $aRoleUserItems = $this->GetRoleUserItemsByFilter(array('user_id' => $iUserId, '#index-from' => 'role_id'));
        $aRoleIds = array_keys($aRoleUserItems);
        /**
         * Теперь получаем список ролей
         */
        if ($aRoleIds) {
            $aFilter = array('id in' => $aRoleIds);
            if ($bActiveOnly) {
                $aFilter['state'] = self::ROLE_STATE_ACTIVE;
            }
            return $this->GetRoleItemsByFilter($aFilter);
        }
        return array();
    }

    /**
     * Возвращает количество пользователей у роли
     *
     * @param ModuleRbac_EntityRole|int $oRole
     *
     * @return int
     */
    public function GetCountUsersByRole($oRole)
    {
        if (!$oRole) {
            return 0;
        }
        if (is_object($oRole)) {
            $iRoleId = $oRole->getId();
        } else {
            $iRoleId = $oRole;
        }

        return $this->GetCountItemsByFilter(array('role_id' => $iRoleId), 'ModuleRbac_EntityRoleUser');
    }

    /**
     * Выполняет загрузку в кеш ролей и разрешений
     */
    protected function LoadRoleAndPermissions()
    {
        /**
         * Роли
         */
        $this->LoadRoles();
        /**
         * Пермишены
         */
        $this->LoadPermissions();
    }

    /**
     * Загружает в кеш разрешения
     */
    protected function LoadPermissions()
    {
        if ($this->aRulePermissionCache) {
            return;
        }
        $aResult = $this->oMapper->GetRoleWithPermissions();
        foreach ($aResult as $aRow) {
            $this->aRulePermissionCache[$aRow['role_id']][$aRow['plugin']][] = $aRow['code'];
            $this->aPermissionCache[$aRow['plugin']][$aRow['code']] = $aRow;
        }
    }

    /**
     * Загружает в кеш роли
     */
    protected function LoadRoles()
    {
        if ($this->aRoleCache) {
            return;
        }
        $aRoles = $this->GetRoleItemsByState(self::ROLE_STATE_ACTIVE);
        foreach ($aRoles as $oRole) {
            $this->aRoleCache[$oRole->getId()] = $oRole;
        }
    }

    /**
     * Проверяет наличие разрешения у конкретной роли, учитывается наследование ролей
     *
     * @param ModuleRbac_EntityRole $oRole Объект роли
     * @param string $sPermissionCode Код разрешения
     * @param string $sPlugin Код плагина или пустая строка (разрешения ядра)
     *
     * @return bool
     */
    protected function CheckPermissionByRole($oRole, $sPermissionCode, $sPlugin = '')
    {
        /**
         * Проверяем наличие пермишена в текущей роли
         */
        if (isset($this->aRulePermissionCache[$oRole->getId()][$sPlugin])) {
            if (in_array($sPermissionCode, $this->aRulePermissionCache[$oRole->getId()][$sPlugin])) {
                return true;
            }
        }
        /**
         * Смотрим родительскую роль
         */
        if ($oRole->getPid() and isset($this->aRoleCache[$oRole->getPid()])) {
            return $this->CheckPermissionByRole($this->aRoleCache[$oRole->getPid()], $sPermissionCode, $sPlugin);
        }
        return false;
    }

    /**
     * Возвращает последнее сообщение о неудачной проверке прав
     *
     * @return null|string
     */
    public function GetMsgLast()
    {
        return $this->sMessageLast;
    }

    /**
     * Добавляет роль к пользователю
     *
     * @param ModuleRbac_EntityRole|string $oRole Объект роли или код роли
     * @param int|ModuleUser_EntityUser $iUserId Объект пользователя или его ID
     *
     * @return bool|Entity
     */
    public function AddRoleToUser($oRole, $iUserId)
    {
        if (is_string($oRole)) {
            $oRole = $this->GetRoleByCode($oRole);
        }
        if (is_object($iUserId)) {
            $iUserId = $iUserId->getId();
        }
        if (!$oRole or !$iUserId) {
            return false;
        }
        if (!($oRoleUser = $this->Rbac_GetRoleUserByFilter(array(
            'role_id' => $oRole->getId(),
            'user_id' => $iUserId
        )))
        ) {
            /**
             * Добавляем
             */
            $oRoleUser = Engine::GetEntity('ModuleRbac_EntityRoleUser');
            $oRoleUser->setRoleId($oRole->getId());
            $oRoleUser->setUserId($iUserId);
            $oRoleUser->Add();
        }
        return $oRoleUser;
    }

    /**
     * Создает разрешений для управления правами
     * В качестве основного параметра передается массив с данными, массив имеет тип корневых ключа: groups, roles и permissions.
     * <pre>
     * $aData=array(
     *        'groups' => array(
     *            array('article','Статьи'),
     *        ),
     *        'roles' => array(
     *            array('article_moderator','Модератор статей'),
     *        ),
     *        'permissions' => array(
     *            array('view','Просмотр статьи','msg_error'=>'У вас нет прав на просмотр статьи','group'=>'article','roles'=>array('guest','user')),
     *            array('create','Создание статей','msg_error'=>'У вас нет прав на создание статьи','group'=>'article','roles'=>'user'),
     *            array('update_all','Правка всех статей','msg_error'=>'У вас нет прав на редактирование статьи','group'=>'article','roles'=>'article_moderator'),
     *        ),
     * );
     * </pre>
     *
     * @param array $aData Набор данных
     * @param mixed $sPlugin Плагин, можно указать код плагина, название класса или объект
     *
     * @return bool
     */
    public function CreatePermissions($aData, $sPlugin = null)
    {
        $sPlugin = $sPlugin ? Plugin::GetPluginCode($sPlugin) : '';
        /**
         * Создаем группы
         */
        if (isset($aData['groups'])) {
            foreach ($aData['groups'] as $aGroup) {
                $sCode = $aGroup[0];
                $sTitle = isset($aGroup[1]) ? $aGroup[1] : $sCode;
                if (!$this->GetGroupByCode($sCode)) {
                    $oGroup = Engine::GetEntity('ModuleRbac_EntityGroup');
                    $oGroup->setCode($sCode);
                    $oGroup->setTitle($sTitle);
                    if ($oGroup->_Validate()) {
                        $oGroup->setTitle(htmlspecialchars($oGroup->getTitle()));
                        $oGroup->Add();
                    }
                }
            }
        }
        /**
         * Создаем роли
         */
        if (isset($aData['roles'])) {
            foreach ($aData['roles'] as $aRole) {
                $sCode = $aRole[0];
                $sTitle = isset($aRole[1]) ? $aRole[1] : $sCode;
                if (!$this->GetRoleByCode($sCode)) {
                    $oRole = Engine::GetEntity('ModuleRbac_EntityRole');
                    $oRole->setCode($sCode);
                    $oRole->setTitle($sTitle);
                    if ($oRole->_Validate()) {
                        $oRole->setTitle(htmlspecialchars($oRole->getTitle()));
                        $oRole->Add();
                    }
                }
            }
        }
        /**
         * Создаем разрешения
         */
        if (isset($aData['permissions'])) {
            foreach ($aData['permissions'] as $aPermission) {
                $sCode = $aPermission[0];
                $sTitle = isset($aPermission[1]) ? $aPermission[1] : $sCode;
                $aFilter = array(
                    'code' => $sCode
                );
                if ($sPlugin) {
                    $aFilter['plugin'] = $sPlugin;
                }
                if (!$this->GetPermissionByFilter($aFilter)) {
                    $oPermission = Engine::GetEntity('ModuleRbac_EntityPermission');
                    $oPermission->setCode($sCode);
                    $oPermission->setTitle($sTitle);
                    $oPermission->setPlugin($sPlugin);
                    $oPermission->setMsgError(isset($aPermission['msg_error']) ? $aPermission['msg_error'] : '');
                    if (isset($aPermission['group']) and $oGroup = $this->GetGroupByCode($aPermission['group'])) {
                        $oPermission->setGroupId($oGroup->getId());
                    }
                    if ($oPermission->_Validate()) {
                        $oPermission->setTitle(htmlspecialchars($oPermission->getTitle()));
                        $oPermission->setMsgError(htmlspecialchars($oPermission->getMsgError()));
                        if ($oPermission->Add()) {
                            /**
                             * Создаем связь с ролями
                             */
                            if (isset($aPermission['roles'])) {
                                $aRoles = is_array($aPermission['roles']) ? $aPermission['roles'] : array($aPermission['roles']);
                                foreach ($aRoles as $sRoleCode) {
                                    if ($oRole = $this->GetRoleByCode($sRoleCode)) {
                                        $oRolePermission = Engine::GetEntity('ModuleRbac_EntityRolePermission');
                                        $oRolePermission->setRoleId($oRole->getId());
                                        $oRolePermission->setPermissionId($oPermission->getId());
                                        $oRolePermission->Add();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Удаляет разрешения - группы, роли, разрешения
     *
     * <pre>
     * $aData=array(
     *        'groups' => array('article'),
     *        'roles' => array('article_moderator'),
     * );
     * </pre>
     * @param array $aData Данные для удаления
     * @param mixed $sPlugin Плагин, можно указать код плагина, название класса или объект
     */
    public function RemovePermissions($aData, $sPlugin)
    {
        if (isset($aData['groups'])) {
            $aGroups = is_array($aData['groups']) ? $aData['groups'] : array($aData['groups']);
            foreach ($aGroups as $sGroupCode) {
                if ($oGroup = $this->GetGroupByCode($sGroupCode)) {
                    $oGroup->Delete();
                }
            }
        }
        if (isset($aData['roles'])) {
            $aRoles = is_array($aData['roles']) ? $aData['roles'] : array($aData['roles']);
            foreach ($aRoles as $sRoleCode) {
                if ($oRole = $this->GetRoleByCode($sRoleCode)) {
                    $oRole->Delete();
                }
            }
        }
        /**
         * Удаляем разрешения
         */
        $sPlugin = $sPlugin ? Plugin::GetPluginCode($sPlugin) : '';
        if ($sPlugin and $aPermissions = $this->GetPermissionItemsByPlugin($sPlugin)) {
            foreach ($aPermissions as $oPermission) {
                $oPermission->Delete();
            }
        }
    }

    /**
     * Алиас для перенаправления экшена на страницу ошибки с сообщением
     *
     * @param bool $bFromAdmin Необходимо указать true, если метод вызывается из стандартной админки
     *
     * @return string
     */
    public function ReturnActionError($bFromAdmin = false)
    {
        if ($bFromAdmin) {
            $this->Message_AddErrorSingle($this->GetMsgLast());
            return Router::Action('admin', 'error');
        } else {
            return Router::ActionError($this->GetMsgLast());
        }
    }
}