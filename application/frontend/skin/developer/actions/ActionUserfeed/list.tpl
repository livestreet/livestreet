{**
 * Лента пользователя
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$sNav = 'topics'}
{/block}

{block 'layout_content'}
	{include 'components/feed/feed.tpl' topics=$feedTopics count=$feedTopicsAllCount classes='js-feed'}
{/block}