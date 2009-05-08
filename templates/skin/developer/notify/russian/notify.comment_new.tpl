Получен новый комментарий к вашему топику <b>«{$oTopic->getTitle()|escape:'html'}»</b>, прочитать его можно перейдя по <a href="{$oTopic->getUrl()}#comment{$oComment->getId()}">этой ссылке</a><br>							
{if $SYS_MAIL_INCLUDE_COMMENT_TEXT}
	Текст сообщения: <i>{$oComment->getText()}</i>				
{/if}				
<br><br>
С уважением, администрация сайта <a href="{$DIR_WEB_ROOT}">{$SITE_NAME}</a>