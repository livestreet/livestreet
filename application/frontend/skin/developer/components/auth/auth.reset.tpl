{**
 * Форма восстановления пароля
 *}

<form action="{router page='login'}reminder/" method="post" class="js-auth-reset-form">
	{* E-mail *}
    {include 'components/field/field.email.tpl' sLabel=$aLang.auth.reset.form.fields.mail.label}

	{include 'components/button/button.tpl' sName='submit_reset' sMods='primary' sText=$aLang.auth.reset.form.fields.submit.text}
</form>