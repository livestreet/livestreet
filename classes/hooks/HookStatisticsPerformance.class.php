<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Регистрация хука для вывода статистики производительности
 *
 * @package hooks
 * @since 1.0
 */
class HookStatisticsPerformance extends Hook {
	/**
	 * Регистрируем хуки
	 */
	public function RegisterHook() {
		$this->AddHook('template_body_end','Statistics',__CLASS__,-1000);
	}
	/**
	 * Обработка хука перед закрывающим тегом body
	 *
	 * @return string
	 */
	public function Statistics() {
    /*
      Не нужно рендерить шаблон если не админ
    */
    if (!$oUserCurrent = $this -> User_GetUserCurrent () or !$oUserCurrent -> isAdministrator ()) return false;
    /*
      Не нужно рендерить шаблон если запрещено показывать статистику (для RSS и т.п.)
    */
    if (!Router::GetIsShowStats()) return false;

		$oEngine=Engine::getInstance();
		/**
		 * Подсчитываем время выполнения
		 */
		$iTimeInit=$oEngine->GetTimeInit();
		$iTimeFull=round(microtime(true)-$iTimeInit,3);
		$this->Viewer_Assign('iTimeFullPerformance',$iTimeFull);
		/**
		 * Получаем статистику по кешу и БД
		 */
		$aStats=$oEngine->getStats();
		$aStats['cache']['time']=round($aStats['cache']['time'],5);
		$this->Viewer_Assign('aStatsPerformance',$aStats);
		/**
		 * В ответ рендерим шаблон статистики
		 */
		return $this->Viewer_Fetch('statistics_performance.tpl');
	}
}

?>