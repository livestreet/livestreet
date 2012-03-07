{if !$oUserCurrent}
	<div class="jqmWindow modal-login" id="login_form">
		<header>
			<h3>{$aLang.user_authorization}</h3>
			<a href="#" class="close jqmClose"></a>
		</header>
		
		
		<form action="{router page='login'}" method="post">
			{hook run='form_login_popup_begin'}

			<p><label for="login">{$aLang.user_login}:</label>
			<input type="text" name="login" id="login" class="input-text input-width-full"></p>
			
			<p><label for="password">{$aLang.user_password}:</label>
			<input type="password" name="password" id="password" class="input-text input-width-full"></p>
			
			<p><label><input type="checkbox" name="remember" class="input-checkbox" checked> {$aLang.user_login_remember}</label></p>

			{hook run='form_login_popup_end'}

			<button name="submit_login" class="button button-primary">{$aLang.user_login_submit}</button>
			
			<br /><br />
			
			<a href="{router page='login'}reminder/">{$aLang.user_password_reminder}</a>
		</form>
	</div>
{/if}