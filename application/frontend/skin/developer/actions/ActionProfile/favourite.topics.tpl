{**
 * Избранные топики пользователя
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
	{lang name='user.favourites.title'}
{/block}

{block 'layout_content' append}
	{include 'navs/nav.user.favourite.tpl'}

	{* Блок с тегами избранного *}
	{if $oUserCurrent && $oUserCurrent->getId() == $oUserProfile->getId()}
		{insert name="block" block=tagsFavouriteTopic params={$aBlockParams.user=$oUserProfile}}
	{/if}

	{include 'components/topic/topic-list.tpl' topics=$aTopics paging=$aPaging}
{/block}