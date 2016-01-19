{**
 * Выбор пользователей для чтения в ленте
 *
 * @param array $users
 *}

{component_define_params params=[ 'users' ]}

{component 'user-list-add'
    users      = $users
    classes    = 'js-feed-users'
    attributes = [ 'data-param-type' => 'users' ]
    note       = $aLang.feed.users.note}