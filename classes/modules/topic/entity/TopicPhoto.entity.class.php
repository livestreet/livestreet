<?php

class ModuleTopic_EntityTopicPhoto extends Entity
{
	public function getId()
	{
		return $this->_getDataOne('id');
	}
	public function getTopicId()
	{
		return $this->_getDataOne('topic_id');
	}
	public function getTargetTmp()
	{
		return $this->_getDataOne('target_tmp');
	}
	public function getDescription()
	{
		return $this->_getDataOne('description');
	}

	public function getPath()
	{		
		return $this->_getDataOne('path');
	}

	public function getWebPath($sWidth = null)
	{
		if ($this->getPath()) {
			if ($sWidth) {
				$aPathInfo=pathinfo($this->getPath());
				return $aPathInfo['dirname'].'/'.$aPathInfo['filename'].'_'.$sWidth.'.'.$aPathInfo['extension'];
			} else {
				return $this->getPath();
			}
		} else {
			return null;
		}
	}

	public function setTopicId($iTopicId)
	{
		$this->_aData['topic_id'] = $iTopicId;
	}
	public function setTargetTmp($sTargetTmp)
	{
		$this->_aData['target_tmp'] = $sTargetTmp;
	}
	public function setDescription($sDescription)
	{
		$this->_aData['description'] = $sDescription;
	}

}