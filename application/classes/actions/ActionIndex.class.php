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
 * Обработка главной страницы, т.е. УРЛа вида /index/
 *
 * @package application.actions
 * @since 1.0
 */
class ActionIndex extends Action
{
    /**
     * Главное меню
     *
     * @var string
     */
    protected $sMenuHeadItemSelect = 'blog';
    /**
     * Меню
     *
     * @var string
     */
    protected $sMenuItemSelect = 'index';
    /**
     * Субменю
     *
     * @var string
     */
    protected $sMenuSubItemSelect = 'good';
    /**
     * Число новых топиков
     *
     * @var int
     */
    protected $iCountTopicsNew = 0;
    /**
     * Число новых топиков в коллективных блогах
     *
     * @var int
     */
    protected $iCountTopicsCollectiveNew = 0;
    /**
     * Число новых топиков в персональных блогах
     *
     * @var int
     */
    protected $iCountTopicsPersonalNew = 0;
    /**
     * URL-префикс для навигации по топикам
     *
     * @var string
     */
    protected $sNavTopicsSubUrl = '';

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        /**
         * Подсчитываем новые топики
         */
        $this->iCountTopicsCollectiveNew = $this->Topic_GetCountTopicsCollectiveNew();
        $this->iCountTopicsPersonalNew = $this->Topic_GetCountTopicsPersonalNew();
        $this->iCountTopicsNew = $this->iCountTopicsCollectiveNew + $this->iCountTopicsPersonalNew;
        $this->sNavTopicsSubUrl = Router::GetPath('index');
    }

    /**
     * Регистрация евентов
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^(page([1-9]\d{0,5}))?$/i', 'EventIndex');
        $this->AddEventPreg('/^new$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventNew');
        $this->AddEventPreg('/^newall$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventNewAll');
        $this->AddEventPreg('/^discussed$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventDiscussed');
        $this->AddEventPreg('/^top$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventTop');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Вывод рейтинговых топиков
     */
    protected function EventTop()
    {
        $sPeriod = Config::Get('module.topic.default_period_top');
        if (in_array(getRequestStr('period'), array(1, 7, 30, 'all'))) {
            $sPeriod = getRequestStr('period');
        }
        if (!$sPeriod) {
            $sPeriod = 1;
        }
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = 'top';
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        if ($iPage == 1 and !getRequest('period')) {
            $this->Viewer_SetHtmlCanonical(Router::GetPath('index') . 'top/');
        }
        /**
         * Получаем список топиков
         */
        $aResult = $this->Topic_GetTopicsTop($iPage, Config::Get('module.topic.per_page'),
            $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
        /**
         * Если нет топиков за 1 день, то показываем за неделю (7)
         */
        if (!$aResult['count'] and $iPage == 1 and !getRequest('period')) {
            $sPeriod = 7;
            $aResult = $this->Topic_GetTopicsTop($iPage, Config::Get('module.topic.per_page'),
                $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
        }
        $aTopics = $aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_list_show', array('aTopics' => &$aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('index') . 'top', array('period' => $sPeriod));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_Assign('paging', $aPaging);
        $this->Viewer_Assign('periodSelectCurrent', $sPeriod);
        $this->Viewer_Assign('periodSelectRoot', Router::GetPath('index') . 'top/');
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
        $this->Viewer_AddHtmlTitle($this->Lang_Get('blog.menu.all_top'));
        $this->Viewer_AddHtmlTitle($this->Lang_Get('blog.menu.top_period_' . $sPeriod));
    }

    /**
     * Вывод обсуждаемых топиков
     */
    protected function EventDiscussed()
    {
        $sPeriod = Config::Get('module.topic.default_period_discussed');
        if (in_array(getRequestStr('period'), array(1, 7, 30, 'all'))) {
            $sPeriod = getRequestStr('period');
        }
        if (!$sPeriod) {
            $sPeriod = 1;
        }
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = 'discussed';
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        if ($iPage == 1 and !getRequest('period')) {
            $this->Viewer_SetHtmlCanonical(Router::GetPath('index') . 'discussed/');
        }
        /**
         * Получаем список топиков
         */
        $aResult = $this->Topic_GetTopicsDiscussed($iPage, Config::Get('module.topic.per_page'),
            $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
        /**
         * Если нет топиков за 1 день, то показываем за неделю (7)
         */
        if (!$aResult['count'] and $iPage == 1 and !getRequest('period')) {
            $sPeriod = 7;
            $aResult = $this->Topic_GetTopicsDiscussed($iPage, Config::Get('module.topic.per_page'),
                $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
        }
        $aTopics = $aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_list_show', array('aTopics' => &$aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('index') . 'discussed', array('period' => $sPeriod));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_Assign('paging', $aPaging);
        $this->Viewer_Assign('periodSelectCurrent', $sPeriod);
        $this->Viewer_Assign('periodSelectRoot', Router::GetPath('index') . 'discussed/');
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
        $this->Viewer_AddHtmlTitle($this->Lang_Get('blog.menu.all_discussed'));
        $this->Viewer_AddHtmlTitle($this->Lang_Get('blog.menu.top_period_' . $sPeriod));
    }

    /**
     * Вывод новых топиков
     */
    protected function EventNew()
    {
        $this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss') . 'new/', Config::Get('view.name'));
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = 'new';
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        /**
         * Получаем список топиков
         */
        $aResult = $this->Topic_GetTopicsNew($iPage, Config::Get('module.topic.per_page'));
        $aTopics = $aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_list_show', array('aTopics' => &$aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('index') . 'new');
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_Assign('paging', $aPaging);
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
        $this->Viewer_AddHtmlTitle($this->Lang_Get('blog.menu.all_new'));
    }

    /**
     * Вывод ВСЕХ новых топиков
     */
    protected function EventNewAll()
    {
        $this->Viewer_AddHtmlTitle($this->Lang_Get('blog.menu.all'));
        $this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss') . 'new/', Config::Get('view.name'));
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = 'new';
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        /**
         * Получаем список топиков
         */
        $aResult = $this->Topic_GetTopicsNewAll($iPage, Config::Get('module.topic.per_page'));
        $aTopics = $aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_list_show', array('aTopics' => &$aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('index') . 'newall');
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_Assign('paging', $aPaging);
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
    }

    /**
     * Вывод интересных на главную
     *
     */
    protected function EventIndex()
    {
        $this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss') . 'index/', Config::Get('view.name'));
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = 'good';
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetEventMatch(2) ? $this->GetEventMatch(2) : 1;
        /**
         * Устанавливаем основной URL для поисковиков
         */
        if ($iPage == 1) {
            $this->Viewer_SetHtmlCanonical(Router::GetPath('/'));
        }
        /**
         * Получаем список топиков
         */
        $aResult = $this->Topic_GetTopicsGood($iPage, Config::Get('module.topic.per_page'));
        $aTopics = $aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_list_show', array('aTopics' => &$aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('index'));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_Assign('paging', $aPaging);
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
    }

    /**
     * При завершении экшена загружаем переменные в шаблон
     *
     */
    public function EventShutdown()
    {
        $this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
        $this->Viewer_Assign('sMenuSubItemSelect', $this->sMenuSubItemSelect);
        $this->Viewer_Assign('iCountTopicsNew', $this->iCountTopicsNew);
        $this->Viewer_Assign('iCountTopicsCollectiveNew', $this->iCountTopicsCollectiveNew);
        $this->Viewer_Assign('iCountTopicsPersonalNew', $this->iCountTopicsPersonalNew);
        $this->Viewer_Assign('iCountTopicsSubNew', $this->iCountTopicsNew);
        $this->Viewer_Assign('sNavTopicsSubUrl', $this->sNavTopicsSubUrl);
    }
}