<ul class="nav nav-pills nav-pills-profile">
	<li {if $aParams[0]=='favourites' and $aParams[1]==''}class="active"{/if}>
		<a href="{router page='profile'}{$oUserProfile->getLogin()}/favourites/">{$aLang.user_menu_profile_favourites}  {if $iCountTopicFavourite} ({$iCountTopicFavourite}) {/if}</a>
	</li>
	<li {if $aParams[1]=='comments'}class="active"{/if}>
		<a href="{router page='profile'}{$oUserProfile->getLogin()}/favourites/comments/">{$aLang.user_menu_profile_favourites_comments}  {if $iCountCommentFavourite} ({$iCountCommentFavourite}) {/if}</a>
	</li>
</ul>
