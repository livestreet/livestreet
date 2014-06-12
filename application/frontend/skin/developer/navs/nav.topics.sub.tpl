{**
 * Саб-навигация по топикам (Интересные, новые и т.д.)
 *}

{if $sNavTopicsSubUrl}
	{include 'components/nav/nav.tpl'
			 sName       = 'topics_sub'
			 sActiveItem = $sMenuSubItemSelect
			 sMods       = 'pills'
			 aItems = [
			   	[ 'name' => 'good',      'url' => $sNavTopicsSubUrl,               'text' => $aLang.blog_menu_all_good ],
			   	[ 'name' => 'new',       'url' => "{$sNavTopicsSubUrl}newall/",    'text' => $aLang.blog_menu_all_new, 'title' => $aLang.blog_menu_top_period_all, 'count' => $iCountTopicsNew ],
			   	[ 'name' => 'new',       'url' => "{$sNavTopicsSubUrl}new/",       'text' => "+$iCountTopicsSubNew", 'title' => $aLang.blog_menu_top_period_24h, 'is_enabled' => $iCountTopicsSubNew ],
			   	[ 'name' => 'discussed', 'url' => "{$sNavTopicsSubUrl}discussed/", 'text' => $aLang.blog_menu_all_discussed ],
			   	[ 'name' => 'top',       'url' => "{$sNavTopicsSubUrl}top/",       'text' => $aLang.blog_menu_all_top ]
			 ]}

	{include file='components/sort/sort.timespan.tpl'}
{/if}

{hook run='nav_topics_sub_after' sMenuSubItemSelect=$sMenuSubItemSelect sNavTopicsSubUrl=$sNavTopicsSubUrl}