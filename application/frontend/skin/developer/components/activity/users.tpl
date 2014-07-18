{**
 * Список пользователей на которых подписан текущий пользователь
 *
 * @param array $users
 *}

{include 'components/user_list_add/user_list_add.tpl'
	sUserListAddClasses = 'js-activity-users'
	aUserList           = $smarty.local.users
	sUserListNote       = $aLang.activity.users.note}