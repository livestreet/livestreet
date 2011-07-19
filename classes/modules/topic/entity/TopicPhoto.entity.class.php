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
        return $this->_aData['path'];
    }
    
     public function getWebPath($sWidth = null)
    {
         if (isset($this->_aData['path']) && $this->_aData['path']) {
             if ($sWidth) {
                 $iDotPos = strrpos($this->_aData['path'], '.');
                 $sFileBase = substr($this->_aData['path'], 0, $iDotPos);
                 $sFileExt = substr($this->_aData['path'], $iDotPos);
                 $sFileName = $sFileBase.'_'.$sWidth.$sFileExt;
             } else {
                 $sFileName = $this->_aData['path'];
             }
             return Config::Get('path.static.root').Config::Get('path.uploads.root').'/'.$sFileName;
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