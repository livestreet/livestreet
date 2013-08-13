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
		return $this->_getDataOne('request_id');
	}
	public function getDate() {
		return $this->_getDataOne('request_date');
	}
	public function getTimeFull() {
		return str_replace(',','.',$this->_getDataOne('time_full'));
	}

	public function getTimeStart($mode=null) {
		switch ($mode) {
			case 'seconds':
				list($iSeconds,)=explode(' ',$this->_getDataOne('time_start'),2);
				return $iSeconds;

			case 'time':
				list(,$iTime)=explode(' ',$this->_getDataOne('time_start'),2);
				return $iTime;

			case null:
			default:
				return $this->_getDataOne('time_start');

		}
	}
	public function getTimeStop($mode=null) {
		switch ($mode) {
			case 'seconds':
				list($iSeconds,)=explode(' ',$this->_getDataOne('time_stop'),2);
				return $iSeconds;

			case 'time':
				list(,$iTime)=explode(' ',$this->_getDataOne('time_stop'),2);
				return $iTime;

			case null:
			default:
				return $this->_getDataOne('time_stop');

		}
	}

	public function getId() {
		return $this->_getDataOne('time_id');
	}
	public function getPid() {
		return $this->_getDataOne('time_pid') ? $this->_getDataOne('time_pid'): 0;
	}
	public function getName() {
		return $this->_getDataOne('time_name');
	}
	public function getComment() {
		return $this->_getDataOne('time_comment');
	}

	public function getLevel() {
		return $this->_getDataOne('level');
	}
	public function getChildCount() {
		return $this->_getDataOne('child_count');
	}
	public function getParentTimeFull() {
		return $this->_getDataOne('parent_time_full');
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