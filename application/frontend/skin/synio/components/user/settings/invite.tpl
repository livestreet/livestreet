{**
 * Управление инвайтами
 *}

<div class="syn-form-panel">
    <p class="text-info">
        {lang 'user.settings.invites.note'}
    </p>

    {* @hook Начало формы с настройками инвайтов *}
    {hook run='user_settings_invite_begin'}

    <p>
        {if Config::Get('general.reg.invite')}
            {lang 'user.settings.invites.available'}:
            <strong>
                {if $oUserCurrent->isAdministrator()}
                    {lang 'user.settings.invites.many'}
                {else}
                    {$iCountInviteAvailable}
                {/if}
            </strong>
        {else}
            {if $sReferralLink}
                {lang 'user.settings.invites.referral_link'}:<br/>
                <strong>{$sReferralLink|escape}</strong>
            {/if}

        {/if}
        <br />

        {lang 'user.settings.invites.used'}: <strong>{($iCountInviteUsed) ? $iCountInviteUsed : {lang 'user.settings.invites.used_empty'}}</strong>
    </p>

    <form action="" method="POST" enctype="multipart/form-data">
        {hook run='form_settings_invite_begin'}

        {* E-mail *}
        {component 'field' template='text'
            name  = 'invite_mail'
            placeholder  = 'e-mail'
            note  = {lang 'user.settings.invites.fields.email.note'}
            label = {lang 'user.settings.invites.fields.email.label'}}

        {hook run='form_settings_invite_end'}

        {* Скрытые поля *}
        {component 'field' template='hidden.security-key'}

        {* Кнопки *}
        {component 'button' mods='primary' text={lang 'user.settings.invites.fields.submit.text'}}
    </form>

    {* @hook Конец формы с настройками инвайтов *}
    {hook run='user_settings_invite_end'}
</div>