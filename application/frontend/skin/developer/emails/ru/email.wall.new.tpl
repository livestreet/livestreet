{extends file='components/email/email.tpl'}

{block name='content'}
	Пользователь <a href="{$oUser->getUserWebPath()}">{$oUser->getDisplayName()}</a> оставил сообщение на <a href="{$oUserWall->getUserWebPath()}wall/">вашей стене</a>
	<br>
	<br>
	Текст сообщения:
	<br>
	<i>{$oWall->getText()}</i>
{/block}