<?php

define('INSTALL_DIR',dirname(__FILE__));

function install_func_underscore($sStr) {
	return strtolower(preg_replace('/([^A-Z])([A-Z])/',"$1_$2",$sStr));
}

/**
 * Загрузка классов инсталлятора
 * пример - InstallCore, InstallStepInit
 *
 * @param $sClassName
 * @return bool
 */
function install_autoload($sClassName) {
	$aPath=explode('_',install_func_underscore($sClassName));
	if (count($aPath)<2 or $aPath[0]!='install') {
		return;
	}
	array_shift($aPath);
	$sName=array_pop($aPath);
	$sDir=join(DIRECTORY_SEPARATOR,$aPath);
	$sPath=dirname(__FILE__).DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.($sDir ? $sDir.DIRECTORY_SEPARATOR : '').$sName.'.php';
	if (file_exists($sPath)) {
		require_once($sPath);
		return true;
	}
	return false;
}

/**
 * Подключаем загрузкик классов
 */
spl_autoload_register('install_autoload');