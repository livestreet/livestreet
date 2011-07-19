<?php

$config['head']['default']['js']  = array(
	"___path.root.engine_lib___/external/JsHttpRequest/JsHttpRequest.js", 
	"___path.static.skin___/js/libs/jquery.js",
	"___path.static.skin___/js/libs/jquery-ui-1.8.10.custom.min.js",
	"___path.static.skin___/js/libs/jquery.notifier.js",
	"___path.static.skin___/js/libs/jquery.jqmodal.js",
	"___path.static.skin___/js/libs/jquery.scrollto.js",
	"___path.static.skin___/js/libs/jquery.rich-array.min.js",
	"___path.static.skin___/js/libs/markitup/jquery.markitup.js",
	"___path.static.skin___/js/libs/markitup/sets/default/set.js",
	"___path.static.skin___/js/libs/jquery.form.js",
	"___path.static.skin___/js/libs/jquery.jqplugin.1.0.2.min.js",
	"___path.static.skin___/js/libs/jquery.cookie.js",
         "___path.static.skin___/js/ls.lang.ru.js",
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
         "___path.static.skin___/js/other.js",
);
$config['head']['default']['css'] = array(
	"___path.static.skin___/css/reset.css",
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
	"___path.static.skin___/js/libs/markitup/skins/simple/style.css",
	"___path.static.skin___/js/libs/markitup/sets/default/style.css",
	"___path.static.skin___/css/smoothness/jquery-ui-1.8.10.custom.css",
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