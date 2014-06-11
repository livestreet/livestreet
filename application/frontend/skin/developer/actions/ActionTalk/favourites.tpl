{**
 * Список избранных сообщений
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
	{include './talk-list.tpl'}
	{include 'components/pagination/pagination.tpl' aPaging=$aPaging}
{/block}