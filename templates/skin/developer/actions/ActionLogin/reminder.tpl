{include file='header.light.tpl'}

<div class="center-block">
	<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LOGIN}/reminder/" method="POST">
		<h3>{$aLang.password_reminder}</h3>
		
		<p><label for="mail">{$aLang.password_reminder_email}:</label>
		<input type="text" class="input-text" name="mail" id="name" /></p>	
		
		<input type="submit" name="submit_reminder" value="{$aLang.password_reminder_submit}" />
	</form>
</div>

{include file='footer.light.tpl'}