You have a new incoming message from <a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getLogin()}</a>. You can read and answer it by clicking on <a href="{router page='talk'}read/{$oTalk->getId()}/"> this link</a><br>
Letter topic: <b>{$oTalk->getTitle()|escape:'html'}</b><br>
{if $oConfig->GetValue('sys.mail.include_talk')}
	Message: <i>{$oTalk->getText()}</i>	<br>			
{/if}
<br><br>
Best regards, 
<br>
<a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>