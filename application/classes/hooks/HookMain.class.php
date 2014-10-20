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
         * Проверяем наличие директории install
         */
        if (is_dir(rtrim(Config::Get('path.application.server'),
                    '/') . '/install') && (!isset($_SERVER['HTTP_APP_ENV']) or $_SERVER['HTTP_APP_ENV'] != 'test')
        ) {
            if (Config::Get('install_completed')) {
                $this->Message_AddErrorSingle($this->Lang_Get('install_directory_exists'));
                Router::Action('error');
            } else {
                Router::Location(rtrim(str_replace('index.php', '', $_SERVER['PHP_SELF']),
                        '/\\') . '/application/install/');
            }
        }
        /**
         * Проверка на закрытый режим
         */
        $oUserCurrent = $this->User_GetUserCurrent();
        if (!$oUserCurrent and Config::Get('general.close')) {
            $bAllow = false;
            $aExceptions = (array)Config::Get('general.close_exceptions');
            foreach ($aExceptions as $mKey => $sAction) {
                if (is_int($mKey)) {
                    $aEvents = array();
                } else {
                    $aEvents = $sAction;
                    $sAction = $mKey;
                }
                if (Router::GetAction() == $sAction) {
                    if ($aEvents) {
                        if (in_array(Router::GetActionEvent(), $aEvents)) {
                            $bAllow = true;
                            break;
                        }
                    } else {
                        $bAllow = true;
                        break;
                    }
                }
            }
            if (!$bAllow) {
                Router::Action('login');
            }
        }
        /**
         * Запуск обработки сборщика
         */
        $this->Ls_SenderRun();
    }
}