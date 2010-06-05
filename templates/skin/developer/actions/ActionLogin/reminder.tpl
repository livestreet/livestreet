{assign var="noSidebar" value=true}
{include file='header.tpl'}


<div class="center">
	<form action="{router page='login'}reminder/" method="POST">
		<h2>{$aLang.password_reminder}</h2>

		<p><label for="mail">{$aLang.password_reminder_email}<br />
		<input type="text" name="mail" id="name" class="input-200" /></label></p>	

		<input type="submit" name="submit_reminder" value="{$aLang.password_reminder_submit}" />
	</form>
</div>


{include file='footer.tpl'}