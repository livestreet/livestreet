{**
 * Форма входа
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

{hook run='login_begin' isModal=$isModal}

<form action="{router page='login'}" method="post" class="js-form-login">
	{hook run='form_login_begin' isModal=$isModal}

	{* Логин *}
	{include file='components/field/field.text.tpl'
			 sName   = 'login'
			 aRules  = [ 'required' => true, 'rangelength' => '[2,20]' ]
			 sLabel  = $aLang.user_login}

	{* Пароль *}
	{include file='components/field/field.text.tpl'
			 sName   = 'password'
			 sType   = 'password'
			 aRules  = [ 'required' => true, 'rangelength' => '[2,20]' ]
			 sLabel  = $aLang.user_password}

	{* Каптча *}
	{if Config::Get('general.login.captcha')}
		{include file='components/field/field.captcha.tpl'
				sName   = 'captcha'
				sCaptchaName   = 'user_auth'
				sLabel  = $aLang.registration_captcha}
	{/if}

	{* Запомнить *}
	{include file='components/field/field.checkbox.tpl'
			 sName    = 'remember'
			 sLabel   = $aLang.user_login_remember
			 bChecked = true}

	{hook run='form_login_end' isModal=$isModal}

	{include file='components/field/field.hidden.tpl' sName='return-path' value=$PATH_WEB_CURRENT}
	{include file='components/button/button.tpl' sName='submit_login' sMods='primary' sText=$aLang.user_login_submit}
</form>

{if ! $isModal}
	<div class="pt-20">
		<a href="{router page='registration'}">{$aLang.user_registration}</a><br />
		<a href="{router page='login'}reminder/">{$aLang.user_password_reminder}</a>
	</div>
{/if}

{hook run='login_end' isModal=$isModal}