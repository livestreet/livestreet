{**
 * Основные настройки профиля
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
    {include 'components/user/settings/profile.tpl' user=$oUserCurrent}
{/block}