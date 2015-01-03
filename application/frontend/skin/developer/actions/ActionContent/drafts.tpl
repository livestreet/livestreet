{**
 * Черновики
 *
 * @parama array $topics
 * @parama array $paging
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$sNav = 'create'}
{/block}

{block 'layout_page_title'}
    {$aLang.topic.add.title.add}
{/block}

{block 'layout_content'}
    {component 'topic' template='list' topics=$topics paging=$paging}
{/block}