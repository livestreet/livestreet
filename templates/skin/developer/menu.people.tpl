<ul class="menu">
	<li class="active"><a href="{router page='people'}">{$aLang.people_menu_users}</a>
		<ul class="sub-menu">
			<li {if $sEvent=='' || $sEvent=='good' || $sEvent=='bad'}class="active"{/if}><a href="{router page='people'}">{$aLang.people_menu_users_all}</a></li>
			<li {if $sEvent=='online'}class="active"{/if}><a href="{router page='people'}online/">{$aLang.people_menu_users_online}</a></li>
			<li {if $sEvent=='new'}class="active"{/if}><a href="{router page='people'}new/">{$aLang.people_menu_users_new}</a></li>
		</ul>
	</li>
</ul>