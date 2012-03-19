<h3 class="profile-page-header">{$aLang.user_menu_profile_favourites}</h3>

<ul class="nav nav-pills nav-pills-profile">
	<li {if $aParams[1]=='topics' or $aParams[1]==''}class="active"{/if}>
		<a href="{$oUserProfile->getUserWebPath()}favourites/topics/">{$aLang.user_menu_profile_favourites_topics}  {if $iCountTopicFavourite} ({$iCountTopicFavourite}) {/if}</a>
	</li>
	<li {if $aParams[1]=='comments'}class="active"{/if}>
		<a href="{$oUserProfile->getUserWebPath()}favourites/comments/">{$aLang.user_menu_profile_favourites_comments}  {if $iCountCommentFavourite} ({$iCountCommentFavourite}) {/if}</a>
	</li>
</ul>
