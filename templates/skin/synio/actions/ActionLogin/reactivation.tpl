{$noSidebar = true}
{include file='header.tpl'}

<h2 class="page-header">{$aLang.reactivation}</h2>

<form action="{router page='login'}reactivation/" method="POST" id="reactivation-form">
	<p><label for="reactivation-mail">{$aLang.password_reminder_email}</label>
	<input type="text" name="mail" id="reactivation-mail" class="input-text input-width-200" />
	<small class="validate-error-hide validate-error-reactivation"></small></p>

	<button type="submit"  name="submit_reactivation" class="button button-primary" id="reactivation-form-submit" disabled="disabled">{$aLang.reactivation_submit}</button>
</form>

{include file='footer.tpl'}