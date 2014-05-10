{**
 * Форма регистрации через инвайт
 *}

<form action="{router page='registration'}invite/" method="post">
	{include file='components/field/field.text.tpl'
			 sName    = 'invite_code'
			 aRules   = [ 'required' => true, 'type' => 'alphanum' ]
			 sLabel   = $aLang.registration_invite_code
			 sInputClasses = 'width-300'}

	{include file='components/button/button.tpl' sName='submit_invite' sStyle='primary' sText=$aLang.registration_invite_check}
</form>