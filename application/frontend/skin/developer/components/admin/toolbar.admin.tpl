{**
 * Тулбар
 * Кнопка перехода в админку
 *}

{extends 'component@toolbar.toolbar.item'}

{block 'toolbar_item_options' append}
    {$_mods = 'admin'}
    {$_bShow = $oUserCurrent && $oUserCurrent->isAdministrator()}
{/block}

{block 'toolbar_item'}
    {toolbar_item_icon url="{router page='admin'}" title="{lang name='admin.title'}" icon="icon-cog"}
{/block}