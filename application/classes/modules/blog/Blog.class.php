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
 * Модуль для работы с блогами
 *
 * @package application.modules.blog
 * @since 1.0
 */
class ModuleBlog extends Module
{
    /**
     * Возможные роли пользователя в блоге
     */
    const BLOG_USER_ROLE_GUEST = 0;
    const BLOG_USER_ROLE_USER = 1;
    const BLOG_USER_ROLE_MODERATOR = 2;
    const BLOG_USER_ROLE_ADMINISTRATOR = 4;
    /**
     * Пользователь, приглашенный админом блога в блог
     */
    const BLOG_USER_ROLE_INVITE = -1;
    /**
     * Пользователь, отклонивший приглашение админа
     */
    const BLOG_USER_ROLE_REJECT = -2;
    /**
     * Забаненный в блоге пользователь
     */
    const BLOG_USER_ROLE_BAN = -4;
    /**
     * Список типов блога
     *
     * @var array
     */
    protected $aBlogTypes = array(
        'open',
        'close'
    );

    /**
     * Объект маппера
     *
     * @var ModuleBlog_MapperBlog
     */
    protected $oMapperBlog;
    /**
     * Объект текущего пользователя
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;
    /**
     * Список поведений
     *
     * @var array
     */
    protected $aBehaviors = array(
        // Категории
        'category' => array(
            'class'       => 'ModuleCategory_BehaviorModule',
            'target_type' => 'blog',
        ),
    );

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        $this->oMapperBlog = Engine::GetMapper(__CLASS__);
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    /**
     * Возвращает список типов блога
     *
     * @return array
     */
    public function GetBlogTypes()
    {
        return $this->aBlogTypes;
    }

    /**
     * Добавляет в новый тип блога
     *
     * @param string $sType Новый тип
     * @return bool
     */
    public function AddBlogType($sType)
    {
        if (!in_array($sType, $this->aBlogTypes)) {
            $this->aBlogTypes[] = $sType;
            return true;
        }
        return false;
    }

    /**
     * Проверяет разрешен ли данный тип блога
     *
     * @param string $sType Тип
     * @return bool
     */
    public function IsAllowBlogType($sType)
    {
        return in_array($sType, $this->aBlogTypes);
    }

    /**
     * Получает дополнительные данные(объекты) для блогов по их ID
     *
     * @param array $aBlogId Список ID блогов
     * @param array $aAllowData Список типов дополнительных данных, которые нужно получить для блогов
     * @param array $aOrder Порядок сортировки
     * @return array
     */
    public function GetBlogsAdditionalData($aBlogId, $aAllowData = null, $aOrder = null)
    {
        if (is_null($aAllowData)) {
            $aAllowData = array('vote', 'owner' => array(), 'relation_user');
        }
        func_array_simpleflip($aAllowData);
        if (!is_array($aBlogId)) {
            $aBlogId = array($aBlogId);
        }
        /**
         * Получаем блоги
         */
        $aBlogs = $this->GetBlogsByArrayId($aBlogId, $aOrder);
        /**
         * Формируем ID дополнительных данных, которые нужно получить
         */
        $aUserId = array();
        foreach ($aBlogs as $oBlog) {
            if (isset($aAllowData['owner'])) {
                $aUserId[] = $oBlog->getOwnerId();
            }
        }
        /**
         * Получаем дополнительные данные
         */
        $aBlogUsers = array();
        $aBlogsVote = array();
        $aUsers = isset($aAllowData['owner']) && is_array($aAllowData['owner']) ? $this->User_GetUsersAdditionalData($aUserId,
            $aAllowData['owner']) : $this->User_GetUsersAdditionalData($aUserId);
        if (isset($aAllowData['relation_user']) and $this->oUserCurrent) {
            $aBlogUsers = $this->GetBlogUsersByArrayBlog($aBlogId, $this->oUserCurrent->getId());
        }
        if (isset($aAllowData['vote']) and $this->oUserCurrent) {
            $aBlogsVote = $this->Vote_GetVoteByArray($aBlogId, 'blog', $this->oUserCurrent->getId());
        }
        /**
         * Добавляем данные к результату - списку блогов
         */
        foreach ($aBlogs as $oBlog) {
            if (isset($aUsers[$oBlog->getOwnerId()])) {
                $oBlog->setOwner($aUsers[$oBlog->getOwnerId()]);
            } else {
                $oBlog->setOwner(null); // или $oBlog->setOwner(new ModuleUser_EntityUser());
            }
            if (isset($aBlogUsers[$oBlog->getId()])) {
                $oBlog->setUserIsJoin(true);
                $oBlog->setUserIsAdministrator($aBlogUsers[$oBlog->getId()]->getIsAdministrator());
                $oBlog->setUserIsModerator($aBlogUsers[$oBlog->getId()]->getIsModerator());
            } else {
                $oBlog->setUserIsJoin(false);
                $oBlog->setUserIsAdministrator(false);
                $oBlog->setUserIsModerator(false);
            }
            if (isset($aBlogsVote[$oBlog->getId()])) {
                $oBlog->setVote($aBlogsVote[$oBlog->getId()]);
            } else {
                $oBlog->setVote(null);
            }
        }
        return $aBlogs;
    }

