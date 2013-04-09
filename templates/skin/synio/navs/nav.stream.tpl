<ul class="nav nav-menu">
	{if $oUserCurrent}
		<li {if $sMenuItemSelect=='user'}class="active"{/if}><a href="{router page='stream'}user/">{$aLang.stream_menu_user}</a></li>
	{/if}
	<li {if $sMenuItemSelect=='all'}class="active"{/if}><a href="{router page='stream'}all/">{$aLang.stream_menu_all}</a></li>

	{hook run='menu_stream_item'}
</ul>
{hook run='menu_stream'}
