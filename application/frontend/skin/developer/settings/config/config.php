<?php

$config = array();

/**
 * Grid type:
 *
 * fluid - резина
 * fixed - фиксированная ширина
 */
$config['view']['grid']['type'] = 'fluid';

/* Fluid settings */
$config['view']['grid']['fluid_min_width'] = '320px';
$config['view']['grid']['fluid_max_width'] = '1200px';

/* Fixed settings */
$config['view']['grid']['fixed_width'] = '1000px';

$config['head']['default']['js'] = Config::Get('head.default.js');
$config['head']['default']['js'][] = '___path.skin.assets.web___/js/init.js';


$aCss = array(
	// Base styles
	"___path.skin.assets.web___/css/base.css",
	"___path.framework.frontend.web___/js/vendor/jquery-ui/css/smoothness/jquery-ui-1.10.2.custom.css",
	"___path.framework.frontend.web___/js/vendor/jcrop/jquery.Jcrop.css",
	"___path.framework.frontend.web___/js/vendor/prettify/prettify.css",
	"___path.framework.frontend.web___/js/vendor/notifier/jquery.notifier.css",
	"___path.framework.frontend.web___/js/vendor/fotorama/fotorama.css",
	"___path.framework.frontend.web___/js/vendor/nprogress/nprogress.css",
	"___path.framework.frontend.web___/js/vendor/colorbox/colorbox.css",
	"___path.skin.assets.web___/css/grid.css",
	"___path.skin.assets.web___/css/forms.css",
	"___path.skin.assets.web___/css/common.css",

	// Components
	"___path.skin.assets.web___/css/components/vote.css",
	"___path.skin.assets.web___/css/components/actionbar.css",
	"___path.skin.assets.web___/css/components/more.css",
	"___path.skin.assets.web___/css/components/favourite.css",
	"___path.skin.assets.web___/css/components/user_note.css",
	"___path.skin.assets.web___/css/components/user_item.css",
	"___path.skin.assets.web___/css/components/user_list_small.css",
	"___path.skin.assets.web___/css/components/user_list_add.css",
	"___path.skin.assets.web___/css/components/user_list_avatar.css",
	"___path.skin.assets.web___/css/components/pagination.css",
	"___path.skin.assets.web___/css/components/info_list.css",
	"___path.skin.assets.web___/css/components/tags.css",
	"___path.skin.assets.web___/css/components/alphanumeric.css",
	"___path.skin.assets.web___/css/components/search_form.css",
	"___path.skin.assets.web___/css/components/field.css",

	// Template's styles
	"___path.skin.assets.web___/css/icons.css",
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
	"___path.skin.assets.web___/css/print.css",
);

// Подключение темы
if ( Config::Get('view.theme') ) {
	$aCss[] = "___path.skin.web___/themes/___view.theme___/style.css";
}

// Стили для RTL языков
if ( Config::Get('view.rtl') ) {
	$aCss[] = "___path.skin.assets.web___/css/components/vote-rtl.css";
}

// Подключение фронтенд фреймворка
$config['head']['default']['css'] = array_merge(Config::Get('head.default.css'), $aCss);


return $config;