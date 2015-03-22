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
 * Экшен обрабтки настроек профиля юзера (/settings/)
 *
 * @package application.actions
 * @since 1.0
 */
class ActionSettings extends Action
{
    /**
     * Какое меню активно
     *
     * @var string
     */
    protected $sMenuItemSelect = 'settings';
    /**
     * Меню профиля пользователя
     *
     * @var string
     */
    protected $sMenuProfileItemSelect = 'settings';
    /**
     * Какое подменю активно
     *
     * @var string
     */
    protected $sMenuSubItemSelect = 'profile';
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
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.not_access'), $this->Lang_Get('common.error.error'));
            return Router::Action('error');
        }
        /**
         * Получаем текущего юзера
         */
        $this->oUserCurrent = $this->User_GetUserCurrent();
        $this->SetDefaultEvent('profile');
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($this->Lang_Get('user.settings.title'));
    }

    /**
     * Регистрация евентов
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('profile', 'EventProfile');
        $this->AddEvent('invite', 'EventInvite');
        $this->AddEvent('tuning', 'EventTuning');
        $this->AddEvent('account', 'EventAccount');

        $this->AddEventPreg('/^ajax-upload-photo$/i', '/^$/i', 'EventAjaxUploadPhoto');
        $this->AddEventPreg('/^ajax-crop-photo$/i', '/^$/i', 'EventAjaxCropPhoto');
        $this->AddEventPreg('/^ajax-crop-cancel-photo$/i', '/^$/i', 'EventAjaxCropCancelPhoto');
        $this->AddEventPreg('/^ajax-remove-photo$/i', '/^$/i', 'EventAjaxRemovePhoto');
        $this->AddEventPreg('/^ajax-change-avatar$/i', '/^$/i', 'EventAjaxChangeAvatar');
        $this->AddEventPreg('/^ajax-modal-crop-photo$/i', '/^$/i', 'EventAjaxModalCropPhoto');
        $this->AddEventPreg('/^ajax-modal-crop-avatar$/i', '/^$/i', 'EventAjaxModalCropAvatar');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Загрузка фотографии в профиль пользователя
     */
    protected function EventAjaxUploadPhoto()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('jsonIframe', false);
        if (!isset($_FILES['photo']['tmp_name'])) {
            return $this->EventErrorDebug();
        }

        if (!$oUser = $this->User_GetUserById(getRequestStr('target_id'))) {
            return $this->EventErrorDebug();
        }
        if (!$oUser->isAllowEdit()) {
            return $this->EventErrorDebug();
        }
        /**
         * Копируем загруженный файл
         */
        $sFileTmp = Config::Get('sys.cache.dir') . func_generator();
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $sFileTmp)) {
            return false;
        }
        /**
         * Если объект изображения не создан, возвращаем ошибку
         */
        if (!$oImage = $this->Image_Open($sFileTmp)) {
            $this->Fs_RemoveFileLocal($sFileTmp);
            $this->Message_AddError($this->Image_GetLastError());
            return;
        }
        /**
         * Ресайзим и сохраняем именьшенную копию
         * Храним две копии - мелкую для показа пользователю и крупную в качестве исходной для ресайза
         */
        $sDir = Config::Get('path.uploads.images') . "/tmp/userphoto/{$oUser->getId()}";
        if ($sFileOriginal = $oImage->resize(1000, null)->saveSmart($sDir, 'original')) {
            if ($sFilePreview = $oImage->resize(350, null)->saveSmart($sDir, 'preview')) {
                list($iOriginalWidth, $iOriginalHeight) = @getimagesize($this->Fs_GetPathServer($sFileOriginal));
                list($iWidth, $iHeight) = @getimagesize($this->Fs_GetPathServer($sFilePreview));
                /**
                 * Сохраняем в сессии временный файл с изображением
                 */
                $this->Session_Set('sPhotoFileTmp', $sFileOriginal);
                $this->Session_Set('sPhotoFilePreviewTmp', $sFilePreview);
                $this->Viewer_AssignAjax('path', $this->Fs_GetPathWeb($sFilePreview));
                $this->Viewer_AssignAjax('original_width', $iOriginalWidth);
                $this->Viewer_AssignAjax('original_height', $iOriginalHeight);
                $this->Viewer_AssignAjax('width', $iWidth);
                $this->Viewer_AssignAjax('height', $iHeight);
                $this->Fs_RemoveFileLocal($sFileTmp);
                return;
            }
        }
        $this->Message_AddError($this->Image_GetLastError());
        $this->Fs_RemoveFileLocal($sFileTmp);
    }

    /**
     * Обрезка фотографии в профиль пользователя
     */
    protected function EventAjaxCropPhoto()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');

        if (!$oUser = $this->User_GetUserById(getRequestStr('target_id'))) {
            return $this->EventErrorDebug();
        }
        if (!$oUser->isAllowEdit()) {
            return $this->EventErrorDebug();
        }

        $sFile = $this->Session_Get('sPhotoFileTmp');
        $sFilePreview = $this->Session_Get('sPhotoFilePreviewTmp');
        if (!$this->Image_IsExistsFile($sFile)) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
            return;
        }

        if (true === ($res = $this->User_CreateProfilePhoto($sFile, $oUser, getRequest('size'),
                getRequestStr('canvas_width')))
        ) {
            $this->Image_RemoveFile($sFile);
            $this->Image_RemoveFile($sFilePreview);
            $this->Session_Drop('sPhotoFileTmp');
            $this->Session_Drop('sPhotoFilePreviewTmp');
            /**
             * Создаем аватар на основе фото
             */
            $this->User_CreateProfileAvatar($oUser->getProfileFoto(), $oUser);

            $this->Viewer_AssignAjax('upload_text', $this->Lang_Get('user.photo.actions.change_photo'));
            $this->Viewer_AssignAjax('photo', $oUser->getProfileFotoPath());
        } else {
            $this->Message_AddError(is_string($res) ? $res : $this->Lang_Get('common.error.error'));
        }
    }

    /**
     * Показывает модальное окно с кропом фото
     */
    protected function EventAjaxModalCropPhoto()
    {
        $this->Viewer_SetResponseAjax('json');

        $oViewer = $this->Viewer_GetLocalViewer();

        $oViewer->Assign('image', getRequestStr('path'), true);
        $oViewer->Assign('originalWidth', (int)getRequest('original_width'), true);
        $oViewer->Assign('originalHeight', (int)getRequest('original_height'), true);
        $oViewer->Assign('width', (int)getRequest('width'), true);
        $oViewer->Assign('height', (int)getRequest('height'), true);

        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("component@user.modal.crop-photo"));
    }

    /**
     * Показывает модальное окно с кропом аватарки
     */
    protected function EventAjaxModalCropAvatar()
    {
        $this->Viewer_SetResponseAjax('json');

        $oViewer = $this->Viewer_GetLocalViewer();

        $oViewer->Assign('image', getRequestStr('path'), true);
        $oViewer->Assign('originalWidth', (int)getRequest('original_width'), true);
        $oViewer->Assign('originalHeight', (int)getRequest('original_height'), true);
        $oViewer->Assign('width', (int)getRequest('width'), true);
        $oViewer->Assign('height', (int)getRequest('height'), true);
        $oViewer->Assign('usePreview', true, true);

        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("component@user.modal.crop-avatar"));
    }

    /**
     * Удаляет временные файлы кропа фото
     */
    protected function EventAjaxCropCancelPhoto()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');

        if (!$oUser = $this->User_GetUserById(getRequestStr('target_id'))) {
            return $this->EventErrorDebug();
        }
        if (!$oUser->isAllowEdit()) {
            return $this->EventErrorDebug();
        }

        $sFile = $this->Session_Get('sPhotoFileTmp');
        $sFilePreview = $this->Session_Get('sPhotoFilePreviewTmp');

        $this->Image_RemoveFile($sFile);
        $this->Image_RemoveFile($sFilePreview);
        $this->Session_Drop('sPhotoFileTmp');
        $this->Session_Drop('sPhotoFilePreviewTmp');
    }

    /**
     * Удаление фотографии профиля
     */
    protected function EventAjaxRemovePhoto()
    {
        $this->Viewer_SetResponseAjax('json');

        if (!$oUser = $this->User_GetUserById(getRequestStr('target_id'))) {
            return $this->EventErrorDebug();
        }
        if (!$oUser->isAllowEdit()) {
            return $this->EventErrorDebug();
        }

        $this->User_DeleteProfilePhoto($oUser);
        $this->User_DeleteProfileAvatar($oUser);
        $this->User_Update($oUser);

        $this->Viewer_AssignAjax('upload_text', $this->Lang_Get('user.photo.actions.upload_photo'));
        $this->Viewer_AssignAjax('photo', $oUser->getProfileFotoPath());
        $this->Viewer_AssignAjax('avatars', $oUser->GetProfileAvatarsPath());
    }

    /**
     * Обновление аватара на основе фото профиля
     */
    protected function EventAjaxChangeAvatar()
    {
        $this->Viewer_SetResponseAjax('json');

        if (!$oUser = $this->User_GetUserById(getRequestStr('target_id'))) {
            return $this->EventErrorDebug();
        }
        if (!$oUser->isAllowEdit()) {
            return $this->EventErrorDebug();
        }

        if (true === ($res = $this->User_CreateProfileAvatar($oUser->getProfileFoto(), $oUser, getRequest('size'),
                getRequestStr('canvas_width')))
        ) {
            // Формируем массив с путями до аватаров
            $this->Viewer_AssignAjax('avatars', $oUser->GetProfileAvatarsPath());
        } else {
            $this->Message_AddError(is_string($res) ? $res : $this->Lang_Get('common.error.error'));
        }
    }

    /**
     * Дополнительные настройки сайта
     */
    protected function EventTuning()
    {
        $this->sMenuItemSelect = 'settings';
        $this->sMenuSubItemSelect = 'tuning';

        $this->Viewer_AddHtmlTitle($this->Lang_Get('user.settings.nav.tuning'));
        $aTimezoneList = DateTimeZone::listIdentifiers();
        $this->Viewer_Assign('aTimezoneList', $aTimezoneList);
        /**
         * Если отправили форму с настройками - сохраняем
         */
        if (isPost('submit_settings_tuning')) {
            $this->Security_ValidateSendForm();

            if (in_array(getRequestStr('settings_general_timezone'), $aTimezoneList)) {
                $this->oUserCurrent->setSettingsTimezone(getRequestStr('settings_general_timezone'));
            }

            $this->oUserCurrent->setSettingsNoticeNewTopic(getRequest('settings_notice_new_topic') ? 1 : 0);
            $this->oUserCurrent->setSettingsNoticeNewComment(getRequest('settings_notice_new_comment') ? 1 : 0);
            $this->oUserCurrent->setSettingsNoticeNewTalk(getRequest('settings_notice_new_talk') ? 1 : 0);
            $this->oUserCurrent->setSettingsNoticeReplyComment(getRequest('settings_notice_reply_comment') ? 1 : 0);
            $this->oUserCurrent->setSettingsNoticeNewFriend(getRequest('settings_notice_new_friend') ? 1 : 0);
            $this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
            /**
             * Запускаем выполнение хуков
             */
            $this->Hook_Run('settings_tuning_save_before', array('oUser' => $this->oUserCurrent));
            if ($this->User_Update($this->oUserCurrent)) {
                $this->Message_AddNoticeSingle($this->Lang_Get('common.success.save'));
                $this->Hook_Run('settings_tuning_save_after', array('oUser' => $this->oUserCurrent));
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
            }
        } else {
            if (is_null($this->oUserCurrent->getSettingsTimezone())) {
                $_REQUEST['settings_general_timezone'] = date_default_timezone_get();
            } else {
                $_REQUEST['settings_general_timezone'] = $this->oUserCurrent->getSettingsTimezone();
            }
        }
    }

    /**
     * Показ и обработка формы приглаешний
     *
     */
    protected function EventInvite()
    {
        $this->sMenuSubItemSelect = 'invite';
        $this->Viewer_AddHtmlTitle($this->Lang_Get('user.settings.nav.invites'));

        $this->Viewer_Assign('iCountInviteAvailable', $this->Invite_GetCountInviteAvailable($this->oUserCurrent));
        $this->Viewer_Assign('iCountInviteUsed', $this->Invite_GetCountInviteUsed($this->oUserCurrent->getId()));
        $this->Viewer_Assign('sReferralLink', $this->Invite_GetReferralLink($this->oUserCurrent));
        /**
         * Если отправили форму
         */
        if (isPost('submit_invite')) {
            $this->Security_ValidateSendForm();

            $bError = false;
            /**
             * Есть права на отправку инвайтов?
             */
            if (!$this->ACL_CanSendInvite($this->oUserCurrent)) {
                $this->Message_AddErrorSingle($this->Rbac_GetMsgLast());
                return;
            }
            /**
             * Емайл корректен?
             */
            if (!$this->Validate_Validate('email', getRequestStr('invite_mail'))) {
                $this->Message_AddError($this->Validate_GetErrorLast());
                return;
            }

            if (Config::Get('general.reg.invite')) {
                if (!($oInvite = $this->Invite_GenerateInvite($this->oUserCurrent))) {
                    return $this->EventErrorDebug();
                }
                $sRefCode = $oInvite->getCode();
            } else {
                if (!($sRefCode = $this->Invite_GetReferralCode($this->oUserCurrent))) {
                    return $this->EventErrorDebug();
                }
            }
            /**
             * Если нет ошибок, то отправляем инвайт
             */
            if (!$bError) {
                /**
                 * Запускаем выполнение хуков
                 */
                $this->Hook_Run('settings_invite_send_before', array('oUser' => $this->oUserCurrent, 'sRefCode' => $sRefCode));

                $this->Invite_SendNotifyInvite($this->oUserCurrent, getRequestStr('invite_mail'), $sRefCode);
                $this->Message_AddNoticeSingle($this->Lang_Get('user.settings.invites.notices.success'));
                $this->Hook_Run('settings_invite_send_after', array('oUser' => $this->oUserCurrent, 'sRefCode' => $sRefCode));
            }
        }
    }

    /**
     * Форма смены пароля, емайла
     */
    protected function EventAccount()
    {
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($this->Lang_Get('user.settings.nav.account'));
        $this->sMenuSubItemSelect = 'account';
        /**
         * Если нажали кнопку "Сохранить"
         */
        if (isPost('submit_account_edit')) {
            $this->Security_ValidateSendForm();

            $bError = false;
            /**
             * Проверка мыла
             */
            if (func_check(getRequestStr('mail'), 'mail')) {
                if ($oUserMail = $this->User_GetUserByMail(getRequestStr('mail')) and $oUserMail->getId() != $this->oUserCurrent->getId()) {
                    $this->Message_AddError($this->Lang_Get('user.settings.account.fields.email.notices.error_used'),
                        $this->Lang_Get('common.error.error'));
                    $bError = true;
                }
            } else {
                $this->Message_AddError($this->Lang_Get('fields.email.notices.error'), $this->Lang_Get('common.error.error'));
                $bError = true;
            }
            /**
             * Проверка на смену пароля
             */
            if (getRequestStr('password', '') != '') {
                if (func_check(getRequestStr('password'), 'password', 5)) {
                    if (getRequestStr('password') == getRequestStr('password_confirm')) {
                        if (func_encrypt(getRequestStr('password_now')) == $this->oUserCurrent->getPassword()) {
                            $this->oUserCurrent->setPassword(func_encrypt(getRequestStr('password')));
                        } else {
                            $bError = true;
                            $this->Message_AddError($this->Lang_Get('user.settings.account.fields.password.notices.error'),
                                $this->Lang_Get('common.error.error'));
                        }
                    } else {
                        $bError = true;
                        $this->Message_AddError($this->Lang_Get('user.settings.account.fields.password_confirm.notices.error'),
                            $this->Lang_Get('common.error.error'));
                    }
                } else {
                    $bError = true;
                    $this->Message_AddError($this->Lang_Get('user.settings.account.fields.password_new.notices.error'),
                        $this->Lang_Get('common.error.error'));
                }
            }
            /**
             * Ставим дату последнего изменения
             */
            $this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
            /**
             * Запускаем выполнение хуков
             */
            $this->Hook_Run('settings_account_save_before',
                array('oUser' => $this->oUserCurrent, 'bError' => &$bError));
            /**
             * Сохраняем изменения
             */
            if (!$bError) {
                if ($this->User_Update($this->oUserCurrent)) {
                    $this->Message_AddNoticeSingle($this->Lang_Get('common.success.save'));
                    /**
                     * Подтверждение смены емайла
                     */
                    if (getRequestStr('mail') and getRequestStr('mail') != $this->oUserCurrent->getMail()) {
                        if ($oChangemail = $this->User_MakeUserChangemail($this->oUserCurrent, getRequestStr('mail'))) {
                            if ($oChangemail->getMailFrom()) {
                                $this->Message_AddNotice($this->Lang_Get('user.settings.account.fields.email.notices.change_from_notice'));
                            } else {
                                $this->Message_AddNotice($this->Lang_Get('user.settings.account.fields.email.notices.change_to_notice'));
                            }
                        }
                    }

                    $this->Hook_Run('settings_account_save_after', array('oUser' => $this->oUserCurrent));
                } else {
                    $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
                }
            }
        }
    }

    /**
     * Выводит форму для редактирования профиля и обрабатывает её
     *
     */
    protected function EventProfile()
    {
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($this->Lang_Get('user.settings.nav.profile'));
        $this->Viewer_Assign('aUserFields', $this->User_getUserFields(''));
        $this->Viewer_Assign('aUserFieldsContact', $this->User_getUserFields(array('contact', 'social')));
        /**
         * Загружаем в шаблон JS текстовки
         */
        $this->Lang_AddLangJs(array(
            'user.settings.profile.notices.error_max_userfields'
        ));
        /**
         * Если нажали кнопку "Сохранить"
         */
        if (isPost('submit_profile_edit')) {
            $this->Security_ValidateSendForm();

            $bError = false;
            /**
             * Заполняем профиль из полей формы
             */
            /**
             * Определяем гео-объект
             */
            if (getRequest('geo_city')) {
                $oGeoObject = $this->Geo_GetGeoObject('city', getRequestStr('geo_city'));
            } elseif (getRequest('geo_region')) {
                $oGeoObject = $this->Geo_GetGeoObject('region', getRequestStr('geo_region'));
            } elseif (getRequest('geo_country')) {
                $oGeoObject = $this->Geo_GetGeoObject('country', getRequestStr('geo_country'));
            } else {
                $oGeoObject = null;
            }
            /**
             * Проверяем имя
             */
            if (func_check(getRequestStr('profile_name'), 'text', 2, Config::Get('module.user.name_max'))) {
                $this->oUserCurrent->setProfileName(getRequestStr('profile_name'));
            } else {
                $this->oUserCurrent->setProfileName(null);
            }
            /**
             * Проверяем пол
             */
            if (in_array(getRequestStr('profile_sex'), array('man', 'woman', 'other'))) {
                $this->oUserCurrent->setProfileSex(getRequestStr('profile_sex'));
            } else {
                $this->oUserCurrent->setProfileSex('other');
            }
            /**
             * Проверяем дату рождения
             */
            if (func_check(getRequestStr('profile_birthday_day'), 'id', 1,
                    2) and func_check(getRequestStr('profile_birthday_month'), 'id', 1,
                    2) and func_check(getRequestStr('profile_birthday_year'), 'id', 4, 4)
            ) {
                $this->oUserCurrent->setProfileBirthday(date("Y-m-d H:i:s",
                    mktime(0, 0, 0, getRequestStr('profile_birthday_month'), getRequestStr('profile_birthday_day'),
                        getRequestStr('profile_birthday_year'))));
            } else {
                $this->oUserCurrent->setProfileBirthday(null);
            }
            /**
             * Проверяем информацию о себе
             */
            if (func_check(getRequestStr('profile_about'), 'text', 1, 3000)) {
                $this->oUserCurrent->setProfileAbout($this->Text_Parser(getRequestStr('profile_about')));
            } else {
                $this->oUserCurrent->setProfileAbout(null);
            }
            /**
             * Ставим дату последнего изменения профиля
             */
            $this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
            /**
             * Запускаем выполнение хуков
             */
            $this->Hook_Run('settings_profile_save_before',
                array('oUser' => $this->oUserCurrent, 'bError' => &$bError));
            /**
             * Сохраняем изменения профиля
             */
            if (!$bError) {
                if ($this->User_Update($this->oUserCurrent)) {
                    /**
                     * Создаем связь с гео-объектом
                     */
                    if ($oGeoObject) {
                        $this->Geo_CreateTarget($oGeoObject, 'user', $this->oUserCurrent->getId());
                        if ($oCountry = $oGeoObject->getCountry()) {
                            $this->oUserCurrent->setProfileCountry($oCountry->getName());
                        } else {
                            $this->oUserCurrent->setProfileCountry(null);
                        }
                        if ($oRegion = $oGeoObject->getRegion()) {
                            $this->oUserCurrent->setProfileRegion($oRegion->getName());
                        } else {
                            $this->oUserCurrent->setProfileRegion(null);
                        }
                        if ($oCity = $oGeoObject->getCity()) {
                            $this->oUserCurrent->setProfileCity($oCity->getName());
                        } else {
                            $this->oUserCurrent->setProfileCity(null);
                        }
                    } else {
                        $this->Geo_DeleteTargetsByTarget('user', $this->oUserCurrent->getId());
                        $this->oUserCurrent->setProfileCountry(null);
                        $this->oUserCurrent->setProfileRegion(null);
                        $this->oUserCurrent->setProfileCity(null);
                    }
                    $this->User_Update($this->oUserCurrent);

                    /**
                     * Обрабатываем дополнительные поля, type = ''
                     */
                    $aFields = $this->User_getUserFields('');
                    $aData = array();
                    foreach ($aFields as $iId => $aField) {
                        if (isset($_REQUEST['profile_user_field_' . $iId])) {
                            $aData[$iId] = getRequestStr('profile_user_field_' . $iId);
                        }
                    }
                    $this->User_setUserFieldsValues($this->oUserCurrent->getId(), $aData);
                    /**
                     * Динамические поля контактов, type = array('contact','social')
                     */
                    $aType = array('contact', 'social');
                    $aFields = $this->User_getUserFields($aType);
                    /**
                     * Удаляем все поля с этим типом
                     */
                    $this->User_DeleteUserFieldValues($this->oUserCurrent->getId(), $aType);
                    $aFieldsContactType = getRequest('profile_user_field_type');
                    $aFieldsContactValue = getRequest('profile_user_field_value');
                    if (is_array($aFieldsContactType)) {
                        foreach ($aFieldsContactType as $k => $v) {
                            $v = (string)$v;
                            if (isset($aFields[$v]) and isset($aFieldsContactValue[$k]) and is_string($aFieldsContactValue[$k])) {
                                $this->User_setUserFieldsValues($this->oUserCurrent->getId(),
                                    array($v => $aFieldsContactValue[$k]),
                                    Config::Get('module.user.userfield_max_identical'));
                            }
                        }
                    }
                    $this->Message_AddNoticeSingle($this->Lang_Get('common.success.save'));
                    $this->Hook_Run('settings_profile_save_after', array('oUser' => $this->oUserCurrent));
                } else {
                    $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
                }
            }
        }
        /**
         * Загружаем гео-объект привязки
         */
        $oGeoTarget = $this->Geo_GetTargetByTarget('user', $this->oUserCurrent->getId());
        $this->Viewer_Assign('oGeoTarget', $oGeoTarget);
        /**
         * Загружаем в шаблон список стран, регионов, городов
         */
        $aCountries = $this->Geo_GetCountries(array(), array('sort' => 'asc'), 1, 300);
        $this->Viewer_Assign('aGeoCountries', $aCountries['collection']);
        if ($oGeoTarget) {
            if ($oGeoTarget->getCountryId()) {
                $aRegions = $this->Geo_GetRegions(array('country_id' => $oGeoTarget->getCountryId()),
                    array('sort' => 'asc'), 1, 500);
                $this->Viewer_Assign('aGeoRegions', $aRegions['collection']);
            }
            if ($oGeoTarget->getRegionId()) {
                $aCities = $this->Geo_GetCities(array('region_id' => $oGeoTarget->getRegionId()),
                    array('sort' => 'asc'), 1, 500);
                $this->Viewer_Assign('aGeoCities', $aCities['collection']);
            }
        }

    }

    /**
     * Выполняется при завершении работы экшена
     *
     */
    public function EventShutdown()
    {
        $iCountTopicFavourite = $this->Topic_GetCountTopicsFavouriteByUserId($this->oUserCurrent->getId());
        $iCountTopicUser = $this->Topic_GetCountTopicsPersonalByUser($this->oUserCurrent->getId(), 1);
        $iCountCommentUser = $this->Comment_GetCountCommentsByUserId($this->oUserCurrent->getId(), 'topic');
        $iCountCommentFavourite = $this->Comment_GetCountCommentsFavouriteByUserId($this->oUserCurrent->getId());
        $iCountNoteUser = $this->User_GetCountUserNotesByUserId($this->oUserCurrent->getId());

        $this->Viewer_Assign('oUserProfile', $this->oUserCurrent);
        $this->Viewer_Assign('iCountWallUser',
            $this->Wall_GetCountWall(array('wall_user_id' => $this->oUserCurrent->getId(), 'pid' => null)));
        /**
         * Общее число публикация и избранного
         */
        $this->Viewer_Assign('iCountCreated', $iCountNoteUser + $iCountTopicUser + $iCountCommentUser);
        $this->Viewer_Assign('iCountFavourite', $iCountCommentFavourite + $iCountTopicFavourite);
        $this->Viewer_Assign('iCountFriendsUser', $this->User_GetCountUsersFriend($this->oUserCurrent->getId()));

        /**
         * Загружаем в шаблон необходимые переменные
         */
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
        $this->Viewer_Assign('sMenuProfileItemSelect', $this->sMenuProfileItemSelect);
        $this->Viewer_Assign('sMenuSubItemSelect', $this->sMenuSubItemSelect);

        $this->Hook_Run('action_shutdown_settings');
    }
}