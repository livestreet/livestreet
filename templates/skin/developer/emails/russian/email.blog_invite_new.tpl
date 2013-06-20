{extends file='emails/email.base.tpl'}

{block name='content'}
	Пользователь «<a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getLogin()}</a>» приглашает вас вступить в блог <a href="{$oBlog->getUrlFull()}">"{$oBlog->getTitle()|escape:'html'}"</a>.
	<br>
	<br>
	<a href='{$sPath}'>Посмотреть приглашение</a> (Не забудьте предварительно авторизоваться!)
{/block}