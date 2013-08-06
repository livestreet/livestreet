{**
 * Просьба перейти по ссылке отправленной на емэйл для активации аккаунта
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}{$aLang.registration_confirm_header}{/block}

{block name='layout_content'}
	{$aLang.registration_confirm_text}<br /><br />

	<a href="{cfg name='path.root.web'}">{$aLang.site_go_main}</a>
{/block}