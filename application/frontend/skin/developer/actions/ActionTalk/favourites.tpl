{**
 * Список избранных сообщений
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_content'}
	{if $aTalks}
		{include file='actions/ActionTalk/message_list.tpl'}
	{else}
		<div class="notice-empty">{$aLang.talk_favourite_empty}</div>
	{/if}

	{include file='pagination.tpl' aPaging=$aPaging}
{/block}