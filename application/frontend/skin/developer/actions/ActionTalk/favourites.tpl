{**
 * Список избранных сообщений
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_content'}
	{if $aTalks}
		{include file='actions/ActionTalk/message_list.tpl'}
	{else}
		{include file='alert.tpl' mAlerts=$aLang.talk_favourite_empty sAlertStyle='empty'}
	{/if}

	{include file='pagination.tpl' aPaging=$aPaging}
{/block}