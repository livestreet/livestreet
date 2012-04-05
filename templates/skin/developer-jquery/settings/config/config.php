<?php

$config = array();

$config['view']['theme'] = 'default';

$config['view']['name']        = 'LiveStreet';
$config['view']['description'] = 'Официальный сайт бесплатного движка социальной сети';

$config['head']['default']['js'] = Config::Get('head.default.js');
$config['head']['default']['js'][] = '___path.static.skin___/js/developer-jquery.js';

$config['head']['default']['css'] = array(
	"___path.static.skin___/css/reset.css",
	"___path.static.skin___/css/base.css",
	"___path.root.engine_lib___/external/jquery/markitup/skins/simple/style.css",
	"___path.root.engine_lib___/external/jquery/markitup/sets/default/style.css",
	"___path.root.engine_lib___/external/jquery/jcrop/jquery.Jcrop.css",
	"___path.root.engine_lib___/external/prettify/prettify.css",
	"___path.static.skin___/css/grid.css",
	"___path.static.skin___/css/common.css",
	"___path.static.skin___/css/text.css",
	"___path.static.skin___/css/forms.css",
	"___path.static.skin___/css/buttons.css",
	"___path.static.skin___/css/navs.css",
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
	"___path.static.skin___/css/mobile.css",
	"___path.static.skin___/themes/___view.theme___/style.css",
);

$config['block']['rule_profile'] = array(
	'action'  => array( 'profile' ),
	'blocks'  => array( 'right' => array('actions/ActionProfile/sidebar.tpl') ),
);
/*$config['block']['rule_talk_inbox'] = array(
	'action'  => array( 'talk' => array('inbox','') ),
	'blocks'  => array( 'right' => array('actions/ActionTalk/filter.tpl') ),
);
$config['block']['rule_talk_add'] = array(
	'action'  => array( 'talk' => array('add') ),
	'blocks'  => array( 'right' => array('actions/ActionTalk/friends.tpl') ),
);
$config['block']['rule_talk_read'] = array(
	'action'  => array( 'talk' => array('read') ),
	'blocks'  => array( 'right' => array('actions/ActionTalk/speakers.tpl') ),
);
*/
$config['view']['img_resize_width'] = 570;
$config['profile']['photo_resize_width'] = 290;

$config['module']['blog']['avatar_size'] = array(100,64,48,24,0);
$config['module']['user']['avatar_size'] = array(100,64,48,24,0);


return $config;
?>