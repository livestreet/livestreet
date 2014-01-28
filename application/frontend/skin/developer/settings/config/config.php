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
$config['view']['grid']['fluid_min_width'] = 320;
$config['view']['grid']['fluid_max_width'] = 1200;

/* Fixed settings */
$config['view']['grid']['fixed_width'] = 1000;

$config['head']['default']['js'] = Config::Get('head.default.js');
$config['head']['default']['js'][] = '___path.skin.assets.web___/js/init.js';

$config['head']['default']['css'] = array_merge(Config::Get('head.default.css'), array(
	// Template styles
	"___path.skin.assets.web___/css/base.css",
	"___path.framework.frontend.web___/js/vendor/jquery-ui/css/smoothness/jquery-ui-1.10.2.custom.css",
	"___path.framework.frontend.web___/js/vendor/markitup/skins/synio/style.css",
	"___path.framework.frontend.web___/js/vendor/markitup/sets/synio/style.css",
	"___path.framework.frontend.web___/js/vendor/jcrop/jquery.Jcrop.css",
	"___path.framework.frontend.web___/js/vendor/prettify/prettify.css",
	"___path.framework.frontend.web___/js/vendor/prettyphoto/css/prettyphoto.css",
	"___path.framework.frontend.web___/js/vendor/notifier/jquery.notifier.css",
	"___path.framework.frontend.web___/js/vendor/fotorama/fotorama.css",
	"___path.skin.assets.web___/css/grid.css",
	"___path.skin.assets.web___/css/forms.css",
	"___path.skin.assets.web___/css/common.css",
	"___path.skin.assets.web___/css/vote.css",
	"___path.skin.assets.web___/css/icons.css",
	"___path.skin.assets.web___/css/navs.css",
	"___path.skin.assets.web___/css/tables.css",
	"___path.skin.assets.web___/css/topic.css",
	"___path.skin.assets.web___/css/comments.css",
	"___path.skin.assets.web___/css/blocks.css",
	"___path.skin.assets.web___/css/blog.css",
	"___path.skin.assets.web___/css/modals.css",
	"___path.skin.assets.web___/css/profile.css",
	"___path.skin.assets.web___/css/wall.css",
	"___path.skin.assets.web___/css/activity.css",
	"___path.skin.assets.web___/css/admin.css",
	"___path.skin.assets.web___/css/toolbar.css",
	"___path.skin.assets.web___/css/poll.css",
	"___path.skin.assets.web___/css/messages.css",
	"___path.skin.assets.web___/css/sort.css",
	"___path.skin.web___/themes/___view.theme___/style.css",
	"___path.skin.assets.web___/css/print.css",
));


return $config;
?>