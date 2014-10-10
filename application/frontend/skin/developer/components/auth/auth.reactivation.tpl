{**
 * Форма запроса повторной активации аккаунта
 *}

<form action="{router page='registration'}reactivation/" method="post" class="js-form-reactivation">
	{* E-mail *}
    {include 'components/field/field.email.tpl' sLabel=$aLang.auth.reactivation.form.fields.mail.label}

	{include 'components/button/button.tpl' name='submit_reactivation' mods='primary' text=$aLang.auth.reactivation.form.fields.submit.text}
</form>