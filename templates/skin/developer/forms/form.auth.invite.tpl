{**
 * Форма регистрации через инвайт
 *}

<form action="{router page='registration'}invite/" method="post">
	<p><label>{$aLang.registration_invite_code}:</label>
	<input type="text" name="invite_code" class="width-300" /></p>

	<button type="submit" name="submit_invite" class="button button-primary">{$aLang.registration_invite_check}</button>
</form>