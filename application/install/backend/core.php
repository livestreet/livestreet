<?php

class InstallCore
{

    const COOKIE_NAME = 'install_data';

    static public $aGroups = array();
    static public $aGroupsParams = array();
    static public $oLayout = null;
    static public $aLangMsg = array();
    static public $aStoredData = array();

    public function __construct($aGroups)
    {
        if (!$aGroups) {
            throw new Exception('Empty groups');
        }

        $this->defineGroups($aGroups);
        $this->loadLang();
        $this->loadStoredData();
        self::$oLayout = new InstallTemplate('layout.tpl.php');
    }

    protected function defineGroups($aGroups)
    {
        $aGroupsResult = array();
        $aParamsResult = array();
        foreach ($aGroups as $sGroup => $aSteps) {
            foreach ($aSteps as $sStep => $aParams) {
                if (is_int($sStep)) {
                    $sStep = $aParams;
                    $aParams = array();
                }
                $aParamsResult[$sGroup][$sStep] = $aParams;
                $aGroupsResult[$sGroup][] = $sStep;
            }
        }
        self::$aGroups = $aGroupsResult;
        self::$aGroupsParams = $aParamsResult;
    }

    /**
     * Запускает процесс инсталляции
     */
    public function run()
    {
        if (self::getRequest('reset')) {
            self::$aStoredData = array();
            self::saveStoredData();
        }
        /**
         * Получаем текущую группу
         */
        $sGroup = self::getRequestStr('group');
        if ($sGroup) {
            return $this->runGroup($sGroup);
        }
        /**
         * Если группа не определена и она только одна - запускаем
         */
        if (!$sGroup and count(self::$aGroups) == 1) {
            $aGroupNames = array_keys(self::$aGroups);
            return $this->runGroup(array_shift($aGroupNames));
        }
        /**
         * Показываем страницу выбора группы
         */
        self::setPreviousStepHide();
        self::setNextStepHide();
        self::setInstallResetHide();
        self::render('index.tpl.php', array('groups' => array_keys(self::$aGroups)));
    }

    public function runGroup($sGroup)
    {
        if (!isset(self::$aGroups[$sGroup])) {
            return self::renderError('Not found group');
        }
        $aGroup = self::$aGroups[$sGroup];
        /**
         * Определяем текущий шаг
         * Смотрим его в куках, если там нет, то используем первый
         * Шаг сквозной для всех групп, поэтому при установке у одной группы - у других он сбрасывается на первый
         */
        $sCurrentStep = self::getStoredData('step');
        if (!$sCurrentStep or !in_array($sCurrentStep, $aGroup)) {
            if (!$sFirst = array_shift($aGroup)) {
                return self::renderError('Not found steps');
            }
            $sCurrentStep = $sFirst;
        }

        $sNextStep = self::getNextStep($sGroup, $sCurrentStep);
        $sPrevousStep = self::getPreviousStep($sGroup, $sCurrentStep);
        if (!$sPrevousStep) {

        }
        if (!$sNextStep) {
            self::setNextStepHide();
        }
        if (isset($_POST['action_previous'])) {
            if ($sPrevousStep) {
                InstallCore::setStoredData('step', $sPrevousStep);
                InstallCore::location($sGroup);
            } elseif (count(self::$aGroups) > 1) {
                /**
                 * Перенаправлям на страницу выбора группы
                 */
                self::location();
            }
        }
        return $this->runStep($sCurrentStep, $sGroup);
    }

    public function runStep($sStep, $sGroup)
    {
        $sClass = 'InstallStep' . ucfirst($sStep);
        if (!class_exists($sClass)) {
            return self::renderError('Not found step ' . $sStep);
        }
        $aParams = isset(self::$aGroupsParams[$sGroup][$sStep]) ? self::$aGroupsParams[$sGroup][$sStep] : array();
        $oStep = new $sClass($sGroup, $aParams);
        if (isset($_POST['action_next'])) {
            /**
             * Сначала обрабатываем шаг
             */
            $oStep->_process();
        }
        $oStep->_show();
    }

    protected function loadLang()
    {
        $sLang = 'ru';
        $sFilePath = INSTALL_DIR . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . $sLang . '.php';
        if (file_exists($sFilePath)) {
            self::$aLangMsg = require($sFilePath);
        }
    }

    protected function loadStoredData()
    {
        $aData = isset($_COOKIE[self::COOKIE_NAME]) ? $_COOKIE[self::COOKIE_NAME] : '';
        if (get_magic_quotes_gpc()) {
            $this->stripslashes($aData);
        }
        self::$aStoredData = $aData ? @unserialize($aData) : array();
    }

    static public function saveStoredData()
    {
        $sData = serialize(self::$aStoredData);
        setcookie(self::COOKIE_NAME, $sData, time() + 60 * 60 * 24);
    }

    static public function getStoredData($sName, $mDefault = null)
    {
        return isset(self::$aStoredData[$sName]) ? self::$aStoredData[$sName] : $mDefault;
    }

    static public function setStoredData($sName, $mValue)
    {
        self::$aStoredData[$sName] = $mValue;
        self::saveStoredData();
    }

    static public function getDataFilePath($sFile)
    {
        return dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $sFile;
    }

    static public function renderError($sMsg, $sTitle = null)
    {
        self::render('error.tpl.php', array('msg' => $sMsg, 'title' => $sTitle));
    }

