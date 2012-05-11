<ul class="nav nav-pills">
	{hook run='profile_sidebar_menu_item_first' oUserProfile=$oUserProfile}
	<li {if $sAction=='profile' && ($aParams[0]=='whois' or $aParams[0]=='')}class="active"{/if}><a href="{$oUserProfile->getUserWebPath()}">{$aLang.user_menu_profile_whois}</a></li>
	{hook run='profile_sidebar_menu_item_last' oUserProfile=$oUserProfile}
</ul>