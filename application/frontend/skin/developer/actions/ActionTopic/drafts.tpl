{**
 * Черновики
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'create'}
{/block}

{block name='layout_page_title'}{$aLang.topic_create}{/block}

{block name='layout_content'}
	{include file='topics/topic_list.tpl'}
{/block}