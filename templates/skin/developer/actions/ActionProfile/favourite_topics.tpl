{include file='header.tpl'}



{include file='actions/ActionProfile/profile_top.tpl'}

{include file='navs/nav.profile_favourite.tpl'}

{if $oUserCurrent and $oUserCurrent->getId()==$oUserProfile->getId()}
	{insert name="block" block=tagsFavouriteTopic params={$aBlockParams.user=$oUserProfile}}
{/if}

{include file='topics/topic_list.tpl'}



{include file='footer.tpl'}