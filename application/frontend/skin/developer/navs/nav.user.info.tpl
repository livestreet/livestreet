{**
 * Навигация на главной странице профиля
 *}

{hook run='menu_profile_whois_item' oUserProfile=$oUserProfile assign='sNavProfileHome'}

{if $sNavProfileHome}
	<ul class="nav nav-pills nav-pills-profile">
		<li {if $sMenuSubItemSelect=='main'}class="active"{/if}>
			<a href="{$oUserProfile->getUserWebPath()}">{$aLang.user_menu_profile_whois}</a>
		</li>

		{$sNavProfileHome}
	</ul>
{/if}

{hook run='menu_profile_whois' oUserProfile=$oUserProfile}