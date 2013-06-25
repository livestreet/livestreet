{**
 * Регистрация
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}{$aLang.registration}{/block}

{block name='layout_content'}
	{include file='forms/form.auth.signup.tpl'}
{/block}