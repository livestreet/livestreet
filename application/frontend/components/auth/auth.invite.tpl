{**
 * Форма регистрации через инвайт
 *}

<form action="{router page='auth'}invite/" method="post">
    {component 'field' template='text'
        name  = 'invite_code'
        rules = [ 'required' => true, 'type' => 'alphanum' ]
        label = $aLang.auth.invite.form.fields.code.label}

    {component 'button' name='submit_invite' mods='primary' text=$aLang.auth.invite.form.fields.submit.text}
</form>