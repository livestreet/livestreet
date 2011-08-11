<?php

$config['head']['default']['js']  = array(
	"___path.root.engine_lib___/external/jquery/jquery.js",
	"___path.root.engine_lib___/external/jquery/jquery-ui.js",
	"___path.root.engine_lib___/external/jquery/jquery.notifier.js",
	"___path.root.engine_lib___/external/jquery/jquery.jqmodal.js",
	"___path.root.engine_lib___/external/jquery/jquery.scrollto.js",
	"___path.root.engine_lib___/external/jquery/jquery.rich-array.min.js",
	"___path.root.engine_lib___/external/jquery/markitup/jquery.markitup.js",
	"___path.root.engine_lib___/external/jquery/jquery.form.js",
	"___path.root.engine_lib___/external/jquery/jquery.jqplugin.js",
	"___path.root.engine_lib___/external/jquery/jquery.cookie.js",
	"___path.root.engine_lib___/external/jquery/jquery.serializejson.js",
	"___path.static.skin___/js/main.js",
	"___path.static.skin___/js/favourite.js",
	"___path.static.skin___/js/blocks.js",
	"___path.static.skin___/js/talk.js",
	"___path.static.skin___/js/vote.js",
	"___path.static.skin___/js/poll.js",
	"___path.static.skin___/js/comments.js",
	"___path.static.skin___/js/blog.js",
	"___path.static.skin___/js/friend.js",
	"___path.static.skin___/js/userfeed.js",
	"___path.static.skin___/js/stream.js",
	"___path.static.skin___/js/photoset.js",
	"___path.static.skin___/js/markup_settings.js",
);
$config['head']['default']['css'] = array(
	"___path.static.skin___/css/reset.css",
	"___path.root.engine_lib___/external/jquery/markitup/skins/simple/style.css",
	"___path.root.engine_lib___/external/jquery/markitup/sets/default/style.css",	
	"___path.static.skin___/css/main.css",
	"___path.static.skin___/css/grid.css",
	"___path.static.skin___/css/common.css",
	"___path.static.skin___/css/forms.css",
	"___path.static.skin___/css/popups.css",
	"___path.static.skin___/css/topic.css",
	"___path.static.skin___/css/comments.css",
	"___path.static.skin___/css/blocks.css",
	"___path.static.skin___/css/jquery.jqmodal.css",
	"___path.static.skin___/css/jquery.notifier.css",
	"___path.static.skin___/css/smoothness/jquery-ui.css",
);

/**
 * Настройки вывода блоков
 */
$config['block']['rule_blogs'] = array(
	'action'  => array( 'blogs', 'settings' ),
	'blocks'  => array( 'right' => array('stream') ),
);

return $config;
?>