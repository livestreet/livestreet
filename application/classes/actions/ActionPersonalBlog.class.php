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
 * Экшен обработки персональных блогов, т.е. УРла вида /personal_blog/
 *
 * @package application.actions
 * @since 1.0
 */
class ActionPersonalBlog extends Action
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
    protected $sMenuItemSelect = 'log';
    /**
     * Субменю
     *
     * @var string
     */
    protected $sMenuSubItemSelect = 'good';
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
        $this->SetDefaultEvent('good');
        $this->sNavTopicsSubUrl = Router::GetPath('personal_blog');
    }

    /**
     * Регистрируем необходимые евенты
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^good$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventTopics');
        $this->AddEvent('good', 'EventTopics');
        $this->AddEventPreg('/^bad$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventTopics');
        $this->AddEventPreg('/^new$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventTopics');
        $this->AddEventPreg('/^newall$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventTopics');
        $this->AddEventPreg('/^discussed$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventTopics');
        $this->AddEventPreg('/^top$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventTopics');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Показ топиков
     *
     */
    protected function EventTopics()
    {
        $sPeriod = 1; // по дефолту 1 день
        if (in_array(getRequestStr('period'), array(1, 7, 30, 'all'))) {
            $sPeriod = getRequestStr('period');
        }
        $sShowType = $this->sCurrentEvent;
        if (!in_array($sShowType, array('discussed', 'top'))) {
            $sPeriod = 'all';
        }
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = $sShowType == 'newall' ? 'new' : $sShowType;
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        if ($iPage == 1 and !getRequest('period')) {
            $this->Viewer_SetHtmlCanonical(Router::GetPath('personal_blog') . $sShowType . '/');
        }
        /**
         * Получаем список топиков
         */
        $aResult = $this->Topic_GetTopicsPersonal($iPage, Config::Get('module.topic.per_page'), $sShowType,
            $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
        /**
         * Если нет топиков за 1 день, то показываем за неделю (7)
         */
        if (in_array($sShowType,
                array('discussed', 'top')) and !$aResult['count'] and $iPage == 1 and !getRequest('period')
        ) {
            $sPeriod = 7;
            $aResult = $this->Topic_GetTopicsPersonal($iPage, Config::Get('module.topic.per_page'), $sShowType,
                $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
        }
        $aTopics = $aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_list_show', array('aTopics' => $aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('personal_blog') . $sShowType,
            in_array($sShowType, array('discussed', 'top')) ? array('period' => $sPeriod) : array());
        /**
         * Вызов хуков
         */
        $this->Hook_Run('personal_show', array('sShowType' => $sShowType));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('aTopics', $aTopics);
        $this->Viewer_Assign('paging', $aPaging);
        if (in_array($sShowType, array('discussed', 'top'))) {
            $this->Viewer_Assign('periodSelectCurrent', $sPeriod);
            $this->Viewer_Assign('periodSelectRoot', Router::GetPath('personal_blog') . $sShowType . '/');
        }
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
    }

    /**
     * При завершении экшена загружаем в шаблон необходимые переменные
     *
     */
    public function EventShutdown()
    {
        /**
         * Подсчитываем новые топики
         */
        $iCountTopicsCollectiveNew = $this->Topic_GetCountTopicsCollectiveNew();
        $iCountTopicsPersonalNew = $this->Topic_GetCountTopicsPersonalNew();
        $iCountTopicsNew = $iCountTopicsCollectiveNew + $iCountTopicsPersonalNew;
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
        $this->Viewer_Assign('sMenuSubItemSelect', $this->sMenuSubItemSelect);
        $this->Viewer_Assign('iCountTopicsCollectiveNew', $iCountTopicsCollectiveNew);
        $this->Viewer_Assign('iCountTopicsPersonalNew', $iCountTopicsPersonalNew);
        $this->Viewer_Assign('iCountTopicsNew', $iCountTopicsNew);
        $this->Viewer_Assign('iCountTopicsSubNew', $iCountTopicsPersonalNew);
        $this->Viewer_Assign('sNavTopicsSubUrl', $this->sNavTopicsSubUrl);
    }
}