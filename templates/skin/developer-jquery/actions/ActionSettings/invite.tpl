{include file='header.tpl' menu='settings' noSidebar=true}


<small class="note note-header input-width-400">Вы можете пригласить на сайт своих друзей и знакомых, для этого просто укажите их e-mail и нажмите кнопку "Отправить приглашение"</small>

<form action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p>
		{$aLang.settings_invite_available}: <strong>{if $oUserCurrent->isAdministrator()}{$aLang.settings_invite_many}{else}{$iCountInviteAvailable}{/if}</strong><br />
		{$aLang.settings_invite_used}: <strong>{$iCountInviteUsed}</strong>
	</p>

	<p><label for="invite_mail">{$aLang.settings_invite_mail}:</label>
	<input type="text" name="invite_mail" id="invite_mail" class="input-text input-width-200" /><br />
	<small class="note">{$aLang.settings_invite_mail_notice}</small></p>

	<input type="submit" value="{$aLang.settings_invite_submit}" name="submit_invite" class="button" />
</form>


{include file='footer.tpl'}