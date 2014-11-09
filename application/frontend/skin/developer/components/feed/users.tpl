{**
 * Выбор пользователей для чтения в ленте
 *
 * @param array $users
 *}

{include 'components/user-list-add/user-list-add.tpl'
    users      = $smarty.local.users
    classes    = 'js-feed-users'
    attributes = [ 'data-param-type' => 'users' ]
    note       = $aLang.feed.users.note}