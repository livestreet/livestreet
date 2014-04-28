{**
 * Выпадающее меню выбора временного периода (за 24 часа, за месяц и т.д.)
 *}

{if $sPeriodSelectCurrent}
	{include 'components/dropdown/dropdown.tpl'
		sName = 'sort_by_date'
		sText = 'Loading...'
		sAttributes = "data-dropdown-selectable=\"true\""
		sActiveItem = $sPeriodSelectCurrent
		aMenu = [
			[ 'name' => '1',   'url' => "{$sPeriodSelectRoot}?period=1",   'text' => $aLang.blog_menu_top_period_24h ],
			[ 'name' => '7',   'url' => "{$sPeriodSelectRoot}?period=7",   'text' => $aLang.blog_menu_top_period_7d  ],
			[ 'name' => '30',  'url' => "{$sPeriodSelectRoot}?period=30",  'text' => $aLang.blog_menu_top_period_30d ],
			[ 'name' => 'all', 'url' => "{$sPeriodSelectRoot}?period=all", 'text' => $aLang.blog_menu_top_period_all ]
		]}
{/if}