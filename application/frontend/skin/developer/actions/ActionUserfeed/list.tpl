{**
 * Лента пользователя
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'topics'}
{/block}

{block name='layout_content'}
	{include 'components/feed/feed.tpl' topics=$feedTopics count=$feedTopicsAllCount classes='js-feed'}
{/block}