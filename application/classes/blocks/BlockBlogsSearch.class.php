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
 * Обрабатывает блок категорий для блогов
 *
 * @package application.blocks
 * @since 2.0
 */
class BlockBlogsSearch extends Block
{
    /**
     * Запуск обработки
     */
    public function Exec()
    {
        if (!Config::Get('module.blog.category_allow')) {
            return;
        }
        $aCategories = $this->Blog_GetCategoriesTree();
        $aBlogsAll = $this->Blog_GetBlogsByFilter(array('exclude_type' => 'personal'), array(), 1, 1, array());
        $this->Viewer_Assign('aBlogCategories', $aCategories);
        $this->Viewer_Assign('iCountBlogsAll', $aBlogsAll['count']);
        $this->SetTemplate('component@blog.block.search');
    }
}