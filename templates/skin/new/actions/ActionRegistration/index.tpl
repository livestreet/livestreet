{include file='header.light.tpl'}


	<div class="lite-center register">
		<form action="{router page='registration'}" method="POST">
			<h3>{$aLang.registration}</h3>
			{hook run='form_registration_begin'}
			<label for="login">{$aLang.registration_login}:</label><br />
			<p><input type="text" class="input-text" name="login" id="login" value="{$_aRequest.login}"/>
			<span class="input-note">{$aLang.registration_login_notice}</span></p>
			
			<label for="email">{$aLang.registration_mail}:</label><br />
			<p><input type="text" class="input-text" id="email" name="mail" value="{$_aRequest.mail}"/>
			<span class="input-note">{$aLang.registration_mail_notice}</span></p><br />
			
			<label for="pass">{$aLang.registration_password}:</label><br />
			<p><input type="password" class="input-text" id="pass" value="" name="password"/><br />
			<span class="input-note">{$aLang.registration_password_notice}</span></p>
			
			<label for="repass">{$aLang.registration_password_retry}:</label><br />
			<p><input type="password" class="input-text"  value="" id="repass" name="password_confirm"/></p><br />
			
			{$aLang.registration_captcha}:<br />
			<img src="{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}"  onclick="this.src='{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}&n='+Math.random();">
			<p><input type="text" class="input-text" style="width: 80px;" name="captcha" value="" maxlength=3 /></p>
			{hook run='form_registration_end'}
			<div class="lite-note">
				<button type="submit" name="submit_register" class="button" style="float: none;"><span><em>{$aLang.registration_submit}</em></span></button>
			</div>		
		</form>
	</div>
<br><br><br>


{include file='footer.light.tpl'}