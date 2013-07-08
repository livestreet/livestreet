<?php

$config = array();

$config['view']['theme'] = 'default';
$config['module']['user']['profile_photo_width'] = 300;

/** 
 * Grid type:
 * 
 * fluid - резина
 * fixed - фиксированная ширина
 */
$config['view']['grid']['type'] = 'fluid';

/* Fluid settings */
$config['view']['grid']['fluid_min_width'] = 900;
$config['view']['grid']['fluid_max_width'] = 1200;

/* Fixed settings */
$config['view']['grid']['fixed_width'] = 1000;

$config['head']['default']['js'] = Config::Get('head.default.js');
$config['head']['default']['js'][] = '___path.static.assets___/js/init.js';

$config['head']['default']['css'] = array_merge(Config::Get('head.default.css'), array(
	// Template styles
	"___path.static.assets___/css/base.css",
	"___path.static.framework___/js/vendor/jquery-ui/css/smoothness/jquery-ui-1.10.2.custom.css",
	"___path.static.framework___/js/vendor/markitup/skins/synio/style.css",
	"___path.static.framework___/js/vendor/markitup/sets/synio/style.css",
	"___path.static.framework___/js/vendor/jcrop/jquery.Jcrop.css",
	"___path.static.framework___/js/vendor/prettify/prettify.css",
	"___path.static.framework___/js/vendor/prettyphoto/css/prettyphoto.css",
	"___path.static.framework___/js/vendor/notifier/jquery.notifier.css",
	"___path.static.assets___/css/grid.css",
	"___path.static.assets___/css/forms.css",
	"___path.static.assets___/css/common.css",
	"___path.static.assets___/css/icons.css",
	"___path.static.assets___/css/navs.css",
	"___path.static.assets___/css/tables.css",
	"___path.static.assets___/css/topic.css",
	"___path.static.assets___/css/photoset.css",
	"___path.static.assets___/css/comments.css",
	"___path.static.assets___/css/blocks.css",
	"___path.static.assets___/css/blog.css",
	"___path.static.assets___/css/modals.css",
	"___path.static.assets___/css/profile.css",
	"___path.static.assets___/css/wall.css",
	"___path.static.assets___/css/activity.css",
	"___path.static.assets___/css/admin.css",
	"___path.static.assets___/css/toolbar.css",
	"___path.static.assets___/css/poll.css",
	"___path.static.skin___/themes/___view.theme___/style.css",
	"___path.static.assets___/css/print.css",
));


return $config;
?>