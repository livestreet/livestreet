{**
 * 
 *}

{extends 'component@user-list-add.list'}

{block 'user_list_add_item'}
    {component 'talk' template='participants-item' user=$user showActions=true}
{/block}