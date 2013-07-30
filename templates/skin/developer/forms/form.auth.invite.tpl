{**
 * Форма регистрации через инвайт
 *}

<form action="{router page='registration'}invite/" method="post">
	{include file='forms/form.field.text.tpl' 
			 sFieldName    = 'invite_code' 
			 sFieldRules   = 'required="true" type="alphanum'
			 sFieldLabel   = $aLang.registration_invite_code
			 sFieldClasses = 'width-300'}
			 
	{include file='forms/form.field.button.tpl' sFieldName='submit_invite' sFieldStyle='primary' sFieldText=$aLang.registration_invite_check}
</form>