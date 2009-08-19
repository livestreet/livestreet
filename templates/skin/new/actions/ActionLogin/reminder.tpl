{include file='header.light.tpl'}

	<div class="lite-center">
		<form action="{router page='login'}reminder/" method="POST">
				<h3>{$aLang.password_reminder}</h3>
				<div class="lite-note"><label for="mail">{$aLang.password_reminder_email}:</label></div>
				<p><input type="text" class="input-text" name="mail" id="name"/></p>				
				<input type="submit" name="submit_reminder" value="{$aLang.password_reminder_submit}" />
		</form>
	</div>

{include file='footer.light.tpl'}