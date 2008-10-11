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

<p><span class="form">Логин или e-mail:</span><br />
<input type="text" name="login" id="name" value="" size="25" /><br />

<label for="password"><span class="form">Пароль:</span></label><br />
<input type="password" id="password" name="password" value="" size="25" /><br>

</p>

<p>

<p><input type="submit" name="submit_login" value="войти" /></p>
</form>


{if $USER_USE_INVITE} 
<br>

<p><span class="header">Регистрация по приглашению</span><br />
<form action="{$DIR_WEB_ROOT}/registration/invite/" method="POST">
<p><span class="form">Код приглашения: </span> <input type="text" value="" name="invite_code" size="29"> <input type="submit" name="submit_invite" value="проверить код"></p>
</form>

{/if}


</td>
</tr>
</table>

<!--- / ФОРМА --->

{include file='footer.light.tpl'}

