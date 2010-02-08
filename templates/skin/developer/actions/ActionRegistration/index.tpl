{include file='header.light.tpl'}

<div class="center-block">
	<form action="{router page='registration'}" method="POST">
		<h3>{$aLang.registration}</h3>
		
		<p><label for="login">{$aLang.registration_login}:</label>
		<input type="text" name="login" id="login" class="input-text" value="{$_aRequest.login}" />
		<span>{$aLang.registration_login_notice}</span></p>
		
		<p><label for="email">{$aLang.registration_mail}:</label>
		<input type="text" name="mail" id="email" class="input-text" value="{$_aRequest.mail}" />
		<span>{$aLang.registration_mail_notice}</span></p>

		<p><label for="pass">{$aLang.registration_password}:</label>
		<input type="password" name="password" id="pass" class="input-text" value="" />
		<span>{$aLang.registration_password_notice}</span></p>

		<p><label for="repass">{$aLang.registration_password_retry}:</label>
		<input type="password" name="password_confirm" id="repass" class="input-text" value="" /></p>

		<p><label for="captcha">{$aLang.registration_captcha}:</label>
		<img src="{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}"  onclick="this.src='{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}&n='+Math.random();"><br />
		<input type="text" name="captcha" id="captcha" maxlength="3" class="input-text captcha" value="" /></p>

		<input type="submit" name="submit_register" value="{$aLang.registration_submit}" />
	</form>
</div>

{include file='footer.light.tpl'}