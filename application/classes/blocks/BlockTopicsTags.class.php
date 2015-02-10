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
 * Обрабатывает блок облака тегов
 *
 * @package application.blocks
 * @since 1.0
 */
class BlockTopicsTags extends Block
{
    /**
     * Запуск обработки
     */
    public function Exec()
    {
        /**
         * Получаем список тегов
         */
        $aTags = $this->Topic_GetOpenTopicTags(Config::Get('block.tags.tags_count'));
        /**
         * Расчитываем логарифмическое облако тегов
         */
        if ($aTags) {
            $this->Tools_MakeCloud($aTags);
            /**
             * Устанавливаем шаблон вывода
             */
            $this->Viewer_Assign('tags', $aTags, true);
        }
        /**
         * Теги пользователя
         */
        if ($oUserCurrent = $this->User_getUserCurrent()) {
            $aTags = $this->Topic_GetOpenTopicTags(Config::Get('block.tags.personal_tags_count'),
                $oUserCurrent->getId());
            /**
             * Расчитываем логарифмическое облако тегов
             */
            if ($aTags) {
                $this->Tools_MakeCloud($aTags);
                /**
                 * Устанавливаем шаблон вывода
                 */
                $this->Viewer_Assign('tagsUser', $aTags, true);
            }
        }

        $this->SetTemplate('component@topic.block.tags');
    }
}