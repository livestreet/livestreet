<?php
/**
 * Консольный запуск шагов инсталляции
 * Позволяет выполнить обновление на новую версию через консоль, это актуально при большой БД
 * Запускать шаги желательно от имени пользователя под которым работает веб-сервер, это поможет избежать проблем с правами доступа.
 *
 * Пример запуска обновления с 1.0.3 версии LS до 2.0.0:
 * php -f ./console.php run update_version 1.0.3
 */


error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);

require_once('bootstrap.php');

function console_echo($sMsg, $bExit = false)
{
    echo("{$sMsg} \n");
    if ($bExit) {
        exit();
    }
}

/**
 * Init core
 */
$oInstall = new InstallCore(array('fake' => array()));
/**
 * Получаем параметры
 */
$aArgs = isset($_SERVER['argv']) ? $_SERVER['argv'] : array();
if (count($aArgs) == 1) {
    console_echo(InstallCore::getLang('console.command_empty'), true);
}

/**
 * Ищем команду
 */
$sCommand = strtolower($aArgs[1]);
if ($sCommand == 'run') {
    if (!isset($aArgs[2])) {
        console_echo(InstallCore::getLang('console.command.run.params_step_empty'), true);
    }
    $sStep = install_func_camelize($aArgs[2]);
    $sClass = 'InstallStep' . ucfirst($sStep);
    if (!class_exists($sClass)) {
        console_echo(InstallCore::getLang('Not found step ' . $sStep), true);
    }
    /**
     * Хардкодим параметр для шага обновления
     * TODO: убрать и переделать на нормальную консольную утилиту
     */
    $_REQUEST['from_version'] = isset($aArgs[3]) ? $aArgs[3] : '';
    /**
     * Создаем объект шага и запускаем его
     */
    $oStep = new $sClass('fake', array());
    if ($oStep->process()) {
        console_echo(InstallCore::getLang('console.command_successful'));
    } else {
        $aErrors = $oStep->getErrors();
        if ($aErrors) {
            $sMsgError = join("\n", $aErrors);
        } else {
            $sMsgError = InstallCore::getLang('console.command_failed');
        }
        console_echo($sMsgError, true);
    }
} else {
    console_echo(InstallCore::getLang('console.command_empty'), true);
}