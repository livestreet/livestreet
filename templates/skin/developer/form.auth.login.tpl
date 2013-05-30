{**
 * Форма входа
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

{hook run='login_begin' isModal=$isModal}

<form action="{router page='login'}" method="post" class="js-form-login">
	{hook run='form_login_begin' isModal=$isModal}

	{* Логин *}
	<p><label for="login">{$aLang.user_login}:</label>
	<input type="text" name="login" id="login" class="js-form-login-login width-300"></p>

	{* Пароль *}
	<p><label for="password">{$aLang.user_password}:</label>
	<input type="password" name="password" id="password" class="js-form-login-password width-300">
	<small class="validate-error-hide validate-error-login"></small></p>

	{* Запомнить *}
	<p><label><input type="checkbox" name="remember" class="input-checkbox" checked> {$aLang.user_login_remember}</label></p>

	{hook run='form_login_end' isModal=$isModal}

	<input type="hidden" name="return-path" value="{$PATH_WEB_CURRENT}">
	<button type="submit" name="submit_login" class="button button-primary js-form-login-submit" disabled>{$aLang.user_login_submit}</button>
</form>

{if ! $isModal}
	<div class="pt-20">
		<a href="{router page='registration'}">{$aLang.user_registration}</a><br />
		<a href="{router page='login'}reminder/">{$aLang.user_password_reminder}</a>
	</div>
{/if}

{hook run='login_end' isModal=$isModal}