{**
 * Черновики
 *
 * @parama array $topics
 * @parama array $paging
 *}

{extends 'layouts/layout.content.form.tpl'}

{block 'layout_page_title'}
    {$aLang.topic.add.title.add}
{/block}

{block 'layout_content'}
    {component 'topic.list' topics=$topics paging=$paging}
{/block}