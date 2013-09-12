{extends file='emails/email.base.tpl'}

{block name='content'}
	Пользователь <a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getLogin()}</a> оставил новый комментарий к письму <b>«{$oTalk->getTitle()|escape:'html'}»</b>, прочитать его можно перейдя по <a href="{router page='talk'}read/{$oTalk->getId()}/#comment{$oTalkComment->getId()}">этой ссылке</a>
	<br>
	<br>
	
	{if $oConfig->GetValue('sys.mail.include_talk')}
		Текст сообщения: <i>{$oTalkComment->getText()}</i>
		<br>
		<br>
	{/if}
	
	Не забудьте предварительно авторизоваться!
{/block}