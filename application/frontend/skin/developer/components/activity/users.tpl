{**
 * Список пользователей на которых подписан текущий пользователь
 *
 * @param array $users
 *}

{include 'components/user-list-add/user-list-add.tpl'
    users   = $smarty.local.users
    classes = 'js-activity-users'
    note    = $aLang.activity.users.note}