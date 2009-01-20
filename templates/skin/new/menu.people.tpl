
		<ul class="menu">
			<li class="active"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/">Пользователи</a>
				<ul class="sub-menu">
					<li {if $sEvent=='' || $sEvent=='good' || $sEvent=='bad'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/">Все</a></div></li>
					<li {if $sEvent=='online'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/online/">Онлайн</a></div></li>
					<li {if $sEvent=='new'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/new/">Новые</a></div></li>
				</ul>
			</li>
		</ul>