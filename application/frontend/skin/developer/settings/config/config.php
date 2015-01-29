<?php

$config = array();

/**
 * Тип сетки
 *
 * fluid - резина
 * fixed - фиксированная ширина
 */
$config['view']['grid']['type'] = 'fluid';

// Настройки резины
$config['view']['grid']['fluid_min_width'] = '320px';
$config['view']['grid']['fluid_max_width'] = '1000px';

// Настройки фиксированная ширина
$config['view']['grid']['fixed_width'] = '1000px';

// Показывать баннер с лого и описанием или нет
$config['view']['layout_show_banner'] = true;

// Подключение скриптов шаблона
$config['head']['template']['js'] = array(
	'___path.skin.assets.web___/js/init.js'
);

// Подключение стилей шаблона
$config['head']['template']['css'] = array(
	"___path.skin.assets.web___/css/layout.css",
	"___path.skin.assets.web___/css/print.css"
);

return $config;