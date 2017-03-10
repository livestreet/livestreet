{**
 * Главная страница
 *}

{extends './layout.topics.tpl'}

{block 'layout_options' prepend}
    {* Все / Лента *}
    {$layoutNav = [[
        name       => 'topics',
        activeItem => $sMenuItemSelect,
        showSingle => true,
        items => [
            [ 'name' => 'index', 'url' => {router page='/'},    'text' => {lang name='blog.menu.all'}, 'count' => $iCountTopicsNew ],
            [ 'name' => 'feed',  'url' => {router page='feed'}, 'text' => $aLang.feed.title, 'is_enabled' => !! $oUserCurrent ]
        ]
    ]]}
{/block}