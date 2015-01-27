{**
 * Саб-навигация по топикам (Интересные, новые и т.д.)
 *}

{if $sNavTopicsSubUrl}
    {component 'nav'
        name       = 'topics_sub'
        activeItem = $sMenuSubItemSelect
        mods       = 'pills'
        items = [
            [ 'name' => 'good',      'url' => $sNavTopicsSubUrl,               'text' => {lang name='blog.menu.all_good'} ],
            [ 'name' => 'new',       'url' => "{$sNavTopicsSubUrl}newall/",    'text' => {lang name='blog.menu.all_new'}, 'title' => {lang name='blog.menu.top_period_all'}, 'count' => $iCountTopicsSubNew ],
            [ 'name' => 'new',       'url' => "{$sNavTopicsSubUrl}new/",       'text' => "+$iCountTopicsSubNew", 'title' => {lang name='blog.menu.top_period_1'}, 'is_enabled' => $iCountTopicsSubNew ],
            [ 'name' => 'discussed', 'url' => "{$sNavTopicsSubUrl}discussed/", 'text' => {lang name='blog.menu.all_discussed'} ],
            [ 'name' => 'top',       'url' => "{$sNavTopicsSubUrl}top/",       'text' => {lang name='blog.menu.all_top'} ]
        ]}

    {component 'sort' template='timespan' activeItem=$periodSelectCurrent}
{/if}

{hook run='nav_topics_sub_after' sMenuSubItemSelect=$sMenuSubItemSelect sNavTopicsSubUrl=$sNavTopicsSubUrl}