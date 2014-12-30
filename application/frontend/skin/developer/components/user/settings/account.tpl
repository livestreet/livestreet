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
                 value = $user->getMail()|escape
                 note  = {lang name='user.settings.account.fields.email.note'}}
	</fieldset>


	<fieldset>
		<legend>{lang name='user.settings.account.password'}</legend>

		<p class="text-info">{lang name='user.settings.account.password_note'}</p>

        {* Текущий пароль *}
        {include 'components/field/field.text.tpl'
                 name    = 'password_now'
                 type    = 'password'
                 inputClasses = 'width-200'
                 label   = {lang name='user.settings.account.fields.password.label'}}

        {* Новый пароль *}
        {include 'components/field/field.text.tpl'
                 name    = 'password'
                 rules   = [ 'rangelength' => '[5,20]' ]
                 type    = 'password'
                 inputClasses = 'width-200 js-user-settings-password'
                 label   = {lang name='user.settings.account.fields.password_new.label'}}

        {* Повторить новый пароль *}
        {include 'components/field/field.text.tpl'
                 name    = 'password_confirm'
                 rules   = [ 'rangelength' => '[5,20]', 'equalto' => '.js-user-settings-password' ]
                 type    = 'password'
                 inputClasses = 'width-200'
                 label   = {lang name='user.settings.account.fields.password_confirm.label'}}
	</fieldset>


	{hook run='form_settings_account_end'}

    {* Скрытые поля *}
    {include 'components/field/field.hidden.security_key.tpl'}

    {* Кнопки *}
    {include 'components/button/button.tpl' name='submit_account_edit' mods='primary' text=$aLang.common.save}
</form>

{hook run='settings_account_end'}