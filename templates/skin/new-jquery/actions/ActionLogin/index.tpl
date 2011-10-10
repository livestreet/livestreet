{assign var="noSidebar" value=true}
{include file='header.light.tpl'}


<div class="center">
	{if $bLoginError}
		<p class="system-messages-error">{$aLang.user_login_bad}</p>
	{/if}

	<form action="{router page='login'}" method="POST">
		<h2>{$aLang.user_authorization}</h2>

		{hook run='form_login_begin'}

		<p><label>{$aLang.user_login}<br /><input type="text" name="login" class="input-text" /></label></p>
		<p><label>{$aLang.user_password}<br /><input type="password" name="password" class="input-text" /></label></p>

		<input type="submit" name="submit_login" class="button button-login" value="{$aLang.user_login_submit}" />
		<label><input type="checkbox" name="remember" checked class="checkbox" />{$aLang.user_login_remember}</label>

		<br /><br />
		<p><a href="{router page='registration'}">{$aLang.user_registration}</a><br />
		<a href="{router page='login'}reminder/">{$aLang.user_password_reminder}</a></p>

		{hook run='form_login_end'}
	</form>


	{if $oConfig->GetValue('general.reg.invite')}
		<br /><br />
		<form action="{router page='registration'}invite/" method="POST">
			<h2>{$aLang.registration_invite}</h2>

			<p><label>{$aLang.registration_invite_code}<br />
			<input type="text" name="invite_code" /></label></p>
			<input type="submit" name="submit_invite" value="{$aLang.registration_invite_check}" />
		</form>
	{/if}
</div>


{include file='footer.light.tpl'}