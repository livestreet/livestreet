{**
 * Форма регистрации
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

{hook run='registration_begin' isPopup=$isModal}

<form action="{router page='registration'}" method="post" class="{if $isModal}js-form-signup{else}js-form-registration{/if}">
	{hook run='form_registration_begin' isPopup=$isModal}

	{* Логин *}
    {include file='components/field/field.text.tpl'
             sName   = 'login'
             aRules  = [ 'required' => true, 'rangelength' => '[2,20]', 'remote' => "{router page='registration'}ajax-validate-fields", 'remote-method' => 'POST' ]
             sLabel  = $aLang.user_login}

	{* E-mail *}
    {include file='components/field/field.text.tpl'
             sName   = 'mail'
             aRules  = [ 'required' => true, 'type' => 'email' ]
             sLabel  = $aLang.registration_mail}

	{* Пароль *}
    {include file='components/field/field.text.tpl'
             sName    = 'password'
             sType    = 'password'
             aRules   = [ 'required' => true, 'rangelength' => '[2,20]' ]
             sLabel   = $aLang.registration_password
             sClasses = 'js-input-password-reg'}

	{* Повторите пароль *}
    {include file='components/field/field.text.tpl'
             sName   = 'password_confirm'
             sType   = 'password'
             aRules  = [ 'required' => true, 'rangelength' => '[2,20]', 'equalto' => '.js-input-password-reg' ]
             sLabel  = $aLang.registration_password_retry}

    {* Каптча *}
    {include file='components/field/field.captcha.tpl'
             sName        = 'captcha'
             sCaptchaName = 'user_signup'
             sLabel       = $aLang.registration_captcha}

	{hook run='form_registration_end' isPopup=$isModal}

    {if $isModal}
        {include file='components/field/field.hidden.tpl' sName='return-path' sValue=$PATH_WEB_CURRENT}
    {/if}
    {include file='components/button/button.tpl' sName='submit_register' sMods='primary' sText=$aLang.registration_submit}
</form>

{hook run='registration_end' isPopup=$isModal}
