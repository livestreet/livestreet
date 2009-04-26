Вам пришло новое письмо, прочитать и ответить на него можно перейдя по <a href="{$DIR_WEB_ROOT}/talk/read/{$oTalk->getId()}/">этой ссылке</a><br>
Тема письма: <b>{$oTalk->getTitle()|escape:'html'}</b><br>
{if $SYS_MAIL_INCLUDE_TALK_TEXT}
	Текст сообщения: <i>{$oTalk->getText()}</i>	<br>			
{/if}
Не забудьте предварительно авторизоваться!
<br><br>
С уважением, администрация сайта <a href="{$DIR_WEB_ROOT}">{$SITE_NAME}</a>