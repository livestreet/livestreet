{**
 * Форма восстановления пароля
 *}

<form action="{router page='auth'}password-reset/" method="post" class="js-form-validate js-auth-reset-form">
    {* E-mail *}
    {component 'field' template='email' label=$aLang.auth.reset.form.fields.mail.label}

    {component 'button' name='submit_reset' mods='primary' text=$aLang.auth.reset.form.fields.submit.text}
</form>