<?php

$config = array();

$config['view']['theme'] = 'default';


/* Grid type:
 * 
 * fluid - резина
 * fixed - фиксированная ширина
 */
$config['view']['grid']['type'] = 'fixed';

/* Fluid settings */
$config['view']['grid']['fluid_min_width'] = 1000;
$config['view']['grid']['fluid_max_width'] = 1400;

/* Fixed settings */
$config['view']['grid']['fixed_width'] = 1000;



$config['head']['default']['js'] = Config::Get('head.default.js');
$config['head']['default']['js'][] = '___path.root.server___/templates/framework/js/dropdown.js';
$config['head']['default']['js'][] = '___path.static.skin___/js/template.js';

$config['head']['default']['css'] = array(
	// Framework styles
	"___path.root.server___/templates/framework/css/reset.css",
	"___path.root.server___/templates/framework/css/helpers.css",
	"___path.root.server___/templates/framework/css/text.css",
	"___path.root.server___/templates/framework/css/buttons.css",
	"___path.root.server___/templates/framework/css/forms.css",
	"___path.root.server___/templates/framework/css/navs.css",
	"___path.root.server___/templates/framework/css/dropdowns.css",

	// Template styles
	"___path.static.skin___/css/base.css",
	"___path.root.engine_lib___/external/jquery/markitup/skins/simple/style.css",
	"___path.root.engine_lib___/external/jquery/markitup/sets/default/style.css",
	"___path.root.engine_lib___/external/jquery/jcrop/jquery.Jcrop.css",
	"___path.root.engine_lib___/external/prettify/prettify.css",
	"___path.static.skin___/css/grid.css",
	"___path.static.skin___/css/common.css",
	"___path.static.skin___/css/icons.css",
	"___path.static.skin___/css/tables.css",
	"___path.static.skin___/css/topic.css",
	"___path.static.skin___/css/comments.css",
	"___path.static.skin___/css/blocks.css",
	"___path.static.skin___/css/modals.css",
	"___path.static.skin___/css/blog.css",
	"___path.static.skin___/css/profile.css",
	"___path.static.skin___/css/wall.css",
	"___path.static.skin___/css/infobox.css",
	"___path.static.skin___/css/jquery.notifier.css",
	"___path.static.skin___/css/smoothness/jquery-ui.css",
	"___path.static.skin___/themes/___view.theme___/style.css",
	"___path.static.skin___/css/print.css",
);


return $config;
?>