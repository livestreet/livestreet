<?php

class InstallStepCheckRequirements extends InstallStep
{

    public function show()
    {
        /**
         * Проверяем требования
         */
        $sAdditionalSolution = '';
        $aRequirements = array();
        if (!version_compare(PHP_VERSION, '5.5', '>=')) {
            $aRequirements[] = array(
                'name'    => 'php_version',
                'current' => PHP_VERSION
            );
        }
        if (!in_array(strtolower(@ini_get('safe_mode')), array('0', 'off', ''))) {
            $aRequirements[] = array(
                'name'    => 'safe_mode',
                'current' => InstallCore::getLang('yes')
            );
        }
        if (!@preg_match('//u', '')) {
            $aRequirements[] = array(
                'name'    => 'utf8',
                'current' => InstallCore::getLang('no')
            );
        }
        if (!@extension_loaded('mbstring')) {
            $aRequirements[] = array(
                'name'    => 'mbstring',
                'current' => InstallCore::getLang('no')
            );
        }
        if (!in_array(strtolower(@ini_get('mbstring.func_overload')), array('0', '4', 'no overload'))) {
            $aRequirements[] = array(
                'name'    => 'mbstring_func_overload',
                'current' => InstallCore::getLang('yes')
            );
        }
        if (!@extension_loaded('SimpleXML')) {
            $aRequirements[] = array(
                'name'    => 'xml',
                'current' => InstallCore::getLang('no')
            );
        }
        if (@extension_loaded('xdebug')) {
            $iLevel = (int)@ini_get('xdebug.max_nesting_level');
            if ($iLevel < 1000) {
                $aRequirements[] = array(
                    'name'    => 'xdebug',
                    'current' => InstallCore::getLang('yes') . " ({$iLevel})"
                );
            }
        }
        /**
         * Права на запись файлов
         */
        $bWriteSolutions = false;
        $sAppDir = dirname(INSTALL_DIR);
        $sDir = dirname($sAppDir) . DIRECTORY_SEPARATOR . 'uploads';
        if (!is_dir($sDir) or !is_writable($sDir)) {
            $aRequirements[] = array(
                'name'    => 'dir_uploads',
                'current' => InstallCore::getLang('is_not_writable')
            );
            $bWriteSolutions = true;
        }
        $sDir = $sAppDir . DIRECTORY_SEPARATOR . 'plugins';
        if (!is_dir($sDir) or !is_writable($sDir)) {
            $aRequirements[] = array(
                'name'    => 'dir_plugins',
                'current' => InstallCore::getLang('is_not_writable')
            );
            $bWriteSolutions = true;
        }
        $sDir = $sAppDir . DIRECTORY_SEPARATOR . 'tmp';
        if (!is_dir($sDir) or !is_writable($sDir)) {
            $aRequirements[] = array(
                'name'    => 'dir_tmp',
                'current' => InstallCore::getLang('is_not_writable')
            );
            $bWriteSolutions = true;
        }
        $sDir = $sAppDir . DIRECTORY_SEPARATOR . 'logs';
        if (!is_dir($sDir) or !is_writable($sDir)) {
            $aRequirements[] = array(
                'name'    => 'dir_logs',
                'current' => InstallCore::getLang('is_not_writable')
            );
            $bWriteSolutions = true;
        }
        $sFile = $sAppDir . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.local.php';
        if (!is_file($sFile) or !is_writable($sFile)) {
            $aRequirements[] = array(
                'name'    => 'file_config_local',
                'current' => InstallCore::getLang('is_not_writable')
            );
            $bWriteSolutions = true;
        }

        if (count($aRequirements)) {
            InstallCore::setNextStepDisable();
        }

        if ($bWriteSolutions) {
            $sBuildPath = $sAppDir . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'build.sh';
            $sAdditionalSolution .= '<b>' . InstallCore::getLang('steps.checkRequirements.writable_solution') . '</b><br/>';
            $sAdditionalSolution .= '<i>chmod 0755 ' . $sBuildPath . '</i><br/>';
            $sAdditionalSolution .= '<i>' . $sBuildPath . '</i><br/>';
        }

        $this->assign('requirements', $aRequirements);
        $this->assign('additionalSolution', $sAdditionalSolution);
    }

}
