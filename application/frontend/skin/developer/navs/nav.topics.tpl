{**
 * Навигация по топикам
 *}

{include 'components/nav/nav.tpl'
    name       = 'topics'
    activeItem = $sMenuItemSelect
    mods    = 'pills'
    items = [
        [ 'name' => 'index', 'url' => {router page='/'},    'text' => {lang name='blog.menu.all'}, 'count' => $iCountTopicsNew ],
        [ 'name' => 'feed',  'url' => {router page='feed'}, 'text' => $aLang.feed.title, 'is_enabled' => !! $oUserCurrent ]
    ]}

{include 'navs/nav.topics.sub.tpl'}