{assign var="noSidebar" value=true}
{include file='header.light.tpl'}


<div class="center">
	<form action="{router page='registration'}invite/" method="POST">
		<h2>{$aLang.registration_invite}</h2>

		<p><label>{$aLang.registration_invite_code}<br />
		<input type="text" name="invite_code" class="input-200 input-text" /></label></p>

		<input type="submit" name="submit_invite" class="button" value="{$aLang.registration_invite_check}" />
	</form>
</div>


{include file='footer.light.tpl'}