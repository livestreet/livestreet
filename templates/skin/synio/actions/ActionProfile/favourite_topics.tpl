{assign var="sidebarPosition" value='left'}
{include file='header.tpl' nav='people'}



{include file='actions/ActionProfile/profile_top.tpl'}
{include file='navs/nav.profile_favourite.tpl'}

{if $oUserCurrent and $oUserCurrent->getId()==$oUserProfile->getId()}
	{$aBlockParams.user=$oUserProfile}
	{insert name="block" block=tagsFavouriteTopic params=$aBlock.params}
{/if}

{include file='topics/topic_list.tpl'}



{include file='footer.tpl'}