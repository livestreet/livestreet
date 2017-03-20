<?php

abstract class InstallStep
{

    protected $aParams = array();
    protected $oTemplate = null;
    protected $sGroup = null;
    protected $aErrors = array();
    protected $rDbLink = null;
    protected $aDbParams = array();

    public function __construct($sGroup, $aParams = array())
    {
        $this->aParams = array_merge($this->aParams, $aParams);
        $this->oTemplate = new InstallTemplate($this->getTemplateName());
        $this->sGroup = $sGroup;
        $this->init();
    }

    public function init()
    {

    }

    public function getParam($sName, $mDefault = null)
    {
        return array_key_exists($sName, $this->aParams) ? $this->aParams[$sName] : $mDefault;
    }

    protected function getTemplateName()
    {
        return 'steps/' . $this->getName() . '.tpl.php';
    }

    public function getErrors()
    {
        return $this->aErrors;
    }

    protected function addError($sMsg)
    {
        $this->aErrors[] = $sMsg;
        return false;
    }

    public function getName()
    {
        $aPath = explode('_', install_func_underscore(get_class($this)));
        array_shift($aPath);
        array_shift($aPath);
        $sName = ucfirst(install_func_camelize(join('_', $aPath)));
        $sName{0} = strtolower($sName{0});
        return $sName;
    }

    public function getStepTitle()
    {
        return InstallCore::getLang('steps.' . $this->getName() . '.title');
    }

    public function getGroupTitle()
    {
        return InstallCore::getLang('groups.' . $this->sGroup . '.title');
    }

    /**
     * Выводит шаблон шага
     */
    protected function render()
    {
        InstallCore::assign('currentStep', $this);
        $this->oTemplate->assign('currentStep', $this);
        InstallCore::render($this->oTemplate);
    }

    protected function assign($mName, $mValue = null)
    {
        $this->oTemplate->assign($mName, $mValue);
    }

    /**
     * Запускает отображение шага
     */
    public function _show()
    {
        if ($this->beforeShow()) {
            $this->show();
            $this->afterShow();
            $this->render();
        } else {
            /**
             * todo: нужно изменить - показываем только страницу с ошибкой
             */
            return self::renderError('Вернитесь на прошлый шаг');
        }
    }

    /**
     * Запускает выполнение шага - когда пользователь жмет "Далее" на текущем шаге
     */
    public function _process()
    {
        if ($this->beforeProcess()) {
            if ($this->process()) {
                $this->afterProcess();
                /**
                 * Устанавливаем следующий шаг
                 */
                if ($sNextStep = InstallCore::getNextStep($this->sGroup, $this->getName())) {
                    InstallCore::setStoredData('step', $sNextStep);
                }
                /**
                 * Редиректим
                 */
                InstallCore::location($this->sGroup);
            } else {
                /**
                 * todo: здесь нужно показать сам текущий шаг с сообщением об ошибке
                 */
                //return InstallCore::renderError('Ошибка при выполнении шага');
            }
        } else {
            /**
             * todo: нужно изменить - показываем сам шаг с сообщением об ошибке
             */
            //return InstallCore::renderError('Невозможно выполнить шаг');
        }
    }

    protected function getDBConnection($sHost, $iPort, $sUser, $sPasswd, $bGeneral = false)
    {
        $oDb = @mysqli_connect($sHost, $sUser, $sPasswd, '', $iPort);
        if ($oDb) {
            /**
             * Валидация версии MySQL сервера
             */
            if (!version_compare(mysqli_get_server_info($oDb), '5.0.0', '>')) {
                return $this->addError(InstallCore::getLang('db.errors.db_version'));
            }
            mysqli_query($oDb, 'set names utf8');
            if ($bGeneral) {
                $this->rDbLink = $oDb;
            }
            return $oDb;
        }
        return $this->addError(InstallCore::getLang('db.errors.db_connect'));
    }

    protected function setDbParams($aParams)
    {
        $this->aDbParams = $aParams;
    }

