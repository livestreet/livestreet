<h3 class="profile-page-header">{$aLang.talk_menu_inbox}</h3>

<ul class="nav nav-pills">					
	<li {if $sMenuSubItemSelect=='inbox'}class="active"{/if}><a href="{router page='talk'}">{$aLang.talk_menu_inbox}</a></li>
	{if $iUserCurrentCountTalkNew}
		<li {if $sMenuSubItemSelect=='new'}class="active"{/if}><a href="{router page='talk'}inbox/new/">{$aLang.talk_menu_inbox_new}</a></li>
	{/if}
	<li {if $sMenuSubItemSelect=='add'}class="active"{/if}><a href="{router page='talk'}add/">{$aLang.talk_menu_inbox_create}</a></li>
	<li {if $sMenuSubItemSelect=='favourites'}class="active"{/if}><a href="{router page='talk'}favourites/">{$aLang.talk_menu_inbox_favourites}{if $iCountTalkFavourite} ({$iCountTalkFavourite}){/if}</a></li>
	<li {if $sMenuSubItemSelect=='blacklist'}class="active"{/if}><a href="{router page='talk'}blacklist/">{$aLang.talk_menu_inbox_blacklist}</a></li>

	{hook run='menu_talk_talk_item'}	
</ul>

{hook run='menu_talk'}