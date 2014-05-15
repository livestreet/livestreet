{**
 * Базовый шаблон настроек пользователя
 *}

{extends './layout.user.tpl'}

{block 'layout_options'}
	{$sNav = 'settings'}
{/block}

{block 'layout_user_page_title'}
	{$aLang.settings_menu}
{/block}