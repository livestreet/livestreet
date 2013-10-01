{extends file='emails/email.base.tpl'}

{block name='content'}
	Вы зарегистрировались на сайте <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>
	<br>
	<br>
	Ваши регистрационные данные:<br>
	&nbsp;&nbsp;&nbsp;логин: <b>{$oUser->getDisplayName()}</b><br>
	&nbsp;&nbsp;&nbsp;пароль: <b>{$sPassword}</b>
{/block}