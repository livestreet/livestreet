{**
 * Восстановление пароля.
 * Пароль отправлен на емэйл пользователя.
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}{$aLang.password_reminder}{/block}

{block name='layout_content'}
	{$aLang.password_reminder_send_password}
{/block}