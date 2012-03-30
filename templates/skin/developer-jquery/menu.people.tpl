<h2 class="page-header">{$aLang.user_list}</h2>

<ul class="nav nav-pills">
	<li {if $sEvent=='' || $sEvent=='index'}class="active"{/if}><a href="{router page='people'}">{$aLang.people_menu_users_all}</a></li>
	<li {if $sEvent=='online'}class="active"{/if}><a href="{router page='people'}online/">{$aLang.people_menu_users_online}</a></li>
	<li {if $sEvent=='new'}class="active"{/if}><a href="{router page='people'}new/">{$aLang.people_menu_users_new}</a></li>
	
	{hook run='menu_people_people_item'}
	{hook run='menu_people'}
</ul>
