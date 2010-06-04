The user <a href="{$oUserTopic->getUserWebPath()}">{$oUserTopic->getLogin()}</a> posted a new topic - <a href="{router page='blog'}{$oTopic->getId()}.html">{$oTopic->getTitle()|escape:'html'}</a><br> in a blog <b>«{$oBlog->getTitle()|escape:'html'}»</b> 						
														
<br><br>
Best regards, site administration <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>