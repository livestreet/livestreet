{include file='header.light.tpl'}

<!--- ФОРМА --->

<table width="100%" border="0" cellspacing="4" cellpadding="4">
<tr>
<td align="left">

<p><span class="header">Представьтесь, пожалуйста</span><br />

{if $bLoginError}
<p><span class="form_note_red">Что-то не так! Вероятно, неправильно указан логин(e-mail), или пароль.</span><br />
{/if}

<form action="{$DIR_WEB_ROOT}/login/" method="POST" id="loginform">
<input type="hidden" name="backto" value="http://habrahabr.ru/">

<p><span class="form">Логин или e-mail:</span><br />
<input type="text" name="login" id="name" value="" size="25" /><br />

<label for="password"><span class="form">Пароль:</span></label><br />
<input type="password" id="password" name="password" value="" size="25" /><br>

</p>

<p>

<p><input type="submit" name="submit_login" value="войти" /></p>
</form>

</td>
</tr>
</table>

<!--- / ФОРМА --->

{include file='footer.light.tpl'}

