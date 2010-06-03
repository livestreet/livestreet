Пользователь <a href="{$oUserComment->getUserWebPath()}">{$oUserComment->getLogin()}</a> ответил на ваш комментарий в топике <b>«{$oTopic->getTitle()|escape:'html'}»</b>, прочитать его можно перейдя по <a href="{$oTopic->getUrl()}#comment{$oComment->getId()}">этой ссылке</a><br>							
{if $oConfig->GetValue('sys.mail.include_comment')}
	Текст сообщения: <i>{$oComment->getText()}</i>	
{/if}				
<br><br>
С уважением, администрация сайта <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>