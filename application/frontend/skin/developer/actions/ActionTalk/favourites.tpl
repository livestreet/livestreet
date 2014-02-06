{**
 * Список избранных сообщений
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_content'}
	{include file='actions/ActionTalk/message_list.tpl'}
	{include file='pagination.tpl' aPaging=$aPaging}
{/block}