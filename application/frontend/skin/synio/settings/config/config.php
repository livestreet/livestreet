<?php

$config = array();

/**
 * Тип сетки
 *
 * fluid - резина
 * fixed - фиксированная ширина
 */
$config['view']['grid']['type'] = 'fluid';

// Настройки резины
$config['view']['grid']['fluid_min_width'] = '320px';
$config['view']['grid']['fluid_max_width'] = '1100px';

// Настройки фиксированная ширина
$config['view']['grid']['fixed_width'] = '1100px';

// Показывать баннер с лого и описанием или нет
$config['view']['layout_show_banner'] = true;

// Компоненты
$config['components'] = array(
    // Базовые компоненты
    'css-reset', 'css-helpers', 'typography', 'forms', 'grid', 'ls-vendor', 'ls-core', 'ls-component', 'lightbox', 'avatar', 'slider', 'details', 'alert', 'dropdown', 'button', 'block',
    'nav', 'tooltip', 'tabs', 'modal', 'table', 'text', 'uploader', 'email', 'field', 'pagination', 'editor', 'more', 'crop',
    'performance', 'toolbar', 'actionbar', 'badge', 'autocomplete', 'icon', 'item', 'highlighter', 'jumbotron', 'notification', 'blankslate', 'confirm',

    // Компоненты LS CMS
    'favourite', 'vote', 'auth', 'media', 'property', 'photo', 'note', 'user-list-add', 'subscribe', 'content', 'report', 'comment',
    'toolbar-scrollup', 'toolbar-scrollnav', 'tags-personal', 'search-ajax', 'search', 'sort', 'search-form', 'info-list',
    'tags', 'userbar', 'modal-create', 'admin', 'user', 'wall', 'blog', 'topic', 'poll', 'activity', 'feed', 'talk',

    // Компоненты Synio
    'syn-icon', 'syn-create'
);

/**
 * Настройки вывода блоков
 */
$config['block']['rule_index_blog'] = array(
    'action' => array(
        'index',
        'blog' => array('{topics}', '{topic}', '{blog}')
    ),
    'blocks' => array(
        'right' => array(
            'activityRecent' => array('priority' => 100),
            'topicsTags'   => array('priority' => 50),
            'blogs'  => array('params' => array(), 'priority' => 1)
        )
    ),
    'clear'  => false
);
$config['block']['rule_profile'] = array(
    'action' => array('profile', 'talk', 'settings'),
    'blocks' => array(
        'right' => array(
            'component@user.block.photo'   => array('priority' => 100),
            'component@user.block.nav'     => array('priority' => 50),
            'component@user.block.note'    => array('priority' => 25),
            'component@user.block.actions' => array('priority' => 1),
        )
    )
);

// Подключение скриптов шаблона
$config['head']['template']['js'] = array(
	'___path.skin.assets.web___/js/init.js'
);

// Подключение стилей шаблона
$config['head']['template']['css'] = array(
    "___path.skin.assets.web___/css/layout.css",
    "___path.skin.assets.web___/css/print.css"
);

// Подключение темы
if (Config::Get('view.theme')) {
    $config['head']['template']['css'][] = "___path.skin.web___/themes/___view.theme___/style.css";
}

/**
 * SEO
 */

// Тег используемый для заголовков топиков
$config['view']['seo']['topic_heading'] = 'h1';
$config['view']['seo']['topic_heading_list'] = 'h2';

return $config;