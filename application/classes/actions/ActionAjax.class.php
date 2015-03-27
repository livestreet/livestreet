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
 * Экшен обработки ajax запросов
 * Ответ отдает в JSON фомате
 *
 * @package application.actions
 * @since 1.0
 */
class ActionAjax extends Action
{
    /**
     * Текущий пользователь
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;

    /**
     * Инициализация
     */
    public function Init()
    {
        /**
         * Устанавливаем формат ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Получаем текущего пользователя
         */
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    /**
     * Регистрация евентов
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^vote$/i', '/^comment$/', 'EventVoteComment');
        $this->AddEventPreg('/^vote$/i', '/^topic$/', 'EventVoteTopic');
        $this->AddEventPreg('/^vote$/i', '/^get$/', '/^info$/', '/^topic$/', 'EventVoteGetInfoTopic');

        $this->AddEventPreg('/^favourite$/i', '/^save-tags/', 'EventFavouriteSaveTags');
        $this->AddEventPreg('/^favourite$/i', '/^topic$/', 'EventFavouriteTopic');
        $this->AddEventPreg('/^favourite$/i', '/^comment$/', 'EventFavouriteComment');
        $this->AddEventPreg('/^favourite$/i', '/^talk$/', 'EventFavouriteTalk');

        $this->AddEventPreg('/^stream$/i', '/^comment$/', 'EventStreamComment');
        $this->AddEventPreg('/^stream$/i', '/^topic$/', 'EventStreamTopic');

        $this->AddEventPreg('/^blogs$/i', '/^top$/', 'EventBlogsTop');
        $this->AddEventPreg('/^blogs$/i', '/^self$/', 'EventBlogsSelf');
        $this->AddEventPreg('/^blogs$/i', '/^join$/', 'EventBlogsJoin');
        $this->AddEventPreg('/^blogs$/i', '/^get-by-category$/', 'EventBlogsGetByCategory');

        $this->AddEventPreg('/^preview$/i', '/^text$/', 'EventPreviewText');

        $this->AddEventPreg('/^autocompleter$/i', '/^tag$/', 'EventAutocompleterTag');
        $this->AddEventPreg('/^autocompleter$/i', '/^user$/', 'EventAutocompleterUser');

        $this->AddEventPreg('/^comment$/i', '/^delete$/', 'EventCommentDelete');
        $this->AddEventPreg('/^comment$/i', '/^load$/', 'EventCommentLoad');
        $this->AddEventPreg('/^comment$/i', '/^update$/', 'EventCommentUpdate');

        $this->AddEventPreg('/^geo$/i', '/^get/', '/^regions$/', 'EventGeoGetRegions');
        $this->AddEventPreg('/^geo$/i', '/^get/', '/^cities$/', 'EventGeoGetCities');

        $this->AddEventPreg('/^infobox$/i', '/^info$/', '/^blog$/', 'EventInfoboxInfoBlog');

        $this->AddEventPreg('/^media$/i', '/^upload$/', '/^$/', 'EventMediaUpload');
        $this->AddEventPreg('/^media$/i', '/^upload-link$/', '/^$/', 'EventMediaUploadLink');
        $this->AddEventPreg('/^media$/i', '/^generate-target-tmp$/', '/^$/', 'EventMediaGenerateTargetTmp');
        $this->AddEventPreg('/^media$/i', '/^submit-insert$/', '/^$/', 'EventMediaSubmitInsert');
        $this->AddEventPreg('/^media$/i', '/^submit-create-photoset$/', '/^$/', 'EventMediaSubmitCreatePhotoset');
        $this->AddEventPreg('/^media$/i', '/^load-gallery$/', '/^$/', 'EventMediaLoadGallery');
        $this->AddEventPreg('/^media$/i', '/^remove-file$/', '/^$/', 'EventMediaRemoveFile');
        $this->AddEventPreg('/^media$/i', '/^create-preview-file$/', '/^$/', 'EventMediaCreatePreviewFile');
        $this->AddEventPreg('/^media$/i', '/^remove-preview-file$/', '/^$/', 'EventMediaRemovePreviewFile');
        $this->AddEventPreg('/^media$/i', '/^load-preview-items$/', '/^$/', 'EventMediaLoadPreviewItems');
        $this->AddEventPreg('/^media$/i', '/^save-data-file$/', '/^$/', 'EventMediaSaveDataFile');

        $this->AddEventPreg('/^property$/i', '/^tags$/', '/^autocompleter$/', '/^$/', 'EventPropertyTagsAutocompleter');

        $this->AddEventPreg('/^captcha$/i', '/^$/', 'EventCaptcha');
        $this->AddEventPreg('/^captcha$/i', '/^validate$/', '/^$/', 'EventCaptchaValidate');

        $this->AddEventPreg('/^poll$/i', '/^modal-create$/', '/^$/', 'EventPollModalCreate');
        $this->AddEventPreg('/^poll$/i', '/^modal-update/', '/^$/', 'EventPollModalUpdate');
        $this->AddEventPreg('/^poll$/i', '/^create$/', '/^$/', 'EventPollCreate');
        $this->AddEventPreg('/^poll$/i', '/^update$/', '/^$/', 'EventPollUpdate');
        $this->AddEventPreg('/^poll$/i', '/^remove$/', '/^$/', 'EventPollRemove');
        $this->AddEventPreg('/^poll$/i', '/^vote$/', '/^$/', 'EventPollVote');

        $this->AddEvent('modal-friend-list', 'EventModalFriendList');
        $this->AddEventPreg('/^modal$/i', '/^image-crop$/', '/^$/', 'EventModalImageCrop');

        /**
         * Стена
         */

        // Добавление поста/комментария
        $this->AddEventPreg('/^wall$/i', '/^add$/', 'EventWallAdd');
        // Удаление поста/комментария
        $this->AddEventPreg('/^wall$/i', '/^remove$/', 'EventWallRemove');
        // Подгрузка постов
        $this->AddEventPreg('/^wall$/i', '/^load$/', 'EventWallLoad');
        // Подгрузка комментариев
        $this->AddEventPreg('/^wall$/i', '/^load-comments$/', 'EventWallLoadComments');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Показывает модальное окно с друзьями
     */
    protected function EventModalFriendList()
    {
        if (!$this->oUserCurrent) {
            return parent::EventNotFound();
        }

        $oViewer = $this->Viewer_GetLocalViewer();

        // Получаем переменные
        $bSelectable = getRequest('selectable');
        $sTarget = getRequest('target');

        // Получаем список друзей
        $aUsersFriend = $this->User_GetUsersFriend($this->oUserCurrent->getId());

        if ($aUsersFriend['collection']) {
            $oViewer->Assign('users', $aUsersFriend['collection'], true);
        }

        $oViewer->Assign('selectable', $bSelectable, true);
        $oViewer->Assign('target', $sTarget, true);

        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("component@user.modal.user-list"));
    }

