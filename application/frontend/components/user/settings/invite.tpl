{**
 * Управление инвайтами
 *}

<div class="note mb-20">
	{lang name='user.settings.invites.note'}
</div>

{hook run='settings_invite_begin'}

<p>
	{if Config::Get('general.reg.invite')}
		{lang name='user.settings.invites.available'}:
		<strong>
			{if $oUserCurrent->isAdministrator()}
				{lang name='user.settings.invites.many'}
			{else}
				{$iCountInviteAvailable}
			{/if}
		</strong>
	{else}
		{if $sReferralLink}
			{lang name='user.settings.invites.referral_link'}:<br/>
			<strong>{$sReferralLink|escape}</strong>
		{/if}

	{/if}
	<br />

	{lang name='user.settings.invites.used'}: <strong>{($iCountInviteUsed) ? $iCountInviteUsed : {lang name='user.settings.invites.used_empty'}}</strong>
</p>

<form action="" method="POST" enctype="multipart/form-data">
	{hook run='form_settings_invite_begin'}

	{* E-mail *}
	{component 'field' template='text'
	name  = 'invite_mail'
	placeholder  = 'e-mail'
	note  = {lang name='user.settings.invites.fields.email.note'}
	label = {lang name='user.settings.invites.fields.email.label'}}

	{hook run='form_settings_invite_end'}

	{* Скрытые поля *}
	{component 'field' template='hidden.security-key'}

	{* Кнопки *}
	{component 'button' name='submit_invite' mods='primary' text={lang name='user.settings.invites.fields.submit.text'}}
</form>

{hook run='settings_invite_end'}