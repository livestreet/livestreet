<?php

class InstallTemplate
{

    protected $aVars = array();
    protected $sTemplate = null;
    protected $oParent = null;

    public function __construct($sTemplate, $aVars = array())
    {
        $this->sTemplate = $sTemplate;
        $this->assign($aVars);
    }

    public function assign($mName, $mValue = null)
    {
        if (is_array($mName)) {
            $this->aVars = array_merge($this->aVars, $mName);
        } else {
            $this->aVars[$mName] = $mValue;
        }
    }

    public function get($sName = null, $mDefault = null)
    {
        if (is_null($sName)) {
            return $this->aVars;
        }
        return isset($this->aVars[$sName]) ? $this->aVars[$sName] : $mDefault;
    }

    public function getFromParent($sName = null, $mDefault = null)
    {
        if (!$this->oParent) {
            return $mDefault;
        }
        return $this->oParent->get($sName, $mDefault);
    }

    public function render()
    {
        ob_start();
        include($this->getPathTemplate());
        $sResult = ob_get_contents();
        ob_end_clean();
        return $sResult;
    }

    public function setParent($oTemplate)
    {
        $this->oParent = $oTemplate;
    }

    public function lang($sName)
    {
        return InstallCore::getLang($sName);
    }

    protected function getPathTemplate()
    {
        return INSTALL_DIR . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $this->sTemplate;
    }
}