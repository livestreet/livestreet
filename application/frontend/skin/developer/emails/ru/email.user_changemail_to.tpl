{extends file='components/email/email.tpl'}

{block name='content'}
	Вами отправлен запрос на смену e-mail адреса пользователя <a href="{$oUser->getUserWebPath()}">{$oUser->getDisplayName()}</a> на сайте <a href="{Router::GetPath('/')}">{cfg name='view.name'}</a>.
	<br>
	<br>
	Старый e-mail: <b>{$oChangemail->getMailFrom()}</b><br>
	Новый e-mail: <b>{$oChangemail->getMailTo()}</b>
	<br>
	<br>
	Для подтверждения смены емайла пройдите по ссылке:<br>
	<a href="{router page='profile'}changemail/confirm-to/{$oChangemail->getCodeTo()}/">{router page='profile'}changemail/confirm-to/{$oChangemail->getCodeTo()}/</a>
{/block}