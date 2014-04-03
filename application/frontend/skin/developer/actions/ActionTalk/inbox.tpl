{**
 * Список сообщений
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_options'}
	{$bNoSystemMessages = false}
{/block}

{block name='layout_content'}
	{if $aTalks}
		{include './search.tpl'}
	{/if}

	{include file='./message_list.tpl' bMessageListCheckboxes=true}
	{include file='pagination.tpl' aPaging=$aPaging}
{/block}