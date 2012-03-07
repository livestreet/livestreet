{assign var="noSidebar" value=true}
{include file='header.tpl'}



<h2 class="page-header">{$aLang.user_authorization}</h2>

{if $bLoginError}
	<p class="system-messages-error">{$aLang.user_login_bad}</p>
{/if}

	
<form action="{router page='login'}" method="POST">
	{hook run='form_login_begin'}

	<p><label for="login">{$aLang.user_login}</label>
	<input type="text" id="login" name="login" class="input-text input-width-200" /></p>
	
	<p><label for="password">{$aLang.user_password}</label>
	<input type="password" id="password" name="password" class="input-text input-width-200" /></p>
	
	<p><label><input type="checkbox" name="remember" checked class="input-checkbox" /> {$aLang.user_login_remember}</label></p>
	
	{hook run='form_login_end'}
	
	<input type="submit" name="submit_login" class="input-button" value="{$aLang.user_login_submit}" />
		
	<br />
	<br />
	<a href="{router page='registration'}">{$aLang.user_registration}</a><br />
	<a href="{router page='login'}reminder/">{$aLang.user_password_reminder}</a>
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



{include file='footer.tpl'}