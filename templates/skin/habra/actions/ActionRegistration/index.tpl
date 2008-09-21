{include file='header.light.tpl'}

{include file='system_message.tpl'}

<br>


<table width="100%" border="0" cellspacing="4" cellpadding="4">
<tr>

<td align="left">

<p><span class="header">Попробуем зарегистрироваться?</span>
<p><span class="txt">Пожалуйста, заполните поля ниже. Это нужно сделать обязательно, иначе ничего не получится.</span></p>

<form action="{$DIR_WEB_ROOT}/registration/" method="POST" id="RegisterForm">
	<label for="login"><span class="form">Имя пользователя:</span></label><br />
	<input type="text" name="login" tabindex="1" id="login" value="{$_aRequest.login}" size="20" />
	<br/>
	<span class="form_note">Может состоять только из букв (A-Z a-z), цифр (0-9). Знак подчеркивания (_) лучше не использовать. Длина имени не может быть меньше 3 и больше 20 символов.</span><br />
	

	<p><label for="email"><span class="form">Электропочта:</span></label><br />
	<input type="text" id="email" style="width: 25em;" name="mail" value="{$_aRequest.mail}" size="25" tabindex="3" /><span class="form_note"><br/>Для проверки регистрации и в целях безопасности нам нужен адрес вашей электропочты.</span><br />
	</p>
	
	<p><label for="pass"><span class="form">Пароль:</span></label><br />
	<input type="password" id="pass" value="" name="password" size="25" tabindex="4" /><br />
	<span class="form_note">Должен содержать не менее 5 символов и не может совпадать с логином. Не используйте простые пароли, будьте разумны.</span><br />
	</p>
	
	<p><label for="repass"><span class="form">Повторите пароль:</span></label><br />
	<input type="password" value="" id="repass" name="password_confirm" size="25" tabindex="5"/><br />
	</p>

 	<p><label for="captcha"><span class="form">Нам нужны эти цифры и буквы:</span></label><br>
 	<img src="{$DIR_WEB_ROOT}/classes/lib/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}"><br><br>
 	<input type="text" style="text-align: center;" name="captcha" value="" maxlength=3 size=9><br><br>
 	</p>
 	</p>

	<p class="l-bot"><input type="submit" name="submit_register" tabindex="6" value="зарегистрироваться" /></p>
</form>

<p><span class="txt_small">Может быть, перейти на <a href="{$DIR_WEB_ROOT}/">заглавную страницу</a>?</span><br />

</td>
</tr>
</table>






{include file='footer.light.tpl'}