{**
 * Черный список
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
	{include 'components/user_list_add/user_list_add.tpl' sUserListAddClasses='js-user-list-add-blacklist' iUserListId=$oUserCurrent->getId() aUserList=$aUsersBlacklist}
{/block}