{**
 * Список пользователей
 *}

{extends 'component@user-list-add.list'}

{block 'user_list_add_item'}
    {component 'blog' template='invite-item' user=$user showActions=true}
{/block}