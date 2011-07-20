<ul class="menu">
	<li {if $sMenuItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people_menu_users}</a>
		{if $sMenuItemSelect=='people'}
			<ul class="sub-menu">
				<li {if $sEvent=='' || $sEvent=='good' || $sEvent=='bad'}class="active"{/if}><a href="{router page='people'}">{$aLang.people_menu_users_all}</a></li>
				<li {if $sEvent=='online'}class="active"{/if}><a href="{router page='people'}online/">{$aLang.people_menu_users_online}</a></li>
				<li {if $sEvent=='new'}class="active"{/if}><a href="{router page='people'}new/">{$aLang.people_menu_users_new}</a></li>
				{hook run='menu_people_people_item'}
			</ul>
		{/if}
	</li>
        {if $oUserCurrent}
		<li {if $sMenuItemSelect=='stream'}class="active"{/if}>
			<a href="{router page='stream'}">{$aLang.stream_personal_title}</a>
		</li>
	{/if}
	{hook run='menu_people'}
</ul>
