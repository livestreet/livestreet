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
 * Обработка блока с рейтингом блогов
 *
 * @package application.blocks
 * @since 1.0
 */
class BlockBlogs extends Block
{
    /**
     * Запуск обработки
     */
    public function Exec()
    {
        /**
         * Получаем список блогов
         */
        if ($aResult = $this->Blog_GetBlogsRating(1, Config::Get('block.blogs.row'))) {
            $aBlogs = $aResult['collection'];
            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('aBlogs', $aBlogs);
            /**
             * Формируем результат в виде шаблона и возвращаем
             */
            $sTextResult = $oViewer->Fetch("component@blog.top");
            $this->Viewer_Assign('sBlogsTop', $sTextResult);
        }

        $this->SetTemplate('component@blog.block.blogs');
    }
}