{**
 * Настройки аккаунта (емэйл, пароль)
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
    {component 'user' template='settings/account' user=$oUserCurrent}
{/block}