{**
 * Настройки аккаунта (емэйл, пароль)
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
	{include 'components/user/settings/account.tpl' user=$oUserCurrent}
{/block}