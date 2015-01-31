{**
 * Список пользователей
 *}

{extends 'Component@user-list-add.list'}

{block 'user_list_add_item'}
    {include './invite-item.tpl' user=$user showActions=true}
{/block}