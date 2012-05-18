<ul class="nav nav-pills nav-pills-profile">
	<li {if $sMenuSubItemSelect=='main'}class="active"{/if}>
		<a href="{$oUserProfile->getUserWebPath()}">{$aLang.user_menu_profile_whois}</a>
	</li>

	{hook run='menu_profile_whois_item' oUserProfile=$oUserProfile}
</ul>

{hook run='menu_profile_whois' oUserProfile=$oUserProfile}
