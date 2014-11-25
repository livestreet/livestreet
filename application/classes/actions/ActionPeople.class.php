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

        $this->AddEventPreg('/^country$/i', '/^\d+$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventCountry');
        $this->AddEventPreg('/^city$/i', '/^\d+$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventCity');
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
        if (is_numeric(getRequestStr('pageNext')) and getRequestStr('pageNext') > 0) {
            $iPage = getRequestStr('pageNext');
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
        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("components/user/user-list.tpl"));
        /**
         * Для подгрузки
         */
        $this->Viewer_AssignAjax('count_loaded', count($aResult['collection']));
        $this->Viewer_AssignAjax('pageNext', count($aResult['collection']) > 0 ? $iPage + 1 : $iPage);
        $this->Viewer_AssignAjax('bHideMore', $bHideMore);
    }

    /**
     * Показывает юзеров по стране
     *
     */
    protected function EventCountry()
    {
        $this->sMenuItemSelect = 'country';
        /**
         * Страна существует?
         */
        if (!($oCountry = $this->Geo_GetCountryById($this->getParam(0)))) {
            return parent::EventNotFound();
        }
        /**
         * Получаем статистику
         */
        $this->GetStats();
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(1, 2) ? $this->GetParamEventMatch(1, 2) : 1;
        /**
         * Получаем список связей пользователей со страной
         */
        $aResult = $this->Geo_GetTargets(array('country_id' => $oCountry->getId(), 'target_type' => 'user'), $iPage,
            Config::Get('module.user.per_page'));
        $aUsersId = array();
        foreach ($aResult['collection'] as $oTarget) {
            $aUsersId[] = $oTarget->getTargetId();
        }
        $aUsersCountry = $this->User_GetUsersAdditionalData($aUsersId);
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.user.per_page'),
            Config::Get('pagination.pages.count'),
            Router::GetPath('people') . $this->sCurrentEvent . '/' . $oCountry->getId());
        /**
         * Загружаем переменные в шаблон
         */
        if ($aUsersCountry) {
            $this->Viewer_Assign('aPaging', $aPaging);
        }
        $this->Viewer_Assign('oCountry', $oCountry);
        $this->Viewer_Assign('aUsersCountry', $aUsersCountry);
    }

    /**
     * Показывает юзеров по городу
     *
     */
    protected function EventCity()
    {
        $this->sMenuItemSelect = 'city';
        /**
         * Город существует?
         */
        if (!($oCity = $this->Geo_GetCityById($this->getParam(0)))) {
            return parent::EventNotFound();
        }
        /**
         * Получаем статистику
         */
        $this->GetStats();
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(1, 2) ? $this->GetParamEventMatch(1, 2) : 1;
        /**
         * Получаем список юзеров
         */
        $aResult = $this->Geo_GetTargets(array('city_id' => $oCity->getId(), 'target_type' => 'user'), $iPage,
            Config::Get('module.user.per_page'));
        $aUsersId = array();
        foreach ($aResult['collection'] as $oTarget) {
            $aUsersId[] = $oTarget->getTargetId();
        }
        $aUsersCity = $this->User_GetUsersAdditionalData($aUsersId);
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.user.per_page'),
            Config::Get('pagination.pages.count'),
            Router::GetPath('people') . $this->sCurrentEvent . '/' . $oCity->getId());
        /**
         * Загружаем переменные в шаблон
         */
        if ($aUsersCity) {
            $this->Viewer_Assign('aPaging', $aPaging);
        }
        $this->Viewer_Assign('oCity', $oCity);
        $this->Viewer_Assign('aUsersCity', $aUsersCity);
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
        $aCountriesUsed=$this->Geo_GetCountriesUsedByTargetType('user');
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('aUsers', $aResult['collection']);
        $this->Viewer_Assign('iSearchCount', $aResult['count']);
        $this->Viewer_Assign('aPrefixUser', $aPrefixUser);
        $this->Viewer_Assign('aCountriesUsed', $aCountriesUsed);
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
        $this->Viewer_Assign('aStat', $aStat);
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
