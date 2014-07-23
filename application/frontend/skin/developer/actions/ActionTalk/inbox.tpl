{**
 * Список сообщений
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_options' append}
	{$bNoSystemMessages = false}
{/block}

{block 'layout_content'}
	{include 'components/talk/talk-search-form.tpl'}
	{include 'components/talk/talk-list.tpl' bMessageListCheckboxes=true}
{/block}