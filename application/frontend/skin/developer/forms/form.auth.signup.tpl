{**
 * Форма регистрации
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

{hook run='registration_begin' isPopup=$isModal}

<form action="{router page='registration'}" method="post" class="{if $isModal}js-form-signup{else}js-form-registration{/if}">
	{hook run='form_registration_begin' isPopup=$isModal}

	{* Логин *}
    {include file='forms/form.field.text.tpl'
             sFieldName   = 'login'
             sFieldRules  = 'required="true" rangelength="[2,20]" remote="'|cat:{router page='registration'}|cat:'ajax-validate-fields" remote-method="POST"'
             sFieldLabel  = $aLang.user_login}

	{* E-mail *}
    {include file='forms/form.field.text.tpl'
             sFieldName   = 'mail'
             sFieldRules  = 'required="true" type="email"'
             sFieldLabel  = $aLang.registration_mail}

	{* Пароль *}
    {include file='forms/form.field.text.tpl'
             sFieldName    = 'password'
             sFieldType    = 'password'
             sFieldRules   = 'required="true" rangelength="[2,20]"'
             sFieldLabel   = $aLang.registration_password
             sFieldClasses = 'js-input-password-reg'}

	{* Повторите пароль *}
    {include file='forms/form.field.text.tpl'
             sFieldName   = 'password_confirm'
             sFieldType   = 'password'
             sFieldRules  = 'required="true" rangelength="[2,20]" equalto=".js-input-password-reg"'
             sFieldLabel  = $aLang.registration_password_retry}

    {* Каптча *}
    {include file='forms/form.field.captcha.tpl'
             sFieldName   = 'captcha'
             sFieldLabel  = $aLang.registration_captcha}

	{hook run='form_registration_end' isPopup=$isModal}

    {if $isModal}
        {include file='forms/form.field.hidden.tpl' sFieldName='return-path' sFieldValue=$PATH_WEB_CURRENT}
    {/if}
    {include file='forms/form.field.button.tpl' sFieldName='submit_register' sFieldStyle='primary' sFieldText=$aLang.registration_submit}
</form>

{hook run='registration_end' isPopup=$isModal}
