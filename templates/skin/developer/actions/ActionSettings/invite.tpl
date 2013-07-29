{**
 * Управление инвайтами
 *}

{extends file='layouts/layout.user.settings.tpl'}

{block name='layout_content'}
	<small class="note mb-20">{$aLang.settings_invite_notice} "{$aLang.settings_invite_submit}"</small>

	{hook run='settings_invite_begin'}

	<form action="" method="POST" enctype="multipart/form-data">
		{hook run='form_settings_invite_begin'}

		<p>
			{$aLang.settings_invite_available}: <strong>{if $oUserCurrent->isAdministrator()}{$aLang.settings_invite_many}{else}{$iCountInviteAvailable}{/if}</strong><br />
			{$aLang.settings_invite_used}: <strong>{$iCountInviteUsed}</strong>
		</p>

        {* E-mail *}
        {include file='forms/form.field.text.tpl'
                 sFieldName  = 'invite_mail'
                 sFieldNote  = $aLang.settings_invite_mail_notice
                 sFieldLabel = $aLang.settings_invite_mail}

		{hook run='form_settings_invite_end'}

        {* Скрытые поля *}
        {include file='forms/form.field.hidden.security_key.tpl'}

        {* Кнопки *}
        {include file='forms/form.field.button.tpl' sFieldName='submit_invite' sFieldStyle='primary' sFieldText=$aLang.settings_invite_submit}
	</form>

	{hook run='settings_invite_end'}
{/block}