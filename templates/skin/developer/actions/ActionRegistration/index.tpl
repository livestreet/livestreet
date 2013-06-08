{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.registration}</h2>

	{include file='forms/form.auth.signup.tpl'}
{/block}