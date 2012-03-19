<h3 class="profile-page-header">{$aLang.user_menu_publication}</h3>

<ul class="nav nav-pills nav-pills-profile">
	<li {if $aParams[1]=='topics' or $aParams[1]==''}class="active"{/if}>
		<a href="{$oUserProfile->getUserWebPath()}created/topics/">{$aLang.topic_title}  {if $iCountTopicUser} ({$iCountTopicUser}) {/if}</a>
	</li>
	
	<li {if $aParams[1]=='comments'}class="active"{/if}>
		<a href="{$oUserProfile->getUserWebPath()}created/comments/">{$aLang.user_menu_publication_comment}  {if $iCountCommentUser} ({$iCountCommentUser}) {/if}</a>
	</li>
	
	{if $oUserCurrent and $oUserCurrent->getId()==$oUserProfile->getId()}
		<li {if $aParams[1]=='notes'}class="active"{/if}>
			<a href="{$oUserProfile->getUserWebPath()}created/notes/">{$aLang.user_menu_profile_notes}  {if $iCountNoteUser} ({$iCountNoteUser}) {/if}</a>
		</li>
	{/if}
	
	{hook run='menu_profile_my_item'}
</ul>
