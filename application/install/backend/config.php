<?php

class InstallConfig
{

    static public $sFileConfig = null;
    static public $sLastError = null;

    static public function save($mName, $mValue = null)
    {
        if (!self::checkFile()) {
            return false;
        }
        if (is_array($mName)) {
            $aValues = $mName;
        } else {
            $aValues = array($mName => $mValue);
        }

        $sContent = file_get_contents(self::$sFileConfig);
        foreach ($aValues as $sName => $mValue) {
            $sContent = self::_writeValue($sName, $mValue, $sContent);
        }
        file_put_contents(self::$sFileConfig, $sContent);
        return true;
    }

    static public function get($sName, $mDefault = null)
    {
        if (!self::checkFile(false)) {
            return $mDefault;
        }

        $aConfig = include(self::$sFileConfig);

        if (strpos($sName, '.')) {
            $sVal = $aConfig;
            $aKeys = explode('.', $sName);
            foreach ($aKeys as $k) {
                if (isset($sVal[$k])) {
                    $sVal = $sVal[$k];
                } else {
                    return $mDefault;
                }
            }
        } else {
            if (isset($aConfig[$sName])) {
                $sVal = $aConfig[$sName];
            } else {
                return $mDefault;
            }
        }
        return $sVal;

    }

    static public function _writeValue($sName, $mValue, $sContent)
    {
        $sName = '$config[\'' . implode('\'][\'', explode('.', $sName)) . '\']';
        $mValue = self::_convertToConfigValue($mValue);
        /**
         * Если переменная уже определена в конфиге,
         * то меняем значение.
         */
        if (substr_count($sContent, $sName)) {
            $sContent = preg_replace("~" . preg_quote($sName) . ".+;~Ui", $sName . ' = ' . $mValue . ';', $sContent);
        } else {
            $sContent = str_replace('return $config;', $sName . ' = ' . $mValue . ';' . PHP_EOL . 'return $config;',
                $sContent);
        }
        return $sContent;
    }

    static public function _convertToConfigValue($mValue)
    {
        switch (true) {
            case is_string($mValue):
                return "'" . addslashes($mValue) . "'";

            case is_bool($mValue):
                return ($mValue) ? "true" : "false";

            case is_array($mValue):
                $sArrayString = "";
                foreach ($mValue as $sKey => $sValue) {
                    $sArrayString .= "'{$sKey}'=>" . self::_convertToConfigValue($sValue) . ",";
                }
                return "array(" . $sArrayString . ")";

            case is_numeric($mValue):
                return $mValue;

            default:
                return "'" . (string)$mValue . "'";
        }
    }

    static public function checkFile($bCheckWritable = true)
    {
        if (is_null(self::$sFileConfig) or !file_exists(self::$sFileConfig)) {
            self::$sLastError = InstallCore::getLang('config.errors.file_not_found');
            return false;
        }
        if ($bCheckWritable) {
            if (!is_writable(self::$sFileConfig)) {
                self::$sLastError = InstallCore::getLang('config.errors.file_not_writable');
                return false;
            }
        }
        return true;
    }

}