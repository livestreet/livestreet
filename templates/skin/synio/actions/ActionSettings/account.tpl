{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}



{include file='menu.settings.tpl'}

{hook run='settings_account_begin'}

<form method="post" enctype="multipart/form-data" class="wrapper-content">
	{hook run='form_settings_account_begin'}

	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}">
	
	
	<h3>{$aLang.settings_account}</h3>
		
	<dl class="form-item">
		<dt><label for="mail">{$aLang.settings_profile_mail}:</label></dt>
		<dd>	
			<input type="email" name="mail" id="mail" value="{$oUserCurrent->getMail()|escape:'html'}" class="input-text input-width-300" required />
			<small class="note">{$aLang.settings_profile_mail_notice}</small>
		</dd>
	</dl>
	
	<br />
	<br />
		
	<h3>{$aLang.settings_account_password}</h3>
		
	<small class="note note-header">{$aLang.settings_account_password_notice}</small>
		
	<dl class="form-item">
		<dt><label for="password_now">{$aLang.settings_profile_password_current}:</label></dt>
		<dd><input type="password" name="password_now" id="password_now" value="" class="input-text input-width-200" /></dd>
	</dl>
	
	<dl class="form-item">
		<dt><label for="password">{$aLang.settings_profile_password_new}:</label></dt>
		<dd><input type="password" id="password" name="password" value="" class="input-text input-width-200" /></dd>
	</dl>
	
	<dl class="form-item">
		<dt><label for="password_confirm">{$aLang.settings_profile_password_confirm}:</label></dt>
		<dd><input type="password" id="password_confirm" name="password_confirm" value="" class="input-text input-width-200" /></dd>
	</dl>
	
	{hook run='form_settings_account_end'}
	
	
	<button type="submit"  name="submit_account_edit" class="button button-primary" />{$aLang.settings_profile_submit}</button>
</form>

{hook run='settings_account_end'}

{include file='footer.tpl'}