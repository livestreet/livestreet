<?php

class InstallStepInstallAdmin extends InstallStep
{

    /**
     * Обработка отправки формы
     *
     * @return bool
     */
    public function process()
    {
        /**
         * Проверяем корректность емайла
         */
        $sMail = InstallCore::getRequestStr('admin_mail');
        if (!preg_match("/^[\da-z\_\-\.\+]+@[\da-z_\-\.]+\.[a-z]{2,5}$/i", $sMail)) {
            return $this->addError(InstallCore::getLang('steps.installAdmin.errors.mail'));
        }
        /**
         * Проверяем корректность пароль
         */
        $sPasswd = InstallCore::getRequestStr('admin_passwd');
        if (mb_strlen($sPasswd, 'UTF-8') < 3) {
            return $this->addError(InstallCore::getLang('steps.installAdmin.errors.passwd'));
        }
        /**
         * Получаем данные коннекта к БД из конфига
         */
        InstallConfig::$sFileConfig = dirname(INSTALL_DIR) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.local.php';
        /**
         * Коннект к серверу БД
         */
        if (!$oDb = $this->getDBConnection(InstallConfig::get('db.params.host'), InstallConfig::get('db.params.port'),
            InstallConfig::get('db.params.user'), InstallConfig::get('db.params.pass'))
        ) {
            return false;
        }
        /**
         * Выбираем БД
         */
        if (!@mysqli_select_db($oDb, InstallConfig::get('db.params.dbname'))) {
            return $this->addError(InstallCore::getLang('db.errors.db_query'));
        }
        /**
         * Обновляем пользователя
         */
        $sPrefix = InstallConfig::get('db.table.prefix');
        $sQuery = "
			UPDATE `{$sPrefix}user`
			SET
				`user_mail`	 = '{$sMail}',
				`user_admin`	 = '1',
				`user_password` = '" . md5($sPasswd) . "',
				`user_referral_code` = 'welcome'
			WHERE `user_id` = 1";

        if (!mysqli_query($oDb, $sQuery)) {
            return $this->addError(InstallCore::getLang('db.errors.db_query'));
        }
        return true;
    }
}