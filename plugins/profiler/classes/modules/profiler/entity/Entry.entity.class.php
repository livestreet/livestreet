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

class PluginProfiler_ModuleProfiler_EntityEntry extends Entity 
{    
    public function getRequestId() {
        return $this->_aData['request_id'];
    }
    public function getDate() {
        return $this->_aData['request_date'];
    }
    public function getTimeFull() {
        return str_replace(',','.',$this->_aData['time_full']);
    }
    
    public function getTimeStart($mode=null) {
        switch ($mode) {
        	case 'seconds':
        		list($iSeconds,)=explode(' ',$this->_aData['time_start'],2);
        		return $iSeconds;
        	
        	case 'time':
        		list(,$iTime)=explode(' ',$this->_aData['time_start'],2);
        		return $iTime;        			
        		
        	case null: 
        	default:
        		return $this->_aData['time_start'];
        	
        }
    }
    public function getTimeStop($mode=null) {
        switch ($mode) {
        	case 'seconds':
        		list($iSeconds,)=explode(' ',$this->_aData['time_stop'],2);
        		return $iSeconds;
        	
        	case 'time':
        		list(,$iTime)=explode(' ',$this->_aData['time_stop'],2);
        		return $iTime;        			
        		
        	case null: 
        	default:
        		return $this->_aData['time_stop'];
        	
        }
    }
    
    public function getId() {
        return $this->_aData['time_id'];
    }
    public function getPid() {
        return is_null($this->_aData['time_pid']) ? 0 : $this->_aData['time_pid'];
    }
    public function getName() {
        return $this->_aData['time_name'];
    }
    public function getComment() {
        return $this->_aData['time_comment'];
    }
    
    public function getLevel() {
    	return $this->_aData['level'];
    }
    public function getChildCount() {
    	return $this->_aData['child_count'];
    }
    public function getParentTimeFull() {
    	return $this->_aData['parent_time_full'];
    }
    
    public function setRequestId($data) {
    	$this->_aData['request_id']=$data;
    }
    public function setDate($data) {
    	$this->_aData['request_date']=$data;
    }
    public function setTimeFull($data) {
    	$this->_aData['time_full']=$data;
    }
    public function setTimeStart($data) {
    	$this->_aData['time_start']=$data;
    }
    public function setTimeStop($data) {
    	$this->_aData['time_stop']=$data;
    }    
    public function setId($data) {
    	$this->_aData['time_id']=$data;
    }
    public function setPid($data) {
    	$this->_aData['time_pid']=$data;
    }    
    public function setName($data) {
    	$this->_aData['time_name']=$data;
    }
    public function setComment($data) {
    	$this->_aData['time_comment']=$data;
    }    
    
    public function setLevel($data) {
    	$this->_aData['level']=$data;
    }
    public function setParentTimeFull($data) {
    	$this->_aData['parent_time_full']=$data;
    }
}
?>