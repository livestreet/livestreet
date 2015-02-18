{**
 * Форма запроса повторной активации аккаунта
 *}

<form action="{router page='auth'}reactivation/" method="post" class="js-form-reactivation">
    {* E-mail *}
    {component 'field' template='email' label=$aLang.auth.reactivation.form.fields.mail.label}

    {component 'button' name='submit_reactivation' mods='primary' text=$aLang.auth.reactivation.form.fields.submit.text}
</form>