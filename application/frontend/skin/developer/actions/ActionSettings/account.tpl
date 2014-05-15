{**
 * Настройки аккаунта (емэйл, пароль)
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
	{hook run='settings_account_begin'}

	<form method="post" enctype="multipart/form-data" class="js-form-validate">
		{hook run='form_settings_account_begin'}

		<fieldset>
			<legend>{$aLang.settings_account}</legend>

            {* E-mail *}
            {include 'components/field/field.text.tpl'
                     sName  = 'mail'
                     aRules = [ 'required' => true, 'type'=> 'email' ]
                     sValue = $oUserCurrent->getMail()|escape
                     sNote  = $aLang.settings_profile_mail_notice
                     sLabel = $aLang.settings_profile_mail}
		</fieldset>

			
		<fieldset>
			<legend>{$aLang.settings_account_password}</legend>
			
			<small class="note mb-20">{$aLang.settings_account_password_notice}</small>

            {* Текущий пароль *}
            {include 'components/field/field.text.tpl'
                     sName    = 'password_now'
                     sType    = 'password'
                     sInputClasses = 'width-200'
                     sLabel   = $aLang.settings_profile_password_current}

            {* Новый пароль *}
            {include 'components/field/field.text.tpl'
                     sName    = 'password'
                     aRules   = [ 'rangelength' => '[5,20]' ]
                     sType    = 'password'
                     sInputClasses = 'width-200'
                     sLabel   = $aLang.settings_profile_password_new}

            {* Повторить овый пароль *}
            {include 'components/field/field.text.tpl'
                     sName    = 'password_confirm'
                     aRules   = [ 'rangelength' => '[5,20]', 'equalto' => '.js-input-password' ]
                     sType    = 'password'
                     sInputClasses = 'width-200'
                     sLabel   = $aLang.settings_profile_password_confirm}
		</fieldset>

		
		{hook run='form_settings_account_end'}

        {* Скрытые поля *}
        {include 'components/field/field.hidden.security_key.tpl'}

        {* Кнопки *}
        {include 'components/button/button.tpl' sName='submit_account_edit' sMods='primary' sText=$aLang.settings_profile_submit}
	</form>

	{hook run='settings_account_end'}
{/block}