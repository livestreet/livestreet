{include file='header.tpl' menu='settings'}


<h2>{$aLang.settings_invite}</h2>

<form action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p>
		{$aLang.settings_invite_available}: <strong>{if $oUserCurrent->isAdministrator()}{$aLang.settings_invite_many}{else}{$iCountInviteAvailable}{/if}</strong><br />
		{$aLang.settings_invite_used}: <strong>{$iCountInviteUsed}</strong>
	</p>

	<p><label for="invite_mail">{$aLang.settings_invite_mail}:</label><br />
	<input type="text" name="invite_mail" id="invite_mail" class="input-200" /><br />
	<span class="note">{$aLang.settings_invite_mail_notice}</span></p>

	<input type="submit" value="{$aLang.settings_invite_submit}" name="submit_invite" />
</form>


{include file='footer.tpl'}