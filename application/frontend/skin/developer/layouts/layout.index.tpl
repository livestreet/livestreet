{**
 * Главная страница
 *}

{extends './layout.topics.tpl'}

{block 'layout_options' prepend}
    {* Все / Лента *}
    {$layoutNav = [[
        hook       => 'topics',
        activeItem => $sMenuItemSelect,
        showSingle => false,
        items => [
            [ 'name' => 'index', 'url' => {router page='/'},    'text' => {lang name='blog.menu.all'}, 'count' => $iCountTopicsNew ],
            [ 'name' => 'feed',  'url' => {router page='feed'}, 'text' => $aLang.feed.title, 'is_enabled' => !! $oUserCurrent ]
        ]
    ]]}
{/block}