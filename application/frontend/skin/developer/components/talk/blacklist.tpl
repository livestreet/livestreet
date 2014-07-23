{**
 * Черный список
 *
 * @param array $users
 *}

{include 'components/user_list_add/user_list_add.tpl'
	sUserListTitle      = $aLang.talk.blacklist.title
	sUserListNote       = $aLang.talk.blacklist.note
	sUserListAddClasses = 'js-user-list-add-blacklist'
	iUserListId         = $oUserCurrent->getId()
	aUserList           = $smarty.local.users}