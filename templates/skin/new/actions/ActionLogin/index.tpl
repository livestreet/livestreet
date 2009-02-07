{include file='header.light.tpl'}

	<div class="lite-center">
	
		{if $bLoginError}
			<p><span class="">Что-то не так! Вероятно, неправильно указан логин(e-mail), или пароль.</span><br />
		{/if}

		<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LOGIN}/" method="POST">
				<h3>Авторизация</h3>
				<div class="lite-note"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_REGISTRATION}/">Зарегистрироваться</a><label for="login-input">Логин или эл. почта</label></div>
				<p><input type="text" class="input-text" name="login" tabindex="1" id="login-input"/></p>
				<div class="lite-note"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LOGIN}/reminder/" tabindex="-1">Напомнить пароль</a><label for="password-input">Пароль</label></div>
				<p><input type="password" name="password" class="input-text" tabindex="2" id="password-input"/></p>
				<div class="lite-note"><input type="image" src="{$DIR_STATIC_SKIN}/images/login-button.gif" class="input-button" tabindex="4" /><label for="" class="input-checkbox"><input type="checkbox" name="remember" checked tabindex="3" > Запомнить меня</label></div>
				<input type="hidden" name="submit_login">
		</form>
		
		{if $USER_USE_INVITE} 	
			<br><br>		
			<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_REGISTRATION}/invite/" method="POST">
				<h3>Регистрация по приглашению</h3>
				<div class="lite-note"><label for="invite_code">Код приглашения:</label></div>
				<p><input type="text" class="input-text" name="invite_code" id="invite_code"/></p>				
				<input type="submit" name="submit_invite" value="Проверить код">
			</form>
		{/if}
	</div>

{include file='footer.light.tpl'}