{**
 * Список сообщений
 *
 * @param array $talks
 * @param array $paging
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
    {component 'talk' template='search-form'}
    {component 'talk' template='list' talks=$talks paging=$paging selectable=true}
{/block}