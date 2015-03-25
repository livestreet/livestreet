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
 * Модуль для работы с пользователями
 *
 * @package application.modules.user
 * @since 1.0
 */
class ModuleUser extends Module
{
    /**
     * Статусы дружбы между пользователями
     */
    const USER_FRIEND_OFFER = 1;
    const USER_FRIEND_ACCEPT = 2;
    const USER_FRIEND_DELETE = 4;
    const USER_FRIEND_REJECT = 8;
    const USER_FRIEND_NULL = 16;
    /**
     * Статусы жалобы на пользователя
     */
    const COMPLAINT_STATE_NEW = 1;
    const COMPLAINT_STATE_READ = 2;
    /**
     * Объект маппера
     *
     * @var ModuleUser_MapperUser
     */
    protected $oMapper;
    /**
     * Объект текущего пользователя
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;
    /**
     * Объект сессии текущего пользователя
     *
     * @var ModuleUser_EntitySession|null
     */
    protected $oSession = null;
    /**
     * Список типов пользовательских полей
     *
     * @var array
     */
    protected $aUserFieldTypes = array(
        'social',
        'contact'
    );

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
        /**
         * Проверяем есть ли у юзера сессия, т.е. залогинен или нет
         */
        $sUserId = $this->Session_Get('user_id');
        $sSessionKey = $this->Session_Get('session_key');
        if ($sUserId and $oUser = $this->GetUserById($sUserId) and $oUser->getActivate()) {
            /**
             * Проверяем сессию
             */
            if ($oSession = $oUser->getSession()) {
                $bSessionValid = false;
                /**
                 * Т.к. у пользователя может быть несколько сессий (разные браузеры), то нужно дополнительно сверить
                 */
                if ($oSession->getKey() == $sSessionKey and $oSession->isActive()) {
                    $bSessionValid = true;
                } else {
                    /**
                     * Пробуем скорректировать сессию
                     */
                    if ($oSession = $this->oMapper->GetSessionByKey($sSessionKey) and $oSession->getUserId() == $oUser->getId() and $oSession->isActive()) {
                        $bSessionValid = true;
                        $oUser->setSession($oSession);
                    }
                }
                if ($bSessionValid) {
                    /**
                     * Сюда можно вставить условие на проверку айпишника сессии
                     */
                    $this->oUserCurrent = $oUser;
                    $this->oSession = $oSession;
                }
            }
        }
        /**
         * Запускаем автозалогинивание
         * В куках стоит время на сколько запоминать юзера
         */
        $this->AutoLogin();
        /**
         * Обновляем сессию
         */
        if (isset($this->oSession)) {
            $this->UpdateSession();
        }
    }

    /**
     * Возвращает список типов полей
     *
     * @return array
     */
    public function GetUserFieldTypes()
    {
        return $this->aUserFieldTypes;
    }

    /**
     * Добавляет новый тип с пользовательские поля
     *
     * @param string $sType Тип
     * @return bool
     */
    public function AddUserFieldTypes($sType)
    {
        if (!in_array($sType, $this->aUserFieldTypes)) {
            $this->aUserFieldTypes[] = $sType;
            return true;
        }
        return false;
    }

    /**
     * Получает дополнительные данные(объекты) для юзеров по их ID
     *
     * @param array $aUserId Список ID пользователей
     * @param array|null $aAllowData Список типод дополнительных данных для подгрузки у пользователей
     * @return array
     */
    public function GetUsersAdditionalData($aUserId, $aAllowData = null)
    {
        if (is_null($aAllowData)) {
            $aAllowData = array('vote', 'session', 'friend', 'geo_target', 'note');
        }
        func_array_simpleflip($aAllowData);
        if (!is_array($aUserId)) {
            $aUserId = array($aUserId);
        }
        /**
         * Получаем юзеров
         */
        $aUsers = $this->GetUsersByArrayId($aUserId);
        /**
         * Получаем дополнительные данные
         */
        $aSessions = array();
        $aFriends = array();
        $aVote = array();
        $aGeoTargets = array();
        $aNotes = array();
        if (isset($aAllowData['session'])) {
            $aSessions = $this->GetSessionsByArrayId($aUserId);
        }
        if (isset($aAllowData['friend']) and $this->oUserCurrent) {
            $aFriends = $this->GetFriendsByArray($aUserId, $this->oUserCurrent->getId());
        }

        if (isset($aAllowData['vote']) and $this->oUserCurrent) {
            $aVote = $this->Vote_GetVoteByArray($aUserId, 'user', $this->oUserCurrent->getId());
        }
        if (isset($aAllowData['geo_target'])) {
            $aGeoTargets = $this->Geo_GetTargetsByTargetArray('user', $aUserId);
        }
        if (isset($aAllowData['note']) and $this->oUserCurrent) {
            $aNotes = $this->GetUserNotesByArray($aUserId, $this->oUserCurrent->getId());
        }
        /**
         * Добавляем данные к результату
         */
        foreach ($aUsers as $oUser) {
            if (isset($aSessions[$oUser->getId()])) {
                $oUser->setSession($aSessions[$oUser->getId()]);
            } else {
                $oUser->setSession(null); // или $oUser->setSession(new ModuleUser_EntitySession());
            }
            if ($aFriends && isset($aFriends[$oUser->getId()])) {
                $oUser->setUserFriend($aFriends[$oUser->getId()]);
            } else {
                $oUser->setUserFriend(null);
            }

            if (isset($aVote[$oUser->getId()])) {
                $oUser->setVote($aVote[$oUser->getId()]);
            } else {
                $oUser->setVote(null);
            }
            if (isset($aGeoTargets[$oUser->getId()])) {
                $aTargets = $aGeoTargets[$oUser->getId()];
                $oUser->setGeoTarget(isset($aTargets[0]) ? $aTargets[0] : null);
            } else {
                $oUser->setGeoTarget(null);
            }
            if (isset($aAllowData['note'])) {
                if (isset($aNotes[$oUser->getId()])) {
                    $oUser->setUserNote($aNotes[$oUser->getId()]);
                } else {
                    $oUser->setUserNote(false);
                }
            }
        }

        return $aUsers;
    }

    /**
     * Список юзеров по ID
     *
     * @param array $aUserId Список ID пользователей
     * @return array
     */
    public function GetUsersByArrayId($aUserId)
    {
        if (!$aUserId) {
            return array();
        }
        if (Config::Get('sys.cache.solid')) {
            return $this->GetUsersByArrayIdSolid($aUserId);
        }
        if (!is_array($aUserId)) {
            $aUserId = array($aUserId);
        }
        $aUserId = array_unique($aUserId);
        $aUsers = array();
        $aUserIdNotNeedQuery = array();
        /**
         * Делаем мульти-запрос к кешу
         */
        $aCacheKeys = func_build_cache_keys($aUserId, 'user_');
        if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
            /**
             * проверяем что досталось из кеша
             */
            foreach ($aCacheKeys as $sValue => $sKey) {
                if (array_key_exists($sKey, $data)) {
                    if ($data[$sKey]) {
                        $aUsers[$data[$sKey]->getId()] = $data[$sKey];
                    } else {
                        $aUserIdNotNeedQuery[] = $sValue;
                    }
                }
            }
        }
        /**
         * Смотрим каких юзеров не было в кеше и делаем запрос в БД
         */
        $aUserIdNeedQuery = array_diff($aUserId, array_keys($aUsers));
        $aUserIdNeedQuery = array_diff($aUserIdNeedQuery, $aUserIdNotNeedQuery);
        $aUserIdNeedStore = $aUserIdNeedQuery;
        if ($data = $this->oMapper->GetUsersByArrayId($aUserIdNeedQuery)) {
            foreach ($data as $oUser) {
                /**
                 * Добавляем к результату и сохраняем в кеш
                 */
                $aUsers[$oUser->getId()] = $oUser;
                $this->Cache_Set($oUser, "user_{$oUser->getId()}", array(), 60 * 60 * 24 * 4);
                $aUserIdNeedStore = array_diff($aUserIdNeedStore, array($oUser->getId()));
            }
        }
        /**
         * Сохраняем в кеш запросы не вернувшие результата
         */
        foreach ($aUserIdNeedStore as $sId) {
            $this->Cache_Set(null, "user_{$sId}", array(), 60 * 60 * 24 * 4);
        }
        /**
         * Сортируем результат согласно входящему массиву
         */
        $aUsers = func_array_sort_by_keys($aUsers, $aUserId);
        return $aUsers;
    }

    /**
     * Алиас для корректной работы ORM
     *
     * @param array $aFilter Фильтр, который содержит список id пользователей в параметре "id in"
     * @return array
     */
    public function GetUserItemsByFilter($aFilter)
    {
        if (isset($aFilter['id in'])) {
            return $this->GetUsersByArrayId($aFilter['id in']);
        }
        return array();
    }

    /**
     * Получение пользователей по списку ID используя общий кеш
     *
     * @param array $aUserId Список ID пользователей
     * @return array
     */
    public function GetUsersByArrayIdSolid($aUserId)
    {
        if (!is_array($aUserId)) {
            $aUserId = array($aUserId);
        }
        $aUserId = array_unique($aUserId);
        $aUsers = array();
        $s = join(',', $aUserId);
        if (false === ($data = $this->Cache_Get("user_id_{$s}"))) {
            $data = $this->oMapper->GetUsersByArrayId($aUserId);
            foreach ($data as $oUser) {
                $aUsers[$oUser->getId()] = $oUser;
            }
            $this->Cache_Set($aUsers, "user_id_{$s}", array("user_update", "user_new"), 60 * 60 * 24 * 1);
            return $aUsers;
        }
        return $data;
    }

    /**
     * Список сессий юзеров по ID
     *
     * @param array $aUserId Список ID пользователей
     * @return array
     */
    public function GetSessionsByArrayId($aUserId)
    {
        if (!$aUserId) {
            return array();
        }
        if (Config::Get('sys.cache.solid')) {
            return $this->GetSessionsByArrayIdSolid($aUserId);
        }
        if (!is_array($aUserId)) {
            $aUserId = array($aUserId);
        }
        $aUserId = array_unique($aUserId);
        $aSessions = array();
        $aUserIdNotNeedQuery = array();
        /**
         * Делаем мульти-запрос к кешу
         */
        $aCacheKeys = func_build_cache_keys($aUserId, 'user_session_');
        if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
            /**
             * проверяем что досталось из кеша
             */
            foreach ($aCacheKeys as $sValue => $sKey) {
                if (array_key_exists($sKey, $data)) {
                    if ($data[$sKey] and $data[$sKey]['session']) {
                        $aSessions[$data[$sKey]['session']->getUserId()] = $data[$sKey]['session'];
                    } else {
                        $aUserIdNotNeedQuery[] = $sValue;
                    }
                }
            }
        }
        /**
         * Смотрим каких юзеров не было в кеше и делаем запрос в БД
         */
        $aUserIdNeedQuery = array_diff($aUserId, array_keys($aSessions));
        $aUserIdNeedQuery = array_diff($aUserIdNeedQuery, $aUserIdNotNeedQuery);
        $aUserIdNeedStore = $aUserIdNeedQuery;
        if ($data = $this->oMapper->GetSessionsByArrayId($aUserIdNeedQuery)) {
            foreach ($data as $oSession) {
                /**
                 * Добавляем к результату и сохраняем в кеш
                 */
                $aSessions[$oSession->getUserId()] = $oSession;
                $this->Cache_Set(array('time' => time(), 'session' => $oSession),
                    "user_session_{$oSession->getUserId()}", array(), 60 * 60 * 24 * 4);
                $aUserIdNeedStore = array_diff($aUserIdNeedStore, array($oSession->getUserId()));
            }
        }
        /**
         * Сохраняем в кеш запросы не вернувшие результата
         */
        foreach ($aUserIdNeedStore as $sId) {
            $this->Cache_Set(array('time' => time(), 'session' => null), "user_session_{$sId}", array(),
                60 * 60 * 24 * 4);
        }
        /**
         * Сортируем результат согласно входящему массиву
         */
        $aSessions = func_array_sort_by_keys($aSessions, $aUserId);
        return $aSessions;
    }

    /**
     * Получить список сессий по списку айдишников, но используя единый кеш
     *
     * @param array $aUserId Список ID пользователей
     * @return array
     */
    public function GetSessionsByArrayIdSolid($aUserId)
    {
        if (!is_array($aUserId)) {
            $aUserId = array($aUserId);
        }
        $aUserId = array_unique($aUserId);
        $aSessions = array();
        $s = join(',', $aUserId);
        if (false === ($data = $this->Cache_Get("user_session_id_{$s}"))) {
            $data = $this->oMapper->GetSessionsByArrayId($aUserId);
            foreach ($data as $oSession) {
                $aSessions[$oSession->getUserId()] = $oSession;
            }
            $this->Cache_Set($aSessions, "user_session_id_{$s}", array("user_session_update"), 60 * 60 * 24 * 1);
            return $aSessions;
        }
        return $data;
    }

    /**
     * Получает сессию юзера
     *
     * @param int $sUserId ID пользователя
     * @return ModuleUser_EntitySession|null
     */
    public function GetSessionByUserId($sUserId)
    {
        $aSessions = $this->GetSessionsByArrayId($sUserId);
        if (isset($aSessions[$sUserId])) {
            return $aSessions[$sUserId];
        }
        return null;
    }

    /**
     * При завершенни модуля загружаем в шалон объект текущего юзера
     *
     */
    public function Shutdown()
    {
        if ($this->oUserCurrent) {
            $this->Viewer_Assign('iUserCurrentCountTalkNew', $this->Talk_GetCountTalkNew($this->oUserCurrent->getId()));
            $this->Viewer_Assign('iUserCurrentCountTopicDraft',
                $this->Topic_GetCountDraftTopicsByUserId($this->oUserCurrent->getId()));
        }
        $this->Viewer_Assign('oUserCurrent', $this->oUserCurrent);
    }

    /**
     * Добавляет юзера
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @return ModuleUser_EntityUser|bool
     */
    public function Add(ModuleUser_EntityUser $oUser)
    {
        if (is_null($oUser->getReferralCode())) {
            $oUser->setReferralCode(md5((string)$oUser->getMail() . func_generator(32)));
        }
        if ($sId = $this->oMapper->Add($oUser)) {
            $oUser->setId($sId);
            //чистим зависимые кеши
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_new'));
            /**
             * Создаем персональный блог
             */
            $this->Blog_CreatePersonalBlog($oUser);
            /**
             * Добавляем пользователю дефолтную роль для управления правами
             */
            $this->Rbac_AddRoleToUser(Config::Get('module.user.rbac_role_default'), $oUser);
            return $oUser;
        }
        return false;
    }

    /**
     * Получить юзера по ключу активации
     *
     * @param string $sKey Ключ активации
     * @return ModuleUser_EntityUser|null
     */
    public function GetUserByActivateKey($sKey)
    {
        $id = $this->oMapper->GetUserByActivateKey($sKey);
        return $this->GetUserById($id);
    }

    /**
     * Получить юзера по ключу сессии
     *
     * @param string $sKey Сессионный ключ
     * @return ModuleUser_EntityUser|null
     */
    public function GetUserBySessionKey($sKey)
    {
        $id = $this->oMapper->GetUserBySessionKey($sKey);
        return $this->GetUserById($id);
    }

    /**
     * Получить юзера по мылу
     *
     * @param string $sMail Емайл
     * @return ModuleUser_EntityUser|null
     */
    public function GetUserByMail($sMail)
    {
        $id = $this->oMapper->GetUserByMail($sMail);
        return $this->GetUserById($id);
    }

    /**
     * Получить юзера по реферальному коду
     *
     * @param string $sCode Реферальный код
     * @return ModuleUser_EntityUser|null
     */
    public function GetUserByReferralCode($sCode)
    {
        $id = $this->oMapper->GetUserByReferralCode($sCode);
        return $this->GetUserById($id);
    }

    /**
     * Получить юзера по логину
     *
     * @param string $sLogin Логин пользователя
     * @return ModuleUser_EntityUser|null
     */
    public function GetUserByLogin($sLogin)
    {
        $s = strtolower($sLogin);
        if (false === ($id = $this->Cache_Get("user_login_{$s}"))) {
            if ($id = $this->oMapper->GetUserByLogin($sLogin)) {
                $this->Cache_Set($id, "user_login_{$s}", array(), 60 * 60 * 24 * 1);
            }
        }
        return $this->GetUserById($id);
    }

    /**
     * Получить юзера по айдишнику
     *
     * @param int $sId ID пользователя
     * @return ModuleUser_EntityUser|null
     */
    public function GetUserById($sId)
    {
        if (!is_numeric($sId)) {
            return null;
        }
        $aUsers = $this->GetUsersAdditionalData($sId);
        if (isset($aUsers[$sId])) {
            return $aUsers[$sId];
        }
        return null;
    }

    /**
     * Обновляет юзера
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @return bool
     */
    public function Update(ModuleUser_EntityUser $oUser)
    {
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_update'));
        $this->Cache_Delete("user_{$oUser->getId()}");
        return $this->oMapper->Update($oUser);
    }

    /**
     * Авторизовывает юзера
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @param bool $bRemember Запоминать пользователя или нет
     * @param string $sKey Уникальный ключ сессии
     * @return bool
     */
    public function Authorization(ModuleUser_EntityUser $oUser, $bRemember = true, $sKey = null)
    {
        if (!$oUser->getId() or !$oUser->getActivate()) {
            return false;
        }
        /**
         * Создаём новую сессию
         */
        if (!$this->CreateSession($oUser, $sKey)) {
            return false;
        }
        /**
         * Запоминаем в сесси юзера
         */
        $this->Session_Set('user_id', $oUser->getId());
        $this->Session_Set('session_key', $this->oSession->getKey());
        $this->oUserCurrent = $oUser;
        /**
         * Ставим куку
         */
        if ($bRemember) {
            $this->Session_SetCookie('key', $this->oSession->getKey(),
                time() + Config::Get('module.user.time_login_remember'), false,
                true);
        }
        return true;
    }

    /**
     * Автоматическое заллогинивание по ключу из куков
     *
     */
    protected function AutoLogin()
    {
        if ($this->oUserCurrent) {
            return;
        }
        if ($sKey = $this->Session_GetCookie('key') and is_string($sKey)) {
            if ($oUser = $this->GetUserBySessionKey($sKey) and $oSession = $this->oMapper->GetSessionByKey($sKey) and $oSession->isActive()) {
                /**
                 * Перед запуском авторизации дополнительно можно проверить user-agent'а пользователя
                 */
                $this->Authorization($oUser, true, $oSession->getKey());
            } else {
                $this->Logout();
            }
        }
    }

    /**
     * Авторизован ли юзер
     *
     * @return bool
     */
    public function IsAuthorization()
    {
        if ($this->oUserCurrent) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получить текущего юзера
     *
     * @return ModuleUser_EntityUser|null
     */
    public function GetUserCurrent()
    {
        return $this->oUserCurrent;
    }

    /**
     * Устанавливает текущего пользователя
     *
     * @param ModuleUser_EntityUser $oUser
     */
    public function SetUserCurrent($oUser)
    {
        $this->oUserCurrent = $oUser;
    }

    /**
     * Обновляет данные текущего пользователя
     *
     * @param bool $bSafe Обновлять только данные объекта ($bSafe=true) или полностью весь объект. При обновлении всего объекта происходит потеря связей старых ссылок на объект.
     */
    public function ReloadUserCurrent($bSafe = true)
    {
        if ($this->oUserCurrent and $oUser = $this->GetUserById($this->oUserCurrent->getId())) {
            if ($bSafe) {
                $this->oUserCurrent->_setData($oUser->_getData());
            } else {
                $this->oUserCurrent = $oUser;
            }
        }
    }

    /**
     * Проверяет является ли текущий пользователь администратором
     *
     * @param bool $bReturnUser Возвращать или нет объект пользователя
     *
     * @return bool|ModuleUser_EntityUser
     */
    public function GetIsAdmin($bReturnUser = false)
    {
        if ($this->oUserCurrent and $this->oUserCurrent->isAdministrator()) {
            return $bReturnUser ? $this->oUserCurrent : true;
        }
        return false;
    }

    /**
     * Разлогинивание
     *
     */
    public function Logout()
    {
        /**
         * Закрываем текущую сессию
         */
        if ($this->oSession) {
            $this->oSession->setDateLast(date("Y-m-d H:i:s"));
            $this->oSession->setIpLast(func_getIp());
            $this->oSession->setDateClose(date("Y-m-d H:i:s"));
            $this->oMapper->UpdateSession($this->oSession);
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_session_update'));
        }
        $this->oUserCurrent = null;
        $this->oSession = null;
        /**
         * Дропаем из сессии
         */
        $this->Session_Drop('user_id');
        $this->Session_Drop('session_key');
        /**
         * Дропаем куку
         */
        $this->Session_DropCookie('key');
    }

    /**
     * Обновление данных сессии
     * Важный момент: сессию обновляем в кеше и раз в 10 минут скидываем в БД
     */
    protected function UpdateSession()
    {
        $this->oSession->setDateLast(date("Y-m-d H:i:s"));
        $this->oSession->setIpLast(func_getIp());
        if (false === ($data = $this->Cache_Get("user_session_{$this->oSession->getUserId()}"))) {
            $data = array(
                'time'    => time(),
                'session' => $this->oSession
            );
        } else {
            $data['session'] = $this->oSession;
        }
        if (!Config::Get('sys.cache.use') or $data['time'] < time() - 60 * 10) {
            $data['time'] = time();
            $this->oMapper->UpdateSession($this->oSession);
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_session_update'));
        }
        $this->Cache_Set($data, "user_session_{$this->oSession->getUserId()}", array(), 60 * 60 * 24 * 4);
    }

    /**
     * Создание пользовательской сессии
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @param string $sKey Сессионный ключ
     * @return bool
     */
    protected function CreateSession(ModuleUser_EntityUser $oUser, $sKey = null)
    {
        /**
         * Генерим новый ключ
         */
        if (is_null($sKey)) {
            $sKey = md5(func_generator() . time() . $oUser->getId());
        }

        /**
         * Проверяем ключ сессии
         */
        if ($oSession = $this->oMapper->GetSessionByKey($sKey)) {
            /**
             * Если сессия уже не активна, то удаляем её
             */
            if (!$oSession->isActive()) {
                $this->oMapper->DeleteSession($oSession);
                unset($oSession);
            }
        }

        if (!isset($oSession)) {
            /**
             * Проверяем количество активных сессий у пользователя и завершаем сверх лимита
             */
            $iCountMaxSessions = Config::Get('module.user.count_auth_session');
            $aSessions = $this->GetSessionsByUserId($oUser->getId());
            $aSessions = array_slice($aSessions, ($iCountMaxSessions - 1 < 0) ? 0 : $iCountMaxSessions - 1);
            foreach ($aSessions as $oSessionOld) {
                $oSessionOld->setDateClose(date("Y-m-d H:i:s"));
                $this->oMapper->UpdateSession($oSessionOld);
            }
            /**
             * Проверяем количество всех сессий у пользователя и удаляем сверх лимита
             */
            $iCountMaxSessions = Config::Get('module.user.count_auth_session_history');
            $aSessions = $this->GetSessionsByUserId($oUser->getId(), false);
            $aSessions = array_slice($aSessions, ($iCountMaxSessions - 1 < 0) ? 0 : $iCountMaxSessions - 1);
            foreach ($aSessions as $oSessionOld) {
                $this->oMapper->DeleteSession($oSessionOld);
            }
        }

        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('user_session_update'));
        $this->Cache_Delete("user_session_{$oUser->getId()}");
        /**
         * Создаем новую или обновляем данные у старой
         */
        if (!isset($oSession)) {
            $oSession = Engine::GetEntity('User_Session');
            $oSession->setKey($sKey);
            $oSession->setIpCreate(func_getIp());
            $oSession->setDateCreate(date("Y-m-d H:i:s"));
            $oSession->setExtraParam('user_agent',
                isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
        }
        $oSession->setUserId($oUser->getId());
        $oSession->setIpLast(func_getIp());
        $oSession->setDateLast(date("Y-m-d H:i:s"));

        if ($this->oMapper->CreateSession($oSession)) {
            $this->oSession = $oSession;
            return true;
        }
        return false;
    }

    public function GetSessionsByUserId($iUserId, $bOnlyNotClose = true)
    {
        return $this->oMapper->GetSessionsByUserId($iUserId, $bOnlyNotClose);
    }

    /**
     * Возвращает список пользователей по фильтру
     *
     * @param array $aFilter Фильтр
     * @param array $aOrder Сортировка
     * @param int $iCurrPage Номер страницы
     * @param int $iPerPage Количество элментов на страницу
     * @param array $aAllowData Список типо данных для подгрузки к пользователям
     * @return array('collection'=>array,'count'=>int)
     */
    public function GetUsersByFilter($aFilter, $aOrder, $iCurrPage, $iPerPage, $aAllowData = null)
    {
        $sKey = "user_filter_" . serialize($aFilter) . serialize($aOrder) . "_{$iCurrPage}_{$iPerPage}";
        if (false === ($data = $this->Cache_Get($sKey))) {
            $data = array(
                'collection' => $this->oMapper->GetUsersByFilter($aFilter, $aOrder, $iCount, $iCurrPage, $iPerPage),
                'count'      => $iCount
            );
            /**
             * Если есть фильтр по "кто онлайн", то уменьшаем время кеширования до 10 минут
             */
            $iTimeCache = isset($aFilter['date_last_more']) ? 60 * 10 : 60 * 60 * 24 * 2;
            $this->Cache_Set($data, $sKey, array("user_update", "user_new"), $iTimeCache);
        }
        $data['collection'] = $this->GetUsersAdditionalData($data['collection'], $aAllowData);
        return $data;
    }

    /**
     * Получить список юзеров по дате регистрации
     *
     * @param int $iLimit Количество
     * @return array
     */
    public function GetUsersByDateRegister($iLimit = 20)
    {
        $aResult = $this->GetUsersByFilter(array('activate' => 1), array('id' => 'desc'), 1, $iLimit);
        return $aResult['collection'];
    }

    /**
     * Получить статистику по юзерам
     *
     * @return array
     */
    public function GetStatUsers()
    {
        if (false === ($aStat = $this->Cache_Get("user_stats"))) {
            $aStat['count_all'] = $this->oMapper->GetCountUsers();
            $sDate = date("Y-m-d H:i:s", time() - Config::Get('module.user.time_active'));
            $aStat['count_active'] = $this->oMapper->GetCountUsersActive($sDate);
            $aStat['count_inactive'] = $aStat['count_all'] - $aStat['count_active'];
            $aSex = $this->oMapper->GetCountUsersSex();
            $aStat['count_sex_man'] = (isset($aSex['man']) ? $aSex['man']['count'] : 0);
            $aStat['count_sex_woman'] = (isset($aSex['woman']) ? $aSex['woman']['count'] : 0);
            $aStat['count_sex_other'] = (isset($aSex['other']) ? $aSex['other']['count'] : 0);

            $this->Cache_Set($aStat, "user_stats", array("user_update", "user_new"), 60 * 60 * 24 * 4);
        }
        return $aStat;
    }

    /**
     * Получить список юзеров по первым  буквам логина
     *
     * @param string $sUserLogin Логин
     * @param int $iLimit Количество
     * @return array
     */
    public function GetUsersByLoginLike($sUserLogin, $iLimit)
    {
        if (false === ($data = $this->Cache_Get("user_like_{$sUserLogin}_{$iLimit}"))) {
            $data = $this->oMapper->GetUsersByLoginLike($sUserLogin, $iLimit);
            $this->Cache_Set($data, "user_like_{$sUserLogin}_{$iLimit}", array("user_new"), 60 * 60 * 24 * 2);
        }
        $data = $this->GetUsersAdditionalData($data);
        return $data;
    }

    /**
     * Получить список отношений друзей
     *
     * @param  array $aUserId Список ID пользователей проверяемых на дружбу
     * @param  int $sUserId ID пользователя у которого проверяем друзей
     * @return array
     */
    public function GetFriendsByArray($aUserId, $sUserId)
    {
        if (!$aUserId) {
            return array();
        }
        if (Config::Get('sys.cache.solid')) {
            return $this->GetFriendsByArraySolid($aUserId, $sUserId);
        }
        if (!is_array($aUserId)) {
            $aUserId = array($aUserId);
        }
        $aUserId = array_unique($aUserId);
        $aFriends = array();
        $aUserIdNotNeedQuery = array();
        /**
         * Делаем мульти-запрос к кешу
         */
        $aCacheKeys = func_build_cache_keys($aUserId, 'user_friend_', '_' . $sUserId);
        if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
            /**
             * проверяем что досталось из кеша
             */
            foreach ($aCacheKeys as $sValue => $sKey) {
                if (array_key_exists($sKey, $data)) {
                    if ($data[$sKey]) {
                        $aFriends[$data[$sKey]->getFriendId()] = $data[$sKey];
                    } else {
                        $aUserIdNotNeedQuery[] = $sValue;
                    }
                }
            }
        }
        /**
         * Смотрим каких френдов не было в кеше и делаем запрос в БД
         */
        $aUserIdNeedQuery = array_diff($aUserId, array_keys($aFriends));
        $aUserIdNeedQuery = array_diff($aUserIdNeedQuery, $aUserIdNotNeedQuery);
        $aUserIdNeedStore = $aUserIdNeedQuery;
        if ($data = $this->oMapper->GetFriendsByArrayId($aUserIdNeedQuery, $sUserId)) {
            foreach ($data as $oFriend) {
                /**
                 * Добавляем к результату и сохраняем в кеш
                 */
                $aFriends[$oFriend->getFriendId($sUserId)] = $oFriend;
                /**
                 * Тут кеш нужно будет продумать как-то по другому.
                 * Пока не трогаю, ибо этот код все равно не выполняется.
                 * by Kachaev
                 */
                $this->Cache_Set($oFriend, "user_friend_{$oFriend->getFriendId()}_{$oFriend->getUserId()}", array(),
                    60 * 60 * 24 * 4);
                $aUserIdNeedStore = array_diff($aUserIdNeedStore, array($oFriend->getFriendId()));
            }
        }
        /**
         * Сохраняем в кеш запросы не вернувшие результата
         */
        foreach ($aUserIdNeedStore as $sId) {
            $this->Cache_Set(null, "user_friend_{$sId}_{$sUserId}", array(), 60 * 60 * 24 * 4);
        }
        /**
         * Сортируем результат согласно входящему массиву
         */
        $aFriends = func_array_sort_by_keys($aFriends, $aUserId);
        return $aFriends;
    }

    /**
     * Получить список отношений друзей используя единый кеш
     *
     * @param  array $aUserId Список ID пользователей проверяемых на дружбу
     * @param  int $sUserId ID пользователя у которого проверяем друзей
     * @return array
     */
    public function GetFriendsByArraySolid($aUserId, $sUserId)
    {
        if (!is_array($aUserId)) {
            $aUserId = array($aUserId);
        }
        $aUserId = array_unique($aUserId);
        $aFriends = array();
        $s = join(',', $aUserId);
        if (false === ($data = $this->Cache_Get("user_friend_{$sUserId}_id_{$s}"))) {
            $data = $this->oMapper->GetFriendsByArrayId($aUserId, $sUserId);
            foreach ($data as $oFriend) {
                $aFriends[$oFriend->getFriendId($sUserId)] = $oFriend;
            }

            $this->Cache_Set($aFriends, "user_friend_{$sUserId}_id_{$s}", array("friend_change_user_{$sUserId}"),
                60 * 60 * 24 * 1);
            return $aFriends;
        }
        return $data;
    }

    /**
     * Получаем привязку друга к юзеру(есть ли у юзера данный друг)
     *
     * @param  int $sFriendId ID пользователя друга
     * @param  int $sUserId ID пользователя
     * @return ModuleUser_EntityFriend|null
     */
    public function GetFriend($sFriendId, $sUserId)
    {
        $data = $this->GetFriendsByArray($sFriendId, $sUserId);
        if (isset($data[$sFriendId])) {
            return $data[$sFriendId];
        }
        return null;
    }

    /**
     * Добавляет друга
     *
     * @param  ModuleUser_EntityFriend $oFriend Объект дружбы(связи пользователей)
     * @return bool
     */
    public function AddFriend(ModuleUser_EntityFriend $oFriend)
    {
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array("friend_change_user_{$oFriend->getUserFrom()}", "friend_change_user_{$oFriend->getUserTo()}"));
        $this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
        $this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");

        return $this->oMapper->AddFriend($oFriend);
    }

    /**
     * Удаляет друга
     *
     * @param  ModuleUser_EntityFriend $oFriend Объект дружбы(связи пользователей)
     * @return bool
     */
    public function DeleteFriend(ModuleUser_EntityFriend $oFriend)
    {
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array("friend_change_user_{$oFriend->getUserFrom()}", "friend_change_user_{$oFriend->getUserTo()}"));
        $this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
        $this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");

        // устанавливаем статус дружбы "удалено"
        $oFriend->setStatusByUserId(ModuleUser::USER_FRIEND_DELETE, $oFriend->getUserId());
        return $this->oMapper->UpdateFriend($oFriend);
    }

    /**
     * Удаляет информацию о дружбе из базы данных
     *
     * @param  ModuleUser_EntityFriend $oFriend Объект дружбы(связи пользователей)
     * @return bool
     */
    public function EraseFriend(ModuleUser_EntityFriend $oFriend)
    {
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array("friend_change_user_{$oFriend->getUserFrom()}", "friend_change_user_{$oFriend->getUserTo()}"));
        $this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
        $this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");
        return $this->oMapper->EraseFriend($oFriend);
    }

    /**
     * Обновляет информацию о друге
     *
     * @param  ModuleUser_EntityFriend $oFriend Объект дружбы(связи пользователей)
     * @return bool
     */
    public function UpdateFriend(ModuleUser_EntityFriend $oFriend)
    {
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array("friend_change_user_{$oFriend->getUserFrom()}", "friend_change_user_{$oFriend->getUserTo()}"));
        $this->Cache_Delete("user_friend_{$oFriend->getUserFrom()}_{$oFriend->getUserTo()}");
        $this->Cache_Delete("user_friend_{$oFriend->getUserTo()}_{$oFriend->getUserFrom()}");
        return $this->oMapper->UpdateFriend($oFriend);
    }

    /**
     * Получает список друзей
     *
     * @param  int $sUserId ID пользователя
     * @param  int $iPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @return array
     */
    public function GetUsersFriend($sUserId, $iPage = 1, $iPerPage = 10)
    {
        $sKey = "user_friend_{$sUserId}_{$iPage}_{$iPerPage}";
        if (false === ($data = $this->Cache_Get($sKey))) {
            $data = array(
                'collection' => $this->oMapper->GetUsersFriend($sUserId, $iCount, $iPage, $iPerPage),
                'count'      => $iCount
            );
            $this->Cache_Set($data, $sKey, array("friend_change_user_{$sUserId}"), 60 * 60 * 24 * 2);
        }
        $data['collection'] = $this->GetUsersAdditionalData($data['collection']);
        return $data;
    }

    /**
     * Получает количество друзей
     *
     * @param  int $sUserId ID пользователя
     * @return int
     */
    public function GetCountUsersFriend($sUserId)
    {
        $sKey = "count_user_friend_{$sUserId}";
        if (false === ($data = $this->Cache_Get($sKey))) {
            $data = $this->oMapper->GetCountUsersFriend($sUserId);
            $this->Cache_Set($data, $sKey, array("friend_change_user_{$sUserId}"), 60 * 60 * 24 * 2);
        }
        return $data;
    }

    /**
     * Добавляем воспоминание(восстановление) пароля
     *
     * @param ModuleUser_EntityReminder $oReminder Объект восстановления пароля
     * @return bool
     */
    public function AddReminder(ModuleUser_EntityReminder $oReminder)
    {
        return $this->oMapper->AddReminder($oReminder);
    }

    /**
     * Сохраняем воспомнинание(восстановление) пароля
     *
     * @param ModuleUser_EntityReminder $oReminder Объект восстановления пароля
     * @return bool
     */
    public function UpdateReminder(ModuleUser_EntityReminder $oReminder)
    {
        return $this->oMapper->UpdateReminder($oReminder);
    }

    /**
     * Получаем запись восстановления пароля по коду
     *
     * @param string $sCode Код восстановления пароля
     * @return ModuleUser_EntityReminder|null
     */
    public function GetReminderByCode($sCode)
    {
        return $this->oMapper->GetReminderByCode($sCode);
    }

    /**
     * Создает аватар пользователя на основе области из изображения
     *
     * @param      $sFileFrom
     * @param      $oUser
     * @param      $aSize
     * @param null $iCanvasWidth
     *
     * @return bool
     */
    public function CreateProfileAvatar($sFileFrom, $oUser, $aSize = null, $iCanvasWidth = null)
    {
        $aParams = $this->Image_BuildParams('profile_avatar');
        /**
         * Если объект изображения не создан, возвращаем ошибку
         */
        if (!$oImage = $this->Image_OpenFrom($sFileFrom, $aParams)) {
            return $this->Image_GetLastError();
        }
        /**
         * Если нет области, то берем центральный квадрат
         */
        if (!$aSize) {
            $oImage->cropSquare();
        } else {
            /**
             * Вырезаем область из исходного файла
             */
            $oImage->cropFromSelected($aSize, $iCanvasWidth);
        }
        if ($sError = $this->Image_GetLastError()) {
            return $sError;
        }
        /**
         * Сохраняем во временный файл для дальнейшего ресайза
         */
        if (false === ($sFileTmp = $oImage->saveTmp())) {
            return $this->Image_GetLastError();
        }
        $sPath = $this->Image_GetIdDir($oUser->getId(), 'users');
        /**
         * Удаляем старый аватар
         */
        $this->DeleteProfileAvatar($oUser);
        /**
         * Имя файла для сохранения
         */
        $sFileName = 'avatar-user-' . $oUser->getId();
        /**
         * Сохраняем оригинальный аватар
         */
        if (false === ($sFileResult = $oImage->saveSmart($sPath, $sFileName))) {
            return $this->Image_GetLastError();
        }
        /**
         * Генерируем варианты с необходимыми размерами
         */
        $this->Media_GenerateImageBySizes($sFileTmp, $sPath, $sFileName, Config::Get('module.user.avatar_size'),
            $aParams);
        /**
         * Теперь можно удалить временный файл
         */
        $this->Fs_RemoveFileLocal($sFileTmp);
        $oUser->setProfileAvatar($sFileResult);
        $this->User_Update($oUser);
        return true;
    }

    /**
     * Создает фото пользователя на основе области из изображения
     *
     * @param      $sFileFrom
     * @param      $oUser
     * @param      $aSize
     * @param null $iCanvasWidth
     *
     * @return bool
     */
    public function CreateProfilePhoto($sFileFrom, $oUser, $aSize = null, $iCanvasWidth = null)
    {
        $aParams = $this->Image_BuildParams('profile_photo');
        /**
         * Если объект изображения не создан, возвращаем ошибку
         */
        if (!$oImage = $this->Image_OpenFrom($sFileFrom, $aParams)) {
            return $this->Image_GetLastError();
        }
        /**
         * Вырезаем область из исходного файла
         */
        if ($aSize) {
            $oImage->cropFromSelected($aSize, $iCanvasWidth);
        }
        if ($sError = $this->Image_GetLastError()) {
            return $sError;
        }
        /**
         * Сохраняем во временный файл для дальнейшего ресайза
         */
        if (false === ($sFileTmp = $oImage->saveTmp())) {
            return $this->Image_GetLastError();
        }
        $sPath = $this->Image_GetIdDir($oUser->getId(), 'users');
        /**
         * Имя файла для сохранения
         */
        $sFileName = func_generator(8);
        /**
         * Сохраняем копию нужного размера
         */
        $aSize = $this->Media_ParsedImageSize(Config::Get('module.user.profile_photo_size'));
        if ($aSize['crop']) {
            $oImage->cropProportion($aSize['w'] / $aSize['h'], 'center');
        }
        if (!$sFileResult = $oImage->resize($aSize['w'], $aSize['h'], true)->saveSmart($sPath, $sFileName)) {
            return $this->Image_GetLastError();
        }
        /**
         * Теперь можно удалить временный файл
         */
        $this->Fs_RemoveFileLocal($sFileTmp);
        /**
         * Если было старое фото, то удаляем
         */
        $this->DeleteProfilePhoto($oUser);
        $oUser->setProfileFoto($sFileResult);
        $this->User_Update($oUser);
        return true;
    }

    /**
     * Загрузка фото в профиль пользователя
     *
     * @param $aFile
     * @param $oUser
     *
     * @return bool
     */
    public function UploadProfilePhoto($aFile, $oUser)
    {
        if (!is_array($aFile) || !isset($aFile['tmp_name'])) {
            return false;
        }

        $sFileTmp = Config::Get('sys.cache.dir') . func_generator();
        if (!move_uploaded_file($aFile['tmp_name'], $sFileTmp)) {
            return false;
        }

        $aParams = $this->Image_BuildParams('profile_photo');
        /**
         * Если объект изображения не создан, возвращаем ошибку
         */
        if (!$oImage = $this->Image_Open($sFileTmp, $aParams)) {
            $this->Fs_RemoveFileLocal($sFileTmp);
            return $this->Image_GetLastError();
        }
        $sPath = $this->Image_GetIdDir($oUser->getId(), 'users');
        /**
         * Имя файла для сохранения
         */
        $sFileName = func_generator(8);
        /**
         * Сохраняем копию нужного размера
         */
        $aSize = $this->Media_ParsedImageSize(Config::Get('module.user.profile_photo_size'));
        if ($aSize['crop']) {
            $oImage->cropProportion($aSize['w'] / $aSize['h'], 'center');
        }
        if (!$sFileResult = $oImage->resize($aSize['w'], $aSize['h'], true)->saveSmart($sPath, $sFileName)) {
            return $this->Image_GetLastError();
        }
        /**
         * Теперь можно удалить временный файл
         */
        $this->Fs_RemoveFileLocal($sFileTmp);
        /**
         * Если было старое фото, то удаляем
         */
        $this->DeleteProfilePhoto($oUser);
        $oUser->setProfileFoto($sFileResult);
        $this->User_Update($oUser);
        return true;
    }

    /**
     * Удаляет фото пользователя
     *
     * @param ModuleUser_EntityUser $oUser
     */
    public function DeleteProfilePhoto($oUser)
    {
        if ($oUser->getProfileFoto()) {
            $this->Image_RemoveFile($oUser->getProfileFoto());
            $oUser->setProfileFoto(null);
        }
    }

    /**
     * Удаляет аватар пользователя
     *
     * @param ModuleUser_EntityUser $oUser
     */
    public function DeleteProfileAvatar($oUser)
    {
        if ($oUser->getProfileAvatar()) {
            $this->Media_RemoveImageBySizes($oUser->getProfileAvatar(), Config::Get('module.user.avatar_size'));
            $oUser->setProfileAvatar(null);
        }
    }

    /**
     * Проверяет логин на корректность
     *
     * @param string $sLogin Логин пользователя
     * @return bool
     */
    public function CheckLogin($sLogin)
    {
        $charset = Config::Get('module.user.login.charset');
        $min = Config::Get('module.user.login.min_size');
        $max = Config::Get('module.user.login.max_size');
        if (preg_match('/^[' . $charset . ']{' . $min . ',' . $max . '}$/i', $sLogin)) {
            return true;
        }
        return false;
    }

    /**
     * Получить дополнительные поля профиля пользователя
     *
     * @param array|null $aType Типы полей, null - все типы
     * @return array
     */
    public function getUserFields($aType = null)
    {
        return $this->oMapper->getUserFields($aType);
    }

    /**
     * Получить значения дополнительных полей профиля пользователя
     *
     * @param int $iUserId ID пользователя
     * @param bool $bOnlyNoEmpty Загружать только непустые поля
     * @param array $aType Типы полей, null - все типы
     * @return array
     */
    public function getUserFieldsValues($iUserId, $bOnlyNoEmpty = true, $aType = array(''))
    {
        return $this->oMapper->getUserFieldsValues($iUserId, $bOnlyNoEmpty, $aType);
    }

    /**
     * Получить по имени поля его значение дял определённого пользователя
     *
     * @param int $iUserId ID пользователя
     * @param string $sName Имя поля
     * @return string
     */
    public function getUserFieldValueByName($iUserId, $sName)
    {
        return $this->oMapper->getUserFieldValueByName($iUserId, $sName);
    }

    /**
     * Установить значения дополнительных полей профиля пользователя
     *
     * @param int $iUserId ID пользователя
     * @param array $aFields Ассоциативный массив полей id => value
     * @param int $iCountMax Максимальное количество одинаковых полей
     * @return bool
     */
    public function setUserFieldsValues($iUserId, $aFields, $iCountMax = 1)
    {
        return $this->oMapper->setUserFieldsValues($iUserId, $aFields, $iCountMax);
    }

    /**
     * Добавить поле
     *
     * @param ModuleUser_EntityField $oField Объект пользовательского поля
     * @return bool
     */
    public function addUserField($oField)
    {
        return $this->oMapper->addUserField($oField);
    }

    /**
     * Изменить поле
     *
     * @param ModuleUser_EntityField $oField Объект пользовательского поля
     * @return bool
     */
    public function updateUserField($oField)
    {
        return $this->oMapper->updateUserField($oField);
    }

    /**
     * Удалить поле
     *
     * @param int $iId ID пользовательского поля
     * @return bool
     */
    public function deleteUserField($iId)
    {
        return $this->oMapper->deleteUserField($iId);
    }

    /**
     * Проверяет существует ли поле с таким именем
     *
     * @param string $sName Имя поля
     * @param int|null $iId ID поля
     * @return bool
     */
    public function userFieldExistsByName($sName, $iId = null)
    {
        return $this->oMapper->userFieldExistsByName($sName, $iId);
    }

    /**
     * Проверяет существует ли поле с таким ID
     *
     * @param int $iId ID поля
     * @return bool
     */
    public function userFieldExistsById($iId)
    {
        return $this->oMapper->userFieldExistsById($iId);
    }

    /**
     * Удаляет у пользователя значения полей
     *
     * @param int $iUserId ID пользователя
     * @param array|null $aType Список типов для удаления
     * @return bool
     */
    public function DeleteUserFieldValues($iUserId, $aType = null)
    {
        return $this->oMapper->DeleteUserFieldValues($iUserId, $aType);
    }

    /**
     * Возвращает список заметок пользователя
     *
     * @param int $iUserId ID пользователя
     * @param int $iCurrPage Номер страницы
     * @param int $iPerPage Количество элементов на страницу
     * @return array('collection'=>array,'count'=>int)
     */
    public function GetUserNotesByUserId($iUserId, $iCurrPage, $iPerPage)
    {
        $aResult = $this->oMapper->GetUserNotesByUserId($iUserId, $iCount, $iCurrPage, $iPerPage);
        /**
         * Цепляем пользователей
         */
        $aUserId = array();
        foreach ($aResult as $oNote) {
            $aUserId[] = $oNote->getTargetUserId();
        }
        $aUsers = $this->GetUsersAdditionalData($aUserId, array());
        foreach ($aResult as $oNote) {
            if (isset($aUsers[$oNote->getTargetUserId()])) {
                $oNote->setTargetUser($aUsers[$oNote->getTargetUserId()]);
            } else {
                $oNote->setTargetUser(Engine::GetEntity('User')); // пустого пользователя во избеания ошибок, т.к. пользователь всегда должен быть
            }
        }
        return array('collection' => $aResult, 'count' => $iCount);
    }

    /**
     * Возвращает список пользователей к которым юзер оставлял заметку
     *
     * @param int $iUserId ID пользователя
     * @param int $iCurrPage Номер страницы
     * @param int $iPerPage Количество элементов на страницу
     *
     * @return array('collection'=>array,'count'=>int)
     */
    public function GetUsersByNoteAndUserId($iUserId, $iCurrPage, $iPerPage)
    {
        $aUsersId = $this->oMapper->GetUsersByNoteAndUserId($iUserId, $iCount, $iCurrPage, $iPerPage);
        $aResult = $this->GetUsersAdditionalData($aUsersId);
        return array('collection' => $aResult, 'count' => $iCount);
    }

    /**
     * Возвращает количество заметок у пользователя
     *
     * @param int $iUserId ID пользователя
     * @return int
     */
    public function GetCountUserNotesByUserId($iUserId)
    {
        return $this->oMapper->GetCountUserNotesByUserId($iUserId);
    }

    /**
     * Возвращет заметку по автору и пользователю
     *
     * @param int $iTargetUserId ID пользователя о ком заметка
     * @param int $iUserId ID пользователя автора заметки
     * @return ModuleUser_EntityNote
     */
    public function GetUserNote($iTargetUserId, $iUserId)
    {
        return $this->oMapper->GetUserNote($iTargetUserId, $iUserId);
    }

    /**
     * Возвращает заметку по ID
     *
     * @param int $iId ID заметки
     * @return ModuleUser_EntityNote
     */
    public function GetUserNoteById($iId)
    {
        return $this->oMapper->GetUserNoteById($iId);
    }

    /**
     * Возвращает список заметок пользователя по ID целевых юзеров
     *
     * @param array $aUserId Список ID целевых пользователей
     * @param int $sUserId ID пользователя, кто оставлял заметки
     * @return array
     */
    public function GetUserNotesByArray($aUserId, $sUserId)
    {
        if (!$aUserId) {
            return array();
        }
        if (!is_array($aUserId)) {
            $aUserId = array($aUserId);
        }
        $aUserId = array_unique($aUserId);
        $aNotes = array();
        $s = join(',', $aUserId);
        if (false === ($data = $this->Cache_Get("user_notes_{$sUserId}_id_{$s}"))) {
            $data = $this->oMapper->GetUserNotesByArrayUserId($aUserId, $sUserId);
            foreach ($data as $oNote) {
                $aNotes[$oNote->getTargetUserId()] = $oNote;
            }

            $this->Cache_Set($aNotes, "user_notes_{$sUserId}_id_{$s}", array("user_note_change_by_user_{$sUserId}"),
                60 * 60 * 24 * 1);
            return $aNotes;
        }
        return $data;
    }

    /**
     * Удаляет заметку по ID
     *
     * @param int $iId ID заметки
     * @return bool
     */
    public function DeleteUserNoteById($iId)
    {
        if ($oNote = $this->GetUserNoteById($iId)) {
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                array("user_note_change_by_user_{$oNote->getUserId()}"));
        }
        return $this->oMapper->DeleteUserNoteById($iId);
    }

    /**
     * Сохраняет заметку в БД, если ее нет то создает новую
     *
     * @param ModuleUser_EntityNote $oNote Объект заметки
     * @return bool|ModuleUser_EntityNote
     */
    public function SaveNote($oNote)
    {
        if (!$oNote->getDateAdd()) {
            $oNote->setDateAdd(date("Y-m-d H:i:s"));
        }

        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array("user_note_change_by_user_{$oNote->getUserId()}"));
        if ($oNoteOld = $this->GetUserNote($oNote->getTargetUserId(), $oNote->getUserId())) {
            $oNoteOld->setText($oNote->getText());
            $this->oMapper->UpdateUserNote($oNoteOld);
            return $oNoteOld;
        } else {
            if ($iId = $this->oMapper->AddUserNote($oNote)) {
                $oNote->setId($iId);
                return $oNote;
            }
        }
        return false;
    }

    public function AddComplaint($oComplaint)
    {
        if (!$oComplaint->getDateAdd()) {
            $oComplaint->setDateAdd(date("Y-m-d H:i:s"));
        }

        if ($iId = $this->oMapper->AddComplaint($oComplaint)) {
            $oComplaint->setId($iId);
            return $oComplaint;
        }
        return false;
    }

    /**
     * Возвращает список префиксов логинов пользователей (для алфавитного указателя)
     *
     * @param int $iPrefixLength Длина префикса
     * @return array
     */
    public function GetGroupPrefixUser($iPrefixLength = 1)
    {
        if (false === ($data = $this->Cache_Get("group_prefix_user_{$iPrefixLength}"))) {
            $data = $this->oMapper->GetGroupPrefixUser($iPrefixLength);
            $this->Cache_Set($data, "group_prefix_user_{$iPrefixLength}", array("user_new"), 60 * 60 * 24 * 1);
        }
        return $data;
    }

    /**
     * Добавляет запись о смене емайла
     *
     * @param ModuleUser_EntityChangemail $oChangemail Объект смены емайла
     * @return bool|ModuleUser_EntityChangemail
     */
    public function AddUserChangemail($oChangemail)
    {
        if ($sId = $this->oMapper->AddUserChangemail($oChangemail)) {
            $oChangemail->setId($sId);
            return $oChangemail;
        }
        return false;
    }

    /**
     * Обновляет запись о смене емайла
     *
     * @param ModuleUser_EntityChangemail $oChangemail Объект смены емайла
     * @return int
     */
    public function UpdateUserChangemail($oChangemail)
    {
        return $this->oMapper->UpdateUserChangemail($oChangemail);
    }

    /**
     * Возвращает объект смены емайла по коду подтверждения
     *
     * @param string $sCode Код подтверждения
     * @return ModuleUser_EntityChangemail|null
     */
    public function GetUserChangemailByCodeFrom($sCode)
    {
        return $this->oMapper->GetUserChangemailByCodeFrom($sCode);
    }

    /**
     * Возвращает объект смены емайла по коду подтверждения
     *
     * @param string $sCode Код подтверждения
     * @return ModuleUser_EntityChangemail|null
     */
    public function GetUserChangemailByCodeTo($sCode)
    {
        return $this->oMapper->GetUserChangemailByCodeTo($sCode);
    }

    /**
     * Формирование процесса смены емайла в профиле пользователя
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @param string $sMailNew Новый емайл
     * @return bool|ModuleUser_EntityChangemail
     */
    public function MakeUserChangemail($oUser, $sMailNew)
    {
        $oChangemail = Engine::GetEntity('ModuleUser_EntityChangemail');
        $oChangemail->setUserId($oUser->getId());
        $oChangemail->setDateAdd(date("Y-m-d H:i:s"));
        $oChangemail->setDateExpired(date("Y-m-d H:i:s", time() + 3 * 24 * 60 * 60)); // 3 дня для смены емайла
        $oChangemail->setMailFrom($oUser->getMail() ? $oUser->getMail() : '');
        $oChangemail->setMailTo($sMailNew);
        $oChangemail->setCodeFrom(func_generator(32));
        $oChangemail->setCodeTo(func_generator(32));
        if ($this->AddUserChangemail($oChangemail)) {
            /**
             * Если у пользователя раньше не было емайла, то сразу шлем подтверждение на новый емайл
             */
            if (!$oChangemail->getMailFrom()) {
                $oChangemail->setConfirmFrom(1);
                $this->User_UpdateUserChangemail($oChangemail);
                /**
                 * Отправляем уведомление на новый емайл
                 */
                $this->Notify_Send($oChangemail->getMailTo(),
                    'user_changemail_to.tpl',
                    $this->Lang_Get('emails.user_changemail.subject'),
                    array(
                        'oUser'       => $oUser,
                        'oChangemail' => $oChangemail,
                    ));

            } else {
                /**
                 * Отправляем уведомление на старый емайл
                 */
                $this->Notify_Send($oUser,
                    'user_changemail_from.tpl',
                    $this->Lang_Get('emails.user_changemail.subject'),
                    array(
                        'oUser'       => $oUser,
                        'oChangemail' => $oChangemail,
                    ));
            }
            return $oChangemail;
        }
        return false;
    }

    /**
     * Отправляет уведомление с новым линком активации
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     */
    public function SendNotifyReactivationCode(ModuleUser_EntityUser $oUser)
    {
        $this->Notify_Send(
            $oUser,
            'reactivation.tpl',
            $this->Lang_Get('emails.reactivation.subject'),
            array(
                'oUser' => $oUser,
            ), null, true
        );
    }

    /**
     * Отправляет уведомление при регистрации с активацией
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @param string $sPassword Пароль пользователя
     */
    public function SendNotifyRegistrationActivate(ModuleUser_EntityUser $oUser, $sPassword)
    {
        $this->Notify_Send(
            $oUser,
            'registration_activate.tpl',
            $this->Lang_Get('emails.registration_activate.subject'),
            array(
                'oUser'     => $oUser,
                'sPassword' => $sPassword,
            ), null, true
        );
    }

    /**
     * Отправляет уведомление о регистрации
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @param string $sPassword Пароль пользователя
     */
    public function SendNotifyRegistration(ModuleUser_EntityUser $oUser, $sPassword)
    {
        $this->Notify_Send(
            $oUser,
            'registration.tpl',
            $this->Lang_Get('emails.registration.subject'),
            array(
                'oUser'     => $oUser,
                'sPassword' => $sPassword,
            ), null, true
        );
    }

    /**
     * Отправляет пользователю сообщение о добавлении его в друзья
     *
     * @param ModuleUser_EntityUser $oUserTo Объект пользователя
     * @param ModuleUser_EntityUser $oUserFrom Объект пользователя, которого добавляем в друзья
     * @param string $sText Текст сообщения
     * @param string $sPath URL для подтверждения дружбы
     * @return bool
     */
    public function SendNotifyUserFriendNew(ModuleUser_EntityUser $oUserTo, ModuleUser_EntityUser $oUserFrom, $sText, $sPath)
    {
        /**
         * Проверяем можно ли юзеру рассылать уведомление
         */
        if (!$oUserTo->getSettingsNoticeNewFriend()) {
            return false;
        }
        $this->Notify_Send(
            $oUserTo,
            'user_friend_new.tpl',
            $this->Lang_Get('emails.user_friend_new.subject'),
            array(
                'oUserTo'   => $oUserTo,
                'oUserFrom' => $oUserFrom,
                'sText'     => $sText,
                'sPath'     => $sPath,
            )
        );
        return true;
    }

    /**
     * Уведомление при восстановлении пароля
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @param ModuleUser_EntityReminder $oReminder объект напоминания пароля
     */
    public function SendNotifyReminderCode(ModuleUser_EntityUser $oUser, ModuleUser_EntityReminder $oReminder)
    {
        $this->Notify_Send(
            $oUser,
            'reminder_code.tpl',
            $this->Lang_Get('emails.reminder_code.subject'),
            array(
                'oUser'     => $oUser,
                'oReminder' => $oReminder,
            ), null, true
        );
    }

    /**
     * Уведомление с новым паролем после его восставновления
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @param string $sNewPassword Новый пароль
     */
    public function SendNotifyReminderPassword(ModuleUser_EntityUser $oUser, $sNewPassword)
    {
        $this->Notify_Send(
            $oUser,
            'reminder_password.tpl',
            $this->Lang_Get('emails.reminder_password.subject'),
            array(
                'oUser'        => $oUser,
                'sNewPassword' => $sNewPassword,
            ), null, true
        );
    }

    /**
     * Уведомление администрации о новой жалобе
     *
     * @param $oComplaint
     */
    public function SendNotifyUserComplaint($oComplaint)
    {
        $this->Notify_Send(
            Config::Get('general.admin_mail'),
            'user_complaint.tpl',
            $this->Lang_Get('emails.user_complaint.subject'),
            array(
                'oUserTarget' => $oComplaint->getTargetUser(),
                'oUserFrom'   => $oComplaint->getUser(),
                'oComplaint'  => $oComplaint,
            )
        );
    }
}