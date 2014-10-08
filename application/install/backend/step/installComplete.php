<?php

class InstallStepInstallComplete extends InstallStep
{

    public function init()
    {
        InstallConfig::$sFileConfig = dirname(INSTALL_DIR) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.local.php';
    }

    public function show()
    {
        /**
         * Прописываем параметры в конфиг
         */
        $aSave = array(
            'install_completed' => true,
        );
        InstallConfig::save($aSave);
    }
}