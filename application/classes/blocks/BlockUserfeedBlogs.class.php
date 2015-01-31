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
 * Блок настройки списка блогов в ленте
 *
 * @package application.blocks
 * @since 1.0
 */
class BlockUserfeedBlogs extends Block
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
            $aUserSubscribes = $this->Userfeed_getUserSubscribes($oUserCurrent->getId());
            /**
             * Получаем список ID блогов, в которых состоит пользователь
             */
            $aBlogsId = $this->Blog_GetBlogUsersByUserId($oUserCurrent->getId(), array(
                    ModuleBlog::BLOG_USER_ROLE_USER,
                    ModuleBlog::BLOG_USER_ROLE_MODERATOR,
                    ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR
                ), true);
            /**
             * Получаем список ID блогов, которые создал пользователь
             */
            $aBlogsOwnerId = $this->Blog_GetBlogsByOwnerId($oUserCurrent->getId(), true);
            $aBlogsId = array_merge($aBlogsId, $aBlogsOwnerId);

            $aBlogs = $this->Blog_GetBlogsAdditionalData($aBlogsId, array('owner' => array()),
                array('blog_title' => 'asc'));
            /**
             * Выводим в шаблон
             */
            $this->Viewer_Assign('blogsSubscribed', $aUserSubscribes['blogs']);
            $this->Viewer_Assign('blogsJoined', $aBlogs);
        }

        $this->SetTemplate('component@feed.block.blogs');
    }
}