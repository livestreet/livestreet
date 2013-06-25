{**
 * Страница с формой поиска
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_page_title'}{$aLang.search}{/block}

{block name='layout_content'}
	{include file='forms/form.search.main.tpl'}
{/block}