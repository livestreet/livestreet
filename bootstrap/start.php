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
$sPathToFramework = dirname(dirname(__FILE__)) . '/framework/';

/**
 * Подключаем ядро
 */
require_once($sPathToFramework . '/classes/engine/Engine.class.php');

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
require_once($sPathToFramework . '/bootstrap/start.php');

/**
 * Подключаем загрузчик конфигов
 */
require_once($sPathToFramework . '/config/loader.php');

/**
 * Определяем дополнительные параметры роутинга
 */
$aRouterParams = array(
    'callback_after_parse_url' => array(
        function () {
            /**
             * Логика по ЧПУ топиков
             * Если URL соответствует шаблону ЧПУ топика, перенаправляем обработку на экшен/евент /blog/_show_topic_url/
             * Через свои параметры конфига передаем исходный URL
             * Суть обработки _show_topic_url в том, чтобы определить ID топика и корректность его URL, если он некорректен, то произвести его корректировку через внешний редирект на правильный URL
             * Если удалось определить топик и URL корректный, то происходит внутренний редирект на стандартный евент отображения топика по ID (/blog/12345.html)
             */

            $sUrlRequest = '';
            if (Router::GetAction()) {
                $sUrlRequest .= Router::GetAction();
            }
            if (Router::GetActionEvent()) {
                $sUrlRequest .= '/' . Router::GetActionEvent();
            }
            if (Router::GetParams()) {
                $sUrlRequest .= '/' . join('/', Router::GetParams());
            }
            /**
             * Функция для формирования регулярного выражения по маске URL топика
             *
             * @param string $sUrl
             * @return string
             */
            $funcMakePreg = function ($sUrl) {
                $sUrl = preg_quote(trim($sUrl, '/ '));
                return strtr($sUrl, Config::Get('module.topic.url_preg'));
            };
            $sPreg = $funcMakePreg(Config::Get('module.topic.url'));
            if (preg_match('@^' . $sPreg . '$@iu', $sUrlRequest)) {
                Router::SetAction('blog');
                Router::SetActionEvent('_show_topic_url');
                Router::SetParams(array());
                /**
                 * Хак - через конфиг передаем нужные параметры в обработчик эвента
                 * Модуль кеша здесь нельзя использовать, т.к. еще не произошло инициализации ядра
                 */
                Config::Set('module.topic._router_topic_original_url', $sUrlRequest);
            }
        }
    )
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