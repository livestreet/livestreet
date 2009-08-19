Получен ответ на ваш комментарий в топике <b>«{$oTopic->getTitle()|escape:'html'}»</b>, прочитать его можно перейдя по <a href="{$oTopic->getUrl()}#comment{$oComment->getId()}">этой ссылке</a><br>							
{if $aConfig.sys.mail.include_comment}
	Текст сообщения: <i>{$oComment->getText()}</i>	
{/if}				
<br><br>
С уважением, администрация сайта <a href="{$aConfig.path.root.web}">{$aConfig.view.name}</a>