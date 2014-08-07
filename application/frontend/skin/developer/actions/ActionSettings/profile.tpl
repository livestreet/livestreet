{**
 * Основные настройки профиля
 *
 * @scripts <framework>/js/livestreet/userfield.js
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
    {include 'components/user/settings/profile.tpl' user=$oUserCurrent}
{/block}