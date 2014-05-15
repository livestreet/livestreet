{**
 * Базовый шаблон личных сообщений
 *}

{extends './layout.user.tpl'}

{block 'layout_options'}
	{$sNav = 'messages'}
{/block}

{block 'layout_user_page_title'}
	{$aLang.talk_menu_inbox}
{/block}