{**
 * Список друзей
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
	{$aLang.user_menu_profile_friends}
{/block}

{block 'layout_content' append}
	{include 'components/user_list/user_list.tpl' aUsersList=$aFriends}
{/block}