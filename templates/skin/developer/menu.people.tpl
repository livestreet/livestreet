<ul class="menu">
	<li class="active"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/">{$aLang.people_menu_users}</a>
		<ul class="sub-menu">
			<li {if $sEvent=='' || $sEvent=='good' || $sEvent=='bad'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/">{$aLang.people_menu_users_all}</a></li>
			<li {if $sEvent=='online'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/online/">{$aLang.people_menu_users_online}</a></li>
			<li {if $sEvent=='new'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/new/">{$aLang.people_menu_users_new}</a></li>
		</ul>
	</li>
</ul>