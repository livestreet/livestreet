{**
 * Черновики
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNavContent = 'create'}
{/block}

{block name='layout_content'}
	{include file='topics/topic_list.tpl'}
{/block}