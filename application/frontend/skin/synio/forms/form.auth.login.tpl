{**
 * Форма входа
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

{hook run='login_begin' isModal=$isModal}

<form action="{router page='login'}" method="post" class="js-form-login form-login {if ! $isModal}form-login-page{/if}">
	{hook run='form_login_begin' isModal=$isModal}

	{* Логин *}
	<p><input type="text" name="login" id="login" placeholder="{$aLang.user_login}" class="js-form-login-login width-300"></p>

	{* Пароль *}
	<p><input type="password" name="password" id="password" placeholder="{$aLang.user_password}" class="js-form-login-password width-300"></p>

	<small class="validate-error-hide validate-error-login"></small>

	{* Запомнить *}
	<p><label class="remember-label"><input type="checkbox" name="remember" checked> {$aLang.user_login_remember}</label></p>

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