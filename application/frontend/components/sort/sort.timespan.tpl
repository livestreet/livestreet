{**
 * Выпадающее меню выбора временного периода (за 24 часа, за месяц и т.д.)
 *}

{if $periodSelectCurrent}
	{component 'dropdown'
		classes    = 'js-dropdown-default'
		name       = 'sort_by_date'
		text       = {lang "blog.menu.top_period_$periodSelectCurrent"}
		menu       = [
			[ 'name' => '1',   'url' => "{$periodSelectRoot}?period=1",   'text' => {lang 'blog.menu.top_period_1'} ],
			[ 'name' => '7',   'url' => "{$periodSelectRoot}?period=7",   'text' => {lang 'blog.menu.top_period_7'}  ],
			[ 'name' => '30',  'url' => "{$periodSelectRoot}?period=30",  'text' => {lang 'blog.menu.top_period_30'} ],
			[ 'name' => 'all', 'url' => "{$periodSelectRoot}?period=all", 'text' => {lang 'blog.menu.top_period_all'} ]
		]
		params     = $smarty.local.params}
{/if}