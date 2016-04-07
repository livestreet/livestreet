{**
 * Список пользователей
 *
 * @param array $users
 *}

{component_define_params params=[ 'users' ]}

{foreach $users as $user}
    {component 'user' template='list-item' user=$user}
{/foreach}