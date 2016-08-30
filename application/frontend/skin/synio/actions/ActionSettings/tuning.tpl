{**
 * Настройка уведомлений
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
    {component 'user' template='settings/tuning' user=$oUserCurrent}
{/block}