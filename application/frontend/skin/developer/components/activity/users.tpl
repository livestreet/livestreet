{**
 * Список пользователей на которых подписан текущий пользователь
 *
 * @param array $users
 *}

{include 'components/user-list-add/user-list-add.tpl'
	sUserListAddClasses = 'js-activity-users'
	aUserList           = $smarty.local.users
	sUserListNote       = $aLang.activity.users.note}