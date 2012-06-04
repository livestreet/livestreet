{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}

{include file='menu.settings.tpl'}

<small class="note note-header input-width-400">{$aLang.settings_invite_notice} "{$aLang.settings_invite_submit}"</small>

{hook run='settings_invite_begin'}

<div class="wrapper-content">
	<form action="" method="POST" enctype="multipart/form-data">
		{hook run='form_settings_invite_begin'}
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

		<p>
			{$aLang.settings_invite_available}: <strong>{if $oUserCurrent->isAdministrator()}{$aLang.settings_invite_many}{else}{$iCountInviteAvailable}{/if}</strong><br />
			{$aLang.settings_invite_used}: <strong>{$iCountInviteUsed}</strong>
		</p>

		<p><label for="invite_mail">{$aLang.settings_invite_mail}:</label>
		<input type="text" name="invite_mail" id="invite_mail" class="input-text input-width-200" /><br />
		<small class="note">{$aLang.settings_invite_mail_notice}</small></p>

		{hook run='form_settings_invite_end'}

		<button type="submit"  name="submit_invite" class="button button-primary" />{$aLang.settings_invite_submit}</button>
	</form>
</div>

{hook run='settings_invite_end'}

{include file='footer.tpl'}