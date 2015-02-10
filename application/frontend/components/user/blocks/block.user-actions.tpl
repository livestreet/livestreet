{**
 * Меню пользователя ("Добавить в друзья", "Написать письмо" и т.д.)
 *}

{if $oUserCurrent && $oUserCurrent->getId() != $oUserProfile->getId() }
    {component 'block'
        mods     = 'nopadding transparent user-actions'
        content  = {component 'user' template='actions' user=$oUserProfile}}
{/if}