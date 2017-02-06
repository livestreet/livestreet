<?php

define('INSTALL_DIR', dirname(__FILE__));
define('VERSION', '2.0.1');

function install_func_underscore($sStr)
{
    return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $sStr));
}

function install_func_camelize($sStr)
{
    $aParts = explode('_', $sStr);
    $sCamelized = '';
    foreach ($aParts as $sPart) {
        $sCamelized .= ucfirst($sPart);
    }
    return $sCamelized;
}

/**
 * Загрузка классов инсталлятора
 * пример - InstallCore, InstallStepInit
 *
 * @param $sClassName
 * @return bool
 */
function install_autoload($sClassName)
{
    $aPath = explode('_', install_func_underscore($sClassName));
    if (count($aPath) >= 2 and $aPath[0] == 'install') {
        array_shift($aPath);
        if ($aPath[0] == 'step' and count($aPath) > 1) {
            array_shift($aPath);
            $sDir = 'step';
            $sName = ucfirst(install_func_camelize(join('_', $aPath)));
            $sName{0} = strtolower($sName{0});
        } else {
            $sName = array_pop($aPath);
            $sDir = join(DIRECTORY_SEPARATOR, $aPath);
        }
        $sPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'backend' . DIRECTORY_SEPARATOR . ($sDir ? $sDir . DIRECTORY_SEPARATOR : '') . $sName . '.php';
        if (file_exists($sPath)) {
            require_once($sPath);
            return true;
        }
    }
    /**
     * Проверяем соответствие PSR-0 для библиотек фреймворка
     */
    $sClassName = ltrim($sClassName, '\\');
    $sFileName = '';
    $sNameSpace = '';
    if ($iLastNsPos = strrpos($sClassName, '\\')) {
        $sNameSpace = substr($sClassName, 0, $iLastNsPos);
        $sClassName = substr($sClassName, $iLastNsPos + 1);
        $sFileName = str_replace('\\', DIRECTORY_SEPARATOR, $sNameSpace) . DIRECTORY_SEPARATOR;
    }
    $sFileName .= str_replace('_', DIRECTORY_SEPARATOR, $sClassName) . '.php';
    $sFileName = dirname(dirname(INSTALL_DIR)) . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . $sFileName;
    if (file_exists($sFileName)) {
        require_once($sFileName);
        return true;
    }
    return false;
}

/**
 * Подключаем загрузкик классов
 */
spl_autoload_register('install_autoload');