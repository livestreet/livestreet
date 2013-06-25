{**
 * Избранные комментарии пользователя
 *}

{extends file='layout.user.tpl'}

{block name='layout_content'}
	{include file='navs/nav.profile_favourite.tpl'}
	{include file='comments/comment_list.tpl'}
{/block}