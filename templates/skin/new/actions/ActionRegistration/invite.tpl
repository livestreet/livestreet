{include file='header.light.tpl'}

		<div class="lite-center">
			<form action="{router page='registration'}invite/" method="POST">
				<h3>{$aLang.registration_invite}</h3>
				<div class="lite-note"><label for="invite_code">{$aLang.registration_invite_code}:</label></div>
				<p><input type="text" class="input-text" name="invite_code" id="invite_code"/></p>				
				<input type="submit" name="submit_invite" value="{$aLang.registration_invite_check}">
			</form>
		</div>

{include file='footer.light.tpl'}