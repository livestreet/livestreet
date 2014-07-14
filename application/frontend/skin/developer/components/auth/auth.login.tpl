{**
 * Форма входа
 *}

{$redirectUrl = $smarty.local.redirectUrl|default:$PATH_WEB_CURRENT}

{hook run='login_begin'}

<form action="{router page='login'}" method="post" class="js-auth-login-form">
	{hook run='form_login_begin'}

	{* Логин *}
	{include 'components/field/field.text.tpl'
			 sName   = 'login'
			 aRules  = [ 'required' => true, 'rangelength' => '[2,20]' ]
			 sLabel  = $aLang.auth.login.form.fields.login.label}

	{* Пароль *}
	{include 'components/field/field.text.tpl'
			 sName   = 'password'
			 sType   = 'password'
			 aRules  = [ 'required' => true, 'rangelength' => '[2,20]' ]
			 sLabel  = $aLang.auth.labels.password}

	{* Каптча *}
	{if Config::Get('general.login.captcha')}
		{include 'components/field/field.captcha.tpl'
				sName   = 'captcha'
				sCaptchaName   = 'user_auth'
				sLabel  = $aLang.auth.labels.captcha}
	{/if}

	{* Запомнить *}
	{include 'components/field/field.checkbox.tpl'
			 sName    = 'remember'
			 sLabel   = $aLang.auth.login.form.fields.remember.label
			 bChecked = true}

	{hook run='form_login_end'}

    {if $redirectUrl}
        {include 'components/field/field.hidden.tpl' sName='return-path' sValue=$redirectUrl}
    {/if}

	{include 'components/button/button.tpl' sName='submit_login' sMods='primary' sText=$aLang.auth.login.form.fields.submit.text}
</form>

{if $smarty.local.showExtra}
	<div class="pt-20">
		<a href="{router page='registration'}">{$aLang.auth.registration.title}</a><br />
		<a href="{router page='login'}reset/">{$aLang.auth.reset.title}</a>
	</div>
{/if}

{hook run='login_end'}