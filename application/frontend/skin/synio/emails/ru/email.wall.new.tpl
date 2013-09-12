{extends file='emails/email.base.tpl'}

{block name='content'}
	Пользователь <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> оставил сообщение на <a href="{$oUserWall->getUserWebPath()}wall/">вашей стене</a>
	<br>
	<br>
	Текст сообщения:
	<br>
	<i>{$oWall->getText()}</i>
{/block}