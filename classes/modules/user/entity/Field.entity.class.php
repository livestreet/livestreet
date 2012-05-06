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

class ModuleUser_EntityField extends Entity {
	public function getId() {
		return $this->_getDataOne('id');
	}
	public function getName(){
		return $this->_getDataOne('name');
	}
	public function getType(){
		return $this->_getDataOne('type');
	}
	public function getTitle(){
		return $this->_getDataOne('title');
	}
	public function getPattern(){
		return $this->_getDataOne('pattern');
	}
	public function getValue($bEscapeValue = false, $bTransformed = false){
		if (!isset($this->_aData['value']) || !$this->_aData['value']) return '';
		if ($bEscapeValue)  $this->_aData['value'] = htmlspecialchars($this->_aData['value']);

		if ($bTransformed) {
			if (!$this->_aData['pattern']) return $this->_aData['value'];
			return str_replace('{*}', $this->_aData['value'], $this->_aData['pattern']);
		} else {
			return (isset($this->_aData['value'])) ? $this->_aData['value'] : '';
		}
	}



	public function setId($iId) {
		$this->_aData['id']=$iId;
	}
	public function setName($sName) {
		$this->_aData['name']=$sName;
	}
	public function setType($sName) {
		$this->_aData['type']=$sName;
	}
	public function setTitle($sTitle) {
		$this->_aData['title']=$sTitle;
	}
	public function setPattern($sPattern) {
		$this->_aData['pattern']=$sPattern;
	}
	public function setValue($sValue) {
		$this->_aData['value']=$sValue;
	}
}