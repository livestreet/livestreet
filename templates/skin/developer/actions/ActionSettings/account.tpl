{**
 * Настройки аккаунта (емэйл, пароль)
 *}

{extends file='layouts/layout.user.settings.tpl'}

{block name='layout_content'}
	{hook run='settings_account_begin'}

	<form method="post" enctype="multipart/form-data" data-validate="parsley">
		{hook run='form_settings_account_begin'}

		<fieldset>
			<legend>{$aLang.settings_account}</legend>

            {* E-mail *}
            {include file='forms/form.field.text.tpl'
                     sFieldName  = 'mail'
                     sFieldRules = 'required="true" type="email"'
                     sFieldValue = $oUserCurrent->getMail()|escape
                     sFieldNote  = $aLang.settings_profile_mail_notice
                     sFieldLabel = $aLang.settings_profile_mail}
		</fieldset>

			
		<fieldset>
			<legend>{$aLang.settings_account_password}</legend>
			
			<small class="note mb-20">{$aLang.settings_account_password_notice}</small>

            {* Текущий пароль *}
            {include file='forms/form.field.text.tpl'
                     sFieldName    = 'password_now'
                     sFieldType    = 'password'
                     sFieldClasses = 'width-200'
                     sFieldLabel   = $aLang.settings_profile_password_current}

            {* Новый пароль *}
            {include file='forms/form.field.text.tpl'
                     sFieldName    = 'password'
                     sFieldRules   = 'rangelength="[5,20]"'
                     sFieldType    = 'password'
                     sFieldClasses = 'width-200'
                     sFieldLabel   = $aLang.settings_profile_password_new}

            {* Повторить овый пароль *}
            {include file='forms/form.field.text.tpl'
                     sFieldName    = 'password_confirm'
                     sFieldRules   = 'rangelength="[5,20]" equalto=".js-input-password"'
                     sFieldType    = 'password'
                     sFieldClasses = 'width-200'
                     sFieldLabel   = $aLang.settings_profile_password_confirm}
		</fieldset>

		
		{hook run='form_settings_account_end'}

        {* Скрытые поля *}
        {include file='forms/form.field.hidden.security_key.tpl'}

        {* Кнопки *}
        {include file='forms/form.field.button.tpl' sFieldName='submit_account_edit' sFieldStyle='primary' sFieldText=$aLang.settings_profile_submit}
	</form>

	{hook run='settings_account_end'}
{/block}