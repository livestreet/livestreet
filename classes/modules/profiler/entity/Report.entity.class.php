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
		return $this->_aData['report_entries'];
	}

    public function setId($data) {
    	$this->_aData['report_id']=$data;
    }
    public function setDate($data) {
    	$this->_aData['report_date']=$data;
    }
    
    public function addEntry(ProfilerEntity_Entry $data) {
    	if(!isset($this->_aData['report_id'])) {
    		$this->setId($data->getRequestId());
    		$this->setDate($data->getDate());
    	}
    	
    	if($this->getId()!=$data->getRequestId()) return null;
    	$this->_aData['report_entries'][$data->getId()]=$data;
    }
}
?>