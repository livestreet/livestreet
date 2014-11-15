{**
 * Список пользователей
 *}

{extends 'components/user-list-add/list.tpl'}

{block 'user_list_add_item'}
    {include './invite-item.tpl' user=$user showActions=true}
{/block}