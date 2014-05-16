{extends file='components/email/email.tpl'}

{block name='content'}
	Пользователь <a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getDisplayName()}</a> пригласил вас зарегистрироваться на сайте <a href="{Router::GetPath('/')}">{cfg name='view.name'}</a>
	<br>
	<br>
	Код приглашения:  <b>{$oInvite->getCode()}</b>
	<br>
	Для регистрации вам будет необходимо ввести код приглашения на <a href="{router page='login'}">странице входа</a>
{/block}