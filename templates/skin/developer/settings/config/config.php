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



$config['path']['static']['framework'] = "___path.root.server___/templates/framework";

$config['head']['default']['js'] = array(
	/* Vendor libs */
	"___path.static.framework___/js/vendor/html5shiv.js" => array('browser'=>'lt IE 9'),
	"___path.static.framework___/js/vendor/jquery.js",
	//"___path.static.framework___/js/vendor/jquery-1.9.1.min.js",
	"___path.static.framework___/js/vendor/jquery-ui/js/jquery-ui-1.10.2.custom.min.js",
	"___path.static.framework___/js/vendor/jquery-ui/js/localization/jquery-ui-datepicker-ru.js",
	"___path.static.framework___/js/vendor/jquery.notifier.js",
	"___path.static.framework___/js/vendor/jquery.scrollto.js",
	"___path.static.framework___/js/vendor/jquery.rich-array.min.js",
	"___path.static.framework___/js/vendor/jquery.form.js",
	"___path.static.framework___/js/vendor/jquery.jqplugin.js",
	"___path.static.framework___/js/vendor/jquery.cookie.js",
	"___path.static.framework___/js/vendor/jquery.serializejson.js",
	"___path.static.framework___/js/vendor/jquery.file.js",
	"___path.static.framework___/js/vendor/jcrop/jquery.Jcrop.js",
	"___path.static.framework___/js/vendor/jquery.placeholder.min.js",
	"___path.static.framework___/js/vendor/jquery.charcount.js",
	"___path.static.framework___/js/vendor/poshytip/jquery.poshytip.js",
	"___path.static.framework___/js/vendor/markitup/jquery.markitup.js",
	"___path.static.framework___/js/vendor/prettify/prettify.js",

	/* Core */
	"___path.static.framework___/js/core/main.js",
	"___path.static.framework___/js/core/hook.js",

	/* User Interface */
	"___path.static.framework___/js/ui/popup.js",
	"___path.static.framework___/js/ui/dropdown.js",
	"___path.static.framework___/js/ui/tooltip.js",
	"___path.static.framework___/js/ui/popover.js",
	"___path.static.framework___/js/ui/tab.js",
	"___path.static.framework___/js/ui/modal.js",

	/* LiveStreet */
	"___path.static.framework___/js/livestreet/favourite.js",
	"___path.static.framework___/js/livestreet/blocks.js",
	"___path.static.framework___/js/livestreet/talk.js",
	"___path.static.framework___/js/livestreet/vote.js",
	"___path.static.framework___/js/livestreet/poll.js",
	"___path.static.framework___/js/livestreet/subscribe.js",
	"___path.static.framework___/js/livestreet/geo.js",
	"___path.static.framework___/js/livestreet/wall.js",
	"___path.static.framework___/js/livestreet/usernote.js",
	"___path.static.framework___/js/livestreet/comments.js",
	"___path.static.framework___/js/livestreet/blog.js",
	"___path.static.framework___/js/livestreet/user.js",
	"___path.static.framework___/js/livestreet/userfeed.js",
	"___path.static.framework___/js/livestreet/userfield.js",
	"___path.static.framework___/js/livestreet/stream.js",
	"___path.static.framework___/js/livestreet/photoset.js",
	"___path.static.framework___/js/livestreet/toolbar.js",
	"___path.static.framework___/js/livestreet/settings.js",
	"___path.static.framework___/js/livestreet/topic.js",
);
$config['head']['default']['js'][] = '___path.static.skin___/js/template.js';

$config['head']['default']['css'] = array(
	// Framework styles
	"___path.static.framework___/css/reset.css",
	"___path.static.framework___/css/helpers.css",
	"___path.static.framework___/css/text.css",
	"___path.static.framework___/css/dropdowns.css",
	"___path.static.framework___/css/buttons.css",
	"___path.static.framework___/css/forms.css",
	"___path.static.framework___/css/navs.css",
	"___path.static.framework___/css/modals.css",
	"___path.static.framework___/css/tooltip.css",
	"___path.static.framework___/css/popover.css",

	// Template styles
	"___path.static.skin___/css/base.css",
	"___path.static.framework___/js/vendor/jquery-ui/css/smoothness/jquery-ui-1.10.2.custom.css",
	"___path.static.framework___/js/vendor/markitup/skins/simple/style.css",
	"___path.static.framework___/js/vendor/markitup/sets/default/style.css",
	"___path.static.framework___/js/vendor/jcrop/jquery.Jcrop.css",
	"___path.static.framework___/js/vendor/prettify/prettify.css",
	"___path.static.framework___/js/vendor/poshytip/tip-yellow/tip-yellow.css",
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
	"___path.static.skin___/themes/___view.theme___/style.css",
	"___path.static.skin___/css/print.css",
);


return $config;
?>