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
            'module.blog.encrypt' => md5(time() . mt_rand()),
            'module.talk.encrypt' => md5(time() . mt_rand()),
            'module.security.hash' => md5(time() . mt_rand()),
        );
        InstallConfig::save($aSave);
    }
}