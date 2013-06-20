{extends file='emails/email.base.tpl'}

{block name='content'}
	Вам пришло новое письмо от пользователя <a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getLogin()}</a>, прочитать и ответить на него можно перейдя по <a href="{router page='talk'}read/{$oTalk->getId()}/">этой ссылке</a>
	<br>
	<br>
	Тема письма: <b>{$oTalk->getTitle()|escape:'html'}</b>
	<br>

	{if $oConfig->GetValue('sys.mail.include_talk')}
		Текст сообщения:<br>
		<i>{$oTalk->getText()}</i>
		<br>
	{/if}
	
	<br>
	Не забудьте предварительно авторизоваться!
{/block}