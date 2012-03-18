<h3 class="profile-page-header">Публикации</h3>

<ul class="nav nav-pills nav-pills-profile">
	<li {if $aParams[0]=='blog' or $aParams[0]==''}class="active"{/if}>
		<a href="{router page='my'}{$oUserProfile->getLogin()}/">{$aLang.topic_title}  {if $iCountTopicUser} ({$iCountTopicUser}) {/if}</a>
	</li>
	
	<li {if $aParams[0]=='comment'}class="active"{/if}>
		<a href="{router page='my'}{$oUserProfile->getLogin()}/comment/">{$aLang.user_menu_publication_comment}  {if $iCountCommentUser} ({$iCountCommentUser}) {/if}</a>
	</li>
	
	{if $oUserCurrent and $oUserCurrent->getId()==$oUserProfile->getId()}
		<li {if $aParams[0]=='notes'}class="active"{/if}>
			<a href="{$oUserProfile->getUserWebPath()}notes/">{$aLang.user_menu_profile_notes}  {if $iCountNoteUser} ({$iCountNoteUser}) {/if}</a>
		</li>
	{/if}
	
	{hook run='menu_profile_my_item'}
</ul>
