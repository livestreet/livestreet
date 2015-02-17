{**
 * Тулбар
 * Кнопка перехода в админку
 *}

{if $oUserCurrent && $oUserCurrent->isAdministrator()}
    {component 'toolbar' template='item'
        buttons = [[
            url => {router 'admin'},
            attributes => [ 'title' => {lang 'admin.title'} ],
            icon => 'cog'
        ]]
        mods = 'admin'}
{/if}