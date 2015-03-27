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
 * Объект сущности топика
 *
 * @package application.modules.topic
 * @since 1.0
 */
class ModuleTopic_EntityTopic extends Entity
{
    /**
     * Массив объектов(не всегда) для дополнительных типов топиков(линки, опросы, подкасты и т.п.)
     *
     * @var array
     */
    protected $aExtra = null;
    /**
     * Список поведений
     *
     * @var array
     */
    protected $aBehaviors = array(
        /**
         * Дополнительные поля
         */
        'property' => 'ModuleProperty_BehaviorEntity',
    );

    /**
     * Определяем правила валидации
     */
    public function Init()
    {
        parent::Init();
        $this->aValidateRules[] = array(
            'topic_title',
            'string',
            'max'        => Config::Get('module.topic.title_max_length'),
            'min'        => Config::Get('module.topic.title_min_length'),
            'allowEmpty' => Config::Get('module.topic.title_allow_empty'),
            'label'      => $this->Lang_Get('topic.add.fields.title.label')
        );
        $this->aValidateRules[] = array(
            'topic_slug_raw',
            'regexp',
            'allowEmpty' => true,
            'pattern'    => '#^[a-z0-9\-]{1,500}$#i'
        );
        $this->aValidateRules[] = array(
            'topic_text_source',
            'string',
            'max'        => Config::Get('module.topic.max_length'),
            'min'        => Config::Get('module.topic.min_length'),
            'allowEmpty' => Config::Get('module.topic.allow_empty'),
            'condition'  => 'isNeedValidateText',
            'label'      => $this->Lang_Get('topic.add.fields.text.label')
        );
        $this->aValidateRules[] = array(
            'topic_tags',
            'tags',
            'countMax'   => 15,
            'condition'  => 'isNeedValidateTags',
            'label'      => $this->Lang_Get('topic.add.fields.tags.label'),
            'allowEmpty' => Config::Get('module.topic.allow_empty_tags')
        );

        $this->aValidateRules[] = array('blogs_id_raw', 'blogs');
        $this->aValidateRules[] = array('topic_text_source', 'topic_unique');
        $this->aValidateRules[] = array('topic_slug_raw', 'slug_check');
    }

