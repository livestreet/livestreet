<a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getLogin()}</a> has left a new comment to the message<b>«{$oTalk->getTitle()|escape:'html'}»</b>.<br><br>						
{if $oConfig->GetValue('sys.mail.include_talk')}
Their comment reads as follows: <i>{$oTalkComment->getText()}</i>	<br>			
{/if}
<br>
 To view the comment and reply to it, follow <a href="{router page='talk'}read/{$oTalk->getId()}/#comment{$oTalkComment->getId()}">this link</a>					
<br><br>
Best regards, 
<br>
<a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>