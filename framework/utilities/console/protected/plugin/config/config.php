<?php
/**
 * Конфиг
 */

$config = array();

// Переопределить имеющуюся переменную в конфиге:
// Переопределение роутера на наш новый Action - добавляем свой урл  http://domain.com/example
// Обратите внимание на '$root$' - говорит о том, что конфиг применяется к корневым настройкам движка, а не плагина
// $config['$root$']['router']['page']['example'] = 'PluginExample_ActionExample';

// Добавить новую переменную:
// $config['per_page'] = 15;
// Эта переменная будет доступна в плагине как Config::Get('plugin.example.per_page')

return $config;