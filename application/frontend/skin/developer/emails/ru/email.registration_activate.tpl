{extends file='components/email/email.tpl'}

{block name='content'}
	Вы зарегистрировались на сайте <a href="{Router::GetPath('/')}">{cfg name='view.name'}</a>
	<br>
	<br>
	Ваши регистрационные данные:<br>
	&nbsp;&nbsp;&nbsp;логин: <b>{$oUser->getLogin()}</b><br>
	&nbsp;&nbsp;&nbsp;пароль: <b>{$sPassword}</b>
	<br>
	<br>
	Для завершения регистрации вам необходимо активировать аккаунт пройдя по ссылке: 
	<a href="{router page='registration'}activate/{$oUser->getActivateKey()}/">{router page='registration'}activate/{$oUser->getActivateKey()}/</a>
{/block}