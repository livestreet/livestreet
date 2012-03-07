{assign var="noSidebar" value=true}
{include file='header.tpl'}



<h2 class="page-header">{$aLang.password_reminder}</h2>

<form action="{router page='login'}reminder/" method="POST">
	<p><label for="mail">{$aLang.password_reminder_email}</label>
	<input type="text" name="mail" id="name" class="input-200" /></p>	

	<input type="submit" name="submit_reminder" value="{$aLang.password_reminder_submit}" />
</form>



{include file='footer.tpl'}