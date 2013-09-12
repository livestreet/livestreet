{**
 * Поиск по тегам
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_page_title'}{$aLang.block_tags_search}{/block}

{block name='layout_content'}
	{include file='forms/form.search.tags.tpl'}
	{include file='topics/topic_list.tpl'}
{/block}