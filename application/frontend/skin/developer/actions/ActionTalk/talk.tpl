{**
 * Диалог
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
	{include 'components/talk/talk.tpl' talk=$oTalk comments=$aComments}
{/block}