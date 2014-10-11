{**
 * Выбор пользователей для чтения в ленте
 *
 * @param array $users
 *}

{include 'components/user-list-add/user-list-add.tpl'
	sUserListAddClasses    = 'js-feed-users'
	aUserList              = $smarty.local.users
	sUserListAddAttributes = 'data-param-type="users"'
	sUserListNote          = $aLang.feed.users.note}