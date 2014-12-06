{**
 * Базовый шаблон личных сообщений
 *}

{extends './layout.user.tpl'}

{block 'layout_options' append}
	{$sNav = 'messages'}
{/block}

{block 'layout_user_page_title'}
	{$aLang.talk.title}
{/block}