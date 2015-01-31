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
 * Экшен обработки статистики юзеров, т.е. УРЛа вида /people/
 *
 * @package application.actions
 * @since 1.0
 */
class ActionPeople extends Action
{
    /**
     * Главное меню
     *
     * @var string
     */
    protected $sMenuHeadItemSelect = 'people';
    /**
     * Меню
     *
     * @var string
     */
    protected $sMenuItemSelect = 'all';

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($this->Lang_Get('user.users'));
    }

    /**
     * Регистрируем евенты
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^(index)?$/i', '/^(page([1-9]\d{0,5}))?$/i', '/^$/i', 'EventIndex');
        $this->AddEventPreg('/^ajax-search$/i', 'EventAjaxSearch');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Поиск пользователей по логину
     */
    protected function EventAjaxSearch()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Формируем фильтр
         */
        $aFilter = array(
            'activate' => 1
        );
        $sOrderWay = in_array(getRequestStr('order'), array('desc', 'asc')) ? getRequestStr('order') : 'desc';
        $sOrderField = in_array(getRequestStr('sort_by'), array(
            'user_rating',
            'user_date_register',
            'user_login',
            'user_profile_name'
        )) ? getRequestStr('sort_by') : 'user_rating';
        if (is_numeric(getRequestStr('next_page')) and getRequestStr('next_page') > 0) {
            $iPage = getRequestStr('next_page');
        } else {
            $iPage = 1;
        }
        /**
         * Получаем из реквеста первые буквы для поиска пользователей по логину
         */
        $sTitle = getRequest('sText');
        if (is_string($sTitle) and mb_strlen($sTitle, 'utf-8')) {
            $sTitle = str_replace(array('_', '%'), array('\_', '\%'), $sTitle);
        } else {
            $sTitle = '';
        }
        /**
         * Как именно искать: совпадение в любой части логина, или только начало или конец логина
         */
        if ($sTitle) {
            if (getRequest('isPrefix')) {
                $sTitle .= '%';
            } elseif (getRequest('isPostfix')) {
                $sTitle = '%' . $sTitle;
            } else {
                $sTitle = '%' . $sTitle . '%';
            }
        }
        if ($sTitle) {
            $aFilter['login'] = $sTitle;
        }
        /**
         * Пол
         */
        if (in_array(getRequestStr('sex'), array('man', 'woman', 'other'))) {
            $aFilter['profile_sex'] = getRequestStr('sex');
        }
        /**
         * Онлайн
         * date_last
         */
        if (getRequest('is_online')) {
            $aFilter['date_last_more'] = date('Y-m-d H:i:s', time() - Config::Get('module.user.time_onlive'));
        }
        /**
         * Geo привязка
         */
        if (getRequestStr('city')) {
            $aFilter['geo_city'] = getRequestStr('city');
        } elseif (getRequestStr('region')) {
            $aFilter['geo_region'] = getRequestStr('region');
        } elseif (getRequestStr('country')) {
            $aFilter['geo_country'] = getRequestStr('country');
        }
        /**
         * Ищем пользователей
         */
        $aResult = $this->User_GetUsersByFilter($aFilter, array($sOrderField => $sOrderWay), $iPage,
            Config::Get('module.user.per_page'));
        $bHideMore = $iPage * Config::Get('module.user.per_page') >= $aResult['count'];
        /**
         * Формируем ответ
         */
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('users', $aResult['collection'], true);
        $oViewer->Assign('oUserCurrent', $this->User_GetUserCurrent());
        $oViewer->Assign('textEmpty', $this->Lang_Get('search.alerts.empty'), true);
        $oViewer->Assign('useMore', true, true);
        $oViewer->Assign('hideMore', $bHideMore, true);
        $oViewer->Assign('searchCount', $aResult['count'], true);
        $this->Viewer_AssignAjax('html', $oViewer->Fetch("component@user.list"));
        /**
         * Для подгрузки
         */
        $this->Viewer_AssignAjax('count_loaded', count($aResult['collection']));
        $this->Viewer_AssignAjax('next_page', count($aResult['collection']) > 0 ? $iPage + 1 : $iPage);
        $this->Viewer_AssignAjax('hide', $bHideMore);
    }

    /**
     * Показываем юзеров
     *
     */
    protected function EventIndex()
    {
        /**
         * Получаем статистику
         */
        $this->GetStats();
        $aFilter = array(
            'activate' => 1
        );
        /**
         * Получаем список юзеров
         */
        $aResult = $this->User_GetUsersByFilter($aFilter, array('user_rating' => 'desc'), 1,
            Config::Get('module.user.per_page'));
        /**
         * Получаем алфавитный указатель на список пользователей
         */
        $aPrefixUser = $this->User_GetGroupPrefixUser(1);
        /**
         * Список используемых стран
         */
        $aCountriesUsed = $this->Geo_GetCountriesUsedByTargetType('user');
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('users', $aResult['collection']);
        $this->Viewer_Assign('searchCount', $aResult['count']);
        $this->Viewer_Assign('prefixUser', $aPrefixUser);
        $this->Viewer_Assign('countriesUsed', $aCountriesUsed);
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
    }

    /**
     * Получение статистики
     *
     */
    protected function GetStats()
    {
        /**
         * Статистика кто, где и т.п.
         */
        $aStat = $this->User_GetStatUsers();
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('usersStat', $aStat);
    }

    /**
     * Выполняется при завершении работы экшена
     *
     */
    public function EventShutdown()
    {
        /**
         * Загружаем в шаблон необходимые переменные
         */
        $this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
    }
}
