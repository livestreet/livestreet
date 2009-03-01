{include file='header.light.tpl'}


	<div class="lite-center register">
		<form action="{$DIR_WEB_ROOT}/registration/" method="POST">
			<h3>Регистрация</h3>
			<label for="login">Имя пользователя:</label><br />
			<p><input type="text" class="input-text" name="login" id="login" value="{$_aRequest.login}"/>
			<span class="input-note">Может состоять только из букв (A-Z a-z), цифр (0-9). Знак подчеркивания (_) лучше не использовать. Длина имени не может быть меньше 3 и больше 20 символов.</span></p>
			
			<label for="email">Электропочта:</label><br />
			<p><input type="text" class="input-text" id="email" name="mail" value="{$_aRequest.mail}"/>
			<span class="input-note">Для проверки регистрации и в целях безопасности нам нужен адрес вашей электропочты.</span></p><br />
			
			<label for="pass">Пароль:</label><br />
			<p><input type="password" class="input-text" id="pass" value="" name="password"/><br />
			<span class="input-note">Должен содержать не менее 5 символов и не может совпадать с логином. Не используйте простые пароли, будьте разумны.</span></p>
			
			<label for="repass">Повторите пароль:</label><br />
			<p><input type="password" class="input-text"  value="" id="repass" name="password_confirm"/></p><br />
			
			Введите цифры и буквы:<br />
			<img src="{$DIR_WEB_ROOT}/classes/lib/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}">
			<p><input type="text" class="input-text" style="width: 80px;" name="captcha" value="" maxlength=3 /></p>
			<div class="lite-note">
				<button type="submit" name="submit_register" class="button" style="float: none;"><span><em>Зарегистрироваться</em></span></button>
			</div>		
		</form>
	</div>
<br><br><br>


{include file='footer.light.tpl'}