<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */


/************************************************************
 * Здесь выполняется основная подготовка движка к запуску
 * Внимание! Инициализация ядра здесь не происходит.
 * При необходимости нужно вручную выполнить Engine::getInstance()->Init();
 * Подключение автозагрузчика классов происходит только при инициализации ядра.
 */

/**
 * Формируем путь до фреймворка
 */
$sPathToFramework = dirname(__DIR__) . '/framework/';

/**
 * Подключаем ядро
 */
require_once($sPathToFramework . "/classes/engine/Engine.class.php");

/**
 * Определяем окружение
 * В зависимости от окружения будет дополнительно подгружаться необходимый конфиг.
 * Например, для окружения "production" будет загружен конфиг /application/config/config.production.php
 * По дефолту работает окружение "local"
 */
$sEnv = Engine::DetectEnvironment(array(
    'production' => array('your-machine-name'),
));


/**
 * Дополнительные подготовка фреймворка
 */
require_once($sPathToFramework . "/bootstrap/start.php");

/**
 * Подключаем загрузчик конфигов
 */
require_once($sPathToFramework . "/config/loader.php");

/**
 * Определяем дополнительные параметры роутинга
 */
$aRouterParams = array(/*
	'callback_after_parse_url' => function() {

	}
	*/
);



/**
 * Проверяем наличие директории install
 */
if (is_dir(rtrim(Config::Get('path.application.server'),
            '/') . '/install') && (!isset($_SERVER['HTTP_APP_ENV']) or $_SERVER['HTTP_APP_ENV'] != 'test')
) {
    $sUrl = rtrim(str_replace('index.php', '', $_SERVER['PHP_SELF']), '/\\') . '/application/install/';
    header('Location: ' . $sUrl, true, 302);
    exit();
}