{**
 * Список сообщений
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_options' append}
	{$bNoSystemMessages = false}
{/block}

{block 'layout_content'}
	{if $aTalks}
		{include './search.tpl'}
	{/if}

	{include './talk-list.tpl' bMessageListCheckboxes=true}
	{include 'components/pagination/pagination.tpl' aPaging=$aPaging}
{/block}