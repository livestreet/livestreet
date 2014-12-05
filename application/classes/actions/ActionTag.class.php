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
 * Экшен обработки поиска по тегам
 *
 * @package application.actions
 * @since 1.0
 */
class ActionTag extends Action
{
    /**
     * Главное меню
     *
     * @var string
     */
    protected $sMenuHeadItemSelect = 'blog';

    /**
     * Инициализация
     *
     */
    public function Init()
    {
    }

    /**
     * Регистрация евентов
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^.+$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventTags');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Отображение топиков
     *
     */
    protected function EventTags()
    {
        /**
         * Получаем тег из УРЛа
         */
        $sTag = $this->sCurrentEvent;
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        /**
         * Получаем список топиков
         */
        $aResult = $this->Topic_GetTopicsByTag($sTag, $iPage, Config::Get('module.topic.per_page'));
        $aTopics = $aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_list_show', array('aTopics' => $aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('tag') . htmlspecialchars($sTag));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('paging', $aPaging);
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_Assign('tag', $sTag);
        $this->Viewer_AddHtmlTitle($this->Lang_Get('tag_title'));
        $this->Viewer_AddHtmlTitle($sTag);
        $this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss') . 'tag/' . $sTag . '/', $sTag);
        /**
         * Если не удалось найти топиков, то ыставляем 404 заголовок
         */
        if (!count($aTopics)) {
            header("HTTP/1.1 404 Not Found");
        }
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
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
    }
}