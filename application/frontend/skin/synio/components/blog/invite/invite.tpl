{**
 * Приглашение пользователей в закрытый блог
 *}

{extends 'component@user-list-add.user-list-add'}

{block 'user_list_add_list'}
    {component 'blog' template='invite-list'
        hideableEmptyAlert = true
        users              = $users
        showActions        = true
        show               = !! $users
        classes            = "js-$component-users"
        itemClasses        = "js-$component-user"}
{/block}