    /**
     * Проверяет нужно проводить валидацию текста топика или нет
     *
     * @return bool
     */
    public function isNeedValidateText()
    {
        $oTopicType = $this->getTypeObject();
        if (!$oTopicType or $oTopicType->getParam('allow_text')) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет нужно проводить валидацию тегов топика или нет
     *
     * @return bool
     */
    public function isNeedValidateTags()
    {
        $oTopicType = $this->getTypeObject();
        if (!$oTopicType or $oTopicType->getParam('allow_tags')) {
            return true;
        }
        return false;
    }

    /**
     * Проверка типа топика
     *
     * @param string $sValue Проверяемое значение
     * @param array $aParams Параметры
     * @return bool|string
     */
    public function ValidateTopicType($sValue, $aParams)
    {
        if ($this->Topic_IsAllowTopicType($sValue)) {
            return true;
        }
        return $this->Lang_Get('topic.add.notices.error_type');
    }

    /**
     * Проверка URL топика
     *
     * @param string $sValue Проверяемое значение
     * @param array $aParams Параметры
     * @return bool|string
     */
    public function ValidateSlugCheck($sValue, $aParams)
    {
        if (!$this->User_GetIsAdmin()) {
            /**
             * Простому пользователю разрешаем менять url только в течении X времени после создания топика
             * Причем не прямую смену url, а через транлитерацию заголовка топика
             */
            if ($this->getId()) {
                if (strtotime($this->getDatePublish()) < time() - 60 * 60 * 1) {
                    /**
                     * Не меняем url
                     */
                    return true;
                }
            }
            /**
             * Для нового топика всегда формируем url
             */
            $this->setSlugRaw('');
        }

        if ($this->getSlugRaw()) {
            $this->setSlug($this->Topic_GetUniqueSlug($this->getSlugRaw(), $this->getId()));
        } elseif ($this->getTitle()) {
            if ($sUrl = $this->Topic_MakeSlug($this->getTitle())) {
                /**
                 * Получаем уникальный URL
                 */
                $this->setSlug($this->Topic_GetUniqueSlug($sUrl, $this->getId()));
            } else {
                return $this->Lang_Get('topic.add.notices.error_slug');
            }
        }
        return true;
    }

    /**
     * Проверка топика на уникальность
     *
     * @param string $sValue Проверяемое значение
     * @param array $aParams Параметры
     * @return bool|string
     */
    public function ValidateTopicUnique($sValue, $aParams)
    {
        $this->setTextHash(md5($this->getType() . $sValue . $this->getTitle()));
        if ($this->isNeedValidateText()) {
            if ($oTopicEquivalent = $this->Topic_GetTopicUnique($this->getUserId(), $this->getTextHash())) {
                if ($iId = $this->getId() and $oTopicEquivalent->getId() == $iId) {
                    return true;
                }
                return $this->Lang_Get('topic.add.notices.error_text_unique');
            }
        }
        return true;
    }

    /**
     * Валидация ID блогов
     *
     * @param string $sValue Проверяемое значение
     * @param array $aParams Параметры
     * @return bool|string
     */
    public function ValidateBlogs($sValue, $aParams)
    {
        if ($sValue and is_string($sValue)) {
            $sValue = explode(',', $sValue);
        }
        if (!$sValue or !is_array($sValue)) {
            if ($oBlog = $this->Blog_GetPersonalBlogByUserId($this->getUserId())) {
                $this->setBlogs(array($oBlog));
                $this->setBlogId($oBlog->getId());
                $this->setBlogId2(null);
                $this->setBlogId3(null);
                $this->setBlogId4(null);
                $this->setBlogId5(null);
                return true; // персональный блог
            } else {
                return $this->Lang_Get('topic.add.notices.error_blog_not_found');
            }
        }
        /**
         * Проверяем список блогов
         */
        $aBlogs = array();
        foreach ($sValue as $iKey => $iBlogId) {
            if (is_numeric($iBlogId) and $oBlog = $this->Blog_GetBlogById($iBlogId)) {
                /**
                 * Проверяем права на постинг в блог
                 */
                if ($this->ACL_IsAllowBlog($oBlog, $this->getUserCreator())) {
                    $aBlogs[] = $oBlog;
                } else {
                    return $this->Lang_Get('topic.add.notices.error_blog_not_allowed');
                }
            }
        }
        if (count($aBlogs) == 0) {
            return $this->Lang_Get('topic.add.notices.error_blog_not_found');
        }
        if (count($sValue) > Config::Get('module.topic.max_blog_count')) {
            return $this->Lang_Get('topic.add.notices.error_blog_max_count',
                array('count' => Config::Get('module.topic.max_blog_count')));
        }
        /**
         * Заполняем поля с ID
         */
        $this->setBlogId($aBlogs[0]->getId());
        $this->setBlogId2(isset($aBlogs[1]) ? $aBlogs[1]->getId() : null);
        $this->setBlogId3(isset($aBlogs[2]) ? $aBlogs[2]->getId() : null);
        $this->setBlogId4(isset($aBlogs[3]) ? $aBlogs[3]->getId() : null);
        $this->setBlogId5(isset($aBlogs[4]) ? $aBlogs[4]->getId() : null);
        $this->setBlogs($aBlogs);
        return true;
    }

    /**
     * Возвращает ID топика
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_getDataOne('topic_id');
    }

    /**
     * Возвращает ID блога
     *
     * @return int|null
     */
    public function getBlogId()
    {
        return $this->_getDataOne('blog_id');
    }

    /**
     * Возвращает ID блога 2
     *
     * @return int|null
     */
    public function getBlogId2()
    {
        return $this->_getDataOne('blog_id2');
    }

    /**
     * Возвращает ID блога 3
     *
     * @return int|null
     */
    public function getBlogId3()
    {
        return $this->_getDataOne('blog_id3');
    }

    /**
     * Возвращает ID блога 4
     *
     * @return int|null
     */
    public function getBlogId4()
    {
        return $this->_getDataOne('blog_id4');
    }

    /**
     * Возвращает ID блога 5
     *
     * @return int|null
     */
    public function getBlogId5()
    {
        return $this->_getDataOne('blog_id5');
    }

    /**
     * Возвращает ID пользователя
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->_getDataOne('user_id');
    }

    /**
     * Возвращает тип топика
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->_getDataOne('topic_type');
    }

    /**
     * Возвращает заголовок топика
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->_getDataOne('topic_title');
    }

    /**
     * Возвращает url топика
     *
     * @return string|null
     */
    public function getSlug()
    {
        return $this->_getDataOne('topic_slug');
    }

    /**
     * Возвращает текст топика
     *
     * @return string|null
     */
    public function getText()
    {
        return $this->_getDataOne('topic_text');
    }

    /**
     * Возвращает короткий текст топика (до ката)
     *
     * @return string|null
     */
    public function getTextShort()
    {
        return $this->_getDataOne('topic_text_short');
    }

    /**
     * Возвращает исходный текст топика, без примененя парсера тегов
     *
     * @return string|null
     */
    public function getTextSource()
    {
        return $this->_getDataOne('topic_text_source');
    }

    /**
     * Возвращает сериализованные строку дополнительный данных топика
     *
     * @return string
     */
    public function getExtra()
    {
        return $this->_getDataOne('topic_extra') ? $this->_getDataOne('topic_extra') : serialize('');
    }

    /**
     * Возвращает строку со списком тегов через запятую
     *
     * @return string|null
     */
    public function getTags()
    {
        return $this->_getDataOne('topic_tags');
    }

    /**
     * Возвращает дату создания топика
     *
     * @return string|null
     */
    public function getDateAdd()
    {
        return $this->_getDataOne('topic_date_add');
    }

    /**
     * Возвращает дату редактирования топика
     *
     * @return string|null
     */
    public function getDateEdit()
    {
        return $this->_getDataOne('topic_date_edit');
    }

    /**
     * Возвращает дату редактирования контента топика
     *
     * @return string|null
     */
    public function getDateEditContent()
    {
        return $this->_getDataOne('topic_date_edit_content');
    }

    /**
     * Возвращает дату публикации топика
     *
     * @return string|null
     */
    public function getDatePublish()
    {
        return $this->_getDataOne('topic_date_publish');
    }

    /**
     * Возвращает IP пользователя
     *
     * @return string|null
     */
    public function getUserIp()
    {
        return $this->_getDataOne('topic_user_ip');
    }

    /**
     * Возвращает статус опубликованности топика
     *
     * @return int|null
     */
    public function getPublish()
    {
        return $this->_getDataOne('topic_publish');
    }

    /**
     * Возвращает статус опубликованности черновика
     *
     * @return int|null
     */
    public function getPublishDraft()
    {
        return $this->_getDataOne('topic_publish_draft');
    }

    /**
     * Возвращает статус публикации топика на главной странице
     *
     * @return int|null
     */
    public function getPublishIndex()
    {
        return $this->_getDataOne('topic_publish_index');
    }

    /**
     * Возвращает статус пропуска топика на главной странице
     *
     * @return int|null
     */
    public function getSkipIndex()
    {
        return $this->_getDataOne('topic_skip_index');
    }

    /**
     * Возвращает рейтинг топика
     *
     * @return string
     */
    public function getRating()
    {
        return number_format(round($this->_getDataOne('topic_rating'), 2), 0, '.', '');
    }

    /**
     * Возвращает число проголосовавших за топик
     *
     * @return int|null
     */
    public function getCountVote()
    {
        return $this->_getDataOne('topic_count_vote');
    }

    /**
     * Возвращает число проголосовавших за топик положительно
     *
     * @return int|null
     */
    public function getCountVoteUp()
    {
        return $this->_getDataOne('topic_count_vote_up');
    }

    /**
     * Возвращает число проголосовавших за топик отрицательно
     *
     * @return int|null
     */
    public function getCountVoteDown()
    {
        return $this->_getDataOne('topic_count_vote_down');
    }

    /**
     * Возвращает число воздержавшихся при голосовании за топик
     *
     * @return int|null
     */
    public function getCountVoteAbstain()
    {
        return $this->_getDataOne('topic_count_vote_abstain');
    }

    /**
     * Возвращает число прочтений топика
     *
     * @return int|null
     */
    public function getCountRead()
    {
        return $this->_getDataOne('topic_count_read');
    }

    /**
     * Возвращает количество комментариев к топику
     *
     * @return int|null
     */
    public function getCountComment()
    {
        return $this->_getDataOne('topic_count_comment');
    }

    /**
     * Возвращает текст ката
     *
     * @return string|null
     */
    public function getCutText()
    {
        return $this->_getDataOne('topic_cut_text');
    }

    /**
     * Возвращает статус запрета комментировать топик
     *
     * @return int|null
     */
    public function getForbidComment()
    {
        return $this->_getDataOne('topic_forbid_comment');
    }

    /**
     * Возвращает хеш топика для проверки топика на уникальность
     *
     * @return string|null
     */
    public function getTextHash()
    {
        return $this->_getDataOne('topic_text_hash');
    }

    /**
     * Возвращает массив тегов
     *
     * @return array
     */
    public function getTagsArray()
    {
        if ($this->getTags()) {
            return explode(',', $this->getTags());
        }
        return array();
    }

    /**
     * Возвращает количество новых комментариев в топике для текущего пользователя
     *
     * @return int|null
     */
    public function getCountCommentNew()
    {
        return $this->_getDataOne('count_comment_new');
    }

    /**
     * Возвращает дату прочтения топика для текущего пользователя
     *
     * @return string|null
     */
    public function getDateRead()
    {
        return $this->_getDataOne('date_read');
    }

    /**
     * Возвращает объект пользователя, автора топик
     *
     * @return ModuleUser_EntityUser|null
     */
    public function getUser()
    {
        if (!$this->_getDataOne('user')) {
            $this->_aData['user'] = $this->User_GetUserById($this->getUserId());
        }
        return $this->_getDataOne('user');
    }

    /**
     * Возвращает объект блого, в котором находится топик
     *
     * @return ModuleBlog_EntityBlog|null
     */
    public function getBlog()
    {
        if ($aBlogs = $this->getBlogs() and is_array($aBlogs)) {
            return reset($aBlogs);
        }
        return null;
    }

    /**
     * Возвращает список блогов
     *
     * @return mixed|null
     */
    public function getBlogs()
    {
        return $this->_getDataOne('blogs');
    }

    /**
     * Возвращает список ID блогов
     *
     * @return array
     */
    public function getBlogsId()
    {
        $aResult = array();
        if ($aBlogs = $this->getBlogs()) {
            foreach ($aBlogs as $oBlog) {
                $aResult[] = (int)$oBlog->getId();
            }
        }
        return $aResult;
    }

    /**
     * Возвращает полный URL до топика
     *
     * @param bool $bAbsolute При false вернет относительный УРЛ
     * @return string
     */
    public function getUrl($bAbsolute = true)
    {
        return $this->Topic_BuildUrlForTopic($this, $bAbsolute);
    }

    /**
     * Возвращает полный URL до страницы редактировани топика
     *
     * @return string
     */
    public function getUrlEdit()
    {
        return Router::GetPath('content') . 'edit/' . $this->getId() . '/';
    }

    /**
     * Возвращает полный URL для удаления топика
     *
     * @return string
     */
    public function getUrlDelete()
    {
        return Router::GetPath('content') . 'delete/' . $this->getId() . '/';
    }

    /**
     * Возвращает объект голосования за топик текущим пользователем
     *
     * @return ModuleVote_EntityVote|null
     */
    public function getVote()
    {
        return $this->_getDataOne('vote');
    }

    /**
     * Проверяет находится ли данный топик в избранном у текущего пользователя
     *
     * @return bool
     */
    public function getIsFavourite()
    {
        if ($this->getFavourite()) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет разрешение на удаление топика у текущего пользователя
     *
     * @return bool
     */
    public function getIsAllowDelete()
    {
        if ($oUser = $this->User_GetUserCurrent()) {
            return $this->ACL_IsAllowDeleteTopic($this, $oUser);
        }
        return false;
    }

    /**
     * Проверяет разрешение на редактирование топика у текущего пользователя
     *
     * @return bool
     */
    public function getIsAllowEdit()
    {
        if ($oUser = $this->User_GetUserCurrent()) {
            return $this->ACL_IsAllowEditTopic($this, $oUser);
        }
        return false;
    }

    /**
     * Проверяет разрешение на какое-либо действие для топика у текущего пользователя
     *
     * @return bool
     */
    public function getIsAllowAction()
    {
        if ($this->User_GetUserCurrent()) {
            return $this->getIsAllowEdit() || $this->getIsAllowDelete();
        }
        return false;
    }

    /**
     * Возвращает количество добавивших топик в избранное
     *
     * @return int|null
     */
    public function getCountFavourite()
    {
        return $this->_getDataOne('topic_count_favourite');
    }

    /**
     * Возвращает объект подписки на новые комментарии к топику
     *
     * @return ModuleSubscribe_EntitySubscribe|null
     */
    public function getSubscribeNewComment()
    {
        if (!($oUserCurrent = $this->User_GetUserCurrent())) {
            return null;
        }
        return $this->Subscribe_GetSubscribeByTargetAndMail('topic_new_comment', $this->getId(),
            $oUserCurrent->getMail());
    }

    /**
     * Возвращает тип объекта для дополнительных полей
     * Метод необходим для интеграции с дополнительными полями (модуль Property)
     * Данный метод автоматически добавляется поведением 'property' ( $this->property->getPropertyTargetType() ),
     * который возвращает тип из параметра. Но т.к. у нас тип является вычисляемым (зависит от $this->getType() ), то необходимо явно объявить данный метод
     *
     * @return string
     */
    public function getPropertyTargetType()
    {
        return 'topic_' . $this->getType();
    }

    /**
     * Возвращает объект типа топика
     *
     * @return ModuleTopic_EntityTopicType|null
     */
    public function getTypeObject()
    {
        if (!$this->_getDataOne('type_object')) {
            /**
             * Сначала смотрим среди загруженых активных типов, если нет, то делаем запрос к БД
             */
            if (!($this->_aData['type_object'] = $this->Topic_GetTopicType($this->getType()))) {
                $this->_aData['type_object'] = $this->Topic_GetTopicTypeByCode($this->getType());
            }
        }
        return $this->_getDataOne('type_object');
    }

    /**
     * Возвращает список опросов, которые есть у топика
     *
     * @return array|null
     */
    public function getPolls()
    {
        if (!$this->_getDataOne('polls')) {
            $this->_aData['polls'] = $this->Poll_GetPollItemsByTarget('topic', $this->getId());
        }
        return $this->_getDataOne('polls');
    }

    /**
     * Возвращает список ID всех блогов
     *
     * @return array
     */
    public function getBlogIds()
    {
        $aResult = array();
        if ($this->getBlogId()) {
            $aResult[] = $this->getBlogId();
        }
        if ($this->getBlogId2()) {
            $aResult[] = $this->getBlogId2();
        }
        if ($this->getBlogId3()) {
            $aResult[] = $this->getBlogId3();
        }
        if ($this->getBlogId4()) {
            $aResult[] = $this->getBlogId4();
        }
        if ($this->getBlogId5()) {
            $aResult[] = $this->getBlogId5();
        }
        return $aResult;
    }

    /***************************************************************************************************************************************************
     * методы расширения типов топика
     ***************************************************************************************************************************************************
     */

    /**
     * Извлекает сериализованные данные топика
     */
    protected function extractExtra()
    {
        if (is_null($this->aExtra)) {
            $this->aExtra = @unserialize($this->getExtra());
        }
    }

    /**
     * Устанавливает значение нужного параметра
     *
     * @param string $sName Название параметра/данных
     * @param mixed $data Данные
     */
    protected function setExtraValue($sName, $data)
    {
        $this->extractExtra();
        $this->aExtra[$sName] = $data;
        $this->setExtra($this->aExtra);
    }

    /**
     * Извлекает значение параметра
     *
     * @param string $sName Название параметра
     * @return null|mixed
     */
    protected function getExtraValue($sName)
    {
        $this->extractExtra();
        if (isset($this->aExtra[$sName])) {
            return $this->aExtra[$sName];
        }
        return null;
    }

    /**
     * Сохраняет путь до превью
     *
     * @param $data
     */
    public function setPreviewImage($data)
    {
        $this->setExtraValue('preview_image', $data);
    }

    /**
     * Возвращает веб путь до превью нужного размера
     *
     * @param $sSize
     *
     * @return null
     */
    public function getPreviewImageWebPath($sSize)
    {
        if ($sPath = $this->getExtraValue('preview_image')) {
            return $this->Media_GetImageWebPath($sPath, $sSize);
        } else {
            return null;
        }
    }



    //*************************************************************************************************************************************************

    /**
     * Устанваливает ID топика
     *
     * @param int $data
     */
    public function setId($data)
    {
        $this->_aData['topic_id'] = $data;
    }

    /**
     * Устанавливает ID блога
     *
     * @param int $data
     */
    public function setBlogId($data)
    {
        $this->_aData['blog_id'] = $data;
    }

    /**
     * Устанавливает ID блога 2
     *
     * @param int $data
     */
    public function setBlogId2($data)
    {
        $this->_aData['blog_id2'] = $data;
    }

    /**
     * Устанавливает ID блога 3
     *
     * @param int $data
     */
    public function setBlogId3($data)
    {
        $this->_aData['blog_id3'] = $data;
    }

    /**
     * Устанавливает ID блога 4
     *
     * @param int $data
     */
    public function setBlogId4($data)
    {
        $this->_aData['blog_id4'] = $data;
    }

    /**
     * Устанавливает ID блога 5
     *
     * @param int $data
     */
    public function setBlogId5($data)
    {
        $this->_aData['blog_id5'] = $data;
    }

    /**
     * Устанавливает ID пользователя
     *
     * @param int $data
     */
    public function setUserId($data)
    {
        $this->_aData['user_id'] = $data;
    }

    /**
     * Устанавливает тип топика
     *
     * @param string $data
     */
    public function setType($data)
    {
        $this->_aData['topic_type'] = $data;
    }

    /**
     * Устанавливает заголовок топика
     *
     * @param string $data
     */
    public function setTitle($data)
    {
        $this->_aData['topic_title'] = $data;
    }

    /**
     * Устанавливает url топика
     *
     * @param string $data
     */
    public function setSlug($data)
    {
        $this->_aData['topic_slug'] = $data;
    }

    /**
     * Устанавливает текст топика
     *
     * @param string $data
     */
    public function setText($data)
    {
        $this->_aData['topic_text'] = $data;
    }

    /**
     * Устанавливает сериализованную строчку дополнительных данных
     *
     * @param string $data
     */
    public function setExtra($data)
    {
        $this->_aData['topic_extra'] = serialize($data);
    }

    /**
     * Устанавливает короткий текст топика до ката
     *
     * @param string $data
     */
    public function setTextShort($data)
    {
        $this->_aData['topic_text_short'] = $data;
    }

    /**
     * Устаналивает исходный текст топика
     *
     * @param string $data
     */
    public function setTextSource($data)
    {
        $this->_aData['topic_text_source'] = $data;
    }

    /**
     * Устанавливает список тегов в виде строки
     *
     * @param string $data
     */
    public function setTags($data)
    {
        $this->_aData['topic_tags'] = $data;
    }

    /**
     * Устанавливает дату создания топика
     *
     * @param string $data
     */
    public function setDateAdd($data)
    {
        $this->_aData['topic_date_add'] = $data;
    }

    /**
     * Устанавливает дату редактирования топика
     *
     * @param string $data
     */
    public function setDateEdit($data)
    {
        $this->_aData['topic_date_edit'] = $data;
    }

    /**
     * Устанавливает дату редактирования контента топика
     *
     * @param string $data
     */
    public function setDateEditContent($data)
    {
        $this->_aData['topic_date_edit_content'] = $data;
    }

    /**
     * Устанавливает дату публикации топика
     *
     * @param string $data
     */
    public function setDatePublish($data)
    {
        $this->_aData['topic_date_publish'] = $data;
    }

    /**
     * Устанавливает IP пользователя
     *
     * @param string $data
     */
    public function setUserIp($data)
    {
        $this->_aData['topic_user_ip'] = $data;
    }

    /**
     * Устанавливает флаг публикации топика
     *
     * @param string $data
     */
    public function setPublish($data)
    {
        $this->_aData['topic_publish'] = $data;
    }

    /**
     * Устанавливает флаг публикации черновика
     *
     * @param string $data
     */
    public function setPublishDraft($data)
    {
        $this->_aData['topic_publish_draft'] = $data;
    }

    /**
     * Устанавливает флаг публикации на главной странице
     *
     * @param string $data
     */
    public function setPublishIndex($data)
    {
        $this->_aData['topic_publish_index'] = $data;
    }

    /**
     * Устанавливает флаг пропуска на главной странице
     *
     * @param string $data
     */
    public function setSkipIndex($data)
    {
        $this->_aData['topic_skip_index'] = $data;
    }

    /**
     * Устанавливает рейтинг топика
     *
     * @param string $data
     */
    public function setRating($data)
    {
        $this->_aData['topic_rating'] = $data;
    }

    /**
     * Устанавливает количество проголосовавших
     *
     * @param int $data
     */
    public function setCountVote($data)
    {
        $this->_aData['topic_count_vote'] = $data;
    }

    /**
     * Устанавливает количество проголосовавших в плюс
     *
     * @param int $data
     */
    public function setCountVoteUp($data)
    {
        $this->_aData['topic_count_vote_up'] = $data;
    }

    /**
     * Устанавливает количество проголосовавших в минус
     *
     * @param int $data
     */
    public function setCountVoteDown($data)
    {
        $this->_aData['topic_count_vote_down'] = $data;
    }

    /**
     * Устанавливает число воздержавшихся
     *
     * @param int $data
     */
    public function setCountVoteAbstain($data)
    {
        $this->_aData['topic_count_vote_abstain'] = $data;
    }

    /**
     * Устанавливает число прочтения топика
     *
     * @param int $data
     */
    public function setCountRead($data)
    {
        $this->_aData['topic_count_read'] = $data;
    }

    /**
     * Устанавливает количество комментариев
     *
     * @param int $data
     */
    public function setCountComment($data)
    {
        $this->_aData['topic_count_comment'] = $data;
    }

    /**
     * Устанавливает текст ката
     *
     * @param string $data
     */
    public function setCutText($data)
    {
        $this->_aData['topic_cut_text'] = $data;
    }

    /**
     * Устанавливает флаг запрета коментирования топика
     *
     * @param int $data
     */
    public function setForbidComment($data)
    {
        $this->_aData['topic_forbid_comment'] = $data;
    }

    /**
     * Устанавливает хеш топика
     *
     * @param string $data
     */
    public function setTextHash($data)
    {
        $this->_aData['topic_text_hash'] = $data;
    }

    /**
     * Устанавливает объект пользователя
     *
     * @param ModuleUser_EntityUser $data
     */
    public function setUser($data)
    {
        $this->_aData['user'] = $data;
    }

    /**
     * Устанавливает объект блога
     *
     * @param ModuleBlog_EntityBlog $data
     */
    public function setBlog($data)
    {
        $this->_aData['blog'] = $data;
    }

    /**
     * Устанавливает факт голосования пользователя в топике-опросе
     *
     * @param int $data
     */
    public function setUserQuestionIsVote($data)
    {
        $this->_aData['user_question_is_vote'] = $data;
    }

    /**
     * Устанавливает объект голосования за топик
     *
     * @param ModuleVote_EntityVote $data
     */
    public function setVote($data)
    {
        $this->_aData['vote'] = $data;
    }

    /**
     * Устанавливает количество новых комментариев
     *
     * @param int $data
     */
    public function setCountCommentNew($data)
    {
        $this->_aData['count_comment_new'] = $data;
    }

    /**
     * Устанавливает дату прочтения топика текущим пользователем
     *
     * @param string $data
     */
    public function setDateRead($data)
    {
        $this->_aData['date_read'] = $data;
    }

    /**
     * Устанавливает количество пользователей, добавивших топик в избранное
     *
     * @param int $data
     */
    public function setCountFavourite($data)
    {
        $this->_aData['topic_count_favourite'] = $data;
    }
}