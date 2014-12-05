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
 * Обработка основного поиска
 *
 * @package application.actions
 * @since 1.0
 */
class ActionSearch extends Action
{

    public function Init()
    {
        $this->SetDefaultEvent('index');
        $this->Viewer_AddHtmlTitle($this->Lang_Get('search.search'));
    }

    /**
     * Регистрация евентов
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('index', 'EventIndex');
        $this->AddEventPreg('/^topics$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventTopics');
        $this->AddEventPreg('/^comments$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventComments');
        $this->AddEvent('opensearch', 'EventOpenSearch');
    }

    /**
     * Главная страница поиска
     */
    protected function EventIndex()
    {
        $this->SetTemplateAction('index');
    }

    /**
     * Обработка стандарта для браузеров Open Search
     */
    function EventOpenSearch()
    {
        Router::SetIsShowStats(false);
        header('Content-type: text/xml; charset=utf-8');
    }

    /**
     * Обработка поиска топиков
     */
    protected function EventTopics()
    {
        $this->SetTemplateAction('index');
        $sSearchType = $this->sCurrentEvent;
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        /**
         * Получаем список слов для поиска
         */
        $aWords = $this->Search_GetWordsForSearch(getRequestStr('q'));
        if (!$aWords) {
            $this->Message_AddErrorSingle($this->Lang_Get('search.alerts.query_incorrect'));
            return;
        }
        $sQuery = join(' ', $aWords);
        /**
         * Формируем регулярное выражение для поиска
         */
        $sRegexp = $this->Search_GetRegexpForWords($aWords);
        /**
         * Выполняем поиск
         */
        $aResult = $this->Search_SearchTopics($sRegexp, $iPage, Config::Get('module.topic.per_page'));
        $aResultItems = $aResult['collection'];
        /**
         * Конфигурируем парсер jevix
         */
        $this->Text_LoadJevixConfig('search');
        /**
         *  Делаем сниппеты
         */
        foreach ($aResultItems AS $oItem) {
            /**
             * Т.к. текст в сниппетах небольшой, то можно прогнать через парсер
             */
            $oItem->setTextShort($this->Text_JevixParser($this->Search_BuildExcerpts($oItem->getText(), $aWords)));
        }
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('search') . $sSearchType, array('q' => $sQuery));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('resultItems', $aResultItems);
        $this->Viewer_Assign('paging', $aPaging);
        $this->Viewer_Assign('searchType', $sSearchType);
        $this->Viewer_Assign('query', $sQuery);
        $this->Viewer_Assign('typeCounts', array($sSearchType => $aResult['count']));
    }

    /**
     * Обработка поиска комментариев
     */
    protected function EventComments()
    {
        $this->SetTemplateAction('index');
        $sSearchType = $this->sCurrentEvent;
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        /**
         * Получаем список слов для поиска
         */
        $aWords = $this->Search_GetWordsForSearch(getRequestStr('q'));
        if (!$aWords) {
            $this->Message_AddErrorSingle($this->Lang_Get('search.alerts.query_incorrect'));
            return;
        }
        $sQuery = join(' ', $aWords);
        /**
         * Формируем регулярное выражение для поиска
         */
        $sRegexp = $this->Search_GetRegexpForWords($aWords);
        /**
         * Выполняем поиск
         */
        $aResult = $this->Search_SearchComments($sRegexp, $iPage, 4, 'topic');
        $aResultItems = $aResult['collection'];
        /**
         * Конфигурируем парсер jevix
         */
        $this->Text_LoadJevixConfig('search');
        /**
         *  Делаем сниппеты
         */
        foreach ($aResultItems AS $oItem) {
            /**
             * Т.к. текст в сниппетах небольшой, то можно прогнать через парсер
             */
            $oItem->setText($this->Text_JevixParser($this->Search_BuildExcerpts($oItem->getText(), $aWords)));
        }
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, 4, Config::Get('pagination.pages.count'),
            Router::GetPath('search') . $sSearchType, array('q' => $sQuery));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('resultItems', $aResultItems);
        $this->Viewer_Assign('paging', $aPaging);
        $this->Viewer_Assign('searchType', $sSearchType);
        $this->Viewer_Assign('query', $sQuery);
        $this->Viewer_Assign('typeCounts', array($sSearchType => $aResult['count']));
    }
}
