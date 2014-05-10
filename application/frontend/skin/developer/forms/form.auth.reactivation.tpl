{**
 * Форма запроса повторной активации аккаунта
 *}

<form action="{router page='login'}reactivation/" method="post" class="js-form-reactivation">
	{* E-mail *}
    {include file='components/field/field.text.tpl'
             sName   = 'mail'
             aRules  = [ 'required' => true, 'type' => 'email' ]
             sLabel  = $aLang.password_reminder_email}

	{include file='components/button/button.tpl' sName='submit_reactivation' sStyle='primary' sText=$aLang.reactivation_submit}
</form>