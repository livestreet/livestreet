<?php

$config = array();

/**
 * Grid type:
 *
 * fluid - резина
 * fixed - фиксированная ширина
 */
$config['view']['grid']['type'] = 'fluid';

/* Fluid settings */
$config['view']['grid']['fluid_min_width'] = '320px';
$config['view']['grid']['fluid_max_width'] = '1200px';

/* Fixed settings */
$config['view']['grid']['fixed_width'] = '1000px';

// Подключение скриптов шаблона
$config['head']['default']['js'] = array_merge(Config::Get('head.default.js'), array(
	'___path.skin.assets.web___/js/init.js'
));

// Подключение стилей шаблона
$config['head']['default']['css'] = array_merge(Config::Get('head.default.css'), array(
	"___path.skin.assets.web___/css/layout.css",
	"___path.skin.assets.web___/css/print.css"
));

return $config;