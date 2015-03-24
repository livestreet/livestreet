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
 * Регистрация хука для вывода статистики производительности
 *
 * @package application.hooks
 * @since 1.0
 */
class HookStatisticsPerformance extends Hook
{
    /**
     * Регистрируем хуки
     */
    public function RegisterHook()
    {
        if ($this->User_GetIsAdmin()) {
            $this->AddHook('template_body_end', 'Statistics', __CLASS__, -1000);
        }
    }

    /**
     * Обработка хука перед закрывающим тегом body
     *
     * @return string
     */
    public function Statistics()
    {
        $oEngine = Engine::getInstance();
        /**
         * Подсчитываем время выполнения
         */
        $iTimeInit = $oEngine->GetTimeInit();
        $iTimeFull = round(microtime(true) - $iTimeInit, 3);
        $this->Viewer_Assign('timeFullPerformance', $iTimeFull, true);
        /**
         * Получаем статистику по кешу и БД
         */
        $aStats = $oEngine->getStats();
        $aStats['cache']['time'] = round($aStats['cache']['time'], 5);
        $this->Viewer_Assign('stats', $aStats, true);
        $this->Viewer_Assign('bIsShowStatsPerformance', Router::GetIsShowStats());
        /**
         * В ответ рендерим шаблон статистики
         */
        return $this->Viewer_Fetch('component@performance.performance');
    }
}