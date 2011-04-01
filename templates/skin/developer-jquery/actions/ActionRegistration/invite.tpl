{assign var="noSidebar" value=true}
{include file='header.tpl'}


<div class="center">
	<form action="{router page='registration'}invite/" method="POST">
		<h2>{$aLang.registration_invite}</h2>

		<p><label>{$aLang.registration_invite_code}<br />
		<input type="text" name="invite_code" class="input-200" /></label></p>

		<input type="submit" name="submit_invite" value="{$aLang.registration_invite_check}" />
	</form>
</div>


{include file='footer.tpl'}