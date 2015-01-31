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
 * Блок настройки списка пользователей в ленте
 *
 * @package application.blocks
 * @since 1.0
 */
class BlockUserfeedUsers extends Block
{
    /**
     * Запуск обработки
     */
    public function Exec()
    {
        /**
         * Пользователь авторизован?
         */
        if ($oUserCurrent = $this->User_getUserCurrent()) {
            /**
             * Получаем необходимые переменные и прогружаем в шаблон
             */
            $aResult = $this->Userfeed_getUserSubscribes($oUserCurrent->getId());
            $this->Viewer_Assign('users', $aResult['users']);
        }

        $this->SetTemplate('component@feed.block.users');
    }
}