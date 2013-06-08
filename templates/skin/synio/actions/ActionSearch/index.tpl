{extends file='layout.base.tpl'}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.search}</h2>

	{include file='forms/form.search.main.tpl'}
{/block}