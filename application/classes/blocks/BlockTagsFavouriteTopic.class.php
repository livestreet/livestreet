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
 * Обрабатывает блок облака тегов для избранного
 *
 * @package application.blocks
 * @since 1.0
 */
class BlockTagsFavouriteTopic extends Block
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
            if (!($oUser = $this->getParam('user'))) {
                $oUser = $oUserCurrent;
            }
            /**
             * Получаем список тегов пользователя
             */
            $aTags = $this->Favourite_GetGroupTags($oUser->getId(), 'topic', true, 70);
            /**
             * Расчитываем логарифмическое облако тегов
             */
            $this->Tools_MakeCloud($aTags);
            /**
             * Устанавливаем шаблон вывода
             */
            $this->Viewer_Assign('tags', $aTags, true);
            $this->Viewer_Assign('user', $oUser, true);
            $this->Viewer_Assign('activeTag', $this->getParam('activeTag'), true);

            $this->SetTemplate('component@tags-favourite.cloud');
        }
    }
}