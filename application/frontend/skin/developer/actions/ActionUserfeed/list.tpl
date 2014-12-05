{**
 * Лента пользователя
 *
 * @param array  $topics
 * @param array  $paging
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
    {$sNav = 'topics'}
{/block}

{block 'layout_content'}
    {include 'components/topic/topic-list.tpl' topics=$topics paging=$paging}
{/block}