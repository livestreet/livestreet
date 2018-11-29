<?php
/**
 * Настройки для локального сервера.
 * Для использования - переименовать файл в config.local.php
 * Именно в этом файле необходимо переопределять все настройки конфига
 */

/**
 * Настройка базы данных
 */
$config['db']['params']['host'] = 'localhost';
$config['db']['params']['port'] = '3306';
$config['db']['params']['user'] = 'root';
$config['db']['params']['pass'] = '23197';
$config['db']['params']['type']   = 'mysqli';
$config['db']['params']['dbname'] = 'lsnew';
$config['db']['table']['prefix'] = 'ls_';

/**
 * Настройки кеширования
 */
$config['sys']['cache']['use'] = false;               // использовать кеширование или нет
$config['sys']['cache']['type'] = 'file';             // тип кеширования: file, xcache и memory. memory использует мемкеш, xcache - использует XCache

/**
 * Параметры обработки css/js-файлов
 */
$config['module']['asset']['force_https'] = true; // При использовании https принудительно заменять http на https у путях до css/js
$config['module']['asset']['css']['merge'] = true; // указывает на необходимость слияния css файлов
$config['module']['asset']['js']['merge'] = true; // указывает на необходимость слияния js файлов


$config['db']['tables']['engine'] = 'InnoDB';
$config['path']['root']['web'] = 'http://ls.new';
$config['path']['offset_request_url'] = 0;
$config['module']['blog']['encrypt'] = 'cdac5d673b886f84a5866469c0aa2586';
$config['module']['talk']['encrypt'] = 'a0c5ae1984861ff38c61b4606753ae6d';
$config['module']['security']['hash'] = 'd84d8caddd172bddcfa8916433c7443b';
return $config;