    static public function render($sTemplate, $aVars = array())
    {

        if (is_object($sTemplate)) {
            $oTemplate = $sTemplate;
            self::$oLayout->assign($aVars);
        } else {
            $oTemplate = new InstallTemplate($sTemplate, $aVars);
        }

        $oTemplate->setParent(self::$oLayout);
        $sContent = $oTemplate->render();
        self::$oLayout->assign('content', $sContent);

        echo(self::$oLayout->render());
        exit();
    }

    static public function assign($mName, $mValue = null)
    {
        self::$oLayout->assign($mName, $mValue);
    }

    static public function getRequest($sName, $mDefault = null)
    {
        $sName = str_replace('.', '_', $sName);
        return isset($_REQUEST[$sName]) ? $_REQUEST[$sName] : $mDefault;
    }

    static public function getRequestStr($sName, $mDefault = null)
    {
        $sVal = self::getRequest($sName, $mDefault);
        return is_scalar($sVal) ? (string)$sVal : '';
    }

    static public function getLang($sName)
    {
        if (strpos($sName, '.')) {
            $sLang = self::$aLangMsg;
            $aKeys = explode('.', $sName);
            foreach ($aKeys as $k) {
                if (isset($sLang[$k])) {
                    $sLang = $sLang[$k];
                } else {
                    return $sName;
                }
            }
        } else {
            if (isset(self::$aLangMsg[$sName])) {
                $sLang = self::$aLangMsg[$sName];
            } else {
                return $sName;
            }
        }
        return $sLang;
    }

    static public function getNextStep($sGroup, $sStep = null)
    {
        $aGroups = self::$aGroups;
        if (isset($aGroups[$sGroup])) {
            if (is_null($sStep)) {
                return array_shift($aGroups[$sGroup]);
            } else {
                if (false !== ($iPos = array_search($sStep, $aGroups[$sGroup]))) {
                    $aNext = array_slice($aGroups[$sGroup], $iPos + 1, 1);
                    $sNext = current($aNext);
                    return $sNext !== false ? $sNext : null;
                }
            }
        } else {
            return null;
        }
    }

    static public function getPreviousStep($sGroup, $sStep = null)
    {
        $aGroups = self::$aGroups;
        if (isset($aGroups[$sGroup])) {
            if (is_null($sStep)) {
                return array_shift($aGroups[$sGroup]);
            } else {
                if ($iPos = array_search($sStep, $aGroups[$sGroup])) {
                    $aPrev = array_slice($aGroups[$sGroup], $iPos - 1, 1);
                    $sPrev = current($aPrev);
                    return $sPrev !== false ? $sPrev : null;
                }
            }
        } else {
            return null;
        }
    }

    static public function location($sGroup = '')
    {
        header('Location: ./' . ($sGroup ? '?group=' . $sGroup : ''));
        exit;
    }

    static public function setInstallResetHide($bHide = true)
    {
        self::$oLayout->assign('install_reset_hide', $bHide);
    }

    static public function setNextStepHide($bHide = true)
    {
        self::$oLayout->assign('next_step_hide', $bHide);
    }

    static public function setNextStepDisable($bDisable = true)
    {
        self::$oLayout->assign('next_step_disable', $bDisable);
    }

    static public function setPreviousStepHide($bHide = true)
    {
        self::$oLayout->assign('previous_step_hide', $bHide);
    }

    static public function setPreviousStepDisable($bDisable = true)
    {
        self::$oLayout->assign('previous_step_disable', $bDisable);
    }

    protected function stripslashes(&$data)
    {
        if (is_array($data)) {
            foreach ($data as $sKey => $value) {
                if (is_array($value)) {
                    $this->stripslashes($data[$sKey]);
                } else {
                    $data[$sKey] = stripslashes($value);
                }
            }
        } else {
            $data = stripslashes($data);
        }
    }

    /**
     * Выполняет транслитерацию текста
     *
     * @param $sText
     * @param bool $bLower
     * @return mixed|string
     */
    static public function transliteration($sText, $bLower = true)
    {
        $aConverter = array(
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ь' => "'",
            'ы' => 'y',
            'ъ' => "'",
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Ж' => 'Zh',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'Y',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Sch',
            'Ь' => "'",
            'Ы' => 'Y',
            'Ъ' => "'",
            'Э' => 'E',
            'Ю' => 'Yu',
            'Я' => 'Ya',
            " " => "-",
            "." => "",
            "/" => "-",
            "_" => "-",
            'і' => 'i',
            'І' => 'I',
            'ї' => 'i',
            'Ї' => 'I',
            'є' => 'e',
            'Є' => 'E',
            'ґ' => 'g',
            'Ґ' => 'G',
            '«' => '',
            '»' => '',
        );
        $sRes = strtr($sText, $aConverter);
        if ($sResIconv = @iconv("UTF-8", "ISO-8859-1//IGNORE//TRANSLIT", $sRes)) {
            $sRes = $sResIconv;
        }
        $sRes = preg_replace('/[^A-Za-z0-9\-]/', '', $sRes);
        $sRes = preg_replace('/\-{2,}/', '-', $sRes);
        if ($bLower) {
            $sRes = strtolower($sRes);
        }
        return $sRes;
    }
}