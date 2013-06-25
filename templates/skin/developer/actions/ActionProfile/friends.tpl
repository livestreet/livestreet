{**
 * Список друзей
 *}

{extends file='layouts/layout.user.tpl'}

{block name='layout_user_page_title'}{$aLang.user_menu_profile_friends}{/block}

{block name='layout_content'}
	{include file='user_list.tpl' aUsersList=$aFriends}
{/block}