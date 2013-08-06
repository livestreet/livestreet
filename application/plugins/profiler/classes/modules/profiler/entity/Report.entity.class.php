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

class PluginProfiler_ModuleProfiler_EntityReport extends Entity
{
	public function getId() {
		return $this->_getDataOne('report_id');
	}
	public function getDate() {
		return $this->_getDataOne('report_date');
	}
	public function getAllEntries() {
		return $this->_getDataOne('report_entries')?$this->_getDataOne('report_entries'):array();
	}
	public function getTime() {
		return $this->_getDataOne('report_time_full')?$this->_getDataOne('report_time_full'):0;
	}

	public function getEntriesByName($sName=null) {
		if(!$sName) return $this->getAllEntries();

		$aResult=array();
		foreach ($this->getAllEntries() as $oEntry) {
			if($oEntry->getName()==$sName) $aResult[$oEntry->getId()]=$oEntry;
		}
		return $aResult;
	}
	public function getCountEntriesByName($sName=null) {
		return count($this->getEntriesByName($sName));
	}

	public function getEntriesByCommentFilter($sFilter) {
		$sFilter=str_replace('*','[\W]+',$sFilter);

		$aResult=array();
		foreach ($this->_getDataOne('report_entries') as $oEntry) {
			if(preg_match("/{$sFilter}/Ui",$oEntry->getComment())) {
				$aResult[$oEntry->getId()]=$oEntry;
			}
		}
		return $aResult;
	}
	public function getCountEntriesByCommentFilter($sFilter) {
		return count($this->getEntriesByCommentFilter($sFilter));
	}

	public function getEntriesByPid($sPid) {
		$aResult=array();
		foreach ($this->_getDataOne('report_entries') as $oEntry) {
			if($oEntry->getPid()==$sPid) $aResult[]=$oEntry;
		}
		return $aResult;
	}
	public function getCountEntriesByPid($sPid) {
		return $this->getEntriesByPid($sPid);
	}

	public function getEntryShare($sEntryId) {
		if(!isset($this->_aData['report_entries'][$sEntryId])) return null;
		if(!$this->_aData['report_entries'][$sEntryId]->getParentTimeFull()) return '-';

		return round($this->_aData['report_entries'][$sEntryId]->getTimeFull()*100/$this->_aData['report_entries'][$sEntryId]->getParentTimeFull(), 2);
	}

	public function getEntryFullShare($sEntryId) {
		if(!isset($this->_aData['report_entries'][$sEntryId])) return null;

		return ($iTimeFull=$this->getStat('time_full'))
			? round($this->_aData['report_entries'][$sEntryId]->getTimeFull()*100/$iTimeFull, 2)
			: '?';
	}
	/**
	 * Получает статистику отчета
	 *
	 * @param  string [$sKey default=null
	 * @return array|string|null
	 */
	public function getStat($sKey=null) {
		if(!$sKey) return $this->_getDataOne('report_stat');
		if(isset($this->_aData['report_stat'][$sKey])) return $this->_aData['report_stat'][$sKey];

		return null;
	}

	public function setId($data) {
		$this->_aData['report_id']=$data;
	}
	public function setDate($data) {
		$this->_aData['report_date']=$data;
	}
	protected function setTime($data) {
		$this->_aData['report_time_full']=$data;
	}

	public function addEntry(PluginProfiler_ModuleProfiler_EntityEntry $data) {
		if(!isset($this->_aData['report_id'])) {
			$this->setId($data->getRequestId());
			$this->setDate($data->getDate());
		}

		if($this->getId()!=$data->getRequestId()) return null;
		$this->_aData['report_entries'][$data->getId()]=$data;
	}
	/**
	 * Устанавливаем все записи одним массивом
	 *
	 * @param  array $data
	 * @return null
	 */
	public function setAllEntries($data) {
		if(!is_array($data)) return null;
		$this->_aData['report_entries']=$data;
	}
	public function setStat($data,$sKey=null) {
		if(!$sKey) {
			$this->_aData['report_stat']=$data;
			return ;
		}
		$this->_aData[$sKey]=$data;
	}
}
?>