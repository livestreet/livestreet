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
 * Обрабатывает авторизацию/регистрацию
 *
 * @package application.actions
 * @since 1.0
 */
class ActionAuth extends Action
{
    /**
     * Инициализация
     *
     */
    public function Init()
    {
        /**
         * Если включены инвайты то перенаправляем на страницу регистрации по инвайтам
         */
        if (!$this->User_IsAuthorization() and Config::Get('general.reg.invite') and in_array(Router::GetActionEvent(),
                array('register', 'ajax-register')) and !$this->CheckInviteRegister()
        ) {
            return Router::Action('auth', 'invite');
        }
        /**
         * Устанавливаем дефолтный евент
         */
        $this->SetDefaultEvent('login');
        /**
         * Отключаем отображение статистики выполнения
         */
        Router::SetIsShowStats(false);
    }

    /**
     * Регистрируем евенты
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('login', 'EventLogin');
        $this->AddEvent('logout', 'EventLogout');
        $this->AddEvent('password-reset', 'EventPasswordReset');
        $this->AddEvent('register', 'EventRegister');
        $this->AddEvent('register-confirm', 'EventRegisterConfirm');
        $this->AddEvent('activate', 'EventActivate');
        $this->AddEvent('reactivation', 'EventReactivation');
        $this->AddEvent('invite', 'EventInvite');
        $this->AddEventPreg('/^referral$/i', '/^[\w\-\_]{1,200}$/i', 'EventReferral');

        $this->AddEvent('ajax-login', 'EventAjaxLogin');
        $this->AddEvent('ajax-password-reset', 'EventAjaxPasswordReset');
        $this->AddEvent('ajax-validate-fields', 'EventAjaxValidateFields');
        $this->AddEvent('ajax-register', 'EventAjaxRegister');
        $this->AddEvent('ajax-reactivation', 'EventAjaxReactivation');
    }

    /**
     * Ajax авторизация
     */
    protected function EventAjaxLogin()
    {
        /**
         * Устанвливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Логин и пароль являются строками?
         */
        if (!is_string(getRequest('login')) or !is_string(getRequest('password'))) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
            return;
        }
        /**
         * Проверяем есть ли такой юзер по логину
         */
        if ((func_check(getRequest('login'),
                    'mail') and $oUser = $this->User_GetUserByMail(getRequest('login'))) or $oUser = $this->User_GetUserByLogin(getRequest('login'))
        ) {
            /**
             *  Выбираем сценарий валидации
             */
            $oUser->_setValidateScenario('signIn');
            /**
             * Заполняем поля (данные)
             */
            $oUser->setCaptcha(getRequestStr('captcha'));
            /**
             * Запускаем валидацию
             */
            if ($oUser->_Validate()) {
                /**
                 * Сверяем хеши паролей и проверяем активен ли юзер
                 */

                if ($oUser->getPassword() == func_encrypt(getRequest('password'))) {
                    if (!$oUser->getActivate()) {
                        $this->Message_AddErrorSingle($this->Lang_Get('auth.login.notices.error_not_activated',
                            array('reactivation_path' => Router::GetPath('auth/reactivation'))));
                        return;
                    }
                    $bRemember = getRequest('remember', false) ? true : false;
                    /**
                     * Убиваем каптчу
                     */
                    $this->Session_Drop('captcha_keystring_user_auth');
                    /**
                     * Авторизуем
                     */
                    $this->User_Authorization($oUser, $bRemember);
                    /**
                     * Определяем редирект
                     */
                    $sUrl = Config::Get('module.user.redirect_after_login');
                    if (getRequestStr('return-path')) {
                        $sUrl = getRequestStr('return-path');
                    }
                    $this->Viewer_AssignAjax('sUrlRedirect', $sUrl ? $sUrl : Router::GetPath('/'));
                    return;
                }
            } else {
                /**
                 * Получаем ошибки
                 */
                $this->Viewer_AssignAjax('aErrors', $oUser->_getValidateErrors());
            }
        }
        $this->Message_AddErrorSingle($this->Lang_Get('auth.login.notices.error_login'));
    }

    /**
     * Обрабатываем процесс залогинивания
     * По факту только отображение шаблона, дальше вступает в дело Ajax
     *
     */
    protected function EventLogin()
    {
        /**
         * Если уже авторизирован
         */
        if ($this->User_IsAuthorization()) {
            Router::Location(Router::GetPath('/'));
        }
        $this->Viewer_AddHtmlTitle($this->Lang_Get('auth.login.title'));
    }

    /**
     * Обрабатываем процесс разлогинивания
     *
     */
    protected function EventLogout()
    {
        $this->Security_ValidateSendForm();
        if ($this->User_GetUserCurrent()) {
            $this->User_Logout();
        }
        Router::LocationAction('/');
    }

    /**
     * Ajax запрос на восстановление пароля
     */
    protected function EventAjaxPasswordReset()
    {
        /**
         * Устанвливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Пользователь с таким емайлом существует?
         */
        if ((func_check(getRequestStr('mail'), 'mail') and $oUser = $this->User_GetUserByMail(getRequestStr('mail')))) {
            /**
             * Формируем и отправляем ссылку на смену пароля
             */
            $oReminder = Engine::GetEntity('User_Reminder');
            $oReminder->setCode(func_generator(32));
            $oReminder->setDateAdd(date("Y-m-d H:i:s"));
            $oReminder->setDateExpire(date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 7));
            $oReminder->setDateUsed(null);
            $oReminder->setIsUsed(0);
            $oReminder->setUserId($oUser->getId());
            if ($this->User_AddReminder($oReminder)) {
                $this->Message_AddNotice($this->Lang_Get('auth.reset.notices.success_send_link'));
                $this->User_SendNotifyReminderCode($oUser, $oReminder);
                return;
            }
        }
        $this->Message_AddError($this->Lang_Get('auth.notices.error_bad_email'), $this->Lang_Get('common.error.error'));
    }

    /**
     * Обработка напоминания пароля, подтверждение смены пароля
     *
     */
    protected function EventPasswordReset()
    {
        if ($this->User_IsAuthorization()) {
            Router::LocationAction('/');
        }
        $this->SetTemplateAction('reset');
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($this->Lang_Get('auth.reset.title'));
        /**
         * Проверка кода на восстановление пароля и генерация нового пароля
         */
        if (func_check($this->GetParam(0), 'md5')) {
            /**
             * Проверка кода подтверждения
             */
            if ($oReminder = $this->User_GetReminderByCode($this->GetParam(0))) {
                if (!$oReminder->getIsUsed() and strtotime($oReminder->getDateExpire()) > time() and $oUser = $this->User_GetUserById($oReminder->getUserId())) {
                    $sNewPassword = func_generator(7);
                    $oUser->setPassword(func_encrypt($sNewPassword));
                    if ($this->User_Update($oUser)) {
                        $oReminder->setDateUsed(date("Y-m-d H:i:s"));
                        $oReminder->setIsUsed(1);
                        $this->User_UpdateReminder($oReminder);
                        $this->User_SendNotifyReminderPassword($oUser, $sNewPassword);
                        $this->SetTemplateAction('reset_confirm');
                        return;
                    }
                }
            }
            $this->Message_AddErrorSingle($this->Lang_Get('auth.reset.alerts.error_bad_code'),
                $this->Lang_Get('common.error.error'));
            return Router::Action('error');
        }
    }


    /**
     * Ajax валидация форму регистрации
     */
    protected function EventAjaxValidateFields()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Создаем объект пользователя и устанавливаем сценарий валидации
         */
        $oUser = Engine::GetEntity('ModuleUser_EntityUser');
        $oUser->_setValidateScenario('registration');
        /**
         * Пробегаем по переданным полям/значениям и валидируем их каждое в отдельности
         */
        $aFields = getRequest('fields');
        if (is_array($aFields)) {
            foreach ($aFields as $aField) {
                if (isset($aField['field']) and isset($aField['value'])) {
                    $this->Hook_Run('registration_validate_field', array('aField' => &$aField, 'oUser' => $oUser));

                    $sField = $aField['field'];
                    $sValue = $aField['value'];
                    /**
                     * Список полей для валидации
                     */
                    switch ($sField) {
                        case 'login':
                            $oUser->setLogin($sValue);
                            break;
                        case 'mail':
                            $oUser->setMail($sValue);
                            break;
                        case 'captcha':
                            $oUser->setCaptcha($sValue);
                            break;
                        case 'password':
                            $oUser->setPassword($sValue);
                            break;
                        case 'password_confirm':
                            $oUser->setPasswordConfirm($sValue);
                            $oUser->setPassword(isset($aField['params']['password']) ? $aField['params']['password'] : null);
                            break;
                        default:
                            continue;
                            break;
                    }
                    /**
                     * Валидируем поле
                     */
                    $oUser->_Validate(array($sField), false);
                }
            }
        }
        /**
         * Возникли ошибки?
         */
        if ($oUser->_hasValidateErrors()) {
            /**
             * Получаем ошибки
             */
            $this->Viewer_AssignAjax('aErrors', $oUser->_getValidateErrors());
        }
    }

    /**
     * Обработка Ajax регистрации
     */
    protected function EventAjaxRegister()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Создаем объект пользователя и устанавливаем сценарий валидации
         */
        $oUser = Engine::GetEntity('ModuleUser_EntityUser');
        $oUser->_setValidateScenario('registration');
        /**
         * Заполняем поля (данные)
         */
        $oUser->setLogin(getRequestStr('login'));
        $oUser->setMail(getRequestStr('mail'));
        $oUser->setPassword(getRequestStr('password'));
        $oUser->setPasswordConfirm(getRequestStr('password_confirm'));
        $oUser->setCaptcha(getRequestStr('captcha'));
        $oUser->setDateRegister(date("Y-m-d H:i:s"));
        $oUser->setIpRegister(func_getIp());
        /**
         * Если используется активация, то генерим код активации
         */
        if (Config::Get('general.reg.activation')) {
            $oUser->setActivate(0);
            $oUser->setActivateKey(md5(func_generator() . time()));
        } else {
            $oUser->setActivate(1);
            $oUser->setActivateKey(null);
        }
        $this->Hook_Run('registration_validate_before', array('oUser' => $oUser));
        /**
         * Запускаем валидацию
         */
        if ($oUser->_Validate()) {
            $this->Hook_Run('registration_validate_after', array('oUser' => $oUser));
            $oUser->setPassword(func_encrypt($oUser->getPassword()));
            if ($this->User_Add($oUser)) {
                $this->Hook_Run('registration_after', array('oUser' => $oUser));
                /**
                 * Убиваем каптчу
                 */
                $this->Session_Drop('captcha_keystring_user_signup');
                /**
                 * Подписываем пользователя на дефолтные события в ленте активности
                 */
                $this->Stream_switchUserEventDefaultTypes($oUser->getId());
                /**
                 * Если юзер зарегистрировался по приглашению то обновляем инвайт
                 */
                if ($sCode = $this->GetInviteRegister()) {
                    $this->Invite_UseCode($sCode, $oUser);
                }
                /**
                 * Если стоит регистрация с активацией то проводим её
                 */
                if (Config::Get('general.reg.activation')) {
                    /**
                     * Отправляем на мыло письмо о подтверждении регистрации
                     */
                    $this->User_SendNotifyRegistrationActivate($oUser, getRequestStr('password'));
                    $this->Viewer_AssignAjax('sUrlRedirect', Router::GetPath('auth/register-confirm'));
                } else {
                    $this->User_SendNotifyRegistration($oUser, getRequestStr('password'));
                    $oUser = $this->User_GetUserById($oUser->getId());
                    /**
                     * Сразу авторизуем
                     */
                    $this->User_Authorization($oUser, false);
                    $this->DropInviteRegister();
                    /**
                     * Определяем URL для редиректа после авторизации
                     */
                    $sUrl = Config::Get('module.user.redirect_after_registration');
                    if (getRequestStr('return-path')) {
                        $sUrl = getRequestStr('return-path');
                    }
                    $this->Viewer_AssignAjax('sUrlRedirect', $sUrl ? $sUrl : Router::GetPath('/'));
                    $this->Message_AddNoticeSingle($this->Lang_Get('auth.registration.notices.success'));
                }
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
                return;
            }
        } else {
            /**
             * Получаем ошибки
             */
            $this->Viewer_AssignAjax('aErrors', $oUser->_getValidateErrors());
        }
    }

    /**
     * Показывает страничку регистрации
     * Просто вывод шаблона
     */
    protected function EventRegister()
    {
        if ($this->User_IsAuthorization()) {
            Router::LocationAction('/');
        }
    }

    /**
     * Обработка реферального кода
     */
    protected function EventReferral()
    {
        if ($this->User_IsAuthorization()) {
            Router::LocationAction('/');
        }
        /**
         * Смотрим наличие реферального кода и сохраняем его в сессию
         */
        if ($sCode = $this->GetParam(0)) {
            if ($iType = $this->Invite_GetInviteTypeByCode($sCode)) {
                if (!Config::Get('general.reg.invite') or $iType != ModuleInvite::INVITE_TYPE_REFERRAL) {
                    $this->Session_Set('invite_code', $sCode);
                }
            }
        }
        Router::LocationAction('auth/register');
    }

    /**
     * Обрабатывает активацию аккаунта
     */
    protected function EventActivate()
    {
        if ($this->User_IsAuthorization()) {
            Router::LocationAction('/');
        }
        $bError = false;
        /**
         * Проверяет передан ли код активации
         */
        $sActivateKey = $this->GetParam(0);
        if (!func_check($sActivateKey, 'md5')) {
            $bError = true;
        }
        /**
         * Проверяет верный ли код активации
         */
        if (!($oUser = $this->User_GetUserByActivateKey($sActivateKey))) {
            $bError = true;
        }
        /**
         *
         */
        if ($oUser and $oUser->getActivate()) {
            $this->Message_AddErrorSingle($this->Lang_Get('auth.registration.notices.error_reactivate'),
                $this->Lang_Get('common.error.error'));
            return Router::Action('error');
        }
        /**
         * Если что то не то
         */
        if ($bError) {
            $this->Message_AddErrorSingle($this->Lang_Get('auth.registration.notices.error_code'),
                $this->Lang_Get('common.error.error'));
            return Router::Action('error');
        }
        /**
         * Активируем
         */
        $oUser->setActivate(1);
        $oUser->setDateActivate(date("Y-m-d H:i:s"));
        /**
         * Сохраняем юзера
         */
        if ($this->User_Update($oUser)) {
            $this->User_Authorization($oUser, false);
            $this->DropInviteRegister();
            return;
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
            return Router::Action('error');
        }
    }

    /**
     * Повторный запрос активации
     */
    protected function EventReactivation()
    {
        if ($this->User_IsAuthorization()) {
            Router::LocationAction('/');
        }

        $this->Viewer_AddHtmlTitle($this->Lang_Get('auth.reactivation.title'));
    }

    /**
     *  Ajax повторной активации
     */
    protected function EventAjaxReactivation()
    {
        $this->Viewer_SetResponseAjax('json');

        if ((func_check(getRequestStr('mail'), 'mail') and $oUser = $this->User_GetUserByMail(getRequestStr('mail')))) {
            if ($oUser->getActivate()) {
                $this->Message_AddErrorSingle($this->Lang_Get('auth.registration.notices.error_reactivate'));
                return;
            } else {
                $oUser->setActivateKey(md5(func_generator() . time()));
                if ($this->User_Update($oUser)) {
                    $this->Message_AddNotice($this->Lang_Get('auth.reactivation.notices.success'));
                    $this->User_SendNotifyReactivationCode($oUser);
                    return;
                }
            }
        }

        $this->Message_AddErrorSingle($this->Lang_Get('auth.notices.error_bad_email'));
    }

    /**
     * Просто выводит шаблон для подтверждения регистрации
     *
     */
    protected function EventRegisterConfirm()
    {
        $this->SetTemplateAction('confirm');
    }

    protected function EventInvite()
    {
        if ($this->User_IsAuthorization()) {
            Router::LocationAction('/');
        }
        $this->SetTemplateAction('invite');

        if (isPost('submit_invite')) {
            /**
             * Проверяем валидность кода
             */
            if ($this->Invite_CheckCode(getRequestStr('invite_code'), ModuleInvite::INVITE_TYPE_CODE)) {
                Router::Location($this->Invite_GetReferralLink(null, getRequestStr('invite_code')));
            } else {
                $this->Message_AddError($this->Lang_Get('auth.invite.alerts.error_code'), $this->Lang_Get('common.error.error'));
            }
        }
    }

    /**
     * Пытается ли юзер зарегистрироваться с помощью кода приглашения
     *
     * @return bool
     */
    protected function CheckInviteRegister()
    {
        if ($this->GetInviteRegister()) {
            return true;
        }
        return false;
    }

    /**
     * Вожвращает код приглашения из сессии
     *
     * @return string
     */
    protected function GetInviteRegister()
    {
        return $this->Session_Get('invite_code');
    }

    /**
     * Удаляет код приглашения из сессии
     */
    protected function DropInviteRegister()
    {
        $this->Session_Drop('invite_code');
    }
}