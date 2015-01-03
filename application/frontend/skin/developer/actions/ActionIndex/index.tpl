{**
 * Главная
 *
 * @parama array $topics
 * @parama array $paging
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$sNav = 'topics'}
{/block}

{block 'layout_content'}
    {component 'topic' template='list' topics=$topics paging=$paging}
{/block}