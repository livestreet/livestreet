{**
 * Форма запроса повторной активации аккаунта
 *}

<form action="{router page='login'}reactivation/" method="POST" id="reactivation-form">
	<p><label for="reactivation-mail">{$aLang.password_reminder_email}</label>
	<input type="text" name="mail" id="reactivation-mail" class="width-200" />
	<small class="validate-error-hide validate-error-reactivation"></small></p>

	<button type="submit"  name="submit_reactivation" class="button button-primary" id="reactivation-form-submit" disabled>{$aLang.reactivation_submit}</button>
</form>