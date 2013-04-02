<div class="nav-filter-wrapper">
	<ul class="nav nav-pills">
		<li {if $sMenuSubItemSelect=='good'}class="active"{/if}><a href="{$sMenuSubBlogUrl}">{$aLang.blog_menu_collective_good}</a></li>
		<li {if $sMenuSubItemSelect=='new'}class="active"{/if}><a href="{$sMenuSubBlogUrl}newall/">{$aLang.blog_menu_collective_new}</a>{if $iCountTopicsBlogNew>0} <a href="{$sMenuSubBlogUrl}new/">+{$iCountTopicsBlogNew}</a>{/if}</li>
		<li {if $sMenuSubItemSelect=='discussed'}class="active"{/if}><a href="{$sMenuSubBlogUrl}discussed/">{$aLang.blog_menu_collective_discussed}</a></li>
		<li {if $sMenuSubItemSelect=='top'}class="active"{/if}><a href="{$sMenuSubBlogUrl}top/">{$aLang.blog_menu_collective_top}</a></li>
		{hook run='menu_blog_blog_item'}
	</ul>

	{if $sPeriodSelectCurrent}
		<div class="dropdown dropdown-toggle" data-type="dropdown-toggle" data-option-target="js-dropdown-date" data-option-change-text="true"></div>

		<ul class="dropdown-menu" id="js-dropdown-date" data-type="dropdown-target">
			<li {if $sPeriodSelectCurrent=='1'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=1">{$aLang.blog_menu_top_period_24h}</a></li>
			<li {if $sPeriodSelectCurrent=='7'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=7">{$aLang.blog_menu_top_period_7d}</a></li>
			<li {if $sPeriodSelectCurrent=='30'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=30">{$aLang.blog_menu_top_period_30d}</a></li>
			<li {if $sPeriodSelectCurrent=='all'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=all">{$aLang.blog_menu_top_period_all}</a></li>
		</ul>
	{/if}
</div>