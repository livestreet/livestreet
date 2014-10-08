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
 * Обрабатывые авторизацию
 *
 * @package application.actions
 * @since 1.0
 */
class ActionLogin extends Action
{
    /**
     * Инициализация
     *
     */
    public function Init()
    {
        /**
         * Устанавливаем дефолтный евент
         */
        $this->SetDefaultEvent('index');
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
        $this->AddEvent('index', 'EventLogin');
        $this->AddEvent('exit', 'EventExit');
        $this->AddEvent('reset', 'EventReset');

        $this->AddEvent('ajax-login', 'EventAjaxLogin');
        $this->AddEvent('ajax-reset', 'EventAjaxReset');
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
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
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
                        $this->Message_AddErrorSingle($this->Lang_Get('auth.notices.not_activated',
                                array('reactivation_path' => Router::GetPath('registration') . 'reactivation')));
                        return;
                    }
                    $bRemember = getRequest('remember', false) ? true : false;
                    /**
                     * Убиваем каптчу
                     */
                    unset($_SESSION['captcha_keystring_user_auth']);
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
        if ($this->User_GetUserCurrent()) {
            Router::Location(Router::GetPath('/'));
        }
        $this->Viewer_AddHtmlTitle($this->Lang_Get('auth.login.title'));
    }

    /**
     * Обрабатываем процесс разлогинивания
     *
     */
    protected function EventExit()
    {
        $this->Security_ValidateSendForm();
        $this->User_Logout();
        Router::Location(Router::GetPath('/'));
    }

    /**
     * Ajax запрос на восстановление пароля
     */
    protected function EventAjaxReset()
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
                $this->Message_AddNotice($this->Lang_Get('auth.notices.success_send_password'));
                $this->Notify_SendReminderCode($oUser, $oReminder);
                return;
            }
        }
        $this->Message_AddError($this->Lang_Get('auth.notices.error_bad_email'), $this->Lang_Get('error'));
    }

    /**
     * Обработка напоминания пароля, подтверждение смены пароля
     *
     */
    protected function EventReset()
    {
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
                        $this->Notify_SendReminderPassword($oUser, $sNewPassword);
                        $this->SetTemplateAction('reset_confirm');
                        return;
                    }
                }
            }
            $this->Message_AddErrorSingle($this->Lang_Get('auth.reset.alerts.error_bad_code'),
                $this->Lang_Get('error'));
            return Router::Action('error');
        }
    }
}