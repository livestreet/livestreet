{**
 * Избранные топики пользователя
 *}

{extends file='layout.user.tpl'}

{block name='layout_content'}
	{include file='navs/nav.profile_favourite.tpl'}

	{if $oUserCurrent and $oUserCurrent->getId()==$oUserProfile->getId()}
		{$aBlockParams.user = $oUserProfile}
		{insert name="block" block=tagsFavouriteTopic params=$aBlock.params}
	{/if}

	{include file='topics/topic_list.tpl'}
{/block}