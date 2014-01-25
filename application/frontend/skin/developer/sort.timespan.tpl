{**
 * Выпадающее меню выбора временного периода (за 24 часа, за месяц и т.д.)
 *}

{if $sPeriodSelectCurrent}
	<div class="sort">
		<div class="dropdown dropdown-toggle js-dropdown-default" data-dropdown-target="js-dropdown-date" data-dropdown-selectable="true">...</div>

		<ul class="dropdown-menu" id="js-dropdown-date">
			<li {if $sPeriodSelectCurrent=='1'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=1">{$aLang.blog_menu_top_period_24h}</a></li>
			<li {if $sPeriodSelectCurrent=='7'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=7">{$aLang.blog_menu_top_period_7d}</a></li>
			<li {if $sPeriodSelectCurrent=='30'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=30">{$aLang.blog_menu_top_period_30d}</a></li>
			<li {if $sPeriodSelectCurrent=='all'}class="active"{/if}><a href="{$sPeriodSelectRoot}?period=all">{$aLang.blog_menu_top_period_all}</a></li>
		</ul>
	</div>
{/if}