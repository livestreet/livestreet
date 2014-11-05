{**
 * Форма регистрации через инвайт
 *}

<form action="{router page='registration'}invite/" method="post">
    {include 'components/field/field.text.tpl'
        name  = 'invite_code'
        rules = [ 'required' => true, 'type' => 'alphanum' ]
        label = $aLang.auth.invite.form.fields.code.label}

    {include 'components/button/button.tpl' name='submit_invite' mods='primary' text=$aLang.auth.invite.form.fields.submit.text}
</form>