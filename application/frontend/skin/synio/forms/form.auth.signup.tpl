{**
 * Форма регистрации
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

{hook run='registration_begin' isPopup=$isModal}

<form action="{router page='registration'}" method="post" class="js-form-signup form-signup {if ! $isModal}form-signup-page{/if}">
	{hook run='form_registration_begin' isPopup=$isModal}

	{* Логин *}
	<p><label for="js-form-signup-login">{$aLang.registration_login}</label>
	<input type="text" name="login" value="{$_aRequest.login}" id="js-form-signup-login" class="width-300 js-ajax-validate js-form-signup-login" />
	<i class="icon-validation-success validate-ok-field-login" style="display: none"></i>
	<i class="icon-question-sign js-tip-help" title="{$aLang.registration_login_notice}"></i>
	<small class="validate-error-hide validate-error-field-login"></small></p>

	{* E-mail *}
	<p><label for="js-form-signup-mail">{$aLang.registration_mail}</label>
	<input type="text" name="mail" value="{$_aRequest.mail}" id="js-form-signup-mail" class="width-300 js-ajax-validate js-form-signup-mail" />
	<i class="icon-validation-success validate-ok-field-mail" style="display: none"></i>
	<i class="icon-question-sign js-tip-help" title="{$aLang.registration_mail_notice}"></i>
	<small class="validate-error-hide validate-error-field-mail"></small></p>

	{* Пароль *}
	<p><label for="js-form-signup-password">{$aLang.registration_password}</label>
	<input type="password" name="password" value="" id="js-form-signup-password" class="width-300 js-ajax-validate js-form-signup-password" />
	<i class="icon-validation-success validate-ok-field-password" style="display: none"></i>
	<i class="icon-question-sign js-tip-help" title="{$aLang.registration_password_notice}"></i>
	<small class="validate-error-hide validate-error-field-password"></small></p>

	{* Подтверждение пароля *}
	<p><label for="js-form-signup-password-confirm">{$aLang.registration_password_retry}</label>
	<input type="password" value="" name="password_confirm" id="js-form-signup-password-confirm" class="width-300 js-ajax-validate js-form-signup-password-confirm" />
	<i class="icon-validation-success validate-ok-field-password_confirm" style="display: none"></i>
	<small class="validate-error-hide validate-error-field-password_confirm"></small></p>

	{**
	 * Каптча 
	 *
	 * @scripts <framework>/js/livestreet/captcha.js
	 *}
	{hookb run="registration_captcha" isPopup=$isModal}
		<p class="form-signup-field-captcha"><label for="js-form-signup-captcha">{$aLang.registration_captcha}</label>
		<span {if ! $isModal}style="background-image: url({cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId});"{/if} class="form-auth-captcha js-form-auth-captcha"></span>
		<input type="text" name="captcha" value="" maxlength="3" id="js-form-signup-captcha" class="width-100 js-ajax-validate js-form-signup-captcha" />
		<small class="validate-error-hide validate-error-field-captcha"></small></p>
	{/hookb}

	{hook run='form_registration_end' isPopup=$isModal}

	<input type="hidden" name="return-path" value="{$PATH_WEB_CURRENT}">
	<p class="form-signup-field-submit"><button type="submit" name="submit_register" class="button button-primary js-form-signup-submit" disabled>{$aLang.registration_submit}</button></p>
</form>

{hook run='registration_end' isPopup=$isModal}