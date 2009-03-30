
		<ul class="menu">
			<li class="active"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/">{$aLang.people_menu_users}</a>
				<ul class="sub-menu">
					<li {if $sEvent=='' || $sEvent=='good' || $sEvent=='bad'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/">{$aLang.people_menu_users_all}</a></div></li>
					<li {if $sEvent=='online'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/online/">{$aLang.people_menu_users_online}</a></div></li>
					<li {if $sEvent=='new'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/new/">{$aLang.people_menu_users_new}</a></div></li>
				</ul>
			</li>
		</ul>