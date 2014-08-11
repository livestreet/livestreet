{**
 * Форма запроса повторной активации аккаунта
 *}

<form action="{router page='registration'}reactivation/" method="post" class="js-form-reactivation">
	{* E-mail *}
    {include 'components/field/field.email.tpl' sLabel=$aLang.auth.reactivation.form.fields.mail.label}

	{include 'components/button/button.tpl' sName='submit_reactivation' sMods='primary' sText=$aLang.auth.reactivation.form.fields.submit.text}
</form>