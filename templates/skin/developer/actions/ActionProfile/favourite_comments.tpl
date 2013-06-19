{**
 * Избранные комментарии пользователя
 *}

{extends file='layout.base.tpl'}

{block name='layout_content'}
	{include file='actions/ActionProfile/profile_top.tpl'}
	{include file='navs/nav.profile_favourite.tpl'}
	{include file='comments/comment_list.tpl'}
{/block}