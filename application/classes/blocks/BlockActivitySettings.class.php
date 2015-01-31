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
 * Блок настройки ленты активности
 *
 * @package application.blocks
 * @since 1.0
 */
class BlockActivitySettings extends Block
{
    /**
     * Запуск обработки
     */
    public function Exec()
    {
        /**
         * пользователь авторизован?
         */
        if ($oUserCurrent = $this->User_getUserCurrent()) {
            $this->Viewer_Assign('types', $this->Stream_getEventTypes());
            $this->Viewer_Assign('typesActive', $this->Stream_getTypesList($oUserCurrent->getId()));
        }

        $this->SetTemplate('component@activity.block.settings');
    }
}