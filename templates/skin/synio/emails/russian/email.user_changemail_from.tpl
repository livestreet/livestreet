{extends file='emails/email.base.tpl'}

{block name='content'}
	Вами отправлен запрос на смену e-mail адреса пользователя <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> на сайте <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>.
	<br>
	<br>
	Старый e-mail: <b>{$oChangemail->getMailFrom()}</b><br>
	Новый e-mail: <b>{$oChangemail->getMailTo()}</b>
	<br>
	<br>
	Для подтверждения смены емайла пройдите по ссылке:<br>
	<a href="{router page='profile'}changemail/confirm-from/{$oChangemail->getCodeFrom()}/">{router page='profile'}changemail/confirm-from/{$oChangemail->getCodeFrom()}/</a>
{/block}