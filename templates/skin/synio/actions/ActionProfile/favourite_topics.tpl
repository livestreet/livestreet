{assign var="sidebarPosition" value='left'}
{include file='header.tpl' menu='people'}



{include file='actions/ActionProfile/profile_top.tpl'}
{include file='menu.profile_favourite.tpl'}

{if $oUserCurrent and $oUserCurrent->getId()==$oUserProfile->getId()}
	{$aBlockParams.user=$oUserProfile}
	{insert name="block" block=tagsFavouriteTopic params=$aBlock.params}
{/if}

{include file='topic_list.tpl'}



{include file='footer.tpl'}