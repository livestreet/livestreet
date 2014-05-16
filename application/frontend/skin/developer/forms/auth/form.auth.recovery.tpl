{**
 * Форма восстановления пароля
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

<form action="{router page='login'}reminder/" method="post" class="js-form-recovery">
	{* E-mail *}
    {include file='components/field/field.text.tpl'
             sName   = 'mail'
             aRules  = [ 'required' => true, 'type' => 'email' ]
             sLabel  = $aLang.password_reminder_email}

	{include file='components/button/button.tpl' sName='submit_reactivation' sMods='primary' sText=$aLang.password_reminder_submit}
</form>