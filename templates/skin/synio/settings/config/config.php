<?php

$config = array();

// Максимальная вложенность комментов при отображении
$config['module']['comment']['max_tree'] = 5;

// Ограничение на вывод числа друзей пользователя на странице его профиля
$config['module']['user']['friend_on_profile']    = 18;

$config['view']['theme'] = 'default';


/** 
 * Grid type:
 * 
 * fluid - резина
 * fixed - фиксированная ширина
 */
$config['view']['grid']['type'] = 'fluid';

/* Fluid settings */
$config['view']['grid']['fluid_min_width'] = 976; // Min - 976px
$config['view']['grid']['fluid_max_width'] = 1100;

/* Fixed settings */
$config['view']['grid']['fixed_width'] = 976;


/**
 * Assets
 */

/* Styles */
$config['head']['default']['js'] = Config::Get('head.default.js');
$config['head']['default']['js'][] = '___path.static.skin___/js/init.js';

/* JavaScript */
$config['head']['default']['css'] = array_merge(Config::Get('head.default.css'), array(
	// Template styles
	"___path.static.skin___/css/base.css",
	"___path.static.framework___/js/vendor/jquery-ui/css/smoothness/jquery-ui-1.10.2.custom.css",
	"___path.static.framework___/js/vendor/markitup/skins/synio/style.css",
	"___path.static.framework___/js/vendor/markitup/sets/synio/style.css",
	"___path.static.framework___/js/vendor/jcrop/jquery.Jcrop.css",
	"___path.static.framework___/js/vendor/prettify/prettify.css",
	"___path.static.framework___/js/vendor/prettyPhoto/css/prettyPhoto.css",
	"___path.static.skin___/css/grid.css",
	"___path.static.skin___/css/common.css",
	"___path.static.skin___/css/text.css",
	"___path.static.skin___/css/forms.css",

	"___path.static.skin___/css/buttons.css",
	"___path.static.skin___/css/tooltip.css",
	"___path.static.skin___/css/popovers.css",
	"___path.static.skin___/css/modals.css",
	"___path.static.skin___/css/dropdowns.css",
	"___path.static.skin___/css/toolbar.css",

	"___path.static.skin___/css/navs.css",
	"___path.static.skin___/css/icons.css",
	"___path.static.skin___/css/tables.css",
	"___path.static.skin___/css/topic.css",
	"___path.static.skin___/css/photoset.css",
	"___path.static.skin___/css/comments.css",
	"___path.static.skin___/css/blocks.css",
	"___path.static.skin___/css/blog.css",
	"___path.static.skin___/css/profile.css",
	"___path.static.skin___/css/wall.css",
	"___path.static.skin___/css/jquery.notifier.css",
	"___path.static.skin___/themes/___view.theme___/style.css",
	"___path.static.skin___/css/print.css",
));

return $config;
?>