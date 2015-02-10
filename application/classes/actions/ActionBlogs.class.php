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
 * Экшен обработки УРЛа вида /comments/
 *
 * @package application.actions
 * @since 1.0
 */
class ActionBlogs extends Action
{
    /**
     * Текущий пользователь
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;

    /**
     * Инициализация
     */
    public function Init()
    {
        /**
         * Загружаем в шаблон JS текстовки
         */
        $this->Lang_AddLangJs(array(
            'blog.join.join',
            'blog.join.leave'
        ));
        /**
         * Получаем текущего пользователя
         */
        $this->oUserCurrent = $this->User_GetUserCurrent();
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($this->Lang_Get('blog.menu.all_list'));
    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^(page([1-9]\d{0,5}))?$/i', 'EventShowBlogs');
        $this->AddEventPreg('/^ajax-search$/i', 'EventAjaxSearch');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Поиск блогов по названию
     */
    protected function EventAjaxSearch()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Фильтр
         */
        $aFilter = array(
            'exclude_type' => 'personal',
        );
        $sOrderWay = in_array(getRequestStr('order'), array('desc', 'asc')) ? getRequestStr('order') : 'desc';
        $sOrderField = in_array(getRequestStr('sort_by'), array(
            'blog_id',
            'blog_title',
            'blog_count_user',
            'blog_count_topic'
        )) ? getRequestStr('sort_by') : 'blog_count_user';
        if (is_numeric(getRequestStr('next_page')) and getRequestStr('next_page') > 0) {
            $iPage = getRequestStr('next_page');
        } else {
            $iPage = 1;
        }
        /**
         * Получаем из реквеста первые буквы блога
         */
        if ($sTitle = getRequestStr('sText')) {
            $sTitle = str_replace('%', '', $sTitle);
        } else {
            $sTitle = '';
        }
        if ($sTitle) {
            $aFilter['title'] = "%{$sTitle}%";
        }
        /**
         * Категории
         */
        if (getRequestStr('category') and $oCategory = $this->Category_GetCategoryById(getRequestStr('category'))) {
            /**
             * Получаем ID всех блогов
             * По сути это костыль, но т.к. блогов обычно не много, то норм
             */
            $aBlogIds = $this->Blog_GetTargetIdsByCategory($oCategory, 1, 1000, true);
            $aFilter['id'] = $aBlogIds ? $aBlogIds : array(0);
        }
        /**
         * Тип
         */
        if (in_array(getRequestStr('type'), array('open', 'close'))) {
            $aFilter['type'] = getRequestStr('type');
        }
        /**
         * Принадлежность
         */
        if ($this->oUserCurrent) {
            if (getRequestStr('relation') == 'my') {
                $aFilter['user_owner_id'] = $this->oUserCurrent->getId();
            } elseif (getRequestStr('relation') == 'join') {
                $aFilter['roles']=array(ModuleBlog::BLOG_USER_ROLE_USER,ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR,ModuleBlog::BLOG_USER_ROLE_MODERATOR);
            }
        }
        /**
         * Ищем блоги
         */
        $aResult = $this->Blog_GetBlogsByFilter($aFilter, array($sOrderField => $sOrderWay), $iPage,
            Config::Get('module.blog.per_page'));
        $bHideMore = $iPage * Config::Get('module.blog.per_page') >= $aResult['count'];
        /**
         * Формируем и возвращает ответ
         */
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('blogs', $aResult['collection'], true);
        $oViewer->Assign('oUserCurrent', $this->User_GetUserCurrent());
        $oViewer->Assign('textEmpty', $this->Lang_Get('search.alerts.empty'), true);
        $oViewer->Assign('useMore', true, true);
        $oViewer->Assign('hideMore', $bHideMore, true);
        $oViewer->Assign('searchCount', $aResult['count'], true);
        $this->Viewer_AssignAjax('html', $oViewer->Fetch("component@blog.list"));
        /**
         * Для подгрузки
         */
        $this->Viewer_AssignAjax('count_loaded', count($aResult['collection']));
        $this->Viewer_AssignAjax('next_page', count($aResult['collection']) > 0 ? $iPage + 1 : $iPage);
        $this->Viewer_AssignAjax('hide', $bHideMore);
    }

    /**
     * Отображение списка блогов
     */
    protected function EventShowBlogs()
    {
        /**
         * Фильтр поиска блогов
         */
        $aFilter = array(
            'exclude_type' => 'personal'
        );
        /**
         * Получаем список блогов
         */
        $aResult = $this->Blog_GetBlogsByFilter($aFilter, array('blog_count_user' => 'desc'), 1,
            Config::Get('module.blog.per_page'));
        $aBlogs = $aResult['collection'];
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('blogs', $aBlogs);
        $this->Viewer_Assign('searchCount', $aResult['count']);
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
    }
}