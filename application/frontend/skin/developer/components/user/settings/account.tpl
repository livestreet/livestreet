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
        {include 'components/field/field.email.tpl'
                 sValue = $user->getMail()|escape
                 sNote  = {lang name='user.settings.account.fields.email.note'}}
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
                 sInputClasses = 'width-200 js-user-settings-password'
                 sLabel   = {lang name='user.settings.account.fields.password_new.label'}}

        {* Повторить новый пароль *}
        {include 'components/field/field.text.tpl'
                 sName    = 'password_confirm'
                 aRules   = [ 'rangelength' => '[5,20]', 'equalto' => '.js-user-settings-password' ]
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