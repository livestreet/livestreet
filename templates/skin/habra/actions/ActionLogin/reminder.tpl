{include file='header.light.tpl'}
{include file='system_message.tpl'}
<!--- ФОРМА --->

<table width="100%" border="0" cellspacing="4" cellpadding="4">
<tr>
<td align="left">

<p><span class="header">Восстановление пароля</span><br />


<form action="{$DIR_WEB_ROOT}/login/reminder/" method="POST" id="loginform">

<p><span class="form">Ваш e-mail:</span><br />
<input type="text" name="mail" id="name" value="" size="25" /><br />

</p>

<p>

<p><input type="submit" name="submit_reminder" value="получить ссылку на изменение пароля" /></p>
</form>





</td>
</tr>
</table>

<!--- / ФОРМА --->

{include file='footer.light.tpl'}

