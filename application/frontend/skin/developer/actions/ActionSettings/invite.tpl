{**
 * Управление инвайтами
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
	<small class="note mb-20">{$aLang.settings_invite_notice} "{$aLang.settings_invite_submit}"</small>

	{hook run='settings_invite_begin'}

	<form action="" method="POST" enctype="multipart/form-data">
		{hook run='form_settings_invite_begin'}

		<p>
			{$aLang.settings_invite_available}: <strong>{if $oUserCurrent->isAdministrator()}{$aLang.settings_invite_many}{else}{$iCountInviteAvailable}{/if}</strong><br />
			{$aLang.settings_invite_used}: <strong>{$iCountInviteUsed}</strong>
		</p>

        {* E-mail *}
        {include 'components/field/field.text.tpl'
                 sName  = 'invite_mail'
                 sNote  = $aLang.settings_invite_mail_notice
                 sLabel = $aLang.settings_invite_mail}

		{hook run='form_settings_invite_end'}

        {* Скрытые поля *}
        {include 'components/field/field.hidden.security_key.tpl'}

        {* Кнопки *}
        {include 'components/button/button.tpl' sName='submit_invite' sMods='primary' sText=$aLang.settings_invite_submit}
	</form>

	{hook run='settings_invite_end'}
{/block}