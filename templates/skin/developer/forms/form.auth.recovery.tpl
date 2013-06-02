{**
 * Форма восстановления пароля
 *
 * isModal Если true, то форма выводится в модальном окне
 *}

<form action="{router page='login'}reminder/" method="post" class="js-form-recovery">
	<p><label for="mail">{$aLang.password_reminder_email}</label>
	<input type="text" name="mail" id="mail" class="width-300" />
	<small class="validate-error-hide validate-error-reminder"></small></p>

	<button type="submit" name="submit_reminder" class="button button-primary js-form-recovery-submit" disabled>{$aLang.password_reminder_submit}</button>
</form>