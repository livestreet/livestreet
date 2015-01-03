{**
 * Выбор пользователей для чтения в ленте
 *
 * @param array $users
 *}

{component 'user-list-add'
    users      = $smarty.local.users
    classes    = 'js-feed-users'
    attributes = [ 'data-param-type' => 'users' ]
    note       = $aLang.feed.users.note}