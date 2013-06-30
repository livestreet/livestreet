{**
 * Базовый шаблон настроек пользователя
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options' append}
	{$sNavContent = 'settings'}
{/block}

{block name='layout_page_title'}{$aLang.settings_menu}{/block}

{block name='layout_content_begin' append}
	{include file='modals/modal.settings.avatar_upload.tpl'}
	{include file='modals/modal.settings.photo_upload.tpl'}
{/block}