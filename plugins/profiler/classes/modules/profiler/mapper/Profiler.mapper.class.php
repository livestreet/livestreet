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

class PluginProfiler_ModuleProfiler_MapperProfiler extends Mapper {

	public function AddEntry(PluginProfiler_ModuleProfiler_EntityEntry $oEntry) {
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
			VALUES(?, ?, ?f, ?f+?f, ?f+?f,  ?d,  ?d,  ?,  ?)
		";
		return $this->oDb->query($sql,$oEntry->getDate(),$oEntry->getRequestId(),$oEntry->getTimeFull(),$oEntry->getTimeStart('time'),$oEntry->getTimeStart('seconds'),$oEntry->getTimeStop('time'),$oEntry->getTimeStop('seconds'),$oEntry->getId(),$oEntry->getPid(),$oEntry->getName(),$oEntry->getComment());
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
					MAX(time_full) as time_full, 
					COUNT(time_id) as count_time_id,
					MIN(request_date) as request_date
				FROM ".Config::Get('db.table.profiler')."
				WHERE 
					1=1
					{ AND request_date >= ? }
					{ AND request_date <= ? }
					{ AND time_full > ? }
				GROUP BY request_id
				ORDER BY request_date desc
				LIMIT ?d, ?d
					";

		if (
			$aRows=$this->oDb->selectPage(
				$iCount,
				$sql,
				isset($aFilter['date_min'])?$aFilter['date_min']:DBSIMPLE_SKIP,
				isset($aFilter['date_max'])?$aFilter['date_max']:DBSIMPLE_SKIP,
				isset($aFilter['time'])?$aFilter['time']:DBSIMPLE_SKIP,
				($iCurrPage-1)*$iPerPage,
				$iPerPage
			)
		) {
			return $aRows;
		}
		return null;
	}


	public function GetReportById($sReportId,$sPid=null) {
		$sql = "
			SELECT 
				p.*,
				p.time_id as ARRAY_KEY,
				p.time_pid as PARENT_KEY,
				COUNT(pc.time_id) as child_count,
				pp.time_full as parent_time_full
			FROM
				".Config::Get('db.table.profiler')." as p
			LEFT JOIN ".Config::Get('db.table.profiler')." as pc ON p.request_id=pc.request_id AND p.time_id = pc.time_pid
			LEFT JOIN ".Config::Get('db.table.profiler')." AS pp  ON p.request_id=pp.request_id AND p.time_pid = pp.time_id
			WHERE
				p.request_id=?
				{ AND p.time_pid=?d }
			GROUP BY p.time_id
		";

		if($aRows=$this->oDb->query($sql,$sReportId,is_null($sPid)?DBSIMPLE_SKIP:$sPid)) {
			return $aRows;
		}
		return array();
	}

	public function GetReportStatById($sReportId) {
		$sql = "
			SELECT time_full, time_name, time_comment
			FROM ".Config::Get('db.table.profiler')."
			WHERE request_id=?
		";

		if($aRows=$this->oDb->query($sql,$sReportId)) {
			return $aRows;
		}
		return array();
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