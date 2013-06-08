{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.registration_invite}</h2>

	{include file='forms/form.auth.invite.tpl'}
{/block}