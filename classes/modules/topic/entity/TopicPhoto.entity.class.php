<?php

class ModuleTopic_EntityTopicPhoto extends Entity
{
	public function getId()
	{
		return $this->_aData['id'];
	}
	public function getTopicId()
	{
		return ( isset($this->_aData['topic_id'])) ? $this->_aData['topic_id'] : null;
	}
	public function getTargetTmp()
	{
		return $this->_aData['target_tmp'];
	}
	public function getDescription()
	{
		return ( isset($this->_aData['description'])) ? $this->_aData['description'] : null;
	}

	public function getPath()
	{		
		return isset($this->_aData['path']) ? $this->_aData['path'] : null;
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