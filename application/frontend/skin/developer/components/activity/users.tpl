{**
 * Список пользователей на которых подписан текущий пользователь
 *
 * @param array $users
 *}

{component 'user-list-add'
    users   = $smarty.local.users
    classes = 'js-activity-users'
    note    = $aLang.activity.users.note}