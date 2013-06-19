{**
 * Список друзей
 *}

{extends file='layout.base.tpl'}

{block name='layout_content'}
	{include file='actions/ActionProfile/profile_top.tpl'}

	<h3 class="profile-page-header">{$aLang.user_menu_profile_friends}</h3>

	{include file='user_list.tpl' aUsersList=$aFriends}
{/block}