    /**
     * Возвращает список блогов по ID
     *
     * @param array $aBlogId Список ID блогов
     * @param array|null $aOrder Порядок сортировки
     * @return array
     */
    public function GetBlogsByArrayId($aBlogId, $aOrder = null)
    {
        if (!$aBlogId) {
            return array();
        }
        if (Config::Get('sys.cache.solid')) {
            return $this->GetBlogsByArrayIdSolid($aBlogId, $aOrder);
        }
        if (!is_array($aBlogId)) {
            $aBlogId = array($aBlogId);
        }
        $aBlogId = array_unique($aBlogId);
        $aBlogs = array();
        $aBlogIdNotNeedQuery = array();
        /**
         * Делаем мульти-запрос к кешу
         */
        $aCacheKeys = func_build_cache_keys($aBlogId, 'blog_');
        if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
            /**
             * проверяем что досталось из кеша
             */
            foreach ($aCacheKeys as $sValue => $sKey) {
                if (array_key_exists($sKey, $data)) {
                    if ($data[$sKey]) {
                        $aBlogs[$data[$sKey]->getId()] = $data[$sKey];
                    } else {
                        $aBlogIdNotNeedQuery[] = $sValue;
                    }
                }
            }
        }
        /**
         * Смотрим каких блогов не было в кеше и делаем запрос в БД
         */
        $aBlogIdNeedQuery = array_diff($aBlogId, array_keys($aBlogs));
        $aBlogIdNeedQuery = array_diff($aBlogIdNeedQuery, $aBlogIdNotNeedQuery);
        $aBlogIdNeedStore = $aBlogIdNeedQuery;
        if ($data = $this->oMapperBlog->GetBlogsByArrayId($aBlogIdNeedQuery)) {
            foreach ($data as $oBlog) {
                /**
                 * Добавляем к результату и сохраняем в кеш
                 */
                $aBlogs[$oBlog->getId()] = $oBlog;
                $this->Cache_Set($oBlog, "blog_{$oBlog->getId()}", array(), 60 * 60 * 24 * 4);
                $aBlogIdNeedStore = array_diff($aBlogIdNeedStore, array($oBlog->getId()));
            }
        }
        /**
         * Сохраняем в кеш запросы не вернувшие результата
         */
        foreach ($aBlogIdNeedStore as $sId) {
            $this->Cache_Set(null, "blog_{$sId}", array(), 60 * 60 * 24 * 4);
        }
        /**
         * Сортируем результат согласно входящему массиву
         */
        $aBlogs = func_array_sort_by_keys($aBlogs, $aBlogId);
        return $aBlogs;
    }

    /**
     * Возвращает список блогов по ID, но используя единый кеш
     *
     * @param array $aBlogId Список ID блогов
     * @param array|null $aOrder Сортировка блогов
     * @return array
     */
    public function GetBlogsByArrayIdSolid($aBlogId, $aOrder = null)
    {
        if (!is_array($aBlogId)) {
            $aBlogId = array($aBlogId);
        }
        $aBlogId = array_unique($aBlogId);
        $aBlogs = array();
        $s = join(',', $aBlogId);
        if (false === ($data = $this->Cache_Get("blog_id_{$s}"))) {
            $data = $this->oMapperBlog->GetBlogsByArrayId($aBlogId, $aOrder);
            foreach ($data as $oBlog) {
                $aBlogs[$oBlog->getId()] = $oBlog;
            }
            $this->Cache_Set($aBlogs, "blog_id_{$s}", array("blog_update"), 60 * 60 * 24 * 1);
            return $aBlogs;
        }
        return $data;
    }

    /**
     * Получить персональный блог юзера
     *
     * @param int $sUserId ID пользователя
     * @return ModuleBlog_EntityBlog
     */
    public function GetPersonalBlogByUserId($sUserId)
    {
        $id = $this->oMapperBlog->GetPersonalBlogByUserId($sUserId);
        return $this->GetBlogById($id);
    }

    /**
     * Получить блог по айдишнику(номеру)
     *
     * @param int $sBlogId ID блога
     * @return ModuleBlog_EntityBlog|null
     */
    public function GetBlogById($sBlogId)
    {
        if (!is_numeric($sBlogId)) {
            return null;
        }
        $aBlogs = $this->GetBlogsAdditionalData($sBlogId);
        if (isset($aBlogs[$sBlogId])) {
            return $aBlogs[$sBlogId];
        }
        return null;
    }

    /**
     * Получить блог по УРЛу
     *
     * @param string $sBlogUrl URL блога
     * @return ModuleBlog_EntityBlog|null
     */
    public function GetBlogByUrl($sBlogUrl)
    {
        if (false === ($id = $this->Cache_Get("blog_url_{$sBlogUrl}"))) {
            if ($id = $this->oMapperBlog->GetBlogByUrl($sBlogUrl)) {
                $this->Cache_Set($id, "blog_url_{$sBlogUrl}", array("blog_update_{$id}"), 60 * 60 * 24 * 2);
            } else {
                $this->Cache_Set(null, "blog_url_{$sBlogUrl}", array('blog_update', 'blog_new'), 60 * 60);
            }
        }
        return $this->GetBlogById($id);
    }

    /**
     * Получить блог по названию
     *
     * @param string $sTitle Название блога
     * @return ModuleBlog_EntityBlog|null
     */
    public function GetBlogByTitle($sTitle)
    {
        if (false === ($id = $this->Cache_Get("blog_title_{$sTitle}"))) {
            if ($id = $this->oMapperBlog->GetBlogByTitle($sTitle)) {
                $this->Cache_Set($id, "blog_title_{$sTitle}", array("blog_update_{$id}", 'blog_new'), 60 * 60 * 24 * 2);
            } else {
                $this->Cache_Set(null, "blog_title_{$sTitle}", array('blog_update', 'blog_new'), 60 * 60);
            }
        }
        return $this->GetBlogById($id);
    }

    /**
     * Создаёт персональный блог
     *
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @return ModuleBlog_EntityBlog|bool
     */
    public function CreatePersonalBlog(ModuleUser_EntityUser $oUser)
    {
        $oBlog = Engine::GetEntity('Blog');
        $oBlog->setOwnerId($oUser->getId());
        $oBlog->setTitle($this->Lang_Get('blog.personal_prefix') . ' ' . $oUser->getLogin());
        $oBlog->setType('personal');
        $oBlog->setDescription($this->Lang_Get('blog.personal_description'));
        $oBlog->setDateAdd(date("Y-m-d H:i:s"));
        $oBlog->setLimitRatingTopic(-1000);
        $oBlog->setUrl(null);
        $oBlog->setAvatar(null);
        return $this->AddBlog($oBlog);
    }

    /**
     * Добавляет блог
     *
     * @param ModuleBlog_EntityBlog $oBlog Блог
     * @return ModuleBlog_EntityBlog|bool
     */
    public function AddBlog(ModuleBlog_EntityBlog $oBlog)
    {
        if ($sId = $this->oMapperBlog->AddBlog($oBlog)) {
            $oBlog->setId($sId);
            //чистим зависимые кеши
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('blog_new'));
            return $oBlog;
        }
        return false;
    }

    /**
     * Обновляет блог
     *
     * @param ModuleBlog_EntityBlog $oBlog Блог
     * @return ModuleBlog_EntityBlog|bool
     */
    public function UpdateBlog(ModuleBlog_EntityBlog $oBlog)
    {
        $oBlog->setDateEdit(date("Y-m-d H:i:s"));
        $res = $this->oMapperBlog->UpdateBlog($oBlog);
        if ($res) {
            //чистим зависимые кеши
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                array('blog_update', "blog_update_{$oBlog->getId()}", "topic_update"));
            $this->Cache_Delete("blog_{$oBlog->getId()}");
            return true;
        }
        return false;
    }

    /**
     * Добавляет отношение юзера к блогу, по сути присоединяет к блогу
     *
     * @param ModuleBlog_EntityBlogUser $oBlogUser Объект связи(отношения) блога с пользователем
     * @return bool
     */
    public function AddRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser)
    {
        if ($this->oMapperBlog->AddRelationBlogUser($oBlogUser)) {
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
                "blog_relation_change_{$oBlogUser->getUserId()}",
                "blog_relation_change_blog_{$oBlogUser->getBlogId()}"
            ));
            $this->Cache_Delete("blog_relation_user_{$oBlogUser->getBlogId()}_{$oBlogUser->getUserId()}");
            return true;
        }
        return false;
    }

    /**
     * Удалет отношение юзера к блогу, по сути отключает от блога
     *
     * @param ModuleBlog_EntityBlogUser $oBlogUser Объект связи(отношения) блога с пользователем
     * @return bool
     */
    public function DeleteRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser)
    {
        if ($this->oMapperBlog->DeleteRelationBlogUser($oBlogUser)) {
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
                "blog_relation_change_{$oBlogUser->getUserId()}",
                "blog_relation_change_blog_{$oBlogUser->getBlogId()}"
            ));
            $this->Cache_Delete("blog_relation_user_{$oBlogUser->getBlogId()}_{$oBlogUser->getUserId()}");
            return true;
        }
        return false;
    }

    /**
     * Получает список блогов по хозяину
     *
     * @param int $sUserId ID пользователя
     * @param bool $bReturnIdOnly Возвращать только ID блогов или полные объекты
     * @return array
     */
    public function GetBlogsByOwnerId($sUserId, $bReturnIdOnly = false)
    {
        $data = $this->oMapperBlog->GetBlogsByOwnerId($sUserId);
        /**
         * Возвращаем только иденитификаторы
         */
        if ($bReturnIdOnly) {
            return $data;
        }

        $data = $this->GetBlogsAdditionalData($data);
        return $data;
    }

    /**
     * Получает список всех НЕ персональных блогов
     *
     * @param bool $bReturnIdOnly Возвращать только ID блогов или полные объекты
     * @return array
     */
    public function GetBlogs($bReturnIdOnly = false)
    {
        $data = $this->oMapperBlog->GetBlogs();
        /**
         * Возвращаем только иденитификаторы
         */
        if ($bReturnIdOnly) {
            return $data;
        }

        $data = $this->GetBlogsAdditionalData($data);
        return $data;
    }

    public function GetBlogsByType($sType)
    {
        $aBlogs = $this->GetBlogsByFilter(array('type' => $sType), array('blog_title' => 'asc'), 1, 100);
        return $aBlogs['collection'];
    }

    public function GetBlogsByTypeAndUserId($sType, $iUserId)
    {
        $aResult = array();
        /**
         * Получаем созданные юзером блоги
         */
        $aBlogs = $this->GetBlogsByFilter(array('user_owner_id' => $iUserId, 'type' => $sType), array(), 1, 100);
        foreach ($aBlogs['collection'] as $oBlog) {
            $aResult[$oBlog->getId()] = $oBlog;
        }
        /**
         * Блоги в которых состоит
         */
        $aBlogs = $this->GetBlogsByFilter(array(
            'type'          => $sType,
            'roles_user_id' => $iUserId,
            'roles'         => array(
                self::BLOG_USER_ROLE_USER,
                self::BLOG_USER_ROLE_MODERATOR,
                self::BLOG_USER_ROLE_ADMINISTRATOR
            )
        ), array(), 1, 100);
        foreach ($aBlogs['collection'] as $oBlog) {
            $aResult[$oBlog->getId()] = $oBlog;
        }
        /**
         * Сотируем по названию
         */
        uasort($aResult, function ($a, $b) {
            if ($a->getTitle() == $b->getTitle()) {
                return 0;
            }
            return ($a->getTitle() < $b->getTitle()) ? -1 : 1;
        });
        return $aResult;
    }

    /**
     * Получает список пользователей блога.
     * Если роль не указана, то считаем что поиск производиться по положительным значениям (статусом выше GUEST).
     *
     * @param int|array $aBlogId ID блога или список ID блогов
     * @param int|null $iRole Роль пользователей в блоге
     * @param int $iPage Номер текущей страницы
     * @param int $iPerPage Количество элементов на одну страницу
     * @return array
     */
    public function GetBlogUsersByBlogId($aBlogId, $iRole = null, $iPage = 1, $iPerPage = 100)
    {
        if (!is_array($aBlogId)) {
            $aBlogId = array($aBlogId);
        }
        $aFilter = array(
            'blog_id' => $aBlogId,
        );
        if ($iRole !== null) {
            $aFilter['user_role'] = $iRole;
        }
        $s = serialize($aFilter);
        if (false === ($data = $this->Cache_Get("blog_relation_user_by_filter_{$s}_{$iPage}_{$iPerPage}"))) {
            $data = array(
                'collection' => $this->oMapperBlog->GetBlogUsers($aFilter, $iCount, $iPage, $iPerPage),
                'count'      => $iCount
            );
            $aTags = array();
            foreach ($aBlogId as $iBlogId) {
                $aTags[] = "blog_relation_change_blog_{$iBlogId}";
            }
            $this->Cache_Set($data, "blog_relation_user_by_filter_{$s}_{$iPage}_{$iPerPage}", $aTags, 60 * 60 * 24 * 3);
        }
        /**
         * Достаем дополнительные данные, для этого формируем список юзеров и делаем мульти-запрос
         */
        if ($data['collection']) {
            $aUserId = array();
            foreach ($data['collection'] as $oBlogUser) {
                $aUserId[] = $oBlogUser->getUserId();
            }
            $aUsers = $this->User_GetUsersAdditionalData($aUserId);
            $aBlogs = $this->Blog_GetBlogsAdditionalData($aBlogId);

            $aResults = array();
            foreach ($data['collection'] as $oBlogUser) {
                if (isset($aUsers[$oBlogUser->getUserId()])) {
                    $oBlogUser->setUser($aUsers[$oBlogUser->getUserId()]);
                } else {
                    $oBlogUser->setUser(null);
                }
                if (isset($aBlogs[$oBlogUser->getBlogId()])) {
                    $oBlogUser->setBlog($aBlogs[$oBlogUser->getBlogId()]);
                } else {
                    $oBlogUser->setBlog(null);
                }
                $aResults[$oBlogUser->getUserId()] = $oBlogUser;
            }
            $data['collection'] = $aResults;
        }
        return $data;
    }

    /**
     * Получает отношения юзера к блогам(состоит в блоге или нет)
     *
     * @param int $sUserId ID пользователя
     * @param int|null $iRole Роль пользователя в блоге
     * @param bool $bReturnIdOnly Возвращать только ID блогов или полные объекты
     * @return array
     */
    public function GetBlogUsersByUserId($sUserId, $iRole = null, $bReturnIdOnly = false)
    {
        $aFilter = array(
            'user_id' => $sUserId
        );
        if ($iRole !== null) {
            $aFilter['user_role'] = $iRole;
        }
        $s = serialize($aFilter);
        if (false === ($data = $this->Cache_Get("blog_relation_user_by_filter_$s"))) {
            $data = $this->oMapperBlog->GetBlogUsers($aFilter);
            $this->Cache_Set($data, "blog_relation_user_by_filter_$s",
                array("blog_update", "blog_relation_change_{$sUserId}"), 60 * 60 * 24 * 3);
        }
        /**
         * Достаем дополнительные данные, для этого формируем список блогов и делаем мульти-запрос
         */
        $aBlogId = array();
        if ($data) {
            foreach ($data as $oBlogUser) {
                $aBlogId[] = $oBlogUser->getBlogId();
            }
            /**
             * Если указано возвращать полные объекты
             */
            if (!$bReturnIdOnly) {
                $aUsers = $this->User_GetUsersAdditionalData($sUserId);
                $aBlogs = $this->Blog_GetBlogsAdditionalData($aBlogId);
                foreach ($data as $oBlogUser) {
                    if (isset($aUsers[$oBlogUser->getUserId()])) {
                        $oBlogUser->setUser($aUsers[$oBlogUser->getUserId()]);
                    } else {
                        $oBlogUser->setUser(null);
                    }
                    if (isset($aBlogs[$oBlogUser->getBlogId()])) {
                        $oBlogUser->setBlog($aBlogs[$oBlogUser->getBlogId()]);
                    } else {
                        $oBlogUser->setBlog(null);
                    }
                }
            }
        }
        return ($bReturnIdOnly) ? $aBlogId : $data;
    }

    /**
     * Состоит ли юзер в конкретном блоге
     *
     * @param int $sBlogId ID блога
     * @param int $sUserId ID пользователя
     * @return ModuleBlog_EntityBlogUser|null
     */
    public function GetBlogUserByBlogIdAndUserId($sBlogId, $sUserId)
    {
        if ($aBlogUser = $this->GetBlogUsersByArrayBlog($sBlogId, $sUserId)) {
            if (isset($aBlogUser[$sBlogId])) {
                return $aBlogUser[$sBlogId];
            }
        }
        return null;
    }

    /**
     * Получить список отношений блог-юзер по списку айдишников
     *
     * @param array $aBlogId Список ID блогов
     * @param int $sUserId ID пользователя
     * @return array
     */
    public function GetBlogUsersByArrayBlog($aBlogId, $sUserId)
    {
        if (!$aBlogId) {
            return array();
        }
        if (Config::Get('sys.cache.solid')) {
            return $this->GetBlogUsersByArrayBlogSolid($aBlogId, $sUserId);
        }
        if (!is_array($aBlogId)) {
            $aBlogId = array($aBlogId);
        }
        $aBlogId = array_unique($aBlogId);
        $aBlogUsers = array();
        $aBlogIdNotNeedQuery = array();
        /**
         * Делаем мульти-запрос к кешу
         */
        $aCacheKeys = func_build_cache_keys($aBlogId, 'blog_relation_user_', '_' . $sUserId);
        if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
            /**
             * проверяем что досталось из кеша
             */
            foreach ($aCacheKeys as $sValue => $sKey) {
                if (array_key_exists($sKey, $data)) {
                    if ($data[$sKey]) {
                        $aBlogUsers[$data[$sKey]->getBlogId()] = $data[$sKey];
                    } else {
                        $aBlogIdNotNeedQuery[] = $sValue;
                    }
                }
            }
        }
        /**
         * Смотрим каких блогов не было в кеше и делаем запрос в БД
         */
        $aBlogIdNeedQuery = array_diff($aBlogId, array_keys($aBlogUsers));
        $aBlogIdNeedQuery = array_diff($aBlogIdNeedQuery, $aBlogIdNotNeedQuery);
        $aBlogIdNeedStore = $aBlogIdNeedQuery;
        if ($data = $this->oMapperBlog->GetBlogUsersByArrayBlog($aBlogIdNeedQuery, $sUserId)) {
            foreach ($data as $oBlogUser) {
                /**
                 * Добавляем к результату и сохраняем в кеш
                 */
                $aBlogUsers[$oBlogUser->getBlogId()] = $oBlogUser;
                $this->Cache_Set($oBlogUser, "blog_relation_user_{$oBlogUser->getBlogId()}_{$oBlogUser->getUserId()}",
                    array(), 60 * 60 * 24 * 4);
                $aBlogIdNeedStore = array_diff($aBlogIdNeedStore, array($oBlogUser->getBlogId()));
            }
        }
        /**
         * Сохраняем в кеш запросы не вернувшие результата
         */
        foreach ($aBlogIdNeedStore as $sId) {
            $this->Cache_Set(null, "blog_relation_user_{$sId}_{$sUserId}", array(), 60 * 60 * 24 * 4);
        }
        /**
         * Сортируем результат согласно входящему массиву
         */
        $aBlogUsers = func_array_sort_by_keys($aBlogUsers, $aBlogId);
        return $aBlogUsers;
    }

    /**
     * Получить список отношений блог-юзер по списку айдишников используя общий кеш
     *
     * @param array $aBlogId Список ID блогов
     * @param int $sUserId ID пользователя
     * @return array
     */
    public function GetBlogUsersByArrayBlogSolid($aBlogId, $sUserId)
    {
        if (!is_array($aBlogId)) {
            $aBlogId = array($aBlogId);
        }
        $aBlogId = array_unique($aBlogId);
        $aBlogUsers = array();
        $s = join(',', $aBlogId);
        if (false === ($data = $this->Cache_Get("blog_relation_user_{$sUserId}_id_{$s}"))) {
            $data = $this->oMapperBlog->GetBlogUsersByArrayBlog($aBlogId, $sUserId);
            foreach ($data as $oBlogUser) {
                $aBlogUsers[$oBlogUser->getBlogId()] = $oBlogUser;
            }
            $this->Cache_Set($aBlogUsers, "blog_relation_user_{$sUserId}_id_{$s}",
                array("blog_update", "blog_relation_change_{$sUserId}"), 60 * 60 * 24 * 1);
            return $aBlogUsers;
        }
        return $data;
    }

    /**
     * Обновляет отношения пользователя с блогом
     *
     * @param ModuleBlog_EntityBlogUser $oBlogUser Объект отновшения
     * @return bool
     */
    public function UpdateRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser)
    {
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
            "blog_relation_change_{$oBlogUser->getUserId()}",
            "blog_relation_change_blog_{$oBlogUser->getBlogId()}"
        ));
        $this->Cache_Delete("blog_relation_user_{$oBlogUser->getBlogId()}_{$oBlogUser->getUserId()}");
        return $this->oMapperBlog->UpdateRelationBlogUser($oBlogUser);
    }

    /**
     * Возвращает список блогов по фильтру
     *
     * @param array $aFilter Фильтр выборки блогов
     * @param array $aOrder Сортировка блогов
     * @param int $iCurrPage Номер текущей страницы
     * @param int $iPerPage Количество элементов на одну страницу
     * @param array $aAllowData Список типов данных, которые нужно подтянуть к списку блогов
     * @return array('collection'=>array,'count'=>int)
     */
    public function GetBlogsByFilter($aFilter, $aOrder, $iCurrPage, $iPerPage, $aAllowData = null)
    {
        if (is_null($aAllowData)) {
            $aAllowData = array('owner' => array(), 'relation_user');
        }
        $sKey = "blog_filter_" . serialize($aFilter) . serialize($aOrder) . "_{$iCurrPage}_{$iPerPage}";
        if (false === ($data = $this->Cache_Get($sKey))) {
            $data = array(
                'collection' => $this->oMapperBlog->GetBlogsByFilter($aFilter, $aOrder, $iCount, $iCurrPage, $iPerPage),
                'count'      => $iCount
            );
            $this->Cache_Set($data, $sKey, array("blog_update", "blog_new"), 60 * 60 * 24 * 2);
        }
        $data['collection'] = $this->GetBlogsAdditionalData($data['collection'], $aAllowData);
        return $data;
    }

    /**
     * Получает список блогов по рейтингу
     *
     * @param int $iCurrPage Номер текущей страницы
     * @param int $iPerPage Количество элементов на одну страницу
     * @return array('collection'=>array,'count'=>int)
     */
    public function GetBlogsRating($iCurrPage, $iPerPage)
    {
        return $this->GetBlogsByFilter(array('exclude_type' => 'personal'), array('blog_count_user' => 'desc'),
            $iCurrPage,
            $iPerPage);
    }

    /**
     * Список подключенных блогов по рейтингу
     *
     * @param int $sUserId ID пользователя
     * @param int $iLimit Ограничение на количество в ответе
     * @return array
     */
    public function GetBlogsRatingJoin($sUserId, $iLimit)
    {
        if (false === ($data = $this->Cache_Get("blog_rating_join_{$sUserId}_{$iLimit}"))) {
            $data = $this->oMapperBlog->GetBlogsRatingJoin($sUserId, $iLimit);
            $this->Cache_Set($data, "blog_rating_join_{$sUserId}_{$iLimit}",
                array('blog_update', "blog_relation_change_{$sUserId}"), 60 * 60 * 24);
        }
        return $data;
    }

    /**
     * Список своих блогов по рейтингу
     *
     * @param int $sUserId ID пользователя
     * @param int $iLimit Ограничение на количество в ответе
     * @return array
     */
    public function GetBlogsRatingSelf($sUserId, $iLimit)
    {
        $aResult = $this->GetBlogsByFilter(array('exclude_type' => 'personal', 'user_owner_id' => $sUserId),
            array('blog_count_user' => 'desc'), 1, $iLimit);
        return $aResult['collection'];
    }

    /**
     * Получает список блогов в которые может постить юзер
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя
     * @return array
     */
    public function GetBlogsAllowByUser($oUser)
    {
        if ($oUser->isAdministrator()) {
            return $this->GetBlogs();
        } else {
            $aAllowBlogsUser = $this->GetBlogsByOwnerId($oUser->getId());
            $aBlogUsers = $this->GetBlogUsersByUserId($oUser->getId());
            foreach ($aBlogUsers as $oBlogUser) {
                $oBlog = $oBlogUser->getBlog();
                if ($oUser->getRating() >= $oBlog->getLimitRatingTopic() or $oBlogUser->getIsAdministrator() or $oBlogUser->getIsModerator()) {
                    $aAllowBlogsUser[$oBlog->getId()] = $oBlog;
                }
            }
            return $aAllowBlogsUser;
        }
    }

    /**
     * Получаем массив блогов, которые являются открытыми для пользователя
     *
     * @param  ModuleUser_EntityUser $oUser Объект пользователя
     * @return array
     */
    public function GetAccessibleBlogsByUser($oUser)
    {
        if ($oUser->isAdministrator()) {
            return $this->GetBlogs(true);
        }
        if (false === ($aOpenBlogsUser = $this->Cache_Get("blog_accessible_user_{$oUser->getId()}"))) {
            /**
             * Заносим блоги, созданные пользователем
             */
            $aOpenBlogsUser = $this->GetBlogsByOwnerId($oUser->getId(), true);
            /**
             * Добавляем блоги, в которых состоит пользователь
             * (читателем, модератором, или администратором)
             */
            $aOpenBlogsUser = array_merge($aOpenBlogsUser, $this->GetBlogUsersByUserId($oUser->getId(), null, true));
            $this->Cache_Set($aOpenBlogsUser, "blog_accessible_user_{$oUser->getId()}",
                array('blog_new', 'blog_update', "blog_relation_change_{$oUser->getId()}"), 60 * 60 * 24);
        }
        return $aOpenBlogsUser;
    }

    /**
     * Получаем массив идентификаторов блогов, которые являются закрытыми для пользователя
     *
     * @param  ModuleUser_EntityUser|null $oUser Пользователь
     * @return array
     */
    public function GetInaccessibleBlogsByUser($oUser = null)
    {
        if ($oUser && $oUser->isAdministrator()) {
            return array();
        }
        $sUserId = $oUser ? $oUser->getId() : 'quest';
        if (false === ($aCloseBlogs = $this->Cache_Get("blog_inaccessible_user_{$sUserId}"))) {
            $aCloseBlogs = $this->oMapperBlog->GetCloseBlogs();

            if ($oUser) {
                /**
                 * Получаем массив идентификаторов блогов,
                 * которые являются откытыми для данного пользователя
                 */
                $aOpenBlogs = $this->GetBlogUsersByUserId($oUser->getId(), null, true);
                /**
                 * Получаем закрытые блоги, где пользователь является автором
                 */
                $aOwnerBlogs = $this->GetBlogsByFilter(array('type' => 'close', 'user_owner_id' => $oUser->getId()),
                    array(), 1, 100, array());
                $aOwnerBlogs = array_keys($aOwnerBlogs['collection']);
                $aCloseBlogs = array_diff($aCloseBlogs, $aOpenBlogs, $aOwnerBlogs);
            }
            /**
             * Сохраняем в кеш
             */
            if ($oUser) {
                $this->Cache_Set($aCloseBlogs, "blog_inaccessible_user_{$sUserId}",
                    array('blog_new', 'blog_update', "blog_relation_change_{$oUser->getId()}"), 60 * 60 * 24);
            } else {
                $this->Cache_Set($aCloseBlogs, "blog_inaccessible_user_{$sUserId}", array('blog_new', 'blog_update'),
                    60 * 60 * 24 * 3);
            }
        }
        return $aCloseBlogs;
    }

    /**
     * Удаляет блог
     *
     * @param  int $iBlogId ID блога
     * @return bool
     */
    public function DeleteBlog($iBlogId)
    {
        if ($iBlogId instanceof ModuleBlog_EntityBlog) {
            $oBlog = $iBlogId;
            $iBlogId = $oBlog->getId();
        } else {
            $oBlog = $this->Blog_GetBlogById($iBlogId);
        }
        /**
         * Получаем идентификаторы топиков блога. Удаляем топики блога.
         * При удалении топиков удаляются комментарии к ним, голоса и т.п.
         */
        $iPage = 1;
        while ($aTopicsRes = $this->Topic_GetTopicsByBlogId($iBlogId, $iPage, 100, array(),
                false) and $aTopicsRes['collection']) {
            /**
             * Удаляем топики
             */
            foreach ($aTopicsRes['collection'] as $oTopic) {
                $aBlogsCurrent = $oTopic->getBlogIds();
                /**
                 * Удалять нужно только те топики, где текущий блог является единственным, у остальных просто удаляем связь
                 */
                if (count($aBlogsCurrent) == 1) {
                    $this->Topic_DeleteTopic($oTopic);
                } else {
                    array_splice($aBlogsCurrent, array_search($oBlog->getId(), $aBlogsCurrent), 1);
                    /**
                     * Устанавливаем новые связи с блогами
                     */
                    foreach ($aBlogsCurrent as $i => $iBlogCurrent) {
                        $sMethodSet = 'setBlogId' . ($i == 0 ? '' : ($i + 1));
                        call_user_func(array($oTopic, $sMethodSet), $iBlogCurrent);
                    }
                    for ($i = $i + 2; $i <= 10; $i++) {
                        $sMethodSet = 'setBlogId' . $i;
                        call_user_func(array($oTopic, $sMethodSet), null);
                    }
                    $this->Topic_UpdateTopic($oTopic);
                }
            }
            $iPage++;
        }
        /**
         * Если блог не удален, возвращаем false
         */
        if (!$this->oMapperBlog->DeleteBlog($iBlogId)) {
            return false;
        }
        /**
         * Чистим кеш
         */
        $this->Cache_Clean(
            Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array(
                "blog_update",
                "blog_relation_change_blog_{$iBlogId}",
                "topic_update",
                "comment_online_update_topic",
                "comment_update"
            )
        );
        $this->Cache_Delete("blog_{$iBlogId}");
        /**
         * Удаляем аватар
         */
        $this->DeleteBlogAvatar($oBlog);
        /**
         * Удаляем связи пользователей блога.
         */
        $this->oMapperBlog->DeleteBlogUsersByBlogId($iBlogId);
        /**
         * Удаляем голосование за блог
         */
        $this->Vote_DeleteVoteByTarget($iBlogId, 'blog');
        /**
         * Удаляем медиа данные
         */
        $this->Media_RemoveTarget('blog', $iBlogId, true);
        /**
         * Обновляем категорию блога
         */
        $oBlog->category->CallbackAfterDelete();
        return true;
    }

    /**
     * Создает аватар пользователя на основе области из изображения
     *
     * @param      $sFileFrom
     * @param      $oBlog
     * @param      $aSize
     * @param null $iCanvasWidth
     *
     * @return bool
     */
    public function CreateAvatar($sFileFrom, $oBlog, $aSize = null, $iCanvasWidth = null)
    {
        $aParams = $this->Image_BuildParams('blog_avatar');
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
        $sPath = $this->Image_GetIdDir($oBlog->getId(), 'blogs');
        /**
         * Удаляем старый аватар
         */
        $this->DeleteBlogAvatar($oBlog);
        /**
         * Имя файла для сохранения
         */
        $sFileName = 'avatar-blog-' . $oBlog->getId();
        /**
         * Сохраняем оригинальный аватар
         */
        if (false === ($sFileResult = $oImage->saveSmart($sPath, $sFileName))) {
            return $this->Image_GetLastError();
        }
        /**
         * Генерируем варианты с необходимыми размерами
         */
        $this->Media_GenerateImageBySizes($sFileTmp, $sPath, $sFileName, Config::Get('module.blog.avatar_size'),
            $aParams);
        /**
         * Теперь можно удалить временный файл
         */
        $this->Fs_RemoveFileLocal($sFileTmp);
        $oBlog->setAvatar($sFileResult);
        $this->UpdateBlog($oBlog);
        return true;
    }

    /**
     * Удаляет аватар блога с сервера
     *
     * @param ModuleBlog_EntityBlog $oBlog Блог
     */
    public function DeleteBlogAvatar($oBlog)
    {
        /**
         * Если аватар есть, удаляем его и его рейсайзы
         */
        if ($oBlog->getAvatar()) {
            $this->Media_RemoveImageBySizes($oBlog->getAvatar(), Config::Get('module.blog.avatar_size'));
        }
        $oBlog->setAvatar(null);
    }

    /**
     * Пересчет количества топиков в блогах
     *
     * @return bool
     */
    public function RecalculateCountTopic()
    {
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('blog_update'));
        return $this->oMapperBlog->RecalculateCountTopic();
    }

    /**
     * Пересчет количества топиков в конкретном блоге
     *
     * @param int|array $aBlogIds Список ID блогов
     * @return bool
     */
    public function RecalculateCountTopicByBlogId($aBlogIds)
    {
        if (!is_array($aBlogIds)) {
            $aBlogIds = array($aBlogIds);
        }
        if ($aBlogIds) {
            foreach ($aBlogIds as $iBlogId) {
                //чистим зависимые кеши
                $this->oMapperBlog->RecalculateCountTopic($iBlogId);
                $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array("blog_update_{$iBlogId}"));
                $this->Cache_Delete("blog_{$iBlogId}");
            }
        }
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('blog_update'));
        return true;
    }

    /**
     * Алиас для корректной работы ORM
     *
     * @param array $aBlogId Список ID блогов
     * @return array
     */
    public function GetBlogItemsByArrayId($aBlogId)
    {
        return $this->GetBlogsByArrayId($aBlogId);
    }

    /**
     * Отправляет пользователю сообщение о приглашение его в закрытый блог
     *
     * @param ModuleUser_EntityUser $oUserTo Объект пользователя, который отправляет приглашение
     * @param ModuleUser_EntityUser $oUserFrom Объект пользователя, которого приглашаем
     * @param ModuleBlog_EntityBlog $oBlog Объект блога
     * @param $sPath
     */
    public function SendNotifyBlogUserInvite(
        ModuleUser_EntityUser $oUserTo,
        ModuleUser_EntityUser $oUserFrom,
        ModuleBlog_EntityBlog $oBlog,
        $sPath
    ) {
        $this->Notify_Send(
            $oUserTo,
            'blog_invite_new.tpl',
            $this->Lang_Get('emails.blog_invite_new.subject'),
            array(
                'oUserTo'   => $oUserTo,
                'oUserFrom' => $oUserFrom,
                'oBlog'     => $oBlog,
                'sPath'     => $sPath,
            )
        );
    }
}