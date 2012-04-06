<h3 class="profile-page-header">{$aLang.talk_menu_inbox}</h3>

<ul class="nav nav-pills">					
	<li {if $sEvent=='inbox'}class="active"{/if}><a href="{router page='talk'}">{$aLang.talk_menu_inbox}</a></li>
	<li {if $sEvent=='add'}class="active"{/if}><a href="{router page='talk'}add/">{$aLang.talk_menu_inbox_create}</a></li>
	<li {if $sEvent=='favourites'}class="active"{/if}><a href="{router page='talk'}favourites/">{$aLang.talk_menu_inbox_favourites}{if $iCountTalkFavourite} ({$iCountTalkFavourite}){/if}</a></li>
	
	{hook run='menu_talk_talk_item'}	
</ul>


{hook run='menu_talk'}