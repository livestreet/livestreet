{**
 * Базовый шаблон настроек пользователя
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options' append}
	{$sNavContent = 'settings'}
{/block}

{block name='layout_page_title'}{$aLang.settings_menu}{/block}