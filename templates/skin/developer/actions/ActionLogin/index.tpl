{include file='header.light.tpl'}

<div class="center-block">
	{if $bLoginError}<p class="error">{$aLang.user_login_bad}</p>{/if}
	
	<form action="{router page='login'}" method="POST">
		<h3>{$aLang.user_authorization}</h3>
		{hook run='form_login_begin'}
		<label for="login-input">{$aLang.user_login}:</label>
		<p><input type="text" name="login" id="login-input" class="input-text" /></p>

		<label for="password-input">{$aLang.user_password}:</label>
		<p><input type="password" name="password" id="password-input" class="input-text" /></p>

		<label for="" class="input-checkbox">
		<input type="checkbox" name="remember" class="checkbox" checked />{$aLang.user_login_remember}</label><br />
		{hook run='form_login_end'}
		<input type="submit" name="submit_login" value="{$aLang.user_login_submit}" /><br /><br />

		<a href="{router page='login'}reminder/">{$aLang.user_password_reminder}</a><br />
		<a href="{router page='registration'}">{$aLang.user_registration}</a>
	</form>
</div>


{if $oConfig->GetValue('general.reg.invite')} 	
	<div class="center-block">	
		<form action="{router page='registration'}invite/" method="POST">
			<h3>{$aLang.registration_invite}</h3>
			<label for="invite_code">{$aLang.registration_invite_code}:</label>
			<p><input type="text" class="input-text" name="invite_code" id="invite_code" /></p>				
			<input type="submit" name="submit_invite" value="{$aLang.registration_invite_check}">
		</form>
	</div>
{/if}


{include file='footer.light.tpl'}