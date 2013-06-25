{**
 * Страница входа
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}{$aLang.user_authorization}{/block}

{block name='layout_content'}
	{include file='forms/form.auth.login.tpl'}
{/block}