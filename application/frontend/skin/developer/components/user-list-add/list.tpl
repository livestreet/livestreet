{**
 * Список пользователей
 *
 * @param object  $users
 * @param string  $title
 * @param boolean $hideableEmptyAlert
 * @param boolean $show
 * @param array   $exclude
 *}

{extends 'components/user/user-list-small.tpl'}

{block 'user_list_small_item'}
    {block 'user_list_add_item'}
        {include 'components/user-list-add/item.tpl' user=$user showActions=true}
    {/block}
{/block}