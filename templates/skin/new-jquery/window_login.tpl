{if !$oUserCurrent}
	<div class="login-form" id="login_form">
		<a href="#" class="close jqmClose"></a>
		
		<form action="{router page='login'}" method="POST">
			<h3>{$aLang.user_authorization}</h3>

			{hook run='form_login_popup_begin'}

			<p><label>{$aLang.user_login}:<br />
			<input type="text" class="input-text" name="login" id="login-input"/></label></p>
			
			<p><label>{$aLang.user_password}:<br />
			<input type="password" name="password" class="input-text" /></label></p>
			
			<p><label><input type="checkbox" name="remember" class="checkbox" checked />{$aLang.user_login_remember}</label></p>

			{hook run='form_login_popup_end'}

			<input type="submit" name="submit_login" value="{$aLang.user_login_submit}" /><br /><br />
			
			<a href="{router page='login'}reminder/">{$aLang.user_password_reminder}</a><br />
			<a href="{router page='registration'}">{$aLang.user_registration}</a>
		</form>
	</div>
{/if}