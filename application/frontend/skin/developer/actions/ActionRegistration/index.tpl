{**
 * Регистрация
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$bNoSidebar = true}
{/block}

{block 'layout_page_title'}
	{$aLang.auth.registration.title}
{/block}

{block 'layout_content'}
	{include 'components/auth/auth.registration.tpl'}
{/block}