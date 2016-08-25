{**
 * Список топиков
 *}

{extends './layout.base.tpl'}

{block 'layout_options' append}
    {* Меню фильтрации топиков *}
    {if $sNavTopicsSubUrl}
        {if ! isset($layoutNav)}
            {$layoutNav = []}
        {/if}

        {$layoutNav[] = [
            hook       => 'topics_sub',
            activeItem => $sMenuSubItemSelect,
            items => [
                [ 'name' => 'good',      'url' => $sNavTopicsSubUrl,               'text' => {lang name='blog.menu.all_good'} ],
                [ 'name' => 'new',       'url' => "{$sNavTopicsSubUrl}newall/",    'text' => {lang name='blog.menu.all_new'} ],
                [ 'name' => 'discussed', 'url' => "{$sNavTopicsSubUrl}discussed/", 'text' => {lang name='blog.menu.all_discussed'} ],
                [ 'name' => 'top',       'url' => "{$sNavTopicsSubUrl}top/",       'text' => {lang name='blog.menu.all_top'} ]
            ]
        ]}

        {if $periodSelectCurrent}
            {* Фильтр по времени *}
            {$layoutNav[] = [
                hook       => 'topics_sub_timespan',
                activeItem => $periodSelectCurrent,
                items => [
                    [
                        'name' => 'good',
                        'text' => {lang name='blog.menu.all_good'},
                        'menu' => [
                            activeItem => $periodSelectCurrent,
                            items => [
                                [ 'name' => '1',   'url' => "{$periodSelectRoot}?period=1",   'text' => {lang 'blog.menu.top_period_1'} ],
                                [ 'name' => '7',   'url' => "{$periodSelectRoot}?period=7",   'text' => {lang 'blog.menu.top_period_7'}  ],
                                [ 'name' => '30',  'url' => "{$periodSelectRoot}?period=30",  'text' => {lang 'blog.menu.top_period_30'} ],
                                [ 'name' => 'all', 'url' => "{$periodSelectRoot}?period=all", 'text' => {lang 'blog.menu.top_period_all'} ]
                            ]
                        ]
                    ]
                ]
            ]}
        {/if}
    {/if}
{/block}

{block 'layout_content'}
    {component 'topic.list' topics=$topics paging=$paging}
{/block}