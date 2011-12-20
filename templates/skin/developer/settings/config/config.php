<?php

$config['head']['default']['js']  = array(
	"___path.root.engine_lib___/external/JsHttpRequest/JsHttpRequest.js",
	"___path.root.engine_lib___/external/MooTools_1.2/mootools-1.2.js?v=1.2.4",
	"___path.root.engine_lib___/external/MooTools_1.2/plugs/Roal/Roar.js",
	"___path.root.engine_lib___/external/MooTools_1.2/plugs/Autocompleter/Observer.js",
	"___path.root.engine_lib___/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.js",
	"___path.root.engine_lib___/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.Request.js",
	"___path.root.engine_lib___/external/MooTools_1.2/plugs/vlaCal-v2.1/jslib/vlaCal-v2.1.js",
	"___path.root.engine_lib___/external/MooTools_1.2/plugs/iFrameFormRequest/iFrameFormRequest.js",
	"___path.static.skin___/js/vote.js",
	"___path.static.skin___/js/favourites.js",
	"___path.static.skin___/js/questions.js",
	"___path.static.skin___/js/block_loader.js",
	"___path.static.skin___/js/friend.js",
	"___path.static.skin___/js/blog.js",
	"___path.static.skin___/js/other.js",
	"___path.static.skin___/js/login.js",
	"___path.static.skin___/js/panel.js",
	"___path.static.skin___/js/messages.js",
	"___path.static.skin___/js/Autocompleter.LS.js",
         "___path.static.skin___/js/userfeed.js",
         "___path.static.skin___/js/stream.js",
);
$config['head']['default']['css'] = array(
	"___path.static.skin___/css/style.css",
	"___path.static.skin___/css/roar.css",
	"___path.static.skin___/css/autocompleter.css",
	"___path.static.skin___/css/vlacal.css",
);
$config['skin']['lib']['mootools'] = true;
$config['skin']['lib']['jshttprequest'] = true;

return $config;
?>