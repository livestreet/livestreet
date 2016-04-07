{**
 * 
 *}

{extends 'Component@user-list-add.user-list-add'}

{block 'user_list_add_list'}
    {component_define_params params=[ 'users' ]}

    {component 'talk' template='participants-list'
        hideableEmptyAlert = true
        users              = $users
        showActions        = true
        show               = !! $users
        classes            = "js-$component-users"
        itemClasses        = "js-$component-user"}
{/block}