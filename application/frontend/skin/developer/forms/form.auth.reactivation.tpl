{**
 * Форма запроса повторной активации аккаунта
 *}

<form action="{router page='login'}reactivation/" method="post" class="js-form-reactivation">
	{* E-mail *}
    {include file='forms/form.field.text.tpl'
             sFieldName   = 'mail'
             sFieldRules  = 'required="true" type="email"'
             sFieldLabel  = $aLang.password_reminder_email}

	{include file='forms/form.field.button.tpl' sFieldName='submit_reactivation' sFieldStyle='primary' sFieldText=$aLang.reactivation_submit}
</form>