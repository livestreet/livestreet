<a href="{$oUserComment->getUserWebPath()}">{$oUserComment->getLogin()}</a> has left a new comment on the topic <b>«{$oTopic->getTitle()|escape:'html'}»</b>.
<br><br>
{if $oConfig->GetValue('sys.mail.include_comment')}
Their comment reads as follows: <i>{$oComment->getText()}</i>				
{/if}<br><br>
To view the comment and reply to it, follow <a href="{if $oConfig->GetValue('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}">this link</a>
{if $sSubscribeKey}
	<br><br>
	<a href="{router page='subscribe'}unsubscribe/{$sSubscribeKey}/">Unsubscribe from new comments on this topic</a>
{/if}

<br><br>
Best regards, 
<br>
<a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>