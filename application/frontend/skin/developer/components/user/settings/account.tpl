{**
 * Настройки аккаунта (емэйл, пароль)
 *}

{$user = $smarty.local.user}

{hook run='settings_account_begin'}

<form method="post" enctype="multipart/form-data" class="js-form-validate">
	{hook run='form_settings_account_begin'}

	<fieldset>
		<legend>{lang name='user.settings.account.account'}</legend>

        {* E-mail *}
        {include 'components/field/field.text.tpl'
                 sName  = 'mail'
                 aRules = [ 'required' => true, 'type'=> 'email' ]
                 sValue = $user->getMail()|escape
                 sNote  = {lang name='user.settings.account.fields.email.note'}
                 sLabel = {lang name='user.settings.account.fields.email.label'}}
	</fieldset>


	<fieldset>
		<legend>{lang name='user.settings.account.password'}</legend>

		<small class="note mb-20">{lang name='user.settings.account.password_note'}</small>

        {* Текущий пароль *}
        {include 'components/field/field.text.tpl'
                 sName    = 'password_now'
                 sType    = 'password'
                 sInputClasses = 'width-200'
                 sLabel   = {lang name='user.settings.account.fields.password.label'}}

        {* Новый пароль *}
        {include 'components/field/field.text.tpl'
                 sName    = 'password'
                 aRules   = [ 'rangelength' => '[5,20]' ]
                 sType    = 'password'
                 sInputClasses = 'width-200'
                 sLabel   = {lang name='user.settings.account.fields.password_new.label'}}

        {* Повторить овый пароль *}
        {include 'components/field/field.text.tpl'
                 sName    = 'password_confirm'
                 aRules   = [ 'rangelength' => '[5,20]', 'equalto' => '.js-input-password' ]
                 sType    = 'password'
                 sInputClasses = 'width-200'
                 sLabel   = {lang name='user.settings.account.fields.password_confirm.label'}}
	</fieldset>


	{hook run='form_settings_account_end'}

    {* Скрытые поля *}
    {include 'components/field/field.hidden.security_key.tpl'}

    {* Кнопки *}
    {include 'components/button/button.tpl' sName='submit_account_edit' sMods='primary' sText=$aLang.common.save}
</form>

{hook run='settings_account_end'}