{**
 * Форма регистрации
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

{$redirectUrl = $smarty.local.redirectUrl|default:$PATH_WEB_CURRENT}

{hook run='registration_begin'}

<form action="{router page='registration'}" method="post" class="js-auth-registration-form">
	{hook run='form_registration_begin'}

	{* Логин *}
    {include 'components/field/field.text.tpl'
             sName   = 'login'
             aRules  = [ 'required' => true, 'rangelength' => '[2,20]', 'remote' => "{router page='registration'}ajax-validate-fields", 'remote-method' => 'POST' ]
             sLabel  = $aLang.auth.labels.login}

	{* E-mail *}
    {include 'components/field/field.email.tpl' aRules=[ 'remote' => "{router page='registration'}ajax-validate-fields", 'remote-method' => 'POST' ]}

	{* Пароль *}
    {include 'components/field/field.text.tpl'
             sName         = 'password'
             sType         = 'password'
             aRules        = [ 'required' => true, 'rangelength' => '[2,20]' ]
             sLabel        = $aLang.auth.labels.password
             sInputClasses = 'js-input-password-reg'}

	{* Повторите пароль *}
    {include 'components/field/field.text.tpl'
             sName   = 'password_confirm'
             sType   = 'password'
             aRules  = [ 'required' => true, 'rangelength' => '[2,20]', 'equalto' => '.js-input-password-reg' ]
             sLabel  = $aLang.auth.registration.form.fields.password_confirm.label}

    {* Каптча *}
    {include 'components/field/field.captcha.tpl'
             sName        = 'captcha'
             sCaptchaName = 'user_signup'
             sLabel       = $aLang.auth.labels.captcha}

	{hook run='form_registration_end'}

    {if $redirectUrl}
        {include 'components/field/field.hidden.tpl' sName='return-path' sValue=$redirectUrl}
    {/if}

    {include 'components/button/button.tpl' sName='submit_register' sMods='primary' sText=$aLang.auth.registration.form.fields.submit.text}
</form>

{hook run='registration_end'}
