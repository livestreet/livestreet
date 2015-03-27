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
 * Модуль для работы с топиками
 *
 * @package application.modules.topic
 * @since 1.0
 */
class ModuleTopic extends Module
{

    const TOPIC_TYPE_STATE_ACTIVE = 1;
    const TOPIC_TYPE_STATE_NOT_ACTIVE = 0;
    /**
     * Объект маппера
     *
     * @var ModuleTopic_MapperTopic
     */
    protected $oMapperTopic;
    /**
     * Объект текущего пользователя
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;
    /**
     * Список типов топика
     *
     * @var array
     */
    protected $aTopicTypes = array();

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        $this->oMapperTopic = Engine::GetMapper(__CLASS__);
        $this->oUserCurrent = $this->User_GetUserCurrent();

        $aTopicTypeItems = $this->GetTopicTypeItems(array('state' => self::TOPIC_TYPE_STATE_ACTIVE));
        foreach ($aTopicTypeItems as $oTypeItem) {
            $this->aTopicTypes[$oTypeItem->getCode()] = $oTypeItem;
        }
    }

    /**
     * Возвращает список типов топика, возвращаются только активные типы
     *
     * @param bool $bOnlyCode Вернуть только коды типов
     *
     * @return array
     */
    public function GetTopicTypes($bOnlyCode = false)
    {
        return $bOnlyCode ? array_keys($this->aTopicTypes) : $this->aTopicTypes;
    }

    /**
     * Возвращает объект типа топика, поиск только среди активных типов
     *
     * @param string $sType
     *
     * @return ModuleTopic_EntityTopicType|null
     */
    public function GetTopicType($sType)
    {
        return isset($this->aTopicTypes[$sType]) ? $this->aTopicTypes[$sType] : null;
    }

    /**
     * Возвращает первый доступные тип топика
     *
     * @return ModuleTopic_EntityTopicType|null
     */
    public function GetTopicTypeFirst()
    {
        $oType = reset($this->aTopicTypes);
        return $oType ?: null;
    }

    /**
     * Проверяет разрешен ли данный тип топика
     *
     * @param string $sType Тип
     * @return bool
     */
    public function IsAllowTopicType($sType)
    {
        return array_key_exists($sType, $this->aTopicTypes);
    }

    /**
     * Получает дополнительные данные(объекты) для топиков по их ID
     *
     * @param array $aTopicId Список ID топиков
     * @param array|null $aAllowData Список типов дополнительных данных, которые нужно подключать к топикам
     * @return array
     */
    public function GetTopicsAdditionalData($aTopicId, $aAllowData = null)
    {
        if (is_null($aAllowData)) {
            $aAllowData = array(
                'user' => array(),
                'blog' => array('owner' => array(), 'relation_user'),
                'vote',
                'favourite',
                'comment_new',
                'properties'
            );
        }
        func_array_simpleflip($aAllowData);
        if (!is_array($aTopicId)) {
            $aTopicId = array($aTopicId);
        }
        /**
         * Получаем "голые" топики
         */
        $aTopics = $this->GetTopicsByArrayId($aTopicId);
        /**
         * Формируем ID дополнительных данных, которые нужно получить
         */
        $aUserId = array();
        $aBlogId = array();
        foreach ($aTopics as $oTopic) {
            if (isset($aAllowData['user'])) {
                $aUserId[] = $oTopic->getUserId();
            }
            if (isset($aAllowData['blog'])) {
                $aBlogId = array_merge($aBlogId, $oTopic->getBlogIds());
            }
        }
        /**
         * Получаем дополнительные данные
         */
        $aTopicsVote = array();
        $aFavouriteTopics = array();
        $aTopicsRead = array();
        $aUsers = isset($aAllowData['user']) && is_array($aAllowData['user']) ? $this->User_GetUsersAdditionalData($aUserId,
            $aAllowData['user']) : $this->User_GetUsersAdditionalData($aUserId);
        $aBlogs = isset($aAllowData['blog']) && is_array($aAllowData['blog']) ? $this->Blog_GetBlogsAdditionalData($aBlogId,
            $aAllowData['blog']) : $this->Blog_GetBlogsAdditionalData($aBlogId);
        if (isset($aAllowData['vote']) and $this->oUserCurrent) {
            $aTopicsVote = $this->Vote_GetVoteByArray($aTopicId, 'topic', $this->oUserCurrent->getId());
        }
        if (isset($aAllowData['favourite']) and $this->oUserCurrent) {
            $aFavouriteTopics = $this->GetFavouriteTopicsByArray($aTopicId, $this->oUserCurrent->getId());
        }
        if (isset($aAllowData['comment_new']) and $this->oUserCurrent) {
            $aTopicsRead = $this->GetTopicsReadByArray($aTopicId, $this->oUserCurrent->getId());
        }
        /**
         * Добавляем данные к результату - списку топиков
         */
        foreach ($aTopics as $oTopic) {
            if (isset($aUsers[$oTopic->getUserId()])) {
                $oTopic->setUser($aUsers[$oTopic->getUserId()]);
            } else {
                $oTopic->setUser(null); // или $oTopic->setUser(new ModuleUser_EntityUser());
            }
            $aBlogsTopic = array();
            foreach ($oTopic->getBlogIds() as $iBlogId) {
                if (isset($aBlogs[$iBlogId])) {
                    $aBlogsTopic[] = $aBlogs[$iBlogId];
                }
            }
            $oTopic->setBlogs($aBlogsTopic);
            if (isset($aTopicsVote[$oTopic->getId()])) {
                $oTopic->setVote($aTopicsVote[$oTopic->getId()]);
            } else {
                $oTopic->setVote(null);
            }
            if (isset($aFavouriteTopics[$oTopic->getId()])) {
                $oTopic->setFavourite($aFavouriteTopics[$oTopic->getId()]);
            } else {
                $oTopic->setFavourite(null);
            }
            if (isset($aTopicsRead[$oTopic->getId()])) {
                $oTopic->setCountCommentNew($oTopic->getCountComment() - $aTopicsRead[$oTopic->getId()]->getCommentCountLast());
                $oTopic->setDateRead($aTopicsRead[$oTopic->getId()]->getDateRead());
            } else {
                $oTopic->setCountCommentNew(0);
                $oTopic->setDateRead(date("Y-m-d H:i:s"));
            }
        }
        /**
         * Цепляем дополнительные поля
         */
        if (isset($aAllowData['properties'])) {
            $this->Property_RewriteGetItemsByFilter($aTopics, array('#properties' => true));
        }
        return $aTopics;
    }

    /**
     * Добавляет топик
     *
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @return ModuleTopic_EntityTopic|bool
     */
    public function AddTopic(ModuleTopic_EntityTopic $oTopic)
    {
        if (!$oTopic->getDatePublish()) {
            $oTopic->setDatePublish($oTopic->getDateAdd());
        }
        if ($sId = $this->oMapperTopic->AddTopic($oTopic)) {
            $oTopic->setId($sId);
            if ($oTopic->getPublish() and $oTopic->getTags()) {
                $aTags = explode(',', $oTopic->getTags());
                foreach ($aTags as $sTag) {
                    $oTag = Engine::GetEntity('Topic_TopicTag');
                    $oTag->setTopicId($oTopic->getId());
                    $oTag->setUserId($oTopic->getUserId());
                    $oTag->setBlogId($oTopic->getBlogId());
                    $oTag->setText($sTag);
                    $this->AddTopicTag($oTag);
                }
            }
            /**
             * Обновляем дополнительные поля
             * Здесь важный момент - перед сохранением топика всегда нужно вызывать валидацию полей $this->Property_ValidateEntityPropertiesCheck($oTopic);
             * т.к. она подготавливает данные полей для сохранений
             * Валидация вызывается автоматически при вызове $oTopic->_Validate();
             */
            $this->Property_UpdatePropertiesValue($oTopic->getPropertiesObject(), $oTopic);
            //чистим зависимые кеши
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
                'topic_new',
                "topic_update_user_{$oTopic->getUserId()}",
                "topic_new_blog_{$oTopic->getBlogId()}"
            ));
            return $oTopic;
        }
        return false;
    }

    /**
     * Добавление тега к топику
     *
     * @param ModuleTopic_EntityTopicTag $oTopicTag Объект тега топика
     * @return int
     */
    public function AddTopicTag(ModuleTopic_EntityTopicTag $oTopicTag)
    {
        return $this->oMapperTopic->AddTopicTag($oTopicTag);
    }

    /**
     * Удаляет теги у топика
     *
     * @param int $sTopicId ID топика
     * @return bool
     */
    public function DeleteTopicTagsByTopicId($sTopicId)
    {
        return $this->oMapperTopic->DeleteTopicTagsByTopicId($sTopicId);
    }

    /**
     * Удаляет топик.
     * Если тип таблиц в БД InnoDB, то удалятся всё связи по топику(комменты,голосования,избранное)
     *
     * @param ModuleTopic_EntityTopic|int $oTopic Объект топика или ID
     * @return bool
     */
    public function DeleteTopic($oTopic)
    {
        if ($oTopic instanceof ModuleTopic_EntityTopic) {
            $sTopicId = $oTopic->getId();
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                array("topic_update_user_{$oTopic->getUserId()}"));
        } else {
            $sTopicId = $oTopic;
            $oTopic = $this->GetTopicById($sTopicId);
        }
        /**
         * Удаляем дополнительные поля
         */
        $this->Property_RemovePropertiesValue($oTopic);
        /**
         * Чистим зависимые кеши
         */
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('topic_update'));
        $this->Cache_Delete("topic_{$sTopicId}");
        /**
         * Если топик успешно удален, удаляем связанные данные
         */
        if ($this->oMapperTopic->DeleteTopic($sTopicId)) {
            /**
             * Обновляем счетчики топиков в блогах
             */
            $this->Blog_RecalculateCountTopicByBlogId($oTopic->getBlogsId());

            return $this->DeleteTopicAdditionalData($sTopicId);
        }

        return false;
    }

    /**
     * Удаляет свзяанные с топика данные
     *
     * @param  int $iTopicId ID топика
     * @return bool
     */
    public function DeleteTopicAdditionalData($iTopicId)
    {
        /**
         * Чистим зависимые кеши
         */
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('topic_update'));
        $this->Cache_Delete("topic_{$iTopicId}");
        /**
         * Удаляем контент топика
         */
        $this->DeleteTopicContentByTopicId($iTopicId);
        /**
         * Удаляем медиа данные топика
         */
        $this->Media_RemoveTarget('topic', $iTopicId, true);
        /**
         * Удаляем комментарии к топику.
         * При удалении комментариев они удаляются из избранного,прямого эфира и голоса за них
         */
        $this->Comment_DeleteCommentByTargetId($iTopicId, 'topic');
        /**
         * Удаляем топик из избранного
         */
        $this->DeleteFavouriteTopicByArrayId($iTopicId);
        /**
         * Удаляем топик из прочитанного
         */
        $this->DeleteTopicReadByArrayId($iTopicId);
        /**
         * Удаляем голосование к топику
         */
        $this->Vote_DeleteVoteByTarget($iTopicId, 'topic');
        /**
         * Удаляем теги
         */
        $this->DeleteTopicTagsByTopicId($iTopicId);
        return true;
    }

    /**
     * Обновляет топик
     *
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @return bool
     */
    public function UpdateTopic(ModuleTopic_EntityTopic $oTopic)
    {
        /**
         * Получаем топик ДО изменения
         */
        $oTopicOld = $this->GetTopicById($oTopic->getId());
        $oTopic->setDateEdit(date("Y-m-d H:i:s"));
        if ($this->oMapperTopic->UpdateTopic($oTopic)) {
            /**
             * Если топик изменил видимость(publish) или локацию (BlogId) или список тегов
             */
            if (($oTopic->getPublish() != $oTopicOld->getPublish()) || ($oTopic->getBlogId() != $oTopicOld->getBlogId()) || ($oTopic->getTags() != $oTopicOld->getTags())) {
                /**
                 * Обновляем теги
                 */
                $this->DeleteTopicTagsByTopicId($oTopic->getId());
                if ($oTopic->getPublish() and $oTopic->getTags()) {
                    $aTags = explode(',', $oTopic->getTags());
                    foreach ($aTags as $sTag) {
                        $oTag = Engine::GetEntity('Topic_TopicTag');
                        $oTag->setTopicId($oTopic->getId());
                        $oTag->setUserId($oTopic->getUserId());
                        $oTag->setBlogId($oTopic->getBlogId());
                        $oTag->setText($sTag);
                        $this->AddTopicTag($oTag);
                    }
                }
            }
            if ($oTopic->getPublish() != $oTopicOld->getPublish()) {
                /**
                 * Обновляем избранное
                 */
                $this->SetFavouriteTopicPublish($oTopic->getId(), $oTopic->getPublish());
                /**
                 * Удаляем комментарий топика из прямого эфира
                 */
                if ($oTopic->getPublish() == 0) {
                    $this->Comment_DeleteCommentOnlineByTargetId($oTopic->getId(), 'topic');
                }
                /**
                 * Изменяем видимость комментов
                 */
                $this->Comment_SetCommentsPublish($oTopic->getId(), 'topic', $oTopic->getPublish());
            }
            /**
             * Смена главного блога
             */
            if ($oTopic->getBlogId() != $oTopicOld->getBlogId()) {
                // меняем target parent у комментов
                $this->Comment_MoveTargetParent($oTopicOld->getBlogId(), 'topic', $oTopic->getBlogId());
                // меняем target parent у комментов в прямом эфире
                $this->Comment_MoveTargetParentOnline($oTopicOld->getBlogId(), 'topic', $oTopic->getBlogId());
            }
            /**
             * Обновляем дополнительные поля
             * Здесь важный момент - перед сохранением топика всегда нужно вызывать валидацию полей $this->Property_ValidateEntityPropertiesCheck($oTopic);
             * т.к. она подготавливает данные полей для сохранений
             * Валидация вызывается автоматически при вызове $oTopic->_Validate();
             */
            $this->Property_UpdatePropertiesValue($oTopic->getPropertiesObject(), $oTopic);
            //чистим зависимые кеши
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                array('topic_update', "topic_update_user_{$oTopic->getUserId()}"));
            $this->Cache_Delete("topic_{$oTopic->getId()}");
            return true;
        }
        return false;
    }

    /**
     * Обновляет контент топика в БД (таблица topic_content)
     *
     * @param ModuleTopic_EntityTopic $oTopic
     *
     * @return bool
     */
    public function UpdateTopicContent($oTopic)
    {
        $bRes = $this->oMapperTopic->UpdateTopicContent($oTopic);
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array('topic_update', "topic_update_user_{$oTopic->getUserId()}"));
        $this->Cache_Delete("topic_{$oTopic->getId()}");
        return $bRes;
    }

    /**
     * Удаление контента топика по его номеру
     *
     * @param int $iTopicId ID топика
     * @return bool
     */
    public function DeleteTopicContentByTopicId($iTopicId)
    {
        return $this->oMapperTopic->DeleteTopicContentByTopicId($iTopicId);
    }

    /**
     * Получить топик по айдишнику
     *
     * @param int $sId ID топика
     * @return ModuleTopic_EntityTopic|null
     */
    public function GetTopicById($sId)
    {
        if (!is_numeric($sId)) {
            return null;
        }
        $aTopics = $this->GetTopicsAdditionalData($sId);
        if (isset($aTopics[$sId])) {
            return $aTopics[$sId];
        }
        return null;
    }

    /**
     * Получить топик по url/slug
     *
     * @param string $sSlug url/slug топика
     * @return ModuleTopic_EntityTopic|null
     */
    public function GetTopicBySlug($sSlug)
    {
        if (!is_scalar($sSlug)) {
            return null;
        }
        $aTopics = $this->GetTopicsByFilter(array('topic_slug' => $sSlug), 1, 1);
        if ($aTopics['collection']) {
            return reset($aTopics['collection']);
        }
        return null;
    }

    /**
     * Получить список топиков по списку айдишников
     *
     * @param array $aTopicId Список ID топиков
     * @return array
     */
    public function GetTopicsByArrayId($aTopicId)
    {
        if (!$aTopicId) {
            return array();
        }
        if (Config::Get('sys.cache.solid')) {
            return $this->GetTopicsByArrayIdSolid($aTopicId);
        }

        if (!is_array($aTopicId)) {
            $aTopicId = array($aTopicId);
        }
        $aTopicId = array_unique($aTopicId);
        $aTopics = array();
        $aTopicIdNotNeedQuery = array();
        /**
         * Делаем мульти-запрос к кешу
         */
        $aCacheKeys = func_build_cache_keys($aTopicId, 'topic_');
        if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
            /**
             * проверяем что досталось из кеша
             */
            foreach ($aCacheKeys as $sValue => $sKey) {
                if (array_key_exists($sKey, $data)) {
                    if ($data[$sKey]) {
                        $aTopics[$data[$sKey]->getId()] = $data[$sKey];
                    } else {
                        $aTopicIdNotNeedQuery[] = $sValue;
                    }
                }
            }
        }
        /**
         * Смотрим каких топиков не было в кеше и делаем запрос в БД
         */
        $aTopicIdNeedQuery = array_diff($aTopicId, array_keys($aTopics));
        $aTopicIdNeedQuery = array_diff($aTopicIdNeedQuery, $aTopicIdNotNeedQuery);
        $aTopicIdNeedStore = $aTopicIdNeedQuery;
        if ($data = $this->oMapperTopic->GetTopicsByArrayId($aTopicIdNeedQuery)) {
            foreach ($data as $oTopic) {
                /**
                 * Добавляем к результату и сохраняем в кеш
                 */
                $aTopics[$oTopic->getId()] = $oTopic;
                $this->Cache_Set($oTopic, "topic_{$oTopic->getId()}", array(), 60 * 60 * 24 * 4);
                $aTopicIdNeedStore = array_diff($aTopicIdNeedStore, array($oTopic->getId()));
            }
        }
        /**
         * Сохраняем в кеш запросы не вернувшие результата
         */
        foreach ($aTopicIdNeedStore as $sId) {
            $this->Cache_Set(null, "topic_{$sId}", array(), 60 * 60 * 24 * 4);
        }
        /**
         * Сортируем результат согласно входящему массиву
         */
        $aTopics = func_array_sort_by_keys($aTopics, $aTopicId);
        return $aTopics;
    }

    /**
     * Получить список топиков по списку айдишников, но используя единый кеш
     *
     * @param array $aTopicId Список ID топиков
     * @return array
     */
    public function GetTopicsByArrayIdSolid($aTopicId)
    {
        if (!is_array($aTopicId)) {
            $aTopicId = array($aTopicId);
        }
        $aTopicId = array_unique($aTopicId);
        $aTopics = array();
        $s = join(',', $aTopicId);
        if (false === ($data = $this->Cache_Get("topic_id_{$s}"))) {
            $data = $this->oMapperTopic->GetTopicsByArrayId($aTopicId);
            foreach ($data as $oTopic) {
                $aTopics[$oTopic->getId()] = $oTopic;
            }
            $this->Cache_Set($aTopics, "topic_id_{$s}", array("topic_update"), 60 * 60 * 24 * 1);
            return $aTopics;
        }
        return $data;
    }

    /**
     * Получает список топиков из избранного
     *
     * @param  int $sUserId ID пользователя
     * @param  int $iCurrPage Номер текущей страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @return array('collection'=>array,'count'=>int)
     */
    public function GetTopicsFavouriteByUserId($sUserId, $iCurrPage, $iPerPage)
    {
        $aCloseTopics = array();
        /**
         * Получаем список идентификаторов избранных записей
         */
        $data = ($this->oUserCurrent && $sUserId == $this->oUserCurrent->getId())
            ? $this->Favourite_GetFavouritesByUserId($sUserId, 'topic', $iCurrPage, $iPerPage, $aCloseTopics)
            : $this->Favourite_GetFavouriteOpenTopicsByUserId($sUserId, $iCurrPage, $iPerPage);
        /**
         * Получаем записи по переданому массиву айдишников
         */
        $data['collection'] = $this->GetTopicsAdditionalData($data['collection']);
        return $data;
    }

    /**
     * Возвращает число топиков в избранном
     *
     * @param  int $sUserId ID пользователя
     * @return int
     */
    public function GetCountTopicsFavouriteByUserId($sUserId)
    {
        $aCloseTopics = array();
        return ($this->oUserCurrent && $sUserId == $this->oUserCurrent->getId())
            ? $this->Favourite_GetCountFavouritesByUserId($sUserId, 'topic', $aCloseTopics)
            : $this->Favourite_GetCountFavouriteOpenTopicsByUserId($sUserId);
    }

    /**
     * Список топиков по фильтру
     *
     * @param  array $aFilter Фильтр
     * @param  int $iPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @param  array|null $aAllowData Список типов данных для подгрузки в топики
     * @return array('collection'=>array,'count'=>int)
     */
    public function GetTopicsByFilter($aFilter, $iPage = 1, $iPerPage = 10, $aAllowData = null)
    {
        if (!is_numeric($iPage) or $iPage <= 0) {
            $iPage = 1;
        }
        $s = serialize($aFilter);
        if (false === ($data = $this->Cache_Get("topic_filter_{$s}_{$iPage}_{$iPerPage}"))) {
            $data = array(
                'collection' => $this->oMapperTopic->GetTopics($aFilter, $iCount, $iPage, $iPerPage),
                'count'      => $iCount
            );
            $this->Cache_Set($data, "topic_filter_{$s}_{$iPage}_{$iPerPage}", array('topic_update', 'topic_new'),
                60 * 60 * 24 * 3);
        }
        $data['collection'] = $this->GetTopicsAdditionalData($data['collection'], $aAllowData);
        return $data;
    }

    /**
     * Количество топиков по фильтру
     *
     * @param array $aFilter Фильтр
     * @return int
     */
    public function GetCountTopicsByFilter($aFilter)
    {
        $s = serialize($aFilter);
        if (false === ($data = $this->Cache_Get("topic_count_{$s}"))) {
            $data = $this->oMapperTopic->GetCountTopics($aFilter);
            $this->Cache_Set($data, "topic_count_{$s}", array('topic_update', 'topic_new'), 60 * 60 * 24 * 1);
        }
        return $data;
    }

    /**
     * Количество черновиков у пользователя
     *
     * @param int $iUserId ID пользователя
     * @return int
     */
    public function GetCountDraftTopicsByUserId($iUserId)
    {
        return $this->GetCountTopicsByFilter(array(
            'user_id'       => $iUserId,
            'topic_publish' => 0
        ));
    }

    /**
     * Получает список хороших топиков для вывода на главную страницу(из всех блогов, как коллективных так и персональных)
     *
     * @param  int $iPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @param  bool $bAddAccessible Указывает на необходимость добавить в выдачу топики,
     *                                из блогов доступных пользователю. При указании false,
     *                                в выдачу будут переданы только топики из общедоступных блогов.
     * @return array
     */
    public function GetTopicsGood($iPage, $iPerPage, $bAddAccessible = true)
    {
        $aFilter = array(
            'blog_type'     => array(
                'personal',
                'open'
            ),
            'topic_publish' => 1,
            'topic_rating'  => array(
                'value'         => Config::Get('module.blog.index_good'),
                'type'          => 'top',
                'publish_index' => 1,
            )
        );
        /**
         * Если пользователь авторизирован, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent && $bAddAccessible) {
            $aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
            if (count($aOpenBlogs)) {
                $aFilter['blog_type']['close'] = $aOpenBlogs;
            }
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array('aFilter' => &$aFilter, 'iPage' => $iPage, 'iPerPage' => $iPerPage, 'sMethod' => __FUNCTION__));
        return $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage);
    }

    /**
     * Получает список новых топиков, ограничение новизны по дате из конфига
     *
     * @param  int $iPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @param  bool $bAddAccessible Указывает на необходимость добавить в выдачу топики,
     *                                из блогов доступных пользователю. При указании false,
     *                                в выдачу будут переданы только топики из общедоступных блогов.
     * @return array
     */
    public function GetTopicsNew($iPage, $iPerPage, $bAddAccessible = true)
    {
        $sDate = date("Y-m-d H:00:00", time() - Config::Get('module.topic.new_time'));
        $aFilter = array(
            'blog_type'     => array(
                'personal',
                'open',
            ),
            'topic_publish' => 1,
            'topic_new'     => $sDate,
        );
        /**
         * Если пользователь авторизирован, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent && $bAddAccessible) {
            $aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
            if (count($aOpenBlogs)) {
                $aFilter['blog_type']['close'] = $aOpenBlogs;
            }
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array('aFilter' => &$aFilter, 'iPage' => $iPage, 'iPerPage' => $iPerPage, 'sMethod' => __FUNCTION__));
        return $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage);
    }

    /**
     * Получает список ВСЕХ новых топиков
     *
     * @param  int $iPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @param  bool $bAddAccessible Указывает на необходимость добавить в выдачу топики,
     *                                из блогов доступных пользователю. При указании false,
     *                                в выдачу будут переданы только топики из общедоступных блогов.
     * @return array
     */
    public function GetTopicsNewAll($iPage, $iPerPage, $bAddAccessible = true)
    {
        $aFilter = array(
            'blog_type'     => array(
                'personal',
                'open',
            ),
            'topic_publish' => 1,
        );
        /**
         * Если пользователь авторизирован, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent && $bAddAccessible) {
            $aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
            if (count($aOpenBlogs)) {
                $aFilter['blog_type']['close'] = $aOpenBlogs;
            }
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array('aFilter' => &$aFilter, 'iPage' => $iPage, 'iPerPage' => $iPerPage, 'sMethod' => __FUNCTION__));
        return $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage);
    }

    /**
     * Получает список ВСЕХ обсуждаемых топиков
     *
     * @param  int $iPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @param  int|string $sPeriod Период в виде секунд или конкретной даты
     * @param  bool $bAddAccessible Указывает на необходимость добавить в выдачу топики,
     *                                из блогов доступных пользователю. При указании false,
     *                                в выдачу будут переданы только топики из общедоступных блогов.
     * @return array
     */
    public function GetTopicsDiscussed($iPage, $iPerPage, $sPeriod = null, $bAddAccessible = true)
    {
        if (is_numeric($sPeriod)) {
            // количество последних секунд
            $sPeriod = date("Y-m-d H:00:00", time() - $sPeriod);
        }

        $aFilter = array(
            'blog_type'     => array(
                'personal',
                'open',
            ),
            'topic_publish' => 1
        );
        if ($sPeriod) {
            $aFilter['topic_date_more'] = $sPeriod;
        }
        $aFilter['order'] = ' t.topic_count_comment desc, t.topic_id desc ';
        /**
         * Если пользователь авторизирован, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent && $bAddAccessible) {
            $aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
            if (count($aOpenBlogs)) {
                $aFilter['blog_type']['close'] = $aOpenBlogs;
            }
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array('aFilter' => &$aFilter, 'iPage' => $iPage, 'iPerPage' => $iPerPage, 'sMethod' => __FUNCTION__));
        return $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage);
    }

    /**
     * Получает список ВСЕХ рейтинговых топиков
     *
     * @param  int $iPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @param  int|string $sPeriod Период в виде секунд или конкретной даты
     * @param  bool $bAddAccessible Указывает на необходимость добавить в выдачу топики,
     *                                из блогов доступных пользователю. При указании false,
     *                                в выдачу будут переданы только топики из общедоступных блогов.
     * @return array
     */
    public function GetTopicsTop($iPage, $iPerPage, $sPeriod = null, $bAddAccessible = true)
    {
        if (is_numeric($sPeriod)) {
            // количество последних секунд
            $sPeriod = date("Y-m-d H:00:00", time() - $sPeriod);
        }

        $aFilter = array(
            'blog_type'     => array(
                'personal',
                'open',
            ),
            'topic_publish' => 1
        );
        if ($sPeriod) {
            $aFilter['topic_date_more'] = $sPeriod;
        }
        $aFilter['order'] = array('t.topic_rating desc', 't.topic_id desc');
        /**
         * Если пользователь авторизирован, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent && $bAddAccessible) {
            $aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
            if (count($aOpenBlogs)) {
                $aFilter['blog_type']['close'] = $aOpenBlogs;
            }
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array('aFilter' => &$aFilter, 'iPage' => $iPage, 'iPerPage' => $iPerPage, 'sMethod' => __FUNCTION__));
        return $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage);
    }

    /**
     * Получает заданое число последних топиков
     *
     * @param int $iCount Количество
     * @return array
     */
    public function GetTopicsLast($iCount)
    {
        $aFilter = array(
            'blog_type'     => array(
                'personal',
                'open',
            ),
            'topic_publish' => 1,
        );
        /**
         * Если пользователь авторизирован, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent) {
            $aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
            if (count($aOpenBlogs)) {
                $aFilter['blog_type']['close'] = $aOpenBlogs;
            }
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array('aFilter' => &$aFilter, 'iPage' => 1, 'iPerPage' => $iCount, 'sMethod' => __FUNCTION__));
        $aReturn = $this->GetTopicsByFilter($aFilter, 1, $iCount);
        if (isset($aReturn['collection'])) {
            return $aReturn['collection'];
        }
        return false;
    }

    /**
     * список топиков из персональных блогов
     *
     * @param int $iPage Номер страницы
     * @param int $iPerPage Количество элементов на страницу
     * @param string $sShowType Тип выборки топиков
     * @param string|int $sPeriod Период в виде секунд или конкретной даты
     * @return array
     */
    public function GetTopicsPersonal($iPage, $iPerPage, $sShowType = 'good', $sPeriod = null)
    {
        if (is_numeric($sPeriod)) {
            // количество последних секунд
            $sPeriod = date("Y-m-d H:00:00", time() - $sPeriod);
        }
        $aFilter = array(
            'blog_type'     => array(
                'personal',
            ),
            'topic_publish' => 1,
        );
        if ($sPeriod) {
            $aFilter['topic_date_more'] = $sPeriod;
        }
        switch ($sShowType) {
            case 'good':
                $aFilter['topic_rating'] = array(
                    'value' => Config::Get('module.blog.personal_good'),
                    'type'  => 'top',
                );
                break;
            case 'bad':
                $aFilter['topic_rating'] = array(
                    'value' => Config::Get('module.blog.personal_good'),
                    'type'  => 'down',
                );
                break;
            case 'new':
                $aFilter['topic_new'] = date("Y-m-d H:00:00", time() - Config::Get('module.topic.new_time'));
                break;
            case 'newall':
                // нет доп фильтра
                break;
            case 'discussed':
                $aFilter['order'] = array('t.topic_count_comment desc', 't.topic_id desc');
                break;
            case 'top':
                $aFilter['order'] = array('t.topic_rating desc', 't.topic_id desc');
                break;
            default:
                break;
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array(
                'aFilter'   => &$aFilter,
                'iPage'     => $iPage,
                'iPerPage'  => $iPerPage,
                'sShowType' => $sShowType,
                'sMethod'   => __FUNCTION__
            ));
        return $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage);
    }

    /**
     * Получает число новых топиков в персональных блогах
     *
     * @return int
     */
    public function GetCountTopicsPersonalNew()
    {
        $sDate = date("Y-m-d H:00:00", time() - Config::Get('module.topic.new_time'));
        $aFilter = array(
            'blog_type'     => array(
                'personal',
            ),
            'topic_publish' => 1,
            'topic_new'     => $sDate,
        );
        return $this->GetCountTopicsByFilter($aFilter);
    }

    /**
     * Получает список топиков по юзеру
     *
     * @param int $sUserId ID пользователя
     * @param int $iPublish Флаг публикации топика
     * @param int $iPage Номер страницы
     * @param int $iPerPage Количество элементов на страницу
     * @return array
     */
    public function GetTopicsPersonalByUser($sUserId, $iPublish, $iPage, $iPerPage)
    {
        $aFilter = array(
            'topic_publish' => $iPublish,
            'user_id'       => $sUserId,
            'blog_type'     => array('open', 'personal'),
        );
        /**
         * Если пользователь смотрит свой профиль, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent && $this->oUserCurrent->getId() == $sUserId) {
            $aFilter['blog_type'][] = 'close';
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array('aFilter' => &$aFilter, 'iPage' => $iPage, 'iPerPage' => $iPerPage, 'sMethod' => __FUNCTION__));
        return $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage);
    }

    /**
     * Возвращает количество топиков которые создал юзер
     *
     * @param int $sUserId ID пользователя
     * @param int $iPublish Флаг публикации топика
     * @return array
     */
    public function GetCountTopicsPersonalByUser($sUserId, $iPublish)
    {
        $aFilter = array(
            'topic_publish' => $iPublish,
            'user_id'       => $sUserId,
            'blog_type'     => array('open', 'personal'),
        );
        /**
         * Если пользователь смотрит свой профиль, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent && $this->oUserCurrent->getId() == $sUserId) {
            $aFilter['blog_type'][] = 'close';
        }
        $s = serialize($aFilter);
        if (false === ($data = $this->Cache_Get("topic_count_user_{$s}"))) {
            $data = $this->oMapperTopic->GetCountTopics($aFilter);
            $this->Cache_Set($data, "topic_count_user_{$s}", array("topic_update_user_{$sUserId}"), 60 * 60 * 24);
        }
        return $data;
    }

    /**
     * Получает список топиков из указанного блога
     *
     * @param  int $iBlogId ID блога
     * @param  int $iPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @param  array $aAllowData Список типов данных для подгрузки в топики
     * @param  bool $bIdsOnly Возвращать только ID или список объектов
     * @return array
     */
    public function GetTopicsByBlogId($iBlogId, $iPage = 1, $iPerPage = 20, $aAllowData = array(), $bIdsOnly = true)
    {
        $aFilter = array('blog_id' => $iBlogId);
        $this->Hook_Run('get_topics_by_custom_filter',
            array('aFilter' => &$aFilter, 'iPage' => $iPage, 'iPerPage' => $iPerPage, 'sMethod' => __FUNCTION__));
        if (!$aTopics = $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage, $aAllowData)) {
            return array();
        }

        return ($bIdsOnly)
            ? array_keys($aTopics['collection'])
            : $aTopics;
    }

    /**
     * Список топиков из коллективных блогов
     *
     * @param int $iPage Номер страницы
     * @param int $iPerPage Количество элементов на страницу
     * @param string $sShowType Тип выборки топиков
     * @param string $sPeriod Период в виде секунд или конкретной даты
     * @return array
     */
    public function GetTopicsCollective($iPage, $iPerPage, $sShowType = 'good', $sPeriod = null)
    {
        if (is_numeric($sPeriod)) {
            // количество последних секунд
            $sPeriod = date("Y-m-d H:00:00", time() - $sPeriod);
        }
        $aFilter = array(
            'blog_type'     => array(
                'open',
            ),
            'topic_publish' => 1,
        );
        if ($sPeriod) {
            $aFilter['topic_date_more'] = $sPeriod;
        }
        switch ($sShowType) {
            case 'good':
                $aFilter['topic_rating'] = array(
                    'value' => Config::Get('module.blog.collective_good'),
                    'type'  => 'top',
                );
                break;
            case 'bad':
                $aFilter['topic_rating'] = array(
                    'value' => Config::Get('module.blog.collective_good'),
                    'type'  => 'down',
                );
                break;
            case 'new':
                $aFilter['topic_new'] = date("Y-m-d H:00:00", time() - Config::Get('module.topic.new_time'));
                break;
            case 'newall':
                // нет доп фильтра
                break;
            case 'discussed':
                $aFilter['order'] = array('t.topic_count_comment desc', 't.topic_id desc');
                break;
            case 'top':
                $aFilter['order'] = array('t.topic_rating desc', 't.topic_id desc');
                break;
            default:
                break;
        }
        /**
         * Если пользователь авторизирован, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent) {
            $aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
            if (count($aOpenBlogs)) {
                $aFilter['blog_type']['close'] = $aOpenBlogs;
            }
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array(
                'aFilter'   => &$aFilter,
                'iPage'     => $iPage,
                'iPerPage'  => $iPerPage,
                'sShowType' => $sShowType,
                'sMethod'   => __FUNCTION__
            ));
        return $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage);
    }

    /**
     * Получает число новых топиков в коллективных блогах
     *
     * @return int
     */
    public function GetCountTopicsCollectiveNew()
    {
        $sDate = date("Y-m-d H:00:00", time() - Config::Get('module.topic.new_time'));
        $aFilter = array(
            'blog_type'     => array(
                'open',
            ),
            'topic_publish' => 1,
            'topic_new'     => $sDate,
        );
        /**
         * Если пользователь авторизирован, то добавляем в выдачу
         * закрытые блоги в которых он состоит
         */
        if ($this->oUserCurrent) {
            $aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
            if (count($aOpenBlogs)) {
                $aFilter['blog_type']['close'] = $aOpenBlogs;
            }
        }
        return $this->GetCountTopicsByFilter($aFilter);
    }

    /**
     * Получает топики по рейтингу и дате
     *
     * @param string $sDate Дата
     * @param int $iLimit Количество
     * @return array
     */
    public function GetTopicsRatingByDate($sDate, $iLimit = 20)
    {
        /**
         * Получаем список блогов, топики которых нужно исключить из выдачи
         */
        $aCloseBlogs = ($this->oUserCurrent)
            ? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
            : $this->Blog_GetInaccessibleBlogsByUser();

        $s = serialize($aCloseBlogs);

        if (false === ($data = $this->Cache_Get("topic_rating_{$sDate}_{$iLimit}_{$s}"))) {
            $data = $this->oMapperTopic->GetTopicsRatingByDate($sDate, $iLimit, $aCloseBlogs);
            $this->Cache_Set($data, "topic_rating_{$sDate}_{$iLimit}_{$s}", array('topic_update'), 60 * 60 * 24 * 2);
        }
        $data = $this->GetTopicsAdditionalData($data);
        return $data;
    }

    /**
     * Список топиков из блога
     *
     * @param ModuleBlog_EntityBlog $oBlog Объект блога
     * @param int $iPage Номер страницы
     * @param int $iPerPage Количество элементов на страницу
     * @param string $sShowType Тип выборки топиков
     * @param string $sPeriod Период в виде секунд или конкретной даты
     * @return array
     */
    public function GetTopicsByBlog($oBlog, $iPage, $iPerPage, $sShowType = 'good', $sPeriod = null)
    {
        if (is_numeric($sPeriod)) {
            // количество последних секунд
            $sPeriod = date("Y-m-d H:00:00", time() - $sPeriod);
        }
        $aFilter = array(
            'topic_publish' => 1,
            'blog_id'       => $oBlog->getId(),
        );
        if ($sPeriod) {
            $aFilter['topic_date_more'] = $sPeriod;
        }
        switch ($sShowType) {
            case 'good':
                $aFilter['topic_rating'] = array(
                    'value' => Config::Get('module.blog.collective_good'),
                    'type'  => 'top',
                );
                break;
            case 'bad':
                $aFilter['topic_rating'] = array(
                    'value' => Config::Get('module.blog.collective_good'),
                    'type'  => 'down',
                );
                break;
            case 'new':
                $aFilter['topic_new'] = date("Y-m-d H:00:00", time() - Config::Get('module.topic.new_time'));
                break;
            case 'newall':
                // нет доп фильтра
                break;
            case 'discussed':
                $aFilter['order'] = array('t.topic_count_comment desc', 't.topic_id desc');
                break;
            case 'top':
                $aFilter['order'] = array('t.topic_rating desc', 't.topic_id desc');
                break;
            default:
                break;
        }
        $this->Hook_Run('get_topics_by_custom_filter',
            array(
                'aFilter'   => &$aFilter,
                'iPage'     => $iPage,
                'iPerPage'  => $iPerPage,
                'sShowType' => $sShowType,
                'sMethod'   => __FUNCTION__
            ));
        return $this->GetTopicsByFilter($aFilter, $iPage, $iPerPage);
    }

    /**
     * Получает число новых топиков из блога
     *
     * @param ModuleBlog_EntityBlog $oBlog Объект блога
     * @return int
     */
    public function GetCountTopicsByBlogNew($oBlog)
    {
        $sDate = date("Y-m-d H:00:00", time() - Config::Get('module.topic.new_time'));
        $aFilter = array(
            'topic_publish' => 1,
            'blog_id'       => $oBlog->getId(),
            'topic_new'     => $sDate,

        );
        return $this->GetCountTopicsByFilter($aFilter);
    }

    /**
     * Получает список топиков по тегу
     *
     * @param  string $sTag Тег
     * @param  int $iPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @param  bool $bAddAccessible Указывает на необходимость добавить в выдачу топики,
     *                                из блогов доступных пользователю. При указании false,
     *                                в выдачу будут переданы только топики из общедоступных блогов.
     * @return array
     */
    public function GetTopicsByTag($sTag, $iPage, $iPerPage, $bAddAccessible = true)
    {
        $aCloseBlogs = ($this->oUserCurrent && $bAddAccessible)
            ? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
            : $this->Blog_GetInaccessibleBlogsByUser();

        $s = serialize($aCloseBlogs);
        if (false === ($data = $this->Cache_Get("topic_tag_{$sTag}_{$iPage}_{$iPerPage}_{$s}"))) {
            $data = array(
                'collection' => $this->oMapperTopic->GetTopicsByTag($sTag, $aCloseBlogs, $iCount, $iPage, $iPerPage),
                'count'      => $iCount
            );
            $this->Cache_Set($data, "topic_tag_{$sTag}_{$iPage}_{$iPerPage}_{$s}", array('topic_update', 'topic_new'),
                60 * 60 * 24 * 2);
        }
        $data['collection'] = $this->GetTopicsAdditionalData($data['collection']);
        return $data;
    }

    /**
     * Получает список тегов топиков
     *
     * @param int $iLimit Количество
     * @param array $aExcludeTopic Список ID топиков для исключения
     * @return array
     */
    public function GetTopicTags($iLimit, $aExcludeTopic = array())
    {
        $s = serialize($aExcludeTopic);
        if (false === ($data = $this->Cache_Get("tag_{$iLimit}_{$s}"))) {
            $data = $this->oMapperTopic->GetTopicTags($iLimit, $aExcludeTopic);
            $this->Cache_Set($data, "tag_{$iLimit}_{$s}", array('topic_update', 'topic_new'), 60 * 60 * 24 * 3);
        }
        return $data;
    }

    /**
     * Получает список тегов из топиков открытых блогов (open,personal)
     *
     * @param  int $iLimit Количество
     * @param  int|null $iUserId ID пользователя, чью теги получаем
     * @return array
     */
    public function GetOpenTopicTags($iLimit, $iUserId = null)
    {
        if (false === ($data = $this->Cache_Get("tag_{$iLimit}_{$iUserId}_open"))) {
            $data = $this->oMapperTopic->GetOpenTopicTags($iLimit, $iUserId);
            $this->Cache_Set($data, "tag_{$iLimit}_{$iUserId}_open", array('topic_update', 'topic_new'),
                60 * 60 * 24 * 3);
        }
        return $data;
    }

    /**
     * Увеличивает у топика число комментов
     *
     * @param int $sTopicId ID топика
     * @return bool
     */
    public function increaseTopicCountComment($sTopicId)
    {
        $this->Cache_Delete("topic_{$sTopicId}");
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array("topic_update"));
        return $this->oMapperTopic->increaseTopicCountComment($sTopicId);
    }

    /**
     * Получает привязку топика к ибранному(добавлен ли топик в избранное у юзера)
     *
     * @param int $sTopicId ID топика
     * @param int $sUserId ID пользователя
     * @return ModuleFavourite_EntityFavourite
     */
    public function GetFavouriteTopic($sTopicId, $sUserId)
    {
        return $this->Favourite_GetFavourite($sTopicId, 'topic', $sUserId);
    }

    /**
     * Получить список избранного по списку айдишников
     *
     * @param array $aTopicId Список ID топиков
     * @param int $sUserId ID пользователя
     * @return array
     */
    public function GetFavouriteTopicsByArray($aTopicId, $sUserId)
    {
        return $this->Favourite_GetFavouritesByArray($aTopicId, 'topic', $sUserId);
    }

    /**
     * Получить список избранного по списку айдишников, но используя единый кеш
     *
     * @param array $aTopicId Список ID топиков
     * @param int $sUserId ID пользователя
     * @return array
     */
    public function GetFavouriteTopicsByArraySolid($aTopicId, $sUserId)
    {
        return $this->Favourite_GetFavouritesByArraySolid($aTopicId, 'topic', $sUserId);
    }

    /**
     * Добавляет топик в избранное
     *
     * @param ModuleFavourite_EntityFavourite $oFavouriteTopic Объект избранного
     * @return bool
     */
    public function AddFavouriteTopic(ModuleFavourite_EntityFavourite $oFavouriteTopic)
    {
        return $this->Favourite_AddFavourite($oFavouriteTopic);
    }

    /**
     * Удаляет топик из избранного
     *
     * @param ModuleFavourite_EntityFavourite $oFavouriteTopic Объект избранного
     * @return bool
     */
    public function DeleteFavouriteTopic(ModuleFavourite_EntityFavourite $oFavouriteTopic)
    {
        return $this->Favourite_DeleteFavourite($oFavouriteTopic);
    }

    /**
     * Устанавливает переданный параметр публикации таргета (топика)
     *
     * @param  int $sTopicId ID топика
     * @param  int $iPublish Флаг публикации топика
     * @return bool
     */
    public function SetFavouriteTopicPublish($sTopicId, $iPublish)
    {
        return $this->Favourite_SetFavouriteTargetPublish($sTopicId, 'topic', $iPublish);
    }

    /**
     * Удаляет топики из избранного по списку
     *
     * @param  array $aTopicId Список ID топиков
     * @return bool
     */
    public function DeleteFavouriteTopicByArrayId($aTopicId)
    {
        return $this->Favourite_DeleteFavouriteByTargetId($aTopicId, 'topic');
    }

    /**
     * Получает список тегов по первым буквам тега
     *
     * @param string $sTag Тэг
     * @param int $iLimit Количество
     * @return bool
     */
    public function GetTopicTagsByLike($sTag, $iLimit)
    {
        if (false === ($data = $this->Cache_Get("tag_like_{$sTag}_{$iLimit}"))) {
            $data = $this->oMapperTopic->GetTopicTagsByLike($sTag, $iLimit);
            $this->Cache_Set($data, "tag_like_{$sTag}_{$iLimit}", array("topic_update", "topic_new"), 60 * 60 * 24 * 3);
        }
        return $data;
    }

    /**
     * Обновляем/устанавливаем дату прочтения топика, если читаем его первый раз то добавляем
     *
     * @param ModuleTopic_EntityTopicRead $oTopicRead Объект факта чтения топика
     * @return bool
     */
    public function SetTopicRead(ModuleTopic_EntityTopicRead $oTopicRead)
    {
        if ($this->GetTopicRead($oTopicRead->getTopicId(), $oTopicRead->getUserId())) {
            $this->Cache_Delete("topic_read_{$oTopicRead->getTopicId()}_{$oTopicRead->getUserId()}");
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                array("topic_read_user_{$oTopicRead->getUserId()}"));
            $this->oMapperTopic->UpdateTopicRead($oTopicRead);
        } else {
            $this->Cache_Delete("topic_read_{$oTopicRead->getTopicId()}_{$oTopicRead->getUserId()}");
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                array("topic_read_user_{$oTopicRead->getUserId()}"));
            $this->oMapperTopic->AddTopicRead($oTopicRead);
        }
        return true;
    }

    /**
     * Получаем дату прочтения топика юзером
     *
     * @param int $sTopicId ID топика
     * @param int $sUserId ID пользователя
     * @return ModuleTopic_EntityTopicRead|null
     */
    public function GetTopicRead($sTopicId, $sUserId)
    {
        $data = $this->GetTopicsReadByArray($sTopicId, $sUserId);
        if (isset($data[$sTopicId])) {
            return $data[$sTopicId];
        }
        return null;
    }

    /**
     * Удаляет записи о чтении записей по списку идентификаторов
     *
     * @param  array|int $aTopicId Список ID топиков
     * @return bool
     */
    public function DeleteTopicReadByArrayId($aTopicId)
    {
        if (!is_array($aTopicId)) {
            $aTopicId = array($aTopicId);
        }
        return $this->oMapperTopic->DeleteTopicReadByArrayId($aTopicId);
    }

    /**
     * Получить список просмотром/чтения топиков по списку айдишников
     *
     * @param array $aTopicId Список ID топиков
     * @param int $sUserId ID пользователя
     * @return array
     */
    public function GetTopicsReadByArray($aTopicId, $sUserId)
    {
        if (!$aTopicId) {
            return array();
        }
        if (Config::Get('sys.cache.solid')) {
            return $this->GetTopicsReadByArraySolid($aTopicId, $sUserId);
        }
        if (!is_array($aTopicId)) {
            $aTopicId = array($aTopicId);
        }
        $aTopicId = array_unique($aTopicId);
        $aTopicsRead = array();
        $aTopicIdNotNeedQuery = array();
        /**
         * Делаем мульти-запрос к кешу
         */
        $aCacheKeys = func_build_cache_keys($aTopicId, 'topic_read_', '_' . $sUserId);
        if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
            /**
             * проверяем что досталось из кеша
             */
            foreach ($aCacheKeys as $sValue => $sKey) {
                if (array_key_exists($sKey, $data)) {
                    if ($data[$sKey]) {
                        $aTopicsRead[$data[$sKey]->getTopicId()] = $data[$sKey];
                    } else {
                        $aTopicIdNotNeedQuery[] = $sValue;
                    }
                }
            }
        }
        /**
         * Смотрим каких топиков не было в кеше и делаем запрос в БД
         */
        $aTopicIdNeedQuery = array_diff($aTopicId, array_keys($aTopicsRead));
        $aTopicIdNeedQuery = array_diff($aTopicIdNeedQuery, $aTopicIdNotNeedQuery);
        $aTopicIdNeedStore = $aTopicIdNeedQuery;
        if ($data = $this->oMapperTopic->GetTopicsReadByArray($aTopicIdNeedQuery, $sUserId)) {
            foreach ($data as $oTopicRead) {
                /**
                 * Добавляем к результату и сохраняем в кеш
                 */
                $aTopicsRead[$oTopicRead->getTopicId()] = $oTopicRead;
                $this->Cache_Set($oTopicRead, "topic_read_{$oTopicRead->getTopicId()}_{$oTopicRead->getUserId()}",
                    array(), 60 * 60 * 24 * 4);
                $aTopicIdNeedStore = array_diff($aTopicIdNeedStore, array($oTopicRead->getTopicId()));
            }
        }
        /**
         * Сохраняем в кеш запросы не вернувшие результата
         */
        foreach ($aTopicIdNeedStore as $sId) {
            $this->Cache_Set(null, "topic_read_{$sId}_{$sUserId}", array(), 60 * 60 * 24 * 4);
        }
        /**
         * Сортируем результат согласно входящему массиву
         */
        $aTopicsRead = func_array_sort_by_keys($aTopicsRead, $aTopicId);
        return $aTopicsRead;
    }

    /**
     * Получить список просмотром/чтения топиков по списку айдишников, но используя единый кеш
     *
     * @param array $aTopicId Список ID топиков
     * @param int $sUserId ID пользователя
     * @return array
     */
    public function GetTopicsReadByArraySolid($aTopicId, $sUserId)
    {
        if (!is_array($aTopicId)) {
            $aTopicId = array($aTopicId);
        }
        $aTopicId = array_unique($aTopicId);
        $aTopicsRead = array();
        $s = join(',', $aTopicId);
        if (false === ($data = $this->Cache_Get("topic_read_{$sUserId}_id_{$s}"))) {
            $data = $this->oMapperTopic->GetTopicsReadByArray($aTopicId, $sUserId);
            foreach ($data as $oTopicRead) {
                $aTopicsRead[$oTopicRead->getTopicId()] = $oTopicRead;
            }
            $this->Cache_Set($aTopicsRead, "topic_read_{$sUserId}_id_{$s}", array("topic_read_user_{$sUserId}"),
                60 * 60 * 24 * 1);
            return $aTopicsRead;
        }
        return $data;
    }

    /**
     * Получает топик по уникальному хешу(текст топика)
     *
     * @param int $sUserId
     * @param string $sHash
     * @return ModuleTopic_EntityTopic|null
     */
    public function GetTopicUnique($sUserId, $sHash)
    {
        $sId = $this->oMapperTopic->GetTopicUnique($sUserId, $sHash);
        return $this->GetTopicById($sId);
    }

    /**
     * Рассылает уведомления о новом топике подписчикам блогов
     *
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @param ModuleUser_EntityUser $oUserTopic Объект пользователя
     */
    public function SendNotifyTopicNew($oTopic, $oUserTopic)
    {
        /**
         * Сначала отправляем подписчикам блогов
         */
        $iPage = 1;
        $aBlogs = $oTopic->getBlogsId();
        $aUserIdSend = array($oUserTopic->getId());
        while ($aBlogUsersResult = $this->Blog_GetBlogUsersByBlogId($aBlogs, null, $iPage,
                50) and $aBlogUsersResult['collection']) {
            $aBlogUsers = $aBlogUsersResult['collection'];
            foreach ($aBlogUsers as $oBlogUser) {
                if (in_array($oBlogUser->getUserId(), $aUserIdSend)) {
                    continue;
                }
                $this->SendNotifyTopicNewToSubscribeBlog($oBlogUser->getUser(), $oTopic, $oBlogUser->getBlog(),
                    $oUserTopic);
                $aUserIdSend[] = $oBlogUser->getUserId();
            }

            $iPage++;
        }
        /**
         * Теперь отправляем авторам блогов
         */
        $aBlogs = $this->Blog_GetBlogsAdditionalData($aBlogs);
        foreach ($aBlogs as $oBlog) {
            if ($oBlog->getOwnerId() != $oUserTopic->getId() and !in_array($oBlog->getOwnerId(), $aUserIdSend)) {
                $this->SendNotifyTopicNewToSubscribeBlog($oBlog->getOwner(), $oTopic, $oBlog, $oUserTopic);
                $aUserIdSend[] = $oBlog->getOwnerId();
            }
        }
    }

    /**
     * Возвращает список последних топиков пользователя, опубликованных не более чем $iTimeLimit секунд назад
     *
     * @param  int $sUserId ID пользователя
     * @param  int $iTimeLimit Число секунд
     * @param  int $iCountLimit Количество
     * @param  array $aAllowData Список типов данных для подгрузки в топики
     * @return array
     */
    public function GetLastTopicsByUserId($sUserId, $iTimeLimit, $iCountLimit = 1, $aAllowData = array())
    {
        $aFilter = array(
            'topic_publish' => 1,
            'user_id'       => $sUserId,
            'topic_new'     => date("Y-m-d H:i:s", time() - $iTimeLimit),
        );
        $this->Hook_Run('get_topics_by_custom_filter',
            array('aFilter' => &$aFilter, 'iPage' => 1, 'iPerPage' => $iCountLimit, 'sMethod' => __FUNCTION__));
        $aTopics = $this->GetTopicsByFilter($aFilter, 1, $iCountLimit, $aAllowData);

        return $aTopics;
    }

    /**
     * Перемещает топики в другой блог
     *
     * @param  int $sBlogId ID старого блога
     * @param  int $sBlogIdNew ID нового блога
     * @return bool
     */
    public function MoveTopics($sBlogId, $sBlogIdNew)
    {
        if ($res = $this->oMapperTopic->MoveTopics($sBlogId, $sBlogIdNew)) {
            // перемещаем теги
            $this->oMapperTopic->MoveTopicsTags($sBlogId, $sBlogIdNew);
            // меняем target parent у комментов
            $this->Comment_MoveTargetParent($sBlogId, 'topic', $sBlogIdNew);
            // меняем target parent у комментов в прямом эфире
            $this->Comment_MoveTargetParentOnline($sBlogId, 'topic', $sBlogIdNew);
            /**
             * Обновляем количество топиков в блоге
             */
            $this->Blog_RecalculateCountTopicByBlogId($sBlogIdNew);
        }
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array("topic_update", "topic_new_blog_{$sBlogId}", "topic_new_blog_{$sBlogIdNew}"));
        return $res;
    }

    /**
     * Пересчитывает счетчик избранных топиков
     *
     * @return bool
     */
    public function RecalculateFavourite()
    {
        return $this->oMapperTopic->RecalculateFavourite();
    }

    /**
     * Пересчитывает счетчики голосований
     *
     * @return bool
     */
    public function RecalculateVote()
    {
        return $this->oMapperTopic->RecalculateVote();
    }

    /**
     * Алиас для корректной работы ORM
     *
     * @param array $aFilter Фильтр, который содержит список id топиков в параметре "id in"
     * @return array
     */
    public function GetTopicItemsByFilter($aFilter)
    {
        if (isset($aFilter['id in'])) {
            return $this->GetTopicsByArrayId($aFilter['id in']);
        }
        return array();
    }

    /**
     * Парсинг текста с учетом конкретного топика
     *
     * @param string $sText
     * @param ModuleTopic_EntityTopic $oTopic
     *
     * @return string
     */
    public function Parser($sText, $oTopic)
    {
        $this->Text_AddParams(array('oTopic' => $oTopic));
        $sResult = $this->Text_Parser($sText);
        $this->Text_RemoveParams(array('oTopic'));
        return $sResult;
    }

    /**
     * Возвращает объект типа топика по его коду
     *
     * @param string $sCode
     *
     * @return ModuleTopic_EntityTopicType|null
     */
    public function GetTopicTypeByCode($sCode)
    {
        return $this->oMapperTopic->GetTopicTypeByCode($sCode);
    }

    /**
     * Возвращает объект типа топика по его ID
     *
     * @param int $iId
     *
     * @return ModuleTopic_EntityTopicType|null
     */
    public function GetTopicTypeById($iId)
    {
        return $this->oMapperTopic->GetTopicTypeById($iId);
    }

    /**
     * Добавляет новый тип топика в БД
     *
     * @param ModuleTopic_EntityTopicType $oType
     *
     * @return ModuleTopic_EntityTopicType|bool
     */
    public function AddTopicType($oType)
    {
        if ($sId = $this->oMapperTopic->AddTopicType($oType)) {
            $oType->setId($sId);
            //чистим зависимые кеши
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('topic_type_new'));
            /**
             * Регистрируем новый тип в дополнительных полях
             * todo: fix lang text
             */
            $this->Property_CreateTargetType('topic_' . $oType->getCode(),
                array('entity' => 'ModuleTopic_EntityTopic', 'name' => 'Топик - ' . $oType->getName()), true);
            return $oType;
        }
        return false;
    }

    /**
     * @param array $aFilter
     *
     * @return mixed
     */
    public function GetTopicTypeItems($aFilter = array())
    {
        return $this->oMapperTopic->GetTopicTypeItems($aFilter);
    }

    /**
     * Обновляет тип топика в БД
     *
     * @param ModuleTopic_EntityTopicType $oType
     *
     * @return bool
     */
    public function UpdateTopicType($oType)
    {
        return $this->oMapperTopic->UpdateTopicType($oType);
    }

    /**
     * Удаляет тип топика из БД
     *
     * @param $sTypeId
     *
     * @return bool
     */
    public function DeleteTopicType($sTypeId)
    {
        return $this->oMapperTopic->DeleteTopicType($sTypeId);
    }

    public function UpdateTopicByType($sType, $sTypeNew)
    {
        $res = $this->oMapperTopic->UpdateTopicByType($sType, $sTypeNew);
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array("topic_update"));
        return $res;
    }

    /**
     * Формирует и возвращает полный ЧПУ URL для топика
     *
     * @param ModuleTopic_EntityTopic $oTopic
     * @param bool $bAbsolute При false вернет относительный УРЛ
     * @return string
     */
    public function BuildUrlForTopic($oTopic, $bAbsolute = true)
    {
        $sUrlMask = Config::Get('module.topic.url');
        $iDateCreate = strtotime($oTopic->getDatePublish());
        $aReplace = array(
            '%year%'   => date("Y", $iDateCreate),
            '%month%'  => date("m", $iDateCreate),
            '%day%'    => date("d", $iDateCreate),
            '%hour%'   => date("H", $iDateCreate),
            '%minute%' => date("i", $iDateCreate),
            '%second%' => date("s", $iDateCreate),
            '%login%'  => '',
            '%blog%'   => '',
            '%id%'     => $oTopic->getId(),
            '%title%'  => $oTopic->getSlug(),
            '%type%'   => $oTopic->getType(),
        );

        /**
         * Получаем связанные данные только если в этом есть необходимость
         */
        if (strpos($sUrlMask, '%blog%') !== false) {
            if (!($oBlog = $oTopic->GetBlog())) {
                $oBlog = $this->Blog_GetBlogById($oTopic->getBlogId());
            }
            if ($oBlog) {
                if ($oBlog->getType() == 'personal') {
                    $sUrlMask = str_replace('%blog%', '%login%', $sUrlMask);
                } else {
                    $aReplace['%blog%'] = $oBlog->getUrl();
                }
            }
        }

        if (strpos($sUrlMask, '%login%') !== false) {
            if (!($oUser = $oTopic->GetUser())) {
                $oUser = $this->User_GetUserById($oTopic->getUserId());
            }
            if ($oUser) {
                $aReplace['%login%'] = $oUser->getLogin();
            }
        }
        $sUrl = strtr($sUrlMask, $aReplace);
        return $bAbsolute ? Router::GetPathRootWeb() . '/' . $sUrl : $sUrl;
    }

    /**
     * Формирует из строки url
     *
     * @param string $sText
     * @return string
     */
    public function MakeSlug($sText)
    {
        return $this->Text_Transliteration($sText);
    }

    /**
     * Возвращает URL с учетом уникалькости по всем топикам
     *
     * @param string $sSlug
     * @param int|null $iSkipTopicId
     * @return string
     */
    public function GetUniqueSlug($sSlug, $iSkipTopicId = null)
    {
        $iPostfix = 0;
        do {
            $sUrl = $sSlug . ($iPostfix ? '-' . $iPostfix : '');
            $iPostfix++;
        } while ($oTopic = $this->GetTopicBySlug($sUrl) and (is_null($iSkipTopicId) or $iSkipTopicId != $oTopic->getId()));

        return $sUrl;
    }

    /**
     * Отправляет юзеру уведомление об ответе на его комментарий
     *
     * @param ModuleUser_EntityUser $oUserTo Объект пользователя кому отправляем
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @param ModuleComment_EntityComment $oComment Объект комментария
     * @param ModuleUser_EntityUser $oUserComment Объект пользователя, написавшего комментарий
     * @return bool
     */
    public function SendNotifyCommentReplyToAuthorParentComment(
        ModuleUser_EntityUser $oUserTo,
        ModuleTopic_EntityTopic $oTopic,
        ModuleComment_EntityComment $oComment,
        ModuleUser_EntityUser $oUserComment
    ) {
        /**
         * Проверяем можно ли юзеру рассылать уведомление
         */
        if (!$oUserTo->getSettingsNoticeReplyComment()) {
            return false;
        }
        $this->Notify_Send(
            $oUserTo,
            'comment_reply.tpl',
            $this->Lang_Get('emails.comment_reply.subject'),
            array(
                'oUserTo'      => $oUserTo,
                'oTopic'       => $oTopic,
                'oComment'     => $oComment,
                'oUserComment' => $oUserComment,
            )
        );
        return true;
    }

    /**
     * Отправляет юзеру уведомление о новом топике в блоге, в котором он состоит
     *
     * @param ModuleUser_EntityUser $oUserTo Объект пользователя кому отправляем
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @param ModuleBlog_EntityBlog $oBlog Объект блога
     * @param ModuleUser_EntityUser $oUserTopic Объект пользователя, написавшего топик
     * @return bool
     */
    public function SendNotifyTopicNewToSubscribeBlog(
        ModuleUser_EntityUser $oUserTo,
        ModuleTopic_EntityTopic $oTopic,
        ModuleBlog_EntityBlog $oBlog,
        ModuleUser_EntityUser $oUserTopic
    ) {
        /**
         * Проверяем можно ли юзеру рассылать уведомление
         */
        if (!$oUserTo->getSettingsNoticeNewTopic()) {
            return false;
        }
        $this->Notify_Send(
            $oUserTo,
            'topic_new.tpl',
            $this->Lang_Get('emails.topic_new.subject') . ' «' . htmlspecialchars($oBlog->getTitle()) . '»',
            array(
                'oUserTo'    => $oUserTo,
                'oTopic'     => $oTopic,
                'oBlog'      => $oBlog,
                'oUserTopic' => $oUserTopic,
            )
        );
        return true;
    }
}