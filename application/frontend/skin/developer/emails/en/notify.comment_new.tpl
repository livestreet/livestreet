The user <a href="{$oUserComment->getUserWebPath()}">{$oUserComment->getDisplayName()}</a> left a new comment to topic <b>«{$oTopic->getTitle()|escape:'html'}»</b>, you can read it by clicking on <a href="{if Config::Get('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}">this link</a><br>
{if Config::Get('sys.mail.include_comment')}
	Message: <i>{$oComment->getText()}</i>				
{/if}

{if $sSubscribeKey}
	<br><br>
	<a href="{router page='subscribe'}unsubscribe/{$sSubscribeKey}/">Unsubscribe from new comments to this topic</a>
{/if}

<br><br>
Best regards, site administration <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>