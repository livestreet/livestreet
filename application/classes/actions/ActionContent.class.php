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
 * Экшен обработки УРЛа вида /content/ - управление своими топиками
 *
 * @package application.actions
 * @since 2.0
 */
class ActionContent extends Action
{
    /**
     * Главное меню
     *
     * @var string
     */
    protected $sMenuHeadItemSelect = 'blog';
    /**
     * Меню
     *
     * @var string
     */
    protected $sMenuItemSelect = 'topic';
    /**
     * СубМеню
     *
     * @var string
     */
    protected $sMenuSubItemSelect = 'topic';
    /**
     * Текущий юзер
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        /**
         * Проверяем авторизован ли юзер
         */
        if (!$this->User_IsAuthorization()) {
            return parent::EventNotFound();
        }
        $this->oUserCurrent = $this->User_GetUserCurrent();
        /**
         * Усанавливаем дефолтный евент
         */
        $this->SetDefaultEvent('add');
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($this->Lang_Get('topic.topics'));
    }

    /**
     * Регистрируем евенты
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^add$/i', '/^[a-z_0-9]{1,50}$/i', '/^$/i', 'EventAdd');
        $this->AddEventPreg('/^edit$/i', '/^\d{1,10}$/i', '/^$/i', 'EventEdit');
        $this->AddEventPreg('/^delete$/i', '/^\d{1,10}$/i', '/^$/i', 'EventDelete');

        $this->AddEventPreg('/^published$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventShowTopics');
        $this->AddEventPreg('/^drafts$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventShowTopics');

        $this->AddEventPreg('/^ajax$/i', '/^add$/i', '/^$/i', 'EventAjaxAdd');
        $this->AddEventPreg('/^ajax$/i', '/^edit$/i', '/^$/i', 'EventAjaxEdit');
        $this->AddEventPreg('/^ajax$/i', '/^preview$/i', '/^$/i', 'EventAjaxPreview');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Выводит список топиков
     *
     */
    protected function EventShowTopics()
    {
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = $this->sCurrentEvent;
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        /**
         * Получаем список топиков
         */
        $aResult = $this->Topic_GetTopicsPersonalByUser($this->oUserCurrent->getId(),
            $this->sCurrentEvent == 'published' ? 1 : 0, $iPage, Config::Get('module.topic.per_page'));
        $aTopics = $aResult['collection'];
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('content') . $this->sCurrentEvent);
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('paging', $aPaging);
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_AddHtmlTitle($this->Lang_Get('topic.nav.' . $this->sCurrentEvent));
    }

    protected function EventDelete()
    {
        $this->Security_ValidateSendForm();
        /**
         * Получаем номер топика из УРЛ и проверяем существует ли он
         */
        $sTopicId = $this->GetParam(0);
        if (!($oTopic = $this->Topic_GetTopicById($sTopicId))) {
            return parent::EventNotFound();
        }
        /**
         * проверяем есть ли право на удаление топика
         */
        if (!$this->ACL_IsAllowDeleteTopic($oTopic, $this->oUserCurrent)) {
            $this->Message_AddErrorSingle($this->Rbac_GetMsgLast());
            return Router::Action('error');
        }
        /**
         * Удаляем топик
         */
        $this->Hook_Run('topic_delete_before', array('oTopic' => $oTopic));
        $this->Topic_DeleteTopic($oTopic);
        $this->Hook_Run('topic_delete_after', array('oTopic' => $oTopic));
        /**
         * Перенаправляем на страницу со списком топиков из блога этого топика
         */
        Router::Location($oTopic->getBlog()->getUrlFull());
    }

    protected function EventEdit()
    {
        /**
         * Получаем номер топика из УРЛ и проверяем существует ли он
         */
        $sTopicId = $this->GetParam(0);
        if (!($oTopic = $this->Topic_GetTopicById($sTopicId))) {
            return parent::EventNotFound();
        }
        /**
         * Проверяем тип топика
         */
        if (!$oTopicType = $this->Topic_GetTopicType($oTopic->getType())) {
            return parent::EventNotFound();
        }
        /**
         * Если права на редактирование
         */
        if (!$this->ACL_IsAllowEditTopic($oTopic, $this->oUserCurrent)) {
            return parent::EventNotFound();
        }

        /**
         * Получаем доступные блоги по типам
         */
        $aBlogs = array();
        $aBlogs['open'] = $this->Blog_GetBlogsByType('open');
        if ($this->oUserCurrent->isAdministrator()) {
            $aBlogs['close'] = $this->Blog_GetBlogsByType('close');
        } else {
            $aBlogs['close'] = $this->Blog_GetBlogsByTypeAndUserId('close', $this->oUserCurrent->getId());
        }
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topic_edit_show', array('oTopic' => $oTopic, 'aBlogs' => &$aBlogs));

        /**
         * Дополнительно загружам превью
         */
        $aFilter = array(
            'target_type' => 'topic',
            'is_preview'  => 1,
            'target_id'   => $sTopicId
        );
        $aTargetItems = $this->Media_GetTargetItemsByFilter($aFilter);
        $this->Viewer_Assign('imagePreviewItems', $aTargetItems);

        /**
         * Проверяем на отсутствие блогов
         */
        $bSkipBlogs = true;
        foreach ($aBlogs as $aBlogsType) {
            if ($aBlogsType) {
                $bSkipBlogs = false;
            }
        }

        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('blogsAllow', $aBlogs);
        $this->Viewer_Assign('skipBlogs', $bSkipBlogs);
        $this->Viewer_Assign('topicType', $oTopicType);
        $this->Viewer_AddHtmlTitle($this->Lang_Get('topic.add.title.edit'));

        $this->Viewer_Assign('topicEdit', $oTopic);
        $this->SetTemplateAction('add');
    }

    /**
     * Добавление топика
     *
     */
    protected function EventAdd()
    {
        $sTopicType = $this->GetParam(0);
        $iBlogId = (int)getRequest('blog_id');

        if (!$oTopicType = $this->Topic_GetTopicType($sTopicType)) {
            return parent::EventNotFound();
        }
        /**
         * Проверяем права на создание топика
         */
        if (!$this->ACL_CanAddTopic($this->oUserCurrent, $oTopicType)) {
            $this->Message_AddErrorSingle($this->Rbac_GetMsgLast());
            return Router::Action('error');
        }
        $this->sMenuSubItemSelect = $sTopicType;
        /**
         * Получаем доступные блоги по типам
         */
        $aBlogs = array();
        $aBlogs['open'] = $this->Blog_GetBlogsByType('open');
        if ($this->oUserCurrent->isAdministrator()) {
            $aBlogs['close'] = $this->Blog_GetBlogsByType('close');
        } else {
            $aBlogs['close'] = $this->Blog_GetBlogsByTypeAndUserId('close', $this->oUserCurrent->getId());
        }
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topic_add_show', array('aBlogs' => &$aBlogs));
        /**
         * Проверяем на отсутствие блогов
         */
        $bSkipBlogs = true;
        foreach ($aBlogs as $aBlogsType) {
            if ($aBlogsType) {
                $bSkipBlogs = false;
            }
        }
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('topicType', $oTopicType);
        $this->Viewer_Assign('blogsAllow', $aBlogs);
        $this->Viewer_Assign('skipBlogs', $bSkipBlogs);
        $this->Viewer_Assign('blogId', $iBlogId);
        $this->Viewer_AddHtmlTitle($this->Lang_Get('topic.add.title.add'));
        $this->SetTemplateAction('add');
    }

    protected function EventAjaxEdit()
    {
        $this->Viewer_SetResponseAjax();

        $aTopicRequest = getRequest('topic');
        if (!(isset($aTopicRequest['id']) and $oTopic = $this->Topic_GetTopicById($aTopicRequest['id']))) {
            return $this->EventErrorDebug();
        }
        if (!$this->Topic_IsAllowTopicType($oTopic->getType())) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем разрешено ли постить топик по времени
         */
        if (!isPost('is_draft') and !$oTopic->getPublishDraft() and !$this->ACL_CanPostTopicTime($this->oUserCurrent)) {
            $this->Message_AddErrorSingle($this->Lang_Get('topic.add.notices.time_limit'), $this->Lang_Get('common.error.error'));
            return;
        }

        /**
         * Если права на редактирование
         */
        if (!$this->ACL_IsAllowEditTopic($oTopic, $this->oUserCurrent)) {
            return $this->EventErrorDebug();
        }
        /**
         * Сохраняем старое значение идентификатора основного блога и всех блогов
         */
        $sBlogIdOld = $oTopic->getBlogId();
        $aBlogsIdOld = $oTopic->getBlogsId();

        $oTopic->_setDataSafe(getRequest('topic'));
        $oTopic->setProperties(getRequest('property'));
        $oTopic->setUserCreator($this->oUserCurrent);
        $oTopic->setUserIp(func_getIp());
        if (!$oTopic->getTags() or !$oTopic->getTypeObject()->getParam('allow_tags')) {
            $oTopic->setTags('');
        }
        /**
         * Публикуем или сохраняем в черновиках
         */
        $bSendNotify = false;
        if (!isset($_REQUEST['is_draft'])) {
            $oTopic->setPublish(1);
            if ($oTopic->getPublishDraft() == 0) {
                $oTopic->setPublishDraft(1);
                $oTopic->setDatePublish(date("Y-m-d H:i:s"));
                $bSendNotify = true;
            }
        } else {
            $oTopic->setPublish(0);
        }
        /**
         * Принудительный вывод на главную
         */
        if ($this->ACL_IsAllowTopicPublishIndex($this->oUserCurrent)) {
            if (isset($_REQUEST['topic']['topic_publish_index'])) {
                $oTopic->setPublishIndex(1);
            } else {
                $oTopic->setPublishIndex(0);
            }
        }
        /**
         * Принудительный запрет вывода на главную
         */
        if ($this->ACL_IsAllowTopicSkipIndex($this->oUserCurrent)) {
            if (isset($_REQUEST['topic']['topic_skip_index'])) {
                $oTopic->setSkipIndex(1);
            } else {
                $oTopic->setSkipIndex(0);
            }
        }
        /**
         * Запрет на комментарии к топику
         */
        $oTopic->setForbidComment(0);
        if (isset($_REQUEST['topic']['topic_forbid_comment'])) {
            $oTopic->setForbidComment(1);
        }
        /**
         * Дата редактирования контента
         */
        $oTopic->setDateEditContent(date('Y-m-d H:i:s'));

        $this->Hook_Run('topic_edit_validate_before', array('oTopic' => $oTopic));
        if ($oTopic->_Validate()) {
            $oBlog = $oTopic->getBlog();
            /**
             * Получаемый и устанавливаем разрезанный текст по тегу <cut>
             */
            if ($oTopic->getTypeObject()->getParam('allow_text')) {
                list($sTextShort, $sTextNew, $sTextCut) = $this->Text_Cut($oTopic->getTextSource());
                $oTopic->setCutText($sTextCut);
                // TODO: передача параметров в Topic_Parser пока не используется - нужно заменить на этот вызов все места с парсингом топика
                $oTopic->setText($this->Topic_Parser($sTextNew, $oTopic));
                if ($sTextShort != $sTextNew) {
                    $oTopic->setTextShort($this->Topic_Parser($sTextShort, $oTopic));
                } else {
                    $oTopic->setTextShort('');
                }
            } else {
                $oTopic->setCutText('');
                $oTopic->setText('');
                $oTopic->setTextShort('');
                $oTopic->setTextSource('');
            }
            $this->Hook_Run('topic_edit_before', array('oTopic' => $oTopic, 'oBlog' => $oBlog));
            /**
             * Сохраняем топик
             */
            if ($this->Topic_UpdateTopic($oTopic)) {
                $this->Hook_Run('topic_edit_after',
                    array('oTopic' => $oTopic, 'oBlog' => $oBlog, 'bSendNotify' => &$bSendNotify));
                /**
                 * Обновляем данные в комментариях, если топик был перенесен в новый блог
                 */
                if ($sBlogIdOld != $oTopic->getBlogId()) {
                    $this->Comment_UpdateTargetParentByTargetId($oTopic->getBlogId(), 'topic', $oTopic->getId());
                    $this->Comment_UpdateTargetParentByTargetIdOnline($oTopic->getBlogId(), 'topic', $oTopic->getId());
                }
                /**
                 * Обновляем количество топиков в блоге
                 */
                if ($aBlogsIdOld != $oTopic->getBlogsId()) {
                    $this->Blog_RecalculateCountTopicByBlogId($aBlogsIdOld);
                }
                $this->Blog_RecalculateCountTopicByBlogId($oTopic->getBlogsId());
                /**
                 * Добавляем событие в ленту
                 */
                $this->Stream_write($oTopic->getUserId(), 'add_topic', $oTopic->getId(),
                    $oTopic->getPublish() && $oBlog->getType() != 'close');
                /**
                 * Рассылаем о новом топике подписчикам блога
                 */
                if ($bSendNotify) {
                    $this->Topic_SendNotifyTopicNew($oTopic, $oTopic->getUser());
                }
                if (!$oTopic->getPublish() and !$this->oUserCurrent->isAdministrator() and $this->oUserCurrent->getId() != $oTopic->getUserId()) {
                    $sUrlRedirect = $oBlog->getUrlFull();
                } else {
                    $sUrlRedirect = $oTopic->getUrl();
                }

                $this->Viewer_AssignAjax('sUrlRedirect', $sUrlRedirect);
                $this->Message_AddNotice('Обновление прошло успешно', $this->Lang_Get('common.attention'));
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
            }
        } else {
            $this->Message_AddError($oTopic->_getValidateError(), $this->Lang_Get('common.error.error'));
        }
    }

    protected function EventAjaxAdd()
    {
        $this->Viewer_SetResponseAjax();
        /**
         * Проверяем тип топика
         */
        $sTopicType = getRequestStr('topic_type');
        if (!$oTopicType = $this->Topic_GetTopicType($sTopicType)) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем права на создание топика
         */
        if (!$this->ACL_CanAddTopic($this->oUserCurrent, $oTopicType)) {
            $this->Message_AddErrorSingle($this->Rbac_GetMsgLast());
            return false;
        }
        /**
         * Создаем топик
         */
        $oTopic = Engine::GetEntity('Topic');
        $oTopic->_setDataSafe(getRequest('topic'));

        $oTopic->setProperties(getRequest('property'));
        $oTopic->setUserCreator($this->oUserCurrent);
        $oTopic->setUserId($this->oUserCurrent->getId());
        $oTopic->setDateAdd(date("Y-m-d H:i:s"));
        $oTopic->setUserIp(func_getIp());
        $oTopic->setTopicType($sTopicType);
        if (!$oTopic->getTags() or !$oTopic->getTypeObject()->getParam('allow_tags')) {
            $oTopic->setTags('');
        }
        /**
         * Публикуем или сохраняем
         */
        if (!isset($_REQUEST['is_draft'])) {
            $oTopic->setPublish(1);
            $oTopic->setPublishDraft(1);
        } else {
            $oTopic->setPublish(0);
            $oTopic->setPublishDraft(0);
        }
        /**
         * Принудительный вывод на главную
         */
        $oTopic->setPublishIndex(0);
        if ($this->ACL_IsAllowTopicPublishIndex($this->oUserCurrent)) {
            if (isset($_REQUEST['topic']['topic_publish_index'])) {
                $oTopic->setPublishIndex(1);
            }
        }
        /**
         * Принудительный запрет вывода на главную
         */
        $oTopic->setSkipIndex(0);
        if ($this->ACL_IsAllowTopicSkipIndex($this->oUserCurrent)) {
            if (isset($_REQUEST['topic']['topic_skip_index'])) {
                $oTopic->setSkipIndex(1);
            }
        }
        /**
         * Запрет на комментарии к топику
         */
        $oTopic->setForbidComment(0);
        if (isset($_REQUEST['topic']['topic_forbid_comment'])) {
            $oTopic->setForbidComment(1);
        }

        $this->Hook_Run('topic_add_validate_before', array('oTopic' => $oTopic));
        if ($oTopic->_Validate()) {
            $oBlog = $oTopic->getBlog();
            /**
             * Получаем и устанавливаем разрезанный текст по тегу <cut>
             */
            if ($oTopic->getTypeObject()->getParam('allow_text')) {
                list($sTextShort, $sTextNew, $sTextCut) = $this->Text_Cut($oTopic->getTextSource());
                $oTopic->setCutText($sTextCut);
                $oTopic->setText($this->Topic_Parser($sTextNew, $oTopic));
                if ($sTextShort != $sTextNew) {
                    $oTopic->setTextShort($this->Topic_Parser($sTextShort, $oTopic));
                } else {
                    $oTopic->setTextShort('');
                }
            } else {
                $oTopic->setCutText('');
                $oTopic->setText('');
                $oTopic->setTextShort('');
                $oTopic->setTextSource('');
            }

            $this->Hook_Run('topic_add_before', array('oTopic' => $oTopic, 'oBlog' => $oBlog));
            if ($this->Topic_AddTopic($oTopic)) {
                $this->Hook_Run('topic_add_after', array('oTopic' => $oTopic, 'oBlog' => $oBlog));
                /**
                 * Получаем топик, чтоб подцепить связанные данные
                 */
                $oTopic = $this->Topic_GetTopicById($oTopic->getId());
                /**
                 * Обновляем количество топиков в блогах
                 */
                $this->Blog_RecalculateCountTopicByBlogId($oTopic->getBlogsId());
                /**
                 * Фиксируем ID у media файлов топика
                 */
                $this->Media_ReplaceTargetTmpById('topic', $oTopic->getId());
                /**
                 * Фиксируем ID у опросов
                 */
                if ($oTopicType->getParam('allow_poll')) {
                    $this->Poll_ReplaceTargetTmpById('topic', $oTopic->getId());
                }
                /**
                 * Добавляем автора топика в подписчики на новые комментарии к этому топику
                 */
                $oUser = $oTopic->getUser();
                if ($oUser) {
                    $this->Subscribe_AddSubscribeSimple('topic_new_comment', $oTopic->getId(), $oUser->getMail(),
                        $oUser->getId());
                }
                /**
                 * Делаем рассылку спама всем, кто состоит в этом блоге
                 */
                if ($oTopic->getPublish() == 1 and $oBlog->getType() != 'personal') {
                    $this->Topic_SendNotifyTopicNew($oTopic, $oUser);
                }
                /**
                 * Добавляем событие в ленту
                 */
                $this->Stream_write($oTopic->getUserId(), 'add_topic', $oTopic->getId(),
                    $oTopic->getPublish() && $oBlog->getType() != 'close');


                $this->Viewer_AssignAjax('sUrlRedirect', $oTopic->getUrl());
                $this->Message_AddNotice('Добавление прошло успешно', $this->Lang_Get('common.attention'));
            } else {
                $this->Message_AddError('Возникла ошибка при добавлении', $this->Lang_Get('common.error.error'));
            }
        } else {
            $this->Message_AddError($oTopic->_getValidateError(), $this->Lang_Get('common.error.error'));
        }
    }

    public function EventAjaxPreview()
    {
        /**
         * Т.к. используется обработка отправки формы, то устанавливаем тип ответа 'jsonIframe' (тот же JSON только обернутый в textarea)
         * Это позволяет избежать ошибок в некоторых браузерах, например, Opera
         */
        $this->Viewer_SetResponseAjax('jsonIframe', false);
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Допустимый тип топика?
         */
        if (!$this->Topic_IsAllowTopicType($sType = getRequestStr('topic_type'))) {
            $this->Message_AddErrorSingle($this->Lang_Get('topic.add.notices.error_type'), $this->Lang_Get('common.error.error'));
            return;
        }
        $aTopicRequest = getRequest('topic');
        /**
         * Проверка на ID при редактировании топика
         */
        $iId = isset($aTopicRequest['id']) ? (int)$aTopicRequest['id'] : null;
        if ($iId and !($oTopicOriginal = $this->Topic_GetTopicById($iId))) {
            return $this->EventErrorDebug();
        }
        /**
         * Создаем объект топика для валидации данных
         */
        $oTopic = Engine::GetEntity('ModuleTopic_EntityTopic');
        $oTopic->setTitle(isset($aTopicRequest['topic_title']) ? strip_tags($aTopicRequest['topic_title']) : '');
        $oTopic->setTextSource(isset($aTopicRequest['topic_text_source']) ? $aTopicRequest['topic_text_source'] : '');
        $oTopic->setTags(isset($aTopicRequest['topic_tags']) ? $aTopicRequest['topic_tags'] : '');
        $oTopic->setDateAdd(date("Y-m-d H:i:s"));
        $oTopic->setDatePublish(date("Y-m-d H:i:s"));
        $oTopic->setUserId($this->oUserCurrent->getId());
        $oTopic->setType($sType);
        $oTopic->setPublish(1);
        $oTopic->setProperties(getRequest('property'));
        /**
         * Перед валидацией аттачим существующие свойста
         */
        if ($iId) {
            $oTopic->setId($iId);
            $a = $oTopic->getPropertyList();
        }
        /**
         * Валидируем необходимые поля топика
         */
        $oTopic->_Validate(array('topic_title', 'topic_text', 'topic_tags', 'topic_type', 'properties'), false);
        if ($oTopic->_hasValidateErrors()) {
            $this->Message_AddErrorSingle($oTopic->_getValidateError());
            return false;
        }
        /**
         * Аттачим дополнительные поля к топику
         */
        $this->Property_AttachPropertiesForTarget($oTopic, $oTopic->getPropertiesObject());
        /**
         * Формируем текст топика
         */
        list($sTextShort, $sTextNew, $sTextCut) = $this->Text_Cut($oTopic->getTextSource());
        $oTopic->setCutText($sTextCut);
        $oTopic->setText($this->Topic_Parser($sTextNew, $oTopic));
        $oTopic->setTextShort($this->Topic_Parser($sTextShort, $oTopic));
        /**
         * Рендерим шаблон для предпросмотра топика
         */
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('isPreview', true, true);
        $oViewer->Assign('topic', $oTopic, true);
        $sTemplate = 'component@topic.type';
        $sTextResult = $oViewer->Fetch($sTemplate);
        /**
         * Передаем результат в ajax ответ
         */
        $this->Viewer_AssignAjax('sText', $sTextResult);
        return true;
    }

    /**
     * При завершении экшена загружаем необходимые переменные
     *
     */
    public function EventShutdown()
    {
        $this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
        $this->Viewer_Assign('sMenuSubItemSelect', $this->sMenuSubItemSelect);
    }
}