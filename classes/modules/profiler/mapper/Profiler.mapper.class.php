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

class Mapper_Profiler extends Mapper {	
	
	public function AddEntry(ProfilerEntity_Entry $oEntry) {
		$sql = "INSERT IGNORE INTO ".Config::Get('db.table.profiler')." 
			(request_date,
			request_id,
			time_full,
			time_start,
			time_stop,
			time_id,
			time_pid,
			time_name,
			time_comment)
			VALUES(?, ?, ?f, ?f, ?f,  ?d,  ?d,  ?,  ?)
		";
		return $this->oDb->query($sql,$oEntry->getDate(),$oEntry->getRequestId(),$oEntry->getTimeFull(),$oEntry->getTimeStart(),$oEntry->getTimeStop(),$oEntry->getId(),$oEntry->getPid(),$oEntry->getName(),$oEntry->getComment()); 
	}
	
	public function GetDatabaseStat() {
		$sql = "
			SELECT 
				MAX(request_date) as max_date,
				COUNT(*) as count 
			FROM ".Config::Get('db.table.profiler') ."
		";
		
		if($aData = $this->oDb->selectRow($sql)) {
			return $aData;
		} 
		return null;
	}
	
	/**
	 * Возвращает список отчетов профайлера, сгруппированных по идентификатору вызова request_id
	 *
	 * @param  array $aFilter
	 * @param  int $iCount
	 * @param  int $iCurrPage
	 * @param  int $iPerPage
	 * @return array
	 */
	public function GetReportsByFilter($aFilter,&$iCount,$iCurrPage,$iPerPage) {		
		$sql = "
				SELECT 
					DISTINCT request_id,
					SUM(time_full) as time_full, 
					COUNT(time_id) as count_time_id,
					MIN(request_date) as request_date
				FROM ".Config::Get('db.table.profiler')."
				WHERE 1
				GROUP BY request_id
				ORDER BY request_date asc
				LIMIT ?d, ?d
					";
		
		if (
			$aRows=$this->oDb->selectPage(
				$iCount,
				$sql,
				($iCurrPage-1)*$iPerPage, 
				$iPerPage
			)
		) {
			return $aRows;
		}
		return null;
	}	
	
	/**
	 * Удаление записей из базы данных по уникальному ключу отчетов
	 *
	 * @param  array|int $aIds
	 * @return bool
	 */
	public function DeleteEntryByRequestId($aIds) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.profiler')."
			WHERE request_id IN(?a)
		";
		
		return $this->oDb->query($sql,$aIds);
	}
}
?>