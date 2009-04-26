Получен новый комментарий на письмо <b>«{$oTalk->getTitle()|escape:'html'}»</b>, прочитать его можно перейдя по <a href="{$DIR_WEB_ROOT}/talk/read/{$oTalk->getId()}/#comment{$oTalkComment->getId()}">этой ссылке</a><br>							
{if $SYS_MAIL_INCLUDE_TALK_TEXT}
	Текст сообщения: <i>{$oTalkComment->getText()}</i>	<br>			
{/if}
Не забудьте предварительно авторизоваться!							
<br><br>
С уважением, администрация сайта <a href="{$DIR_WEB_ROOT}">{$SITE_NAME}</a>