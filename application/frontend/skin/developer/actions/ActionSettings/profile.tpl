{**
 * Основные настройки профиля
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
    {component 'user' template='settings/profile' user=$oUserCurrent}
{/block}