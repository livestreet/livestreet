{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.reactivation}</h2>

	{include file='forms/form.auth.reactivation.tpl'}
{/block}