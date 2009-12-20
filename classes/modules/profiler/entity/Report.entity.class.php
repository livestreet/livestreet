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

class ProfilerEntity_Report extends Entity 
{    
    public function getId() {
        return $this->_aData['report_id'];
    }
    public function getDate() {
        return $this->_aData['report_date'];
    }
	public function getAllEntries() {
		return isset($this->_aData['report_entries'])?$this->_aData['report_entries']:array();
	}
	public function getTimeFull() {
		return isset($this->_aData['report_time_full'])?$this->_aData['report_time_full']:0;
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
		foreach ($this->_aData['report_entries'] as $oEntry) {
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
		foreach ($this->_aData['report_entries'] as $oEntry) {
			if($oEntry->getPid()==$sPid) $aResult[]=$oEntry;
		}
		return $aResult;
	}
	public function getCountEntriesByPid($sPid) {
		return $this->getEntriesByPid($sPid);
	}	
	
	public function getEntryShare($sEntryId) {
		if(!isset($this->_aData['report_entries'][$sEntryId])) return null;

		return round($this->_aData['report_entries'][$sEntryId]->getTimeFull()*100/$this->getTimeFull(), 2);
	}
	
    public function setId($data) {
    	$this->_aData['report_id']=$data;
    }
    public function setDate($data) {
    	$this->_aData['report_date']=$data;
    }
    protected function setTimeFull($data) {
    	$this->_aData['report_time_full']=$data;
    }
    
    public function addEntry(ProfilerEntity_Entry $data) {
    	if(!isset($this->_aData['report_id'])) {
    		$this->setId($data->getRequestId());
    		$this->setDate($data->getDate());
    	}
    	
    	if($this->getId()!=$data->getRequestId()) return null;
    	$this->_aData['report_entries'][$data->getId()]=$data;
    	/**
    	 * Если это родительский элемент, то увеличиваем общее время отчета
    	 */
    	if(!$data->getPid()) $this->setTimeFull($this->getTimeFull()+$data->getTimeFull());
    }
    
    public function setAllEntries($data) {
    	$this->_aData['report_entries']=$data;
    }
}
?>