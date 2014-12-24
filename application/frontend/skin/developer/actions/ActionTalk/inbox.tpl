{**
 * Список сообщений
 *
 * @param array $talks
 * @param array $paging
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
    {include 'components/talk/talk-search-form.tpl'}
    {include 'components/talk/talk-list.tpl' talks=$talks paging=$paging selectable=true}
{/block}