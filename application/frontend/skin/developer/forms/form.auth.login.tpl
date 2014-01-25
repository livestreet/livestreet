{**
 * Форма входа
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

{hook run='login_begin' isModal=$isModal}

<form action="{router page='login'}" method="post" class="js-form-login">
	{hook run='form_login_begin' isModal=$isModal}

	{* Логин *}
	{include file='forms/fields/form.field.text.tpl'
			 sFieldName   = 'login'
			 sFieldRules  = 'required="true" rangelength="[2,20]"'
			 sFieldLabel  = $aLang.user_login}

	{* Пароль *}
	{include file='forms/fields/form.field.text.tpl'
			 sFieldName   = 'password'
			 sFieldType   = 'password'
			 sFieldRules  = 'required="true" rangelength="[2,20]"'
			 sFieldLabel  = $aLang.user_password}

	{* Каптча *}
	{if $oConfig->GetValue('general.login.captcha')}
		{include file='forms/fields/form.field.captcha.tpl'
				sFieldName   = 'captcha'
				sCaptchaName   = 'user_auth'
				sFieldLabel  = $aLang.registration_captcha}
	{/if}
	
	{* Запомнить *}
	{include file='forms/fields/form.field.checkbox.tpl'
			 sFieldName    = 'remember'
			 sFieldLabel   = $aLang.user_login_remember
			 bFieldChecked = true}

	{hook run='form_login_end' isModal=$isModal}

	{include file='forms/fields/form.field.hidden.tpl' sFieldName='return-path' value=$PATH_WEB_CURRENT}
	{include file='forms/fields/form.field.button.tpl' sFieldName='submit_login' sFieldStyle='primary' sFieldText=$aLang.user_login_submit}
</form>

{if ! $isModal}
	<div class="pt-20">
		<a href="{router page='registration'}">{$aLang.user_registration}</a><br />
		<a href="{router page='login'}reminder/">{$aLang.user_password_reminder}</a>
	</div>
{/if}

{hook run='login_end' isModal=$isModal}