    protected function importDumpDB($oDb, $sFile, $aParams = null)
    {
        $sFileQuery = @file_get_contents($sFile);

        if (is_null($aParams)) {
            $aParams = $this->aDbParams;
        }

        if (isset($aParams['prefix'])) {
            $sFileQuery = str_replace('prefix_', $aParams['prefix'], $sFileQuery);
        }
        $aQuery = preg_split("#;(\n|\r)#", $sFileQuery, null, PREG_SPLIT_NO_EMPTY);
        /**
         * Массив для сбора ошибок
         */
        $aErrors = array();

        if (isset($aParams['check_table'])) {
            /**
             * Смотрим, какие таблицы существуют в базе данных
             */
            $aDbTables = array();
            $aResult = @mysqli_query($oDb, "SHOW TABLES");
            if (!$aResult) {
                return array(
                    'result' => false,
                    'errors' => array($this->addError(InstallCore::getLang('db.errors.db_query')))
                );
            }
            while ($aRow = mysqli_fetch_array($aResult, MYSQLI_NUM)) {
                $aDbTables[] = $aRow[0];
            }
            /**
             * Если среди таблиц БД уже есть нужная таблица, то выполнять SQL-дамп не нужно
             */
            if (in_array($aParams['prefix'] . $aParams['check_table'], $aDbTables)) {
                return array('result' => true, 'errors' => array());
            }
        }
        /**
         * Проверка на существование поля
         */
        if (isset($aParams['check_table_field'])) {
            list($sCheckTable, $sCheckField) = $aParams['check_table_field'];
            $sCheckTable = str_replace('prefix_', $aParams['prefix'], $sCheckTable);
            $aResult = @mysqli_query($oDb, "SHOW FIELDS FROM `{$sCheckTable}`");
            if (!$aResult) {
                return array(
                    'result' => false,
                    'errors' => array($this->addError(InstallCore::getLang('db.errors.db_query')))
                );
            }
            while ($aRow = mysqli_fetch_assoc($aResult)) {
                if ($aRow['Field'] == $sCheckField) {
                    return array('result' => true, 'errors' => array());
                }
            }
        }
        /**
         * Выполняем запросы по очереди
         */
        foreach ($aQuery as $sQuery) {
            $sQuery = trim($sQuery);
            /**
             * Заменяем движек, если таковой указан в запросе
             */
            if (isset($aParams['engine'])) {
                $sQuery = str_ireplace('ENGINE=InnoDB', "ENGINE={$aParams['engine']}", $sQuery);
            }

            if ($sQuery != '') {
                $bResult = mysqli_query($oDb, $sQuery);
                if (!$bResult) {
                    $sError = mysqli_error($oDb);
                    if (isset($aParams['skip_fk_errors']) and $aParams['skip_fk_errors'] and
                        (stripos($sError, 'errno: 152') !== false or stripos($sError, 'errno: 150') !== false or (stripos($sError, '_fk') !== false and stripos($sError, 'DROP') !== false))
                    ) {
                        // пропускаем ошибки связанные с внешними ключами
                    } else {
                        $aErrors[] = mysqli_error($oDb);
                    }
                }
            }
        }

        return array('result' => count($aErrors) ? false : true, 'errors' => $aErrors);
    }

    protected function dbCheckTable($sTable)
    {
        /**
         * Смотрим, какие таблицы существуют в базе данных
         */
        $aDbTables = array();
        $aResult = @mysqli_query($this->rDbLink, "SHOW TABLES");
        if (!$aResult) {
            return false;
        }
        while ($aRow = mysqli_fetch_array($aResult, MYSQLI_NUM)) {
            $aDbTables[] = $aRow[0];
        }
        /**
         * Ищем необходимую таблицу
         */
        $aParams = $this->aDbParams;
        if (isset($aParams['prefix'])) {
            $sTable = str_replace('prefix_', $aParams['prefix'], $sTable);
        }
        if (in_array($sTable, $aDbTables)) {
            return true;
        }
        return false;
    }

    protected function dbQuery($sQuery)
    {
        $aParams = $this->aDbParams;
        if (isset($aParams['prefix'])) {
            $sQuery = str_replace('prefix_', $aParams['prefix'], $sQuery);
        }
        if (isset($aParams['engine'])) {
            $sQuery = str_ireplace('ENGINE=InnoDB', "ENGINE={$aParams['engine']}", $sQuery);
        }

        if ($rResult = mysqli_query($this->rDbLink, $sQuery)) {
            return $rResult;
        }
        return $this->addError(mysqli_error($this->rDbLink));
    }

    protected function dbSelect($sQuery)
    {
        $aResult = array();
        if ($rResult = $this->dbQuery($sQuery)) {
            while ($aRow = mysqli_fetch_assoc($rResult)) {
                $aResult[] = $aRow;
            }
        }
        return $aResult;
    }

    protected function dbSelectOne($sQuery)
    {
        $aResult = $this->dbSelect($sQuery);
        if ($aResult) {
            $aRow = reset($aResult);
            return $aRow;
        }
        return array();
    }

    protected function dbInsertQuery($sTable, $aFields, $bRun = true)
    {
        $aPath = array();
        foreach ($aFields as $sFields => $sValue) {
            if (is_int($sValue)) {
                $aPath[] = "`{$sFields}` = " . $sValue;
            } elseif (is_null($sValue)) {
                $aPath[] = "`{$sFields}` = 'NULL'";
            } else {
                $aPath[] = "`{$sFields}` = '" . mysqli_escape_string($this->rDbLink, $sValue) . "'";
            }
        }
        $sQuery = "INSERT INTO {$sTable} SET " . join(', ', $aPath);
        if ($bRun) {
            if ($this->dbQuery($sQuery)) {
                return mysqli_insert_id($this->rDbLink);
            }
            return false;
        } else {
            return $sQuery;
        }
    }

    protected function beforeShow()
    {
        return true;
    }

    protected function afterShow()
    {

    }

    protected function beforeProcess()
    {
        return true;
    }

    protected function afterProcess()
    {

    }

    public function show()
    {

    }

    public function process()
    {
        return true;
    }
}