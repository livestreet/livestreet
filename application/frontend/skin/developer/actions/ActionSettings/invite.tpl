{**
 * Управление инвайтами
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
    {component 'user' template='settings/invite' user=$oUserCurrent}
{/block}