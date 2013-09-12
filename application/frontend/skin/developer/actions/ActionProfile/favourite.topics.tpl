{**
 * Избранные топики пользователя
 *}

{extends file='layouts/layout.user.tpl'}

{block name='layout_user_page_title'}{$aLang.user_menu_profile_favourites}{/block}

{block name='layout_content'}
	{include file='navs/nav.user.favourite.tpl'}

	{if $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId()}
		{insert name="block" block=tagsFavouriteTopic params={$aBlockParams.user=$oUserProfile}}
	{/if}

	{include file='topics/topic_list.tpl'}
{/block}