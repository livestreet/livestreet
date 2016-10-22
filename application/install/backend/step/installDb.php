<?php

class InstallStepInstallDb extends InstallStep
{

    protected $sConfigPath;

    public function init()
    {
        $this->sConfigPath = dirname(INSTALL_DIR) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.local.php';
        InstallConfig::$sFileConfig = $this->sConfigPath;
    }

    /**
     * Получаем данные для загрузки на форму
     * Возможные источники: реквест, конфиг, дефолтные значения
     *
     * @param      $sName
     * @param null $mDefault
     * @param bool $bUseHtmlspecialchars
     *
     * @return mixed|null|string
     */
    public function getValue($sName, $mDefault = null, $bUseHtmlspecialchars = true)
    {
        $mResult = null;
        $sNameRequest = str_replace('.', '_', $sName);
        if (isset($_REQUEST[$sNameRequest])) {
            $mResult = $_REQUEST[$sNameRequest];
        } else {
            $mResult = InstallConfig::get($sName, $mDefault);
        }
        return $bUseHtmlspecialchars ? htmlspecialchars($mResult) : $mResult;
    }

    /**
     * Обработка отправки формы
     *
     * @return bool
     */
    public function process()
    {
        if (!$aRes = $this->processDbCheck()) {
            return $aRes;
        }
        list($oDb, $sEngineDB) = $aRes;
        /**
         * Запускаем импорт дампов, сначала GEO DB
         */
        list($bResult, $aErrors) = array_values($this->importDumpDB($oDb, InstallCore::getDataFilePath('sql/geo.sql'),
                array(
                    'engine'      => $sEngineDB,
                    'prefix'      => InstallCore::getRequestStr('db.table.prefix'),
                    'check_table' => 'geo_city'
                )));
        if ($bResult) {
            /**
             * Запускаем основной дамп
             */
            list($bResult, $aErrors) = array_values($this->importDumpDB($oDb,
                    InstallCore::getDataFilePath('sql/dump.sql'), array(
                        'engine'      => $sEngineDB,
                        'prefix'      => InstallCore::getRequestStr('db.table.prefix'),
                        'check_table' => 'topic'
                    )));
            if ($bResult) {
                return true;
            }
        }
        return $this->addError(join('<br/>', $aErrors));
    }

    protected function processDbCheck()
    {
        /**
         * Коннект к серверу БД
         */
        if (!$oDb = $this->getDBConnection(InstallCore::getRequestStr('db.params.host'),
            InstallCore::getRequestStr('db.params.port'), InstallCore::getRequestStr('db.params.user'),
            InstallCore::getRequestStr('db.params.pass'))
        ) {
            return false;
        }
        /**
         * Выбор БД
         */
        $sNameDb = InstallCore::getRequestStr('db.params.dbname');
        if (!InstallCore::getRequest('db_create')) {
            if (!@mysqli_select_db($oDb, $sNameDb)) {
                return $this->addError(InstallCore::getLang('steps.installDb.errors.db_not_found'));
            }
        } else {
            /**
             * Пытаемся создать БД
             */
            @mysqli_query($oDb,
                "CREATE DATABASE IF NOT EXISTS `{$sNameDb}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
            if (!@mysqli_select_db($oDb, $sNameDb)) {
                return $this->addError(InstallCore::getLang('steps.installDb.errors.db_not_create'));
            }
        }
        /**
         * Проверяем корректность префикса таблиц
         */
        if (!preg_match('#^[a-z0-9\_]*$#i', InstallCore::getRequestStr('db.table.prefix'))) {
            return $this->addError(InstallCore::getLang('steps.installDb.errors.db_table_prefix'));
        }
        /**
         * Определяем поддержку InnoDB
         */
        $sEngineDB = 'MyISAM';
        if ($aRes = @mysqli_query($oDb, 'SHOW ENGINES')) {
            while ($aRow = mysqli_fetch_assoc($aRes)) {
                if (strtoupper($aRow['Engine']) == 'INNODB' and in_array(strtoupper($aRow['Support']),
                        array('DEFAULT', 'YES'))
                ) {
                    $sEngineDB = 'InnoDB';
                    break;
                }
            }
        }

        $sPathRootWeb = $this->getPathRootWeb();
        $aDirs = array();
        $sDirs = trim(str_replace('http://' . $_SERVER['HTTP_HOST'], '', $sPathRootWeb), '/');
        if ($sDirs != '') {
            $aDirs = explode('/', $sDirs);
        }

        /**
         * Прописываем параметры в конфиг
         */
        $aSave = array(
            'db.params.host'          => InstallCore::getRequestStr('db.params.host'),
            'db.params.port'          => InstallCore::getRequestStr('db.params.port'),
            'db.params.dbname'        => InstallCore::getRequestStr('db.params.dbname'),
            'db.params.user'          => InstallCore::getRequestStr('db.params.user'),
            'db.params.pass'          => InstallCore::getRequestStr('db.params.pass'),
            'db.table.prefix'         => InstallCore::getRequestStr('db.table.prefix'),
            'db.tables.engine'        => $sEngineDB,
            'path.root.web'           => $sPathRootWeb,
            'path.offset_request_url' => count($aDirs),
        );
        if (!InstallConfig::save($aSave)) {
            return $this->addError(InstallConfig::$sLastError);
        }
        return array($oDb, $sEngineDB);
    }

    protected function getPathRootWeb()
    {
        $sPath = rtrim('http://' . $_SERVER['HTTP_HOST'], '/') . str_replace('/install/index.php', '',
                $_SERVER['PHP_SELF']);
        return preg_replace('#\/application$#', '', $sPath);
    }
}