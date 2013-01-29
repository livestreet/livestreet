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



$config['head']['default']['js']  = array(
	"___path.root.engine_lib___/external/html5shiv.js" => array('browser'=>'lt IE 9'),
	"___path.root.engine_lib___/external/jquery/jquery.js",
	"___path.root.engine_lib___/external/jquery/jquery-ui.js",
	"___path.root.engine_lib___/external/jquery/jquery.notifier.js",
	"___path.root.engine_lib___/external/jquery/jquery.scrollto.js",
	"___path.root.engine_lib___/external/jquery/jquery.rich-array.min.js",
	"___path.root.engine_lib___/external/jquery/markitup/jquery.markitup.js",
	"___path.root.engine_lib___/external/jquery/jquery.form.js",
	"___path.root.engine_lib___/external/jquery/jquery.jqplugin.js",
	"___path.root.engine_lib___/external/jquery/jquery.cookie.js",
	"___path.root.engine_lib___/external/jquery/jquery.serializejson.js",
	"___path.root.engine_lib___/external/jquery/jquery.file.js",
	"___path.root.engine_lib___/external/jquery/jcrop/jquery.Jcrop.js",
	"___path.root.engine_lib___/external/jquery/poshytip/jquery.poshytip.js",
	"___path.root.engine_lib___/external/jquery/jquery.placeholder.min.js",
	"___path.root.engine_lib___/external/jquery/jquery.charcount.js",
	"___path.root.engine_lib___/external/prettify/prettify.js",
	"___path.root.server___/templates/framework/js/main.js",
	"___path.root.server___/templates/framework/js/favourite.js",
	"___path.root.server___/templates/framework/js/blocks.js",
	"___path.root.server___/templates/framework/js/talk.js",
	"___path.root.server___/templates/framework/js/vote.js",
	"___path.root.server___/templates/framework/js/poll.js",
	"___path.root.server___/templates/framework/js/subscribe.js",
	"___path.root.server___/templates/framework/js/infobox.js",
	"___path.root.server___/templates/framework/js/geo.js",
	"___path.root.server___/templates/framework/js/wall.js",
	"___path.root.server___/templates/framework/js/usernote.js",
	"___path.root.server___/templates/framework/js/comments.js",
	"___path.root.server___/templates/framework/js/blog.js",
	"___path.root.server___/templates/framework/js/user.js",
	"___path.root.server___/templates/framework/js/userfeed.js",
	"___path.root.server___/templates/framework/js/userfield.js",
	"___path.root.server___/templates/framework/js/stream.js",
	"___path.root.server___/templates/framework/js/photoset.js",
	"___path.root.server___/templates/framework/js/toolbar.js",
	"___path.root.server___/templates/framework/js/settings.js",
	"___path.root.server___/templates/framework/js/topic.js",
	"___path.root.server___/templates/framework/js/hook.js",
	'___path.root.server___/templates/framework/js/dropdown.js',
	'___path.root.server___/templates/framework/js/tab.js',
	'___path.root.server___/templates/framework/js/modal.js',
	"http://yandex.st/share/share.js" => array('merge'=>false),
);
$config['head']['default']['js'][] = '___path.static.skin___/js/template.js';

$config['head']['default']['css'] = array(
	// Framework styles
	"___path.root.server___/templates/framework/css/reset.css",
	"___path.root.server___/templates/framework/css/helpers.css",
	"___path.root.server___/templates/framework/css/text.css",
	"___path.root.server___/templates/framework/css/dropdowns.css",
	"___path.root.server___/templates/framework/css/buttons.css",
	"___path.root.server___/templates/framework/css/forms.css",
	"___path.root.server___/templates/framework/css/navs.css",
	"___path.root.server___/templates/framework/css/modals.css",

	// Template styles
	"___path.static.skin___/css/base.css",
	"___path.root.engine_lib___/external/jquery/markitup/skins/simple/style.css",
	"___path.root.engine_lib___/external/jquery/markitup/sets/default/style.css",
	"___path.root.engine_lib___/external/jquery/jcrop/jquery.Jcrop.css",
	"___path.root.engine_lib___/external/prettify/prettify.css",
	"___path.static.skin___/css/grid.css",
	"___path.static.skin___/css/common.css",
	"___path.static.skin___/css/icons.css",
	"___path.static.skin___/css/navs.css",
	"___path.static.skin___/css/tables.css",
	"___path.static.skin___/css/topic.css",
	"___path.static.skin___/css/comments.css",
	"___path.static.skin___/css/blocks.css",
	"___path.static.skin___/css/blog.css",
	"___path.static.skin___/css/modals.css",
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