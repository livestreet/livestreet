The user <a href="{$oUserComment->getUserWebPath()}">{$oUserComment->getDisplayName()}</a> replied your comment in the topic <b>«{$oTopic->getTitle()|escape:'html'}»</b>, you can read it by clicking on <a href="{if Config::Get('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}">this link</a><br>
{if Config::Get('sys.mail.include_comment')}
	Message: <i>{$oComment->getText()}</i>	
{/if}				
<br><br>
Best regards, site administration <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>