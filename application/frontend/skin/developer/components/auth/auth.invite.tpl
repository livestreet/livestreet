{**
 * Форма регистрации через инвайт
 *}

<form action="{router page='registration'}invite/" method="post">
	{include 'components/field/field.text.tpl'
			sName         = 'invite_code'
			aRules        = [ 'required' => true, 'type' => 'alphanum' ]
			sLabel        = $aLang.auth.invite.form.fields.code.label}

	{include 'components/button/button.tpl' sName='submit_invite' sMods='primary' sText=$aLang.auth.invite.form.fields.submit.text}
</form>