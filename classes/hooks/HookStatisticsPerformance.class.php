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
 */
class HookStatisticsPerformance extends Hook {
	public function RegisterHook() {
		$this->AddHook('template_body_end','Statistics',__CLASS__,-1000);
	}

	public function Statistics() {
		$oEngine=Engine::getInstance();
		
		$iTimeInit=$oEngine->GetTimeInit();
		$iTimeFull=round(microtime(true)-$iTimeInit,3);
		$this->Viewer_Assign('iTimeFullPerformance',$iTimeFull);
		
		$aStats=$oEngine->getStats();
		$aStats['cache']['time']=round($aStats['cache']['time'],5);
		$this->Viewer_Assign('aStatsPerformance',$aStats);
		
		$this->Viewer_Assign('bIsShowStatsPerformance',Router::GetIsShowStats());
		return $this->Viewer_Fetch('statistics_performance.tpl');
	}
}
?>