{**
 * Саб-навигация по топикам (Интересные, новые и т.д.)
 *}

{if $sNavTopicsSubUrl}
	<ul class="nav nav-pills">
		<li {if $sMenuSubItemSelect == 'good'}class="active"{/if}>
			<a href="{$sNavTopicsSubUrl}">{$aLang.blog_menu_all_good}</a>
		</li>

		<li {if $sMenuSubItemSelect == 'new'}class="active"{/if}>
			<a href="{$sNavTopicsSubUrl}newall/" title="{$aLang.blog_menu_top_period_all}">{$aLang.blog_menu_all_new}</a>

			{if $iCountTopicsSubNew}
				<a href="{$sNavTopicsSubUrl}new/" title="{$aLang.blog_menu_top_period_24h}">+{$iCountTopicsSubNew}</a>
			{/if}
		</li>

		<li {if $sMenuSubItemSelect == 'discussed'}class="active"{/if}>
			<a href="{$sNavTopicsSubUrl}discussed/">{$aLang.blog_menu_all_discussed}</a>
		</li>

		<li {if $sMenuSubItemSelect == 'top'}class="active"{/if}>
			<a href="{$sNavTopicsSubUrl}top/">{$aLang.blog_menu_all_top}</a>
		</li>

		{hook run='nav_topics_sub_item' sMenuSubItemSelect=$sMenuSubItemSelect sNavTopicsSubUrl=$sNavTopicsSubUrl}
	</ul>

	{include file='dropdown.timespan.tpl'}
{/if}

{hook run='nav_topics_sub_after' sMenuSubItemSelect=$sMenuSubItemSelect sNavTopicsSubUrl=$sNavTopicsSubUrl}