    /**
     * Показывает модальное окно с функцией кропа изображения
     */
    protected function EventModalImageCrop()
    {
        $oViewer = $this->Viewer_GetLocalViewer();

        $oViewer->Assign('usePreview', (bool)getRequest('use_preview'), true);
        $oViewer->Assign('image', getRequestStr('image_src'), true);
        $oViewer->Assign('originalWidth', (int)getRequest('original_width'), true);
        $oViewer->Assign('originalHeight', (int)getRequest('original_height'), true);
        $oViewer->Assign('width', (int)getRequest('width'), true);
        $oViewer->Assign('height', (int)getRequest('height'), true);
        $oViewer->Assign('title', getRequestStr('title'), true);
        $oViewer->Assign('desc', getRequestStr('desc'), true);

        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("component@crop.crop"));
    }

    protected function EventPollVote()
    {
        if (!$oPoll = $this->Poll_GetPollById(getRequestStr('id'))) {
            return $this->EventErrorDebug();
        }

        if (!$this->oUserCurrent and !$oPoll->getIsGuestAllow()) {
            return $this->EventErrorDebug();
        }

        /**
         * Истекло время голосования?
         */
        if (!$oPoll->isAllowVote()) {
            $this->Message_AddErrorSingle('В этом опросе уже нельзя голосовать');
            return;
        }
        /**
         * Пользователь уже голосовал?
         */
        if ($this->Poll_CheckUserAlreadyVote($oPoll, $this->oUserCurrent)) {
            $this->Message_AddErrorSingle('Вы уже голосовали');
            return;
        }

        $aAnswerIds = array();
        $aAnswerItems = array();
        if (!getRequest('abstain')) {
            /**
             * Проверяем варианты ответов
             */
            if (!$aAnswer = (array)getRequest('answers')) {
                $this->Message_AddErrorSingle('Необходимо выбрать вариант');
                return;
            }

            foreach ($aAnswer as $iAnswerId) {
                if (!is_numeric($iAnswerId)) {
                    return $this->EventErrorDebug();
                }
                $aAnswerIds[] = $iAnswerId;
            }
            /**
             * Корректность ID вариантов
             */
            $aAnswerItems = $this->Poll_GetAnswerItemsByFilter(array(
                'id in'   => $aAnswerIds,
                'poll_id' => $oPoll->getId()
            ));
            if (count($aAnswerItems) != count($aAnswerIds)) {
                return $this->EventErrorDebug();
            }
            /**
             * Ограничение на максимальное число ответов
             */
            if (count($aAnswerIds) > $oPoll->getCountAnswerMax()) {
                $this->Message_AddErrorSingle('Максимум можно выбрать вариантов: ' . $oPoll->getCountAnswerMax());
                return;
            }
        }

        /**
         * Голосуем
         */
        $oVote = Engine::GetEntity('ModulePoll_EntityVote');
        $oVote->setPollId($oPoll->getId());
        $oVote->setPoll($oPoll); // для быстродействия/оптимизации
        $oVote->setUserId($this->oUserCurrent ? $this->oUserCurrent->getId() : null);
        $oVote->setGuestKey($this->oUserCurrent ? null : func_generator(32));
        $oVote->setAnswers($aAnswerIds);
        $oVote->setAnswersObject($aAnswerItems); // передаем для быстродействия, чтобы не запрашивать варианты еще раз после сохранения голоса
        if ($oVote->Add()) {
            /**
             * Устанавливаем куку
             */
            if ($oVote->getGuestKey()) {
                $this->Session_SetCookie($this->Poll_GetCookieVoteName($oPoll), $oVote->getGuestKey(), time() + 60 * 60 * 24 * 90);
            }
            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('oPoll', $oPoll);
            $this->Viewer_AssignAjax('sText', $oViewer->Fetch("component@poll.result"));
        } else {
            return $this->EventErrorDebug();
        }
    }

    protected function EventPollCreate()
    {
        if (!$this->oUserCurrent) {
            return $this->EventErrorDebug();
        }
        /**
         * Создаем
         */
        $oPoll = Engine::GetEntity('ModulePoll_EntityPoll');
        $oPoll->_setValidateScenario('create');
        $oPoll->_setDataSafe(getRequest('poll'));
        $oPoll->setAnswersRaw(getRequest('answers'));
        $oPoll->setTargetRaw(getRequest('target'));
        $oPoll->setUserId($this->oUserCurrent->getId());

        if ($oPoll->_Validate()) {
            if ($oPoll->Add()) {
                $oViewer = $this->Viewer_GetLocalViewer();
                $oViewer->Assign('oPoll', $oPoll);
                $this->Viewer_AssignAjax('sPollItem', $oViewer->Fetch("component@poll.manage.item"));
                return true;
            } else {
                $this->Message_AddError($this->Lang_Get('common.error.save'), $this->Lang_Get('common.error.error'));
            }
        } else {
            $this->Message_AddError($oPoll->_getValidateError(), $this->Lang_Get('common.error.error'));
        }
    }

    protected function EventPollUpdate()
    {
        if (!$this->oUserCurrent) {
            return $this->EventErrorDebug();
        }

        if (!$oPoll = $this->Poll_GetPollById(getRequestStr('poll_id'))) {
            return $this->EventErrorDebug();
        }

        /**
         * Проверяем корректность target'а
         */
        if ($oPoll->getTargetId()) {
            if (!$this->Poll_CheckTarget($oPoll->getTargetType(), $oPoll->getTargetId())) {
                return $this->EventErrorDebug();
            }
        } else {
            $sTarget = isset($_REQUEST['target']['tmp']) ? $_REQUEST['target']['tmp'] : '';
            if (!$this->Poll_IsAllowTargetType($oPoll->getTargetType()) or $oPoll->getTargetTmp() != $sTarget) {
                return $this->EventErrorDebug();
            }
        }
        /**
         * Обновляем
         */
        $oPoll->_setValidateScenario('update');
        $oPoll->_setDataSafe(getRequest('poll'));
        $oPoll->setAnswersRaw(getRequest('answers'));

        if ($oPoll->_Validate()) {
            if ($oPoll->Update()) {
                $oViewer = $this->Viewer_GetLocalViewer();
                $oViewer->Assign('oPoll', $oPoll);
                $this->Viewer_AssignAjax('sPollItem', $oViewer->Fetch("component@poll.manage.item"));
                $this->Viewer_AssignAjax('iPollId', $oPoll->getId());
                return true;
            } else {
                $this->Message_AddError($this->Lang_Get('common.error.save'), $this->Lang_Get('common.error.error'));
            }
        } else {
            $this->Message_AddError($oPoll->_getValidateError(), $this->Lang_Get('common.error.error'));
        }
    }

    protected function EventPollRemove()
    {
        if (!$this->oUserCurrent) {
            return $this->EventErrorDebug();
        }

        if (!$oPoll = $this->Poll_GetPollById(getRequestStr('id'))) {
            return $this->EventErrorDebug();
        }

        /**
         * Проверяем корректность target'а
         */
        if ($oPoll->getTargetId()) {
            if (!$this->Poll_CheckTarget($oPoll->getTargetType(), $oPoll->getTargetId())) {
                return $this->EventErrorDebug();
            }
        } else {
            if (!$this->Poll_IsAllowTargetType($oPoll->getTargetType()) or $oPoll->getTargetTmp() != getRequestStr('tmp')) {
                return $this->EventErrorDebug();
            }
        }

        if (!$oPoll->isAllowRemove()) {
            $this->Message_AddError('Этот опрос уже нельзя удалить');
            return;
        }

        /**
         * Удаляем
         */
        if ($oPoll->Delete()) {
            return true;
        } else {
            $this->Message_AddError($this->Lang_Get('common.error.save'), $this->Lang_Get('common.error.error'));
        }
    }

    protected function EventPollModalCreate()
    {
        if (!$this->oUserCurrent) {
            return $this->EventErrorDebug();
        }

        /**
         * Проверяем корректность target'а
         */
        $sTargetType = getRequestStr('target_type');
        $sTargetId = getRequestStr('target_id');

        $sTargetTmp = $this->Session_GetCookie('poll_target_tmp_' . $sTargetType) ? $this->Session_GetCookie('poll_target_tmp_' . $sTargetType) : getRequestStr('target_tmp');
        if ($sTargetId) {
            $sTargetTmp = null;
            if (!$this->Poll_CheckTarget($sTargetType, $sTargetId)) {
                return $this->EventErrorDebug();
            }
        } else {
            $sTargetId = null;
            if (!$this->Poll_IsAllowTargetType($sTargetType)) {
                return $this->EventErrorDebug();
            }
            if (!$sTargetTmp) {
                $sTargetTmp = func_generator();
                $this->Session_SetCookie('poll_target_tmp_' . $sTargetType, $sTargetTmp, time() + 24 * 3600);
            }
        }


        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('sTargetType', $sTargetType);
        $oViewer->Assign('sTargetId', $sTargetId);
        $oViewer->Assign('sTargetTmp', $sTargetTmp);
        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("component@poll.modal.create"));
    }

    protected function EventPollModalUpdate()
    {
        if (!$this->oUserCurrent) {
            return $this->EventErrorDebug();
        }

        if (!$oPoll = $this->Poll_GetPollById(getRequestStr('id'))) {
            return $this->EventErrorDebug();
        }

        /**
         * Проверяем корректность target'а
         */
        if ($oPoll->getTargetId()) {
            if (!$this->Poll_CheckTarget($oPoll->getTargetType(), $oPoll->getTargetId())) {
                return $this->EventErrorDebug();
            }
        } else {
            if (!$this->Poll_IsAllowTargetType($oPoll->getTargetType()) or $oPoll->getTargetTmp() != getRequestStr('target_tmp')) {
                return $this->EventErrorDebug();
            }
        }

        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('oPoll', $oPoll);
        $oViewer->Assign('sTargetTmp', getRequestStr('target_tmp'));
        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("component@poll.modal.create"));
    }

    /**
     * Отображение каптчи
     */
    protected function EventCaptcha()
    {
        $this->Viewer_SetResponseAjax(null);
        /**
         * Подключаем каптчу
         */
        require_once(Config::Get('path.framework.libs_vendor.server') . '/kcaptcha/kcaptcha.php');
        /**
         * Определяем уникальное название (возможность нескольких каптч на одной странице)
         */
        $sName = '';
        if (isset($_GET['name']) and is_string($_GET['name']) and $_GET['name']) {
            $sName = $_GET['name'];
        }
        /**
         * Генерируем каптчу и сохраняем код в сессию
         */
        $oCaptcha = new KCAPTCHA();
        $this->Session_Set('captcha_keystring' . ($sName ? '_' . $sName : ''), $oCaptcha->getKeyString());
        $this->SetTemplate(false);
    }

    /**
     * Ajax валидация каптчи
     */
    protected function EventCaptchaValidate()
    {
        $sName = isset($_REQUEST['params']['name']) ? $_REQUEST['params']['name'] : '';
        $sValue = isset($_REQUEST['fields'][0]['value']) ? $_REQUEST['fields'][0]['value'] : '';
        $sField = isset($_REQUEST['fields'][0]['field']) ? $_REQUEST['fields'][0]['field'] : '';

        $sCaptchaValidateType = func_camelize('captcha_' . Config::Get('sys.captcha.type'));
        if (!$this->Validate_Validate($sCaptchaValidateType, $sValue, array('name' => $sName))) {
            $aErrors = $this->Validate_GetErrors();
            $this->Viewer_AssignAjax('aErrors', array(htmlspecialchars($sField) => array(reset($aErrors))));
        }
    }

    protected function EventPropertyTagsAutocompleter()
    {
        /**
         * Первые буквы тега переданы?
         */
        if (!($sValue = getRequest('value', null, 'post')) or !is_string($sValue)) {
            return;
        }
        $aItems = array();
        /**
         * Формируем список тегов
         */
        $aTags = $this->Property_GetPropertyTagsByLike($sValue, getRequestStr('property_id'), 10);
        foreach ($aTags as $oTag) {
            $aItems[] = $oTag->getText();
        }
        /**
         * Передаем результат в ajax ответ
         */
        $this->Viewer_AssignAjax('aItems', $aItems);
    }

    protected function EventMediaUploadLink()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * URL передали?
         */
        if (!($sUrl = getRequestStr('url'))) {
            return $this->EventErrorDebug();
        }
        /**
         * Необходимо выполнить загрузку файла
         */
        if (getRequest('upload')) {
            /**
             * Проверяем корректность target'а
             */
            $sTargetType = getRequestStr('target_type');
            $sTargetId = getRequestStr('target_id');

            $sTargetTmp = $this->Session_GetCookie('media_target_tmp_' . $sTargetType) ? $this->Session_GetCookie('media_target_tmp_' . $sTargetType) : getRequestStr('target_tmp');
            if ($sTargetId) {
                $sTargetTmp = null;
                if (true !== $res = $this->Media_CheckTarget($sTargetType, $sTargetId,
                        ModuleMedia::TYPE_CHECK_ALLOW_ADD,
                        array('user' => $this->oUserCurrent))
                ) {
                    $this->Message_AddError(is_string($res) ? $res : $this->Lang_Get('common.error.system.base'),
                        $this->Lang_Get('common.error.error'));
                    return false;
                }
            } else {
                $sTargetId = null;
                if (!$sTargetTmp) {
                    return $this->EventErrorDebug();
                }
                if (true !== $res = $this->Media_CheckTarget($sTargetType, null, ModuleMedia::TYPE_CHECK_ALLOW_ADD,
                        array('user' => $this->oUserCurrent))
                ) {
                    $this->Message_AddError(is_string($res) ? $res : $this->Lang_Get('common.error.system.base'),
                        $this->Lang_Get('common.error.error'));
                    return false;
                }
            }

            /**
             * Выполняем загрузку файла
             */
            if ($mResult = $this->Media_UploadUrl($sUrl, $sTargetType, $sTargetId, $sTargetTmp) and is_object($mResult)
            ) {
                $aParams = array(
                    'align' => getRequestStr('align'),
                    'title' => getRequestStr('title')
                );
                $this->Viewer_AssignAjax('sText', $this->Media_BuildCodeForEditor($mResult, $aParams));
            } else {
                $this->Message_AddError(is_string($mResult) ? $mResult : $this->Lang_Get('common.error.system.base'),
                    $this->Lang_Get('common.error.error'));
            }
        } else {
            /**
             * Формируем параметры для билдера HTML
             */
            $aParams = array(
                'align'     => getRequestStr('align'),
                'title'     => getRequestStr('title'),
                'image_url' => $sUrl
            );
            $this->Viewer_AssignAjax('sText', $this->Media_BuildImageCodeForEditor($aParams));
        }
    }

    protected function EventMediaSaveDataFile()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        $aAllowData = array('title');
        $sName = getRequestStr('name');
        $sValue = getRequestStr('value');
        if (!in_array($sName, $aAllowData)) {
            return $this->EventErrorDebug();
        }
        $sId = getRequestStr('id');
        if (!$oMedia = $this->Media_GetMediaById($sId)) {
            return $this->EventErrorDebug();
        }
        if (true === $res = $this->Media_CheckTarget($oMedia->getTargetType(), null,
                ModuleMedia::TYPE_CHECK_ALLOW_UPDATE, array('media' => $oMedia, 'user' => $this->oUserCurrent))
        ) {
            $oMedia->setDataOne($sName, $sValue);
            $oMedia->Update();
        } else {
            $this->Message_AddErrorSingle(is_string($res) ? $res : $this->Lang_Get('common.error.system.base'));
        }
    }

    protected function EventMediaRemoveFile()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        $sId = getRequestStr('id');
        if (!$oMedia = $this->Media_GetMediaById($sId)) {
            return $this->EventErrorDebug();
        }
        if (true === $res = $this->Media_CheckTarget($oMedia->getTargetType(), null,
                ModuleMedia::TYPE_CHECK_ALLOW_REMOVE, array('media' => $oMedia, 'user' => $this->oUserCurrent))
        ) {
            $oMedia->Delete();
        } else {
            $this->Message_AddErrorSingle(is_string($res) ? $res : $this->Lang_Get('common.error.system.base'));
        }
    }

    protected function EventMediaCreatePreviewFile()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        $sId = getRequestStr('id');
        if (!$oMedia = $this->Media_GetMediaById($sId)) {
            return $this->EventErrorDebug();
        }

        $sTargetType = getRequestStr('target_type');
        $sTargetId = getRequestStr('target_id');
        $sTargetTmp = getRequestStr('target_tmp');

        /**
         * Получаем объект связи
         */
        $aFilter = array('media_id' => $oMedia->getId(), 'target_type' => $sTargetType);
        if ($sTargetTmp) {
            $aFilter['target_tmp'] = $sTargetTmp;
        } else {
            $aFilter['target_id'] = $sTargetId;
        }
        if (!$oTarget = $this->Media_GetTargetByFilter($aFilter)) {
            return $this->EventErrorDebug();
        }
        if ($oTarget->getIsPreview()) {
            return $this->EventErrorDebug();
        }


        /**
         * Проверяем доступ к этому медиа
         */
        if (true === $res = $this->Media_CheckTarget($oTarget->getTargetType(), $oTarget->getTargetId(),
                ModuleMedia::TYPE_CHECK_ALLOW_PREVIEW, array('media' => $oMedia, 'user' => $this->oUserCurrent))
        ) {
            /**
             * Удаляем все текущие превью
             */
            $this->Media_RemoveAllPreviewByTarget($oTarget->getTargetType(), $oTarget->getTargetId(),
                $oTarget->getTargetTmp());

            if (true === $res2 = $this->Media_CreateFilePreview($oMedia, $oTarget)) {
                $this->Viewer_AssignAjax('bUnsetOther', true);
            } else {
                $this->Message_AddErrorSingle(is_string($res2) ? $res2 : $this->Lang_Get('common.error.system.base'));
            }
        } else {
            $this->Message_AddErrorSingle(is_string($res) ? $res : $this->Lang_Get('common.error.system.base'));
        }
    }

    protected function EventMediaRemovePreviewFile()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        $sId = getRequestStr('id');
        if (!$oMedia = $this->Media_GetMediaById($sId)) {
            return $this->EventErrorDebug();
        }

        $sTargetType = getRequestStr('target_type');
        $sTargetId = getRequestStr('target_id');
        $sTargetTmp = getRequestStr('target_tmp');

        /**
         * Получаем объект связи
         */
        $aFilter = array('media_id' => $oMedia->getId(), 'target_type' => $sTargetType);
        if ($sTargetTmp) {
            $aFilter['target_tmp'] = $sTargetTmp;
        } else {
            $aFilter['target_id'] = $sTargetId;
        }
        if (!$oTarget = $this->Media_GetTargetByFilter($aFilter)) {
            return $this->EventErrorDebug();
        }
        if (!$oTarget->getIsPreview()) {
            return $this->EventErrorDebug();
        }


        /**
         * Проверяем доступ к этому медиа
         */
        if (true === $res = $this->Media_CheckTarget($oTarget->getTargetType(), $oTarget->getTargetId(),
                ModuleMedia::TYPE_CHECK_ALLOW_PREVIEW, array('media' => $oMedia, 'user' => $this->oUserCurrent))
        ) {
            /**
             * Удаляем превью
             */
            $this->Media_RemoveFilePreview($oMedia, $oTarget);
        } else {
            $this->Message_AddErrorSingle(is_string($res) ? $res : $this->Lang_Get('common.error.system.base'));
        }
    }

    protected function EventMediaLoadGallery()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }

        $sType = getRequestStr('target_type');
        $sId = getRequestStr('target_id');
        $sTmp = getRequestStr('target_tmp');
        $iPage = (int)getRequestStr('page');
        $iPage = $iPage < 1 ? 1 : $iPage;

        $aMediaItems = array();
        if ($sType) {
            /**
             * Получаем медиа для конкретного объекта
             */
            if ($sId) {
                if (!$this->Media_CheckTarget($sType, $sId, ModuleMedia::TYPE_CHECK_ALLOW_VIEW_LIST)) {
                    $this->Message_AddErrorSingle($this->Lang_Get('common.error.not_access'), $this->Lang_Get('common.error.error'));
                    return;
                }
                $aMediaItems = $this->Media_GetMediaByTarget($sType, $sId);
            } elseif ($sTmp) {
                $aMediaItems = $this->Media_GetMediaByTargetTmp($sTmp, $this->oUserCurrent->getId());
            }
        } else {
            /**
             * Получаем все медиа, созданные пользователем
             */
            $aResult = $this->Media_GetMediaItemsByFilter(array(
                'user_id' => $this->oUserCurrent->getId(),
                '#page'   => array($iPage, 20),
                '#order'  => array('id' => 'desc')
            ));
            $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, 20, Config::Get('pagination.pages.count'), null);
            $aMediaItems = $aResult['collection'];
        }

        $oViewer = $this->Viewer_GetLocalViewer();
        $sTemplate = '';
        foreach ($aMediaItems as $oMediaItem) {
            $oViewer->Assign('oMediaItem', $oMediaItem);
            $sTemplate .= $oViewer->Fetch('component@uploader.file');
        }

        $this->Viewer_AssignAjax('html', $sTemplate);
    }

    protected function EventMediaLoadPreviewItems()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }

        $sType = getRequestStr('target_type');
        $sId = getRequestStr('target_id');
        $sTmp = getRequestStr('target_tmp');

        $aFilter = array(
            'target_type' => $sType,
            'is_preview'  => 1,
        );
        if ($sId) {
            $aFilter['target_id'] = $sId;
        } else {
            $aFilter['target_tmp'] = $sTmp;
        }
        $aTargetItems = $this->Media_GetTargetItemsByFilter($aFilter);
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('imagePreviewItems', $aTargetItems);
        $this->Viewer_AssignAjax('sTemplatePreview', $oViewer->Fetch('component@field.image-ajax-items'));
    }

    protected function EventMediaSubmitInsert()
    {
        $aIds = array(0);
        foreach ((array)getRequest('ids') as $iId) {
            $aIds[] = (int)$iId;
        }

        if (!($aMediaItems = $this->Media_GetAllowMediaItemsById($aIds))) {
            $this->Message_AddError('Необходимо выбрать элементы');
            return false;
        }

        $aParams = array(
            'align'        => getRequestStr('align'),
            'size'         => getRequestStr('size'),
            'relative_web' => true
        );
        /**
         * Если изображений несколько, то генерируем идентификатор группы для лайтбокса
         */
        if (count($aMediaItems) > 1) {
            $aParams['lbx_group'] = rand(1, 100);
        }

        $sTextResult = '';
        foreach ($aMediaItems as $oMedia) {
            $sTextResult .= $this->Media_BuildCodeForEditor($oMedia, $aParams) . "\r\n";
        }
        $this->Viewer_AssignAjax('sTextResult', $sTextResult);
    }

    protected function EventMediaSubmitCreatePhotoset()
    {
        $aMediaItems = $this->Media_GetAllowMediaItemsById(getRequest('ids'));
        if (!$aMediaItems) {
            $this->Message_AddError('Необходимо выбрать элементы');
            return false;
        }

        $aItems = array();
        foreach ($aMediaItems as $oMedia) {
            $aItems[] = $oMedia->getId();
        }

        $sTextResult = '<gallery items="' . join(',', $aItems) . '"';
        if (getRequest('use_thumbs')) {
            $sTextResult .= ' nav="thumbs" ';
        }
        if (getRequest('show_caption')) {
            $sTextResult .= ' caption="1" ';
        }
        $sTextResult .= ' />';

        $this->Viewer_AssignAjax('sTextResult', $sTextResult);
    }

    protected function EventMediaGenerateTargetTmp()
    {
        $sType = getRequestStr('type');
        if ($this->Media_IsAllowTargetType($sType)) {
            $sTmp = func_generator();
            $this->Session_SetCookie('media_target_tmp_' . $sType, $sTmp, time() + 24 * 3600);
            $this->Viewer_AssignAjax('sTmpKey', $sTmp);
        }
    }

    protected function EventMediaUpload()
    {
        if (getRequest('is_iframe')) {
            $this->Viewer_SetResponseAjax('jsonIframe', false);
        } else {
            $this->Viewer_SetResponseAjax('json');
        }
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Файл был загружен?
         */
        if (!isset($_FILES['filedata']['tmp_name'])) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем корректность target'а
         */
        $sTargetType = getRequestStr('target_type');
        $sTargetId = getRequestStr('target_id');

        $sTargetTmp = $this->Session_GetCookie('media_target_tmp_' . $sTargetType) ? $this->Session_GetCookie('media_target_tmp_' . $sTargetType) : getRequestStr('target_tmp');
        if ($sTargetId) {
            $sTargetTmp = null;
            if (true !== $res = $this->Media_CheckTarget($sTargetType, $sTargetId, ModuleMedia::TYPE_CHECK_ALLOW_ADD,
                    array('user' => $this->oUserCurrent))
            ) {
                $this->Message_AddError(is_string($res) ? $res : $this->Lang_Get('common.error.system.base'),
                    $this->Lang_Get('common.error.error'));
                return false;
            }
        } else {
            $sTargetId = null;
            if (!$sTargetTmp) {
                return $this->EventErrorDebug();
            }
            if (true !== $res = $this->Media_CheckTarget($sTargetType, null, ModuleMedia::TYPE_CHECK_ALLOW_ADD,
                    array('user' => $this->oUserCurrent))
            ) {
                $this->Message_AddError(is_string($res) ? $res : $this->Lang_Get('common.error.system.base'),
                    $this->Lang_Get('common.error.error'));
                return false;
            }
        }

        /**
         * TODO: необходима проверка на максимальное общее количество файлов, на максимальный размер файла
         * Эти настройки можно хранить в конфиге: module.media.type.topic.max_file_count=30 и т.п.
         */

        /**
         * Выполняем загрузку файла
         */
        if ($mResult = $this->Media_Upload($_FILES['filedata'], $sTargetType, $sTargetId,
                $sTargetTmp) and is_object($mResult)
        ) {
            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('oMediaItem', $mResult);

            $sTemplateFile = $oViewer->Fetch('component@uploader.file');

            $this->Viewer_AssignAjax('sTemplateFile', $sTemplateFile);
        } else {
            $this->Message_AddError(is_string($mResult) ? $mResult : $this->Lang_Get('common.error.system.base'),
                $this->Lang_Get('common.error.error'));
        }
    }

    /**
     * Вывод информации о блоге
     */
    protected function EventInfoboxInfoBlog()
    {
        /**
         * Если блог существует и он не персональный
         */
        if (!is_string(getRequest('iBlogId'))) {
            return $this->EventErrorDebug();
        }
        if (!($oBlog = $this->Blog_GetBlogById(getRequest('iBlogId'))) or $oBlog->getType() == 'personal') {
            return $this->EventErrorDebug();
        }
        /**
         * Получаем локальный вьюер для рендеринга шаблона
         */
        $oViewer = $this->Viewer_GetLocalViewer();

        $oViewer->Assign('oBlog', $oBlog);
        if ($oBlog->getType() != 'close' or $oBlog->getUserIsJoin()) {
            /**
             * Получаем последний топик
             */
            $aResult = $this->Topic_GetTopicsByFilter(array('blog_id' => $oBlog->getId(), 'topic_publish' => 1), 1, 1);
            $oViewer->Assign('oTopicLast', reset($aResult['collection']));
        }
        $oViewer->Assign('oUserCurrent', $this->oUserCurrent);
        /**
         * Устанавливаем переменные для ajax ответа
         */
        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("actions/ActionBlogs/popover.blog.info.tpl"));
    }

    /**
     * Получение информации о голосовании за топик
     */
    protected function EventVoteGetInfoTopic()
    {
        if (!($oTopic = $this->Topic_GetTopicById(getRequestStr('iTargetId', null, 'post')))) {
            return $this->EventErrorDebug();
        }

        if (!$oTopic->getVote() && ($this->oUserCurrent && $oTopic->getUserId() != $this->oUserCurrent->getId()) && (strtotime($oTopic->getDatePublish()) + Config::Get('acl.vote.topic.limit_time') > time())) {
            return $this->EventErrorDebug();
        }

        $oViewer = $this->Viewer_GetLocalViewer();

        $oViewer->Assign('target', $oTopic, true);
        $oViewer->Assign('oUserCurrent', $this->oUserCurrent);

        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("component@vote.info"));
    }

    /**
     * Получение списка регионов по стране
     */
    protected function EventGeoGetRegions()
    {
        $iCountryId = getRequestStr('country');
        $iLimit = 200;
        if (is_numeric(getRequest('limit')) and getRequest('limit') > 0) {
            $iLimit = getRequest('limit');
        }
        /**
         * Находим страну
         */
        if (!($oCountry = $this->Geo_GetGeoObject('country', $iCountryId))) {
            return $this->EventErrorDebug();
        }
        /**
         * Получаем список регионов
         */
        if ($sTargetType = getRequestStr('target_type') and $this->Geo_IsAllowTargetType($sTargetType)) {
            $aRegions = $this->Geo_GetRegionsUsedByTargetType($oCountry->getId(), $sTargetType);
        } else {
            $aRegions = $this->Geo_GetRegions(array('country_id' => $oCountry->getId()), array('sort' => 'asc'), 1,
                $iLimit);
            $aRegions = $aRegions['collection'];
        }
        /**
         * Формируем ответ
         */
        $aReturn = array();
        foreach ($aRegions as $oObject) {
            $aReturn[] = array(
                'id'   => $oObject->getId(),
                'name' => $oObject->getName(),
            );
        }
        /**
         * Устанавливаем переменные для ajax ответа
         */
        $this->Viewer_AssignAjax('aRegions', $aReturn);
    }

    /**
     * Получение списка городов по региону
     */
    protected function EventGeoGetCities()
    {
        $iRegionId = getRequestStr('region');
        $iLimit = 500;
        if (is_numeric(getRequest('limit')) and getRequest('limit') > 0) {
            $iLimit = getRequest('limit');
        }
        /**
         * Находим регион
         */
        if (!($oRegion = $this->Geo_GetGeoObject('region', $iRegionId))) {
            return $this->EventErrorDebug();
        }
        /**
         * Получаем города
         */
        if ($sTargetType = getRequestStr('target_type') and $this->Geo_IsAllowTargetType($sTargetType)) {
            $aCities = $this->Geo_GetCitiesUsedByTargetType($oRegion->getId(), $sTargetType);
        } else {
            $aCities = $this->Geo_GetCities(array('region_id' => $oRegion->getId()), array('sort' => 'asc'), 1,
                $iLimit);
            $aCities = $aCities['collection'];
        }
        /**
         * Формируем ответ
         */
        $aReturn = array();
        foreach ($aCities as $oObject) {
            $aReturn[] = array(
                'id'   => $oObject->getId(),
                'name' => $oObject->getName(),
            );
        }
        /**
         * Устанавливаем переменные для ajax ответа
         */
        $this->Viewer_AssignAjax('aCities', $aReturn);
    }

    /**
     * Голосование за комментарий
     *
     */
    protected function EventVoteComment()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Комментарий существует?
         */
        if (!($oComment = $this->Comment_GetCommentById(getRequestStr('iTargetId', null, 'post')))) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверка типа комментария
         */
        if (!in_array($oComment->getTargetType(), (array)Config::Get('module.comment.vote_target_allow'))) {
            return $this->EventErrorDebug();
        }
        if ($oComment->getTargetType() == 'topic') {
            /**
             * Проверяем права на просмотр топика
             */
            if (!$this->ACL_IsAllowShowTopic($oComment->getTarget(), $this->oUserCurrent)) {
                return parent::EventNotFound();
            }
        }
        /**
         * Пользователь имеет право голоса?
         */
        if (!$this->ACL_CanVoteComment($this->oUserCurrent, $oComment)) {
            $this->Message_AddErrorSingle($this->Rbac_GetMsgLast());
            return;
        }
        /**
         * Как именно голосует пользователь
         */
        $iValue = getRequestStr('value', null, 'post');
        if (!in_array($iValue, array('1', '-1'))) {
            return $this->EventErrorDebug();
        }
        /**
         * Голосуем
         */
        $oTopicCommentVote = Engine::GetEntity('Vote');
        $oTopicCommentVote->setTargetId($oComment->getId());
        $oTopicCommentVote->setTargetType('comment');
        $oTopicCommentVote->setVoterId($this->oUserCurrent->getId());
        $oTopicCommentVote->setDirection($iValue);
        $oTopicCommentVote->setDate(date("Y-m-d H:i:s"));
        $iVal = (float)$this->Rating_VoteComment($this->oUserCurrent, $oComment, $iValue);
        $oTopicCommentVote->setValue($iVal);

        $oComment->setCountVote($oComment->getCountVote() + 1);
        $this->Hook_Run("vote_{$oTopicCommentVote->getTargetType()}_before",
            array('oTarget' => $oComment, 'oVote' => $oTopicCommentVote, 'iValue' => $iValue));
        if ($this->Vote_AddVote($oTopicCommentVote) and $this->Comment_UpdateComment($oComment)) {
            $this->Hook_Run("vote_{$oTopicCommentVote->getTargetType()}_after",
                array('oTarget' => $oComment, 'oVote' => $oTopicCommentVote, 'iValue' => $iValue));

            $this->Message_AddNoticeSingle($this->Lang_Get('vote.notices.success'), $this->Lang_Get('common.attention'));
            $this->Viewer_AssignAjax('iRating', $oComment->getRating());
            /**
             * Добавляем событие в ленту
             */
            $this->Stream_Write($oTopicCommentVote->getVoterId(), 'vote_comment_' . $oComment->getTargetType(),
                $oComment->getId());
        } else {
            return $this->EventErrorDebug();
        }
    }

    /**
     * Голосование за топик
     *
     */
    protected function EventVoteTopic()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Топик существует?
         */
        if (!($oTopic = $this->Topic_GetTopicById(getRequestStr('iTargetId', null, 'post')))) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем права на просмотр топика
         */
        if (!$this->ACL_IsAllowShowTopic($oTopic, $this->oUserCurrent)) {
            return parent::EventNotFound();
        }
        /**
         * Как проголосовал пользователь
         */
        $iValue = getRequestStr('value', null, 'post');
        if (!in_array($iValue, array('1', '-1', '0'))) {
            return $this->EventErrorDebug();
        }
        /**
         * Права на голосование
         */
        if (!$this->ACL_CanVoteTopic($this->oUserCurrent, $oTopic, $iValue)) {
            $this->Message_AddErrorSingle($this->Rbac_GetMsgLast());
            return;
        }
        /**
         * Голосуем
         */
        $oTopicVote = Engine::GetEntity('Vote');
        $oTopicVote->setTargetId($oTopic->getId());
        $oTopicVote->setTargetType('topic');
        $oTopicVote->setVoterId($this->oUserCurrent->getId());
        $oTopicVote->setDirection($iValue);
        $oTopicVote->setDate(date("Y-m-d H:i:s"));
        $iVal = 0;
        if ($iValue != 0) {
            $iVal = (float)$this->Rating_VoteTopic($this->oUserCurrent, $oTopic, $iValue);
        }
        $oTopicVote->setValue($iVal);
        $oTopic->setCountVote($oTopic->getCountVote() + 1);
        if ($iValue == 1) {
            $oTopic->setCountVoteUp($oTopic->getCountVoteUp() + 1);
        } elseif ($iValue == -1) {
            $oTopic->setCountVoteDown($oTopic->getCountVoteDown() + 1);
        } elseif ($iValue == 0) {
            $oTopic->setCountVoteAbstain($oTopic->getCountVoteAbstain() + 1);
        }
        $this->Hook_Run("vote_{$oTopicVote->getTargetType()}_before",
            array('oTarget' => $oTopic, 'oVote' => $oTopicVote, 'iValue' => $iValue));
        if ($this->Vote_AddVote($oTopicVote) and $this->Topic_UpdateTopic($oTopic)) {
            $this->Hook_Run("vote_{$oTopicVote->getTargetType()}_after",
                array('oTarget' => $oTopic, 'oVote' => $oTopicVote, 'iValue' => $iValue));
            if ($iValue) {
                $this->Message_AddNoticeSingle($this->Lang_Get('vote.notices.success'), $this->Lang_Get('common.attention'));
            } else {
                $this->Message_AddNoticeSingle($this->Lang_Get('vote.notices.success_abstain'),
                    $this->Lang_Get('common.attention'));
            }
            $this->Viewer_AssignAjax('iRating', $oTopic->getRating());
            /**
             * Добавляем событие в ленту
             */
            $this->Stream_write($oTopicVote->getVoterId(), 'vote_topic', $oTopic->getId());
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
            return;
        }
    }

    /**
     * Сохраняет теги для избранного
     *
     */
    protected function EventFavouriteSaveTags()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Объект уже должен быть в избранном
         */
        if ($oFavourite = $this->Favourite_GetFavourite(getRequestStr('target_id'), getRequestStr('target_type'),
            $this->oUserCurrent->getId())
        ) {
            /**
             * Обрабатываем теги
             */
            $aTags = explode(',', trim(getRequestStr('tags'), "\r\n\t\0\x0B ."));
            $aTagsNew = array();
            $aTagsNewLow = array();
            $aTagsReturn = array();
            foreach ($aTags as $sTag) {
                $sTag = trim($sTag);
                if (func_check($sTag, 'text', 2, 50) and !in_array(mb_strtolower($sTag, 'UTF-8'), $aTagsNewLow)) {
                    $sTagEsc = htmlspecialchars($sTag);
                    $aTagsNew[] = $sTag;
                    $aTagsReturn[] = array(
                        'tag' => $sTagEsc,
                        'url' => $this->oUserCurrent->getUserWebPath() . 'favourites/' . $oFavourite->getTargetType() . 's/tag/' . $sTagEsc . '/',
                        // костыль для URL с множественным числом
                    );
                    $aTagsNewLow[] = mb_strtolower($sTag, 'UTF-8');
                }
            }
            if (!count($aTagsNew)) {
                $oFavourite->setTags('');
            } else {
                $oFavourite->setTags(join(',', $aTagsNew));
            }
            $this->Viewer_AssignAjax('aTags', $aTagsReturn);
            $this->Favourite_UpdateFavourite($oFavourite);
            return;
        }
        return $this->EventErrorDebug();
    }

    /**
     * Обработка избранного - топик
     *
     */
    protected function EventFavouriteTopic()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Можно только добавить или удалить из избранного
         */
        $iType = getRequestStr('type', null, 'post');
        if (!in_array($iType, array('1', '0'))) {
            return $this->EventErrorDebug();
        }
        /**
         * Топик существует?
         */
        if (!($oTopic = $this->Topic_GetTopicById(getRequestStr('iTargetId', null, 'post')))) {
            return $this->EventErrorDebug();
        }
        /**
         * Есть доступ к комментариям этого топика? Закрытый блог?
         */
        if (!$this->ACL_IsAllowShowBlog($oTopic->getBlog(), $this->oUserCurrent)) {
            return $this->EventErrorDebug();
        }
        /**
         * Пропускаем топик из черновиков
         */
        if (!$oTopic->getPublish()) {
            $this->Message_AddErrorSingle($this->Lang_Get('topic.add.notices.error_favourite_draft'),
                $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Топик уже в избранном?
         */
        $oFavouriteTopic = $this->Topic_GetFavouriteTopic($oTopic->getId(), $this->oUserCurrent->getId());
        if (!$oFavouriteTopic and $iType) {
            $oFavouriteTopicNew = Engine::GetEntity('Favourite',
                array(
                    'target_id'      => $oTopic->getId(),
                    'user_id'        => $this->oUserCurrent->getId(),
                    'target_type'    => 'topic',
                    'target_publish' => $oTopic->getPublish()
                )
            );
            $oTopic->setCountFavourite($oTopic->getCountFavourite() + 1);
            if ($this->Topic_AddFavouriteTopic($oFavouriteTopicNew) and $this->Topic_UpdateTopic($oTopic)) {
                $this->Message_AddNoticeSingle($this->Lang_Get('favourite.notices.add_success'),
                    $this->Lang_Get('common.attention'));
                $this->Viewer_AssignAjax('bState', true);
                $this->Viewer_AssignAjax('iCount', $oTopic->getCountFavourite());
            } else {
                return $this->EventErrorDebug();
            }
        }
        if (!$oFavouriteTopic and !$iType) {
            $this->Message_AddErrorSingle($this->Lang_Get('favourite.notices.already_removed'),
                $this->Lang_Get('common.error.error'));
            return;
        }
        if ($oFavouriteTopic and $iType) {
            $this->Message_AddErrorSingle($this->Lang_Get('favourite.notices.already_added'), $this->Lang_Get('common.error.error'));
            return;
        }
        if ($oFavouriteTopic and !$iType) {
            $oTopic->setCountFavourite($oTopic->getCountFavourite() - 1);
            if ($this->Topic_DeleteFavouriteTopic($oFavouriteTopic) and $this->Topic_UpdateTopic($oTopic)) {
                $this->Message_AddNoticeSingle($this->Lang_Get('favourite.notices.remove_success'),
                    $this->Lang_Get('common.attention'));
                $this->Viewer_AssignAjax('bState', false);
                $this->Viewer_AssignAjax('iCount', $oTopic->getCountFavourite());
            } else {
                return $this->EventErrorDebug();
            }
        }
    }

    /**
     * Обработка избранного - комментарий
     *
     */
    protected function EventFavouriteComment()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Можно только добавить или удалить из избранного
         */
        $iType = getRequestStr('type', null, 'post');
        if (!in_array($iType, array('1', '0'))) {
            return $this->EventErrorDebug();
        }
        /**
         * Комментарий существует?
         */
        if (!($oComment = $this->Comment_GetCommentById(getRequestStr('iTargetId', null, 'post')))) {
            return $this->EventErrorDebug();
        }
        /**
         * Есть права?
         */
        if (!$this->ACL_IsAllowFavouriteComment($oComment, $this->oUserCurrent)) {
            $this->Message_AddErrorSingle($this->Rbac_GetMsgLast());
            return;
        }
        /**
         * Комментарий уже в избранном?
         */
        $oFavouriteComment = $this->Comment_GetFavouriteComment($oComment->getId(), $this->oUserCurrent->getId());
        if (!$oFavouriteComment and $iType) {
            $oFavouriteCommentNew = Engine::GetEntity('Favourite',
                array(
                    'target_id'      => $oComment->getId(),
                    'target_type'    => 'comment',
                    'user_id'        => $this->oUserCurrent->getId(),
                    'target_publish' => $oComment->getPublish()
                )
            );
            $oComment->setCountFavourite($oComment->getCountFavourite() + 1);
            if ($this->Comment_AddFavouriteComment($oFavouriteCommentNew) and $this->Comment_UpdateComment($oComment)) {
                $this->Message_AddNoticeSingle($this->Lang_Get('favourite.notices.add_success'),
                    $this->Lang_Get('common.attention'));
                $this->Viewer_AssignAjax('bState', true);
                $this->Viewer_AssignAjax('iCount', $oComment->getCountFavourite());
            } else {
                return $this->EventErrorDebug();
            }
        }
        if (!$oFavouriteComment and !$iType) {
            return $this->EventErrorDebug();
        }
        if ($oFavouriteComment and $iType) {
            return $this->EventErrorDebug();
        }
        if ($oFavouriteComment and !$iType) {
            $oComment->setCountFavourite($oComment->getCountFavourite() - 1);
            if ($this->Comment_DeleteFavouriteComment($oFavouriteComment) and $this->Comment_UpdateComment($oComment)) {
                $this->Message_AddNoticeSingle($this->Lang_Get('favourite.notices.remove_success'),
                    $this->Lang_Get('common.attention'));
                $this->Viewer_AssignAjax('bState', false);
                $this->Viewer_AssignAjax('iCount', $oComment->getCountFavourite());
            } else {
                return $this->EventErrorDebug();
            }
        }
    }

    /**
     * Обработка избранного - личное сообщение
     *
     */
    protected function EventFavouriteTalk()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Можно только добавить или удалить из избранного
         */
        $iType = getRequestStr('type', null, 'post');
        if (!in_array($iType, array('1', '0'))) {
            return $this->EventErrorDebug();
        }
        /**
         *    Сообщение существует?
         */
        if (!($oTalk = $this->Talk_GetTalkById(getRequestStr('iTargetId', null, 'post')))) {
            return $this->EventErrorDebug();
        }
        /**
         * Есть доступ?
         */
        if (!($oTalkUser = $this->Talk_GetTalkUser($oTalk->getId(), $this->oUserCurrent->getId()))) {
            return $this->EventErrorDebug();
        }
        /**
         * Сообщение уже в избранном?
         */
        $oFavouriteTalk = $this->Talk_GetFavouriteTalk($oTalk->getId(), $this->oUserCurrent->getId());
        if (!$oFavouriteTalk and $iType) {
            $oFavouriteTalkNew = Engine::GetEntity('Favourite',
                array(
                    'target_id'      => $oTalk->getId(),
                    'target_type'    => 'talk',
                    'user_id'        => $this->oUserCurrent->getId(),
                    'target_publish' => '1'
                )
            );
            if ($this->Talk_AddFavouriteTalk($oFavouriteTalkNew)) {
                $this->Message_AddNoticeSingle($this->Lang_Get('favourite.notices.add_success'),
                    $this->Lang_Get('common.attention'));
                $this->Viewer_AssignAjax('bState', true);
            } else {
                return $this->EventErrorDebug();
            }
        }

        // Этого письма нет в вашем избранном
        if (!$oFavouriteTalk and !$iType) {
            return $this->EventErrorDebug();
        }

        // Это письмо уже есть в вашем избранном
        if ($oFavouriteTalk and $iType) {
            return $this->EventErrorDebug();
        }

        if ($oFavouriteTalk and !$iType) {
            if ($this->Talk_DeleteFavouriteTalk($oFavouriteTalk)) {
                $this->Message_AddNoticeSingle($this->Lang_Get('favourite.notices.remove_success'),
                    $this->Lang_Get('common.attention'));
                $this->Viewer_AssignAjax('bState', false);
            } else {
                return $this->EventErrorDebug();
            }
        }

    }

    /**
     * Обработка получения последних комментов
     * Используется в блоке "Прямой эфир"
     *
     */
    protected function EventStreamComment()
    {
        if ($aComments = $this->Comment_GetCommentsOnline('topic', Config::Get('block.stream.row'))) {
            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('comments', $aComments, true);
            $sTextResult = $oViewer->Fetch("component@activity.recent-comments");
            $this->Viewer_AssignAjax('sText', $sTextResult);
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('activity.block_recent.comments_empty'),
                $this->Lang_Get('common.attention'));
            return;
        }
    }

    /**
     * Обработка получения последних топиков
     * Используется в блоке "Прямой эфир"
     *
     */
    protected function EventStreamTopic()
    {
        if ($oTopics = $this->Topic_GetTopicsLast(Config::Get('block.stream.row'))) {
            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('topics', $oTopics, true);
            $sTextResult = $oViewer->Fetch("component@activity.recent-topics");
            $this->Viewer_AssignAjax('sText', $sTextResult);
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('activity.block_recent.topics_empty'),
                $this->Lang_Get('common.attention'));
            return;
        }
    }

    /**
     * Обработка получения TOP блогов
     * Используется в блоке "TOP блогов"
     *
     */
    protected function EventBlogsTop()
    {
        /**
         * Получаем список блогов и формируем ответ
         */
        if ($aResult = $this->Blog_GetBlogsRating(1, Config::Get('block.blogs.row'))) {
            $aBlogs = $aResult['collection'];
            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('aBlogs', $aBlogs);
            $sTextResult = $oViewer->Fetch("component@blog.top");
            $this->Viewer_AssignAjax('sText', $sTextResult);
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
            return;
        }
    }

    /**
     * Обработка получения своих блогов
     * Используется в блоке "TOP блогов"
     *
     */
    protected function EventBlogsSelf()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Получаем список блогов и формируем ответ
         */
        if ($aBlogs = $this->Blog_GetBlogsRatingSelf($this->oUserCurrent->getId(), Config::Get('block.blogs.row'))) {
            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('aBlogs', $aBlogs);
            $sTextResult = $oViewer->Fetch("component@blog.top");
            $this->Viewer_AssignAjax('sText', $sTextResult);
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('blog.blocks.blogs.self_empty'), $this->Lang_Get('common.attention'));
            return;
        }
    }

    /**
     * Обработка получения подключенных блогов
     * Используется в блоке "TOP блогов"
     *
     */
    protected function EventBlogsJoin()
    {
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Получаем список блогов и формируем ответ
         */
        if ($aBlogs = $this->Blog_GetBlogsRatingJoin($this->oUserCurrent->getId(), Config::Get('block.blogs.row'))) {
            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('aBlogs', $aBlogs);
            $sTextResult = $oViewer->Fetch("component@blog.top");
            $this->Viewer_AssignAjax('sText', $sTextResult);
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('blog.blocks.blogs.joined_empty'), $this->Lang_Get('common.attention'));
            return;
        }
    }

    /**
     * Загружает список блогов конкретной категории
     */
    protected function EventBlogsGetByCategory()
    {
        if (!($oCategory = $this->Category_GetCategoryById(getRequestStr('id')))) {
            return $this->EventErrorDebug();
        }
        /**
         * Список ID блогов по категории
         */
        $aBlogIds = $this->Blog_GetTargetIdsByCategory($oCategory, 1, 1000, true);
        /**
         * Формируем фильтр для получения списка блогов
         */
        $aFilter = array(
            'exclude_type' => 'personal',
            'id'           => $aBlogIds ? $aBlogIds : array(0)
        );
        /**
         * Получаем список блогов(все по фильтру)
         */
        $aResult = $this->Blog_GetBlogsByFilter($aFilter, array('blog_title' => 'asc'), 1, PHP_INT_MAX);
        $aBlogs = $aResult['collection'];
        /**
         * Получаем список блогов и формируем ответ
         */
        if ($aBlogs) {
            $aResult = array();
            foreach ($aBlogs as $oBlog) {
                $aResult[] = array(
                    'id'       => $oBlog->getId(),
                    'title'    => htmlspecialchars($oBlog->getTitle()),
                    'type'     => $oBlog->getType(),
                    'rating'   => $oBlog->getRating(),
                    'url'      => $oBlog->getUrl(),
                    'url_full' => $oBlog->getUrlFull(),
                );
            }
            $this->Viewer_AssignAjax('aBlogs', $aResult);
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('blog.blocks.navigator.empty'), $this->Lang_Get('common.attention'));
            return;
        }
    }

    /**
     * Предпросмотр текста
     *
     */
    protected function EventPreviewText()
    {
        $sText = getRequestStr('text', null, 'post');
        $bSave = getRequest('save', null, 'post');
        /**
         * Экранировать или нет HTML теги
         */
        if ($bSave) {
            $sTextResult = htmlspecialchars($sText);
        } else {
            $sTextResult = $this->Text_Parser($sText);
        }
        /**
         * Передаем результат в ajax ответ
         */
        $this->Viewer_AssignAjax('sText', $sTextResult);
    }

    /**
     * Автоподставновка тегов
     *
     */
    protected function EventAutocompleterTag()
    {
        /**
         * Первые буквы тега переданы?
         */
        if (!($sValue = getRequest('value', null, 'post')) or !is_string($sValue)) {
            return;
        }
        $aItems = array();
        /**
         * Формируем список тегов
         */
        $aTags = $this->Topic_GetTopicTagsByLike($sValue, 10);
        foreach ($aTags as $oTag) {
            $aItems[] = $oTag->getText();
        }
        /**
         * Передаем результат в ajax ответ
         */
        $this->Viewer_AssignAjax('aItems', $aItems);
    }

    /**
     * Автоподставновка пользователей
     *
     */
    protected function EventAutocompleterUser()
    {
        /**
         * Первые буквы логина переданы?
         */
        if (!($sValue = getRequest('value', null, 'post')) or !is_string($sValue)) {
            return;
        }
        $bReturnExtended=getRequest('extended') ?: false;
        $aItems = array();
        /**
         * Формируем список пользователей
         */
        $aUsers = $this->User_GetUsersByLoginLike($sValue, 10);
        foreach ($aUsers as $oUser) {
            if ($bReturnExtended) {
                $aItems[] = array(
                    'value' => $oUser->getId(),
                    'label' => $oUser->getLogin(),
                );
            } else {
                $aItems[] = $oUser->getLogin();
            }
        }
        /**
         * Передаем результат в ajax ответ
         */
        $this->Viewer_AssignAjax('aItems', $aItems);
    }

    /**
     * Удаление/восстановление комментария
     *
     */
    protected function EventCommentDelete()
    {
        /**
         * Есть права на удаление комментария?
         */
        if (!$this->ACL_CanDeleteComment($this->oUserCurrent)) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.not_access'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Комментарий существует?
         */
        $idComment = getRequestStr('comment_id', null, 'post');
        if (!($oComment = $this->Comment_GetCommentById($idComment))) {
            return $this->EventErrorDebug();
        }
        /**
         * Устанавливаем пометку о том, что комментарий удален
         */
        $oComment->setDelete(($oComment->getDelete() + 1) % 2);
        $this->Hook_Run('comment_delete_before', array('oComment' => $oComment));
        if (!$this->Comment_UpdateCommentStatus($oComment)) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
            return;
        }
        $this->Hook_Run('comment_delete_after', array('oComment' => $oComment));
        /**
         * Формируем текст ответа
         */
        if ($bState = (bool)$oComment->getDelete()) {
            $sMsg = $this->Lang_Get('common.success.remove');
            $sTextToggle = $this->Lang_Get('comments.comment.restore');
        } else {
            $sMsg = $this->Lang_Get('comments.notices.success_restore');
            $sTextToggle = $this->Lang_Get('common.remove');
        }
        /**
         * Обновление события в ленте активности
         */
        $this->Stream_write($oComment->getUserId(), 'add_comment', $oComment->getId(), !$oComment->getDelete());
        /**
         * Показываем сообщение и передаем переменные в ajax ответ
         */
        $this->Message_AddNoticeSingle($sMsg, $this->Lang_Get('common.attention'));
        $this->Viewer_AssignAjax('state', $bState);
        $this->Viewer_AssignAjax('toggle_text', $sTextToggle);
    }

    /**
     * Загрузка данных комментария для редактировоания
     *
     */
    protected function EventCommentLoad()
    {
        /**
         * Комментарий существует?
         */
        $idComment = getRequestStr('comment_id', null, 'post');
        if (!($oComment = $this->Comment_GetCommentById($idComment))) {
            return $this->EventErrorDebug();
        }
        if (!$oComment->isAllowEdit()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.not_access'), $this->Lang_Get('common.error.error'));
            return;
        }
        $sText = $oComment->getTextSource() ? $oComment->getTextSource() : $oComment->getText();
        $this->Viewer_AssignAjax('text', $sText);
    }

    /**
     * Редактирование комментария
     *
     */
    protected function EventCommentUpdate()
    {
        /**
         * Комментарий существует?
         */
        $idComment = getRequestStr('comment_id', null, 'post');
        if (!($oComment = $this->Comment_GetCommentById($idComment))) {
            return $this->EventErrorDebug();
        }
        if (!$oComment->isAllowEdit()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.not_access'), $this->Lang_Get('common.error.error'));
            return;
        }

        $sText = getRequestStr('comment_text');
        /**
         * Проверяем текст комментария
         */
        if (!$this->Validate_Validate('string', $sText, array('min' => 2, 'max' => 10000, 'allowEmpty' => false))) {
            $this->Message_AddErrorSingle($this->Lang_Get('topic.comments.notices.error_text'),
                $this->Lang_Get('common.error.error'));
            return;
        }

        $oComment->setText($this->Text_Parser($sText));
        $oComment->setTextSource($sText);
        $oComment->setDateEdit(date('Y-m-d H:i:s'));
        $oComment->setCountEdit($oComment->getCountEdit() + 1);

        if ($this->Comment_UpdateComment($oComment)) {
            $oViewerLocal = $this->Viewer_GetLocalViewer();
            $oViewerLocal->Assign('oUserCurrent', $this->oUserCurrent);
            $oViewerLocal->Assign('oneComment', true, true);
            $oViewerLocal->Assign('useEdit', true, true);

            if ($oComment->getTargetType() == 'topic') {
                $oViewerLocal->Assign('useFavourite', true, true);
                $oViewerLocal->Assign('useVote', true, true);
            }

            $oViewerLocal->Assign('comment', $oComment, true);
            $sHtml = $oViewerLocal->Fetch($this->Comment_GetTemplateCommentByTarget($oComment->getTargetId(),
                $oComment->getTargetType()));
            $this->Viewer_AssignAjax('html', $sHtml);
        } else {
            return $this->EventErrorDebug();
        }
    }

    /**
     * Проверка корректности профиля
     */
    protected function WallCheckUserProfile()
    {
        if (!($this->oUserProfile = $this->User_GetUserById((int)getRequestStr('user_id')))) {
            return false;
        }

        return true;
    }

    /**
     * Добавление записи на стену
     */
    public function EventWallAdd()
    {
        $this->Viewer_SetResponseAjax('json');

        if (!$this->oUserCurrent) {
            return $this->EventErrorDebug();
        }

        if (!$this->WallCheckUserProfile()) {
            return $this->EventErrorDebug();
        }

        // Создаем запись
        $oWall = Engine::GetEntity('Wall');

        $oWall->_setValidateScenario('add');
        $oWall->setWallUserId($this->oUserProfile->getId());
        $oWall->setUserId($this->oUserCurrent->getId());
        $oWall->setText(getRequestStr('text'));
        $oWall->setPid(getRequestStr('pid'));

        $this->Hook_Run('wall_add_validate_before', array('oWall' => $oWall));

        if ($oWall->_Validate()) {
            // Экранируем текст и добавляем запись в БД
            $oWall->setText($this->Text_Parser($oWall->getText()));
            $this->Hook_Run('wall_add_before', array('oWall' => $oWall));

            if ($this->Wall_AddWall($oWall)) {
                $this->Hook_Run('wall_add_after', array('oWall' => $oWall));

                // Отправляем уведомления
                if ($oWall->getWallUserId() != $oWall->getUserId()) {
                    $this->Wall_SendNotifyWallNew($oWall, $this->oUserCurrent);
                }

                if ($oWallParent = $oWall->GetPidWall() and $oWallParent->getUserId() != $oWall->getUserId()) {
                    $this->Wall_SendNotifyWallReply($oWallParent, $oWall, $this->oUserCurrent);
                }

                // Добавляем событие в ленту
                $this->Stream_Write($oWall->getUserId(), 'add_wall', $oWall->getId());
            } else {
                $this->Message_AddError($this->Lang_Get('common.error.add'), $this->Lang_Get('common.error.error'));
            }
        } else {
            $this->Message_AddError($oWall->_getValidateError(), $this->Lang_Get('common.error.error'));
        }
    }

    /**
     * Удаление записи со стены
     */
    public function EventWallRemove()
    {
        $this->Viewer_SetResponseAjax('json');

        if (!$this->oUserCurrent) {
            return $this->EventErrorDebug();
        }

        if (!$this->WallCheckUserProfile()) {
            return $this->EventErrorDebug();
        }

        // Получаем запись
        if (!($oWall = $this->Wall_GetWallById(getRequestStr('id')))) {
            return $this->EventErrorDebug();
        }

        // Если разрешено удаление - удаляем
        if ($oWall->isAllowDelete()) {
            $this->Wall_DeleteWall($oWall);
            return;
        }

        return $this->EventErrorDebug();
    }

    /**
     * Ajax подгрузка сообщений стены
     */
    public function EventWallLoad()
    {
        $this->Viewer_SetResponseAjax('json');

        if (!$this->WallCheckUserProfile()) {
            return $this->EventErrorDebug();
        }

        // Формируем фильтр для запроса к БД
        $aFilter = array(
            'wall_user_id' => $this->oUserProfile->getId(),
            'pid'          => null
        );

        if (is_numeric(getRequest('last_id'))) {
            $aFilter['id_less'] = getRequest('last_id');
        } else {
            if (is_numeric(getRequest('first_id'))) {
                $aFilter['id_more'] = getRequest('first_id');
            } else {
                return $this->EventErrorDebug();
            }
        }

        // Получаем сообщения и формируем ответ
        $aWall = $this->Wall_GetWall($aFilter, array('id' => 'desc'), 1, Config::Get('module.wall.per_page'));

        $this->Viewer_Assign('posts', $aWall['collection'], true);
        $this->Viewer_Assign('oUserCurrent',
            $this->oUserCurrent); // хак, т.к. к этому моменту текущий юзер не загружен в шаблон

        $this->Viewer_AssignAjax('html', $this->Viewer_Fetch('component@wall.posts'));
        $this->Viewer_AssignAjax('count_loaded', count($aWall['collection']));

        if (count($aWall['collection'])) {
            $this->Viewer_AssignAjax('last_id', end($aWall['collection'])->getId());
        }
    }

    /**
     * Подгрузка комментариев
     */
    public function EventWallLoadComments()
    {
        $this->Viewer_SetResponseAjax('json');

        if (!$this->WallCheckUserProfile()) {
            return $this->EventErrorDebug();
        }

        if (!($oWall = $this->Wall_GetWallById(getRequestStr('target_id'))) or $oWall->getPid()) {
            return $this->EventErrorDebug();
        }

        // Формируем фильтр для запроса к БД
        $aFilter = array(
            'wall_user_id' => $this->oUserProfile->getId(),
            'pid'          => $oWall->getId()
        );

        if (is_numeric(getRequest('last_id'))) {
            $aFilter['id_less'] = getRequest('last_id');
        } else {
            if (is_numeric(getRequest('first_id'))) {
                $aFilter['id_more'] = getRequest('first_id');
            } else {
                return $this->EventErrorDebug();
            }
        }

        // Получаем сообщения и формируем ответ
        // Необходимо вернуть все ответы, но ставим "разумное" ограничение
        $aWall = $this->Wall_GetWall($aFilter, array('id' => 'asc'), 1, 300);

        // Передаем переменные
        $this->Viewer_Assign('comments', $aWall['collection'], true);

        $this->Viewer_AssignAjax('html', $this->Viewer_Fetch('component@wall.comments'));
        $this->Viewer_AssignAjax('count_loaded', count($aWall['collection']));

        if (count($aWall['collection'])) {
            $this->Viewer_AssignAjax('last_id', end($aWall['collection'])->getId());
        }
    }
}