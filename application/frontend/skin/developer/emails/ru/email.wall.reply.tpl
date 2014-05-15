{extends file='components/email/email.tpl'}

{block name='content'}
	Пользователь <a href="{$oUser->getUserWebPath()}">{$oUser->getDisplayName()}</a> ответил на ваше сообщение на <a href="{$oUserWall->getUserWebPath()}wall/">стене</a>
	<br>
	<br>
	<b>Ваше сообщение:</b><br>
	<i>{$oWallParent->getText()}</i>
	<br>
	<br>
	<b>Текст ответа:</b><br>
	<i>{$oWall->getText()}</i>
{/block}