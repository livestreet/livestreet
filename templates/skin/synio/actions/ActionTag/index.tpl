{extends file='layout.base.tpl'}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.block_tags_search}</h2>

	{include file='forms/form.search.tags.tpl'}
	{include file='topics/topic_list.tpl'}
{/block}