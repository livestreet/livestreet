{**
 * Список друзей
 *}

{extends file='layouts/layout.user.tpl'}

{block name='layout_content'}
	{include file='user_list.tpl' aUsersList=$aFriends}
{/block}