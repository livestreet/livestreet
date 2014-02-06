{**
 * Черный список
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
	{include 'user_list_add.tpl' sUserListType='blacklist' iUserListId=$oUserCurrent->getId() aUserList=$aUsersBlacklist}
{/block}