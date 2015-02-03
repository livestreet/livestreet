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
 * Регистрация основных хуков
 *
 * @package application.hooks
 * @since 1.0
 */
class HookMain extends Hook
{
    /**
     * Регистрируем хуки
     */
    public function RegisterHook()
    {
        $this->AddHook('init_action', 'InitAction', __CLASS__, 1000);
    }

    /**
     * Обработка хука инициализации экшенов
     */
    public function InitAction()
    {
        /**
         * Проверка на закрытый режим
         */
        $oUserCurrent = $this->User_GetUserCurrent();
        if (!$oUserCurrent and Config::Get('general.close') and !Router::CheckIsCurrentAction((array)Config::Get('general.close_exceptions'))) {
            Router::Action('login');
        }
        $this->LoadDefaultJsVar();
        /**
         * Запуск обработки сборщика
         */
        $this->Ls_SenderRun();
    }

    public function LoadDefaultJsVar()
    {
        $this->Viewer_AssignJs('recaptcha.site_key', Config::Get('module.validate.recaptcha.site_key'));
    }
}