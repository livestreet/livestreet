{**
 * Форма восстановления пароля
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

<form action="{router page='login'}reminder/" method="post" class="js-form-recovery">
	{* E-mail *}
    {include file='forms/form.field.text.tpl'
             sFieldName   = 'mail'
             sFieldRules  = 'required="true" type="email"'
             sFieldLabel  = $aLang.password_reminder_email}

	{include file='forms/form.field.button.tpl' sFieldName='submit_reactivation' sFieldStyle='primary' sFieldText=$aLang.password_reminder_submit}
</form>