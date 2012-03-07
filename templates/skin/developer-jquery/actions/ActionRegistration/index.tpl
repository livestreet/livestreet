{assign var="noSidebar" value=true}
{include file='header.tpl'}



<h2 class="page-header">{$aLang.registration}</h2>


<form action="{router page='registration'}" method="post">
	{hook run='form_registration_begin'}

	<p><label for="login">{$aLang.registration_login}</label>
	<input type="text" name="login" id="login" value="{$_aRequest.login}" class="input-text input-width-300" />
	<small class="note">{$aLang.registration_login_notice}</small></p>

	<p><label for="mail">{$aLang.registration_mail}</label>
	<input type="text" name="mail" id="mail" value="{$_aRequest.mail}" class="input-text input-width-300" />
	<small class="note">{$aLang.registration_mail_notice}</small></p>

	<p><label for="password">{$aLang.registration_password}</label>
	<input type="password" name="password" id="password" value="" class="input-text input-width-300" />
	<small class="note">{$aLang.registration_password_notice}</small></p>

	<p><label for="repass">{$aLang.registration_password_retry}</label>
	<input type="password" value="" id="repass" name="password_confirm" class="input-text input-width-300" /></p>

	<p><label for="captcha">{$aLang.registration_captcha}</label>
	<img src="{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}" 
		 onclick="this.src='{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}&n='+Math.random();"
		 class="mb-10" /><br />
	<input type="text" name="captcha" id="captcha" value="" maxlength="3" class="input-text input-width-100" /></p>

	{hook run='form_registration_end'}

	<input type="submit" name="submit_register" value="{$aLang.registration_submit}" class="button" />
</form>



{include file='footer.tpl'}