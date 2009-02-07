{include file='header.light.tpl'}

		<div class="lite-center">
			<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_REGISTRATION}/invite/" method="POST">
				<h3>Регистрация по приглашению</h3>
				<div class="lite-note"><label for="invite_code">Код приглашения:</label></div>
				<p><input type="text" class="input-text" name="invite_code" id="invite_code"/></p>				
				<input type="submit" name="submit_invite" value="Проверить код">
			</form>
		</div>

{include file='footer.light.tpl'}