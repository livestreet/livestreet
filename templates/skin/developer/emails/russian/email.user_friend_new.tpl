{extends file='emails/email.base.tpl'}

{block name='content'}
	Пользователь «<a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getLogin()}</a>» хочет добавить вас в друзья.						
	<br>
	<br>
	<i>{$sText}</i>
	<br>
	<br>
	<a href='{$sPath}'>Посмотреть заявку</a> 
	<br>
	<br>
	Не забудьте предварительно авторизоваться!
{/block}