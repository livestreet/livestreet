{**
 * Восстановление пароля.
 * Пароль отправлен не емэйл пользователя.
 *}

{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}{$aLang.password_reminder}{/block}

{block name='layout_content'}
	{$aLang.password_reminder_send_password}
{/block}