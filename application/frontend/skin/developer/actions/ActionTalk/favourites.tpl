{**
 * Список избранных сообщений
 *
 * @param array $talks
 * @param array $paging
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
    {component 'talk.list' talks=$talks paging=$paging}
{/block}