{**
 * Выпадающее меню выбора временного периода (за 24 часа, за месяц и т.д.)
 *}

{if $sPeriodSelectCurrent}
	{include 'components/dropdown/dropdown.tpl'
		classes    = 'js-dropdown-default'
		name       = 'sort_by_date'
		text       = 'Loading...'
		attributes = "data-dropdown-selectable=\"true\""
		activeItem = $sPeriodSelectCurrent
		menu       = [
			[ 'name' => '1',   'url' => "{$sPeriodSelectRoot}?period=1",   'text' => {lang name='blog.menu.top_period_1'} ],
			[ 'name' => '7',   'url' => "{$sPeriodSelectRoot}?period=7",   'text' => {lang name='blog.menu.top_period_7'}  ],
			[ 'name' => '30',  'url' => "{$sPeriodSelectRoot}?period=30",  'text' => {lang name='blog.menu.top_period_30'} ],
			[ 'name' => 'all', 'url' => "{$sPeriodSelectRoot}?period=all", 'text' => {lang name='blog.menu.top_period_all'} ]
		]}
{/if}