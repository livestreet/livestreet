{**
 * Список друзей
 *}

{extends file='layout.user.tpl'}

{block name='layout_content'}
	{include file='user_list.tpl' aUsersList=$aFriends}
{/block}