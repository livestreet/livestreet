{**
 * Главная
 *
 * @parama array $topics
 * @parama array $paging
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
    {$sNav = 'topics'}
{/block}

{block 'layout_content'}
    {include 'components/topic/topic-list.tpl' topics=$topics paging=$paging}
{/block}