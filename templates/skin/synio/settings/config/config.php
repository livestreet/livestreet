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
$config['head']['default']['js'][] = '___path.static.assets___/js/init.js';
$config['head']['default']['js'][] = '___path.static.assets___/js/stream.js';
$config['head']['default']['js'][] = '___path.static.assets___/js/blog.js';

/* JavaScript */
$config['head']['default']['css'] = array_merge(Config::Get('head.default.css'), array(
	// Template styles
	"___path.static.assets___/css/base.css",
	"___path.static.framework___/js/vendor/jquery-ui/css/smoothness/jquery-ui-1.10.2.custom.css",
	"___path.static.framework___/js/vendor/markitup/skins/synio/style.css",
	"___path.static.framework___/js/vendor/markitup/sets/synio/style.css",
	"___path.static.framework___/js/vendor/jcrop/jquery.Jcrop.css",
	"___path.static.framework___/js/vendor/prettify/prettify.css",
	"___path.static.framework___/js/vendor/prettyphoto/css/prettyphoto.css",
	"___path.static.assets___/css/grid.css",
	"___path.static.assets___/css/common.css",
	"___path.static.assets___/css/text.css",
	"___path.static.assets___/css/forms.css",

	"___path.static.assets___/css/buttons.css",
	"___path.static.assets___/css/tooltip.css",
	"___path.static.assets___/css/popovers.css",
	"___path.static.assets___/css/modals.css",
	"___path.static.assets___/css/dropdowns.css",
	"___path.static.assets___/css/toolbar.css",
	"___path.static.assets___/css/vendor/jquery.notifier.css",
	"___path.static.assets___/css/navs.css",
	"___path.static.assets___/css/icons.css",
	"___path.static.assets___/css/tables.css",
	"___path.static.assets___/css/topic.css",
	"___path.static.assets___/css/photoset.css",
	"___path.static.assets___/css/comments.css",
	"___path.static.assets___/css/blocks.css",
	"___path.static.assets___/css/blog.css",
	"___path.static.assets___/css/profile.css",
	"___path.static.assets___/css/wall.css",
	"___path.static.assets___/css/activity.css",
	"___path.static.assets___/css/admin.css",
	"___path.static.assets___/css/poll.css",
	"___path.static.skin___/themes/___view.theme___/style.css",
	"___path.static.assets___/css/print.css",
));


/**
 * Blocks
 */
$config['block']['rule_profile'] = array(
	'action' => array( 'profile', 'talk', 'settings' ),
	'blocks' => array( 
		'right' => array(
			'blocks/block.userPhoto.tpl'   =>array('priority' => 100),
			'blocks/block.userNav.tpl'     =>array('priority' => 50),
			'blocks/block.userNote.tpl'    =>array('priority' => 25),
			'blocks/block.userActions.tpl' =>array('priority' => 1),
		) 
	)
);

return $config;
?>