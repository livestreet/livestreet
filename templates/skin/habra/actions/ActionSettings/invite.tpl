{include file='header.tpl'}

{include file='menu.settings.tpl'}

{include file='system_message.tpl'}

<BR>
<table width="100%"  border="0" cellspacing="4" cellpadding="4">
<tr>

<td align="left">



<p><span class="header">Управление приглашениями</span>
<BR>
<BR>Доступно: {if $oUserCurrent->isAdministrator()}много{else}{$iCountInviteAvailable}{/if}
<BR>Использовано: {$iCountInviteUsed}
<BR>
<BR>
<form action="{$DIR_WEB_ROOT}/settings/invite/" method="POST" enctype="multipart/form-data">

	<label for="invite_mail"><span class="form">Пригласить по e-mail адресу:</span></label><br />
	<input  style="width: 60%;" type="text" name="invite_mail" tabindex="1" id="invite_mail" value="" size="20" />	<br>
	<span class="form_note">На этот e-mail будет высланно приглашение для регистрации</span><br />
	<br>


	<p class="l-bot"><input type="submit" name="submit_invite" tabindex="6" value="отправить приглашение" /></p>
</form>



</td>
</tr>
</table>


{include file='footer.tpl'}

