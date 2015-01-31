{**
 * 
 *}

{extends 'Component@user-list-add.list'}

{block 'user_list_add_item'}
    {include './participants-item.tpl' user=$user showActions=true}
{/block}