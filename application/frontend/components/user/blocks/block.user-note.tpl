{**
 * Блок с заметкой о пользователе
 *}

{if $oUserCurrent && $oUserCurrent->getId() != $oUserProfile->getId() }
    {component 'block'
        mods     = 'nopadding transparent user-note'
        content  = {component 'note' classes='js-user-note' note=$oUserProfile->getUserNote() targetId=$oUserProfile->getId()}}
{/if}