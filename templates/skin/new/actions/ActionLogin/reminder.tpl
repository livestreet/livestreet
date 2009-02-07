{include file='header.light.tpl'}

	<div class="lite-center">
		<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LOGIN}/reminder/" method="POST">
				<h3>Восстановление пароля</h3>
				<div class="lite-note"><label for="mail">Ваш e-mail:</label></div>
				<p><input type="text" class="input-text" name="mail" id="name"/></p>				
				<input type="submit" name="submit_reminder" value="Получить ссылку на изменение пароля" />
		</form>
	</div>

{include file='footer.light.